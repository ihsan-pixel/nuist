import time
from types import SimpleNamespace

import numpy as np

from main import CandidatePayload, CandidateVector, EmbeddingCache, normalize_embedding, validate_embedding


def vector(seed: int) -> list[float]:
    rng = np.random.default_rng(seed)
    return normalize_embedding(rng.normal(size=512).astype(np.float32)).tolist()


def candidate(user_id: int, seed: int) -> CandidatePayload:
    return CandidatePayload(
        user_id=user_id,
        vectors=[CandidateVector(
            type="face_embedding:insightface_arcface",
            dimension=512,
            values=vector(seed),
        )],
    )


def test_arcface_embedding_contract_is_512d_and_l2_normalized():
    embedding = validate_embedding(np.asarray(vector(1), dtype=np.float32))
    assert embedding.shape == (512,)
    assert np.isclose(np.linalg.norm(embedding), 1.0, atol=1e-4)


def test_cache_uses_vectorized_matrix_for_1000_users():
    cache = EmbeddingCache()
    candidates = [candidate(index, index) for index in range(1000)]
    cached_count = cache.refresh(candidates)
    assert cached_count == 1000
    assert cache.matrix.shape == (1000, 512)

    query = np.asarray(candidates[421].vectors[0].values, dtype=np.float32)
    started = time.perf_counter()
    similarities = cache.matrix @ query
    elapsed_ms = (time.perf_counter() - started) * 1000

    assert int(np.argmax(similarities)) == 421
    assert similarities[421] > 0.99
    assert elapsed_ms < 1000


def test_cache_invalidation_removes_only_requested_users():
    cache = EmbeddingCache()
    cache.refresh([candidate(1, 1), candidate(2, 2), candidate(3, 3)])
    assert cache.invalidate([2]) == 1
    assert set(cache.user_ids.tolist()) == {1, 3}


def test_mobile_verification_uses_current_profiles_without_shared_cache(monkeypatch):
    import main

    current = candidate(1, 5)
    stale = candidate(1, 2)
    other = candidate(2, 5)
    main.embedding_cache.refresh([stale, other])
    best = SimpleNamespace(embedding=np.asarray(current.vectors[0].values, dtype=np.float32), frame_data='test')
    monkeypatch.setattr(main, 'analyze_frames', lambda frames: [best, best, best])
    monkeypatch.setattr(main, 'choose_best_analysis', lambda analyses: best)
    monkeypatch.setattr(main, 'compute_liveness', lambda analyses: (1.0, [], {}))
    try:
        result = main.identify(main.IdentifyRequest(
            frames=['test'], candidates=[current], context={'verification_mode': '1:1'},
        ))
        assert result['success'] is True
        assert result['user_id'] == 1
        assert result['similarity'] > 0.99
        assert set(main.embedding_cache.user_ids.tolist()) == {1, 2}
    finally:
        main.embedding_cache.invalidate([])

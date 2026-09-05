import time

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

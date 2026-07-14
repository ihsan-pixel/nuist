export function initHomeCarousel(root, { registerCleanup } = {}) {
    if (!root) {
        return;
    }

    const track = root.querySelector('[data-home-carousel-track]');
    const viewport = root.querySelector('[data-home-carousel]');

    if (!track || !viewport || track.dataset.carouselReady === 'true') {
        return;
    }

    const items = Array.from(track.children);
    if (!items.length) {
        return;
    }

    const fragment = document.createDocumentFragment();
    items.forEach((item) => {
        fragment.appendChild(item.cloneNode(true));
    });
    track.appendChild(fragment);
    track.dataset.carouselReady = 'true';

    const setPaused = (paused) => {
        track.classList.toggle('is-paused', paused);
    };

    const handleVisibilityChange = () => {
        setPaused(document.hidden);
    };

    const handlePointerEnter = () => setPaused(true);
    const handlePointerLeave = () => setPaused(document.hidden);
    const handleFocusIn = () => setPaused(true);
    const handleFocusOut = () => setPaused(document.hidden);

    viewport.addEventListener('mouseenter', handlePointerEnter);
    viewport.addEventListener('mouseleave', handlePointerLeave);
    viewport.addEventListener('focusin', handleFocusIn);
    viewport.addEventListener('focusout', handleFocusOut);
    document.addEventListener('visibilitychange', handleVisibilityChange);

    if (typeof registerCleanup === 'function') {
        registerCleanup(() => {
            viewport.removeEventListener('mouseenter', handlePointerEnter);
            viewport.removeEventListener('mouseleave', handlePointerLeave);
            viewport.removeEventListener('focusin', handleFocusIn);
            viewport.removeEventListener('focusout', handleFocusOut);
            document.removeEventListener('visibilitychange', handleVisibilityChange);
        });
    }
}

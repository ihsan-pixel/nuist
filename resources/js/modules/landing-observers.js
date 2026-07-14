export function initAnimateObserver(root, { registerCleanup, once = true } = {}) {
    if (!root) {
        return;
    }

    const animateElements = root.querySelectorAll('.animate');
    if (!animateElements.length) {
        return;
    }

    if (!('IntersectionObserver' in window)) {
        animateElements.forEach((element) => element.classList.add('show'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            entry.target.classList.add('show');

            if (once) {
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    animateElements.forEach((element) => {
        if (element.getBoundingClientRect().top < window.innerHeight) {
            element.classList.add('show');

            if (!once) {
                observer.observe(element);
            }

            return;
        }

        observer.observe(element);
    });

    if (typeof registerCleanup === 'function') {
        registerCleanup(() => observer.disconnect());
    }
}

export function initSectionTitleObserver(root, { registerCleanup } = {}) {
    if (!root) {
        return;
    }

    const titles = root.querySelectorAll('.section-title');
    if (!titles.length) {
        return;
    }

    if (!('IntersectionObserver' in window)) {
        titles.forEach((title) => title.classList.add('active'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            entry.target.classList.toggle('active', entry.isIntersecting);
        });
    }, { threshold: 0.5 });

    titles.forEach((title) => observer.observe(title));

    if (typeof registerCleanup === 'function') {
        registerCleanup(() => observer.disconnect());
    }
}

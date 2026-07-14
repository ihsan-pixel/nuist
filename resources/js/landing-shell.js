import '../css/landing-shell.css';

class LruPageCache {
    constructor({ version, ttlMs = 5 * 60 * 1000, maxEntries = 8 }) {
        this.version = version;
        this.ttlMs = ttlMs;
        this.maxEntries = maxEntries;
        this.memory = new Map();
        this.storagePrefix = `landing-shell:${version}:`;
        this.indexKey = `${this.storagePrefix}index`;
        this.index = this.loadIndex();
        this.prune();
    }

    normalize(url) {
        const parsed = new URL(url, window.location.origin);
        parsed.hash = '';
        return parsed.toString();
    }

    loadIndex() {
        try {
            const raw = sessionStorage.getItem(this.indexKey);
            return raw ? JSON.parse(raw) : [];
        } catch (error) {
            return [];
        }
    }

    saveIndex() {
        try {
            sessionStorage.setItem(this.indexKey, JSON.stringify(this.index));
        } catch (error) {
            // Ignore storage failures.
        }
    }

    getStorageKey(url) {
        return `${this.storagePrefix}${this.normalize(url)}`;
    }

    get(url, { allowStale = false } = {}) {
        const normalized = this.normalize(url);
        const now = Date.now();
        const inMemory = this.memory.get(normalized);

        if (inMemory) {
            if (allowStale || now - inMemory.timestamp <= this.ttlMs) {
                this.touch(normalized, inMemory.timestamp);
                return inMemory.payload;
            }

            this.delete(normalized);
        }

        try {
            const raw = sessionStorage.getItem(this.getStorageKey(normalized));
            if (!raw) {
                return null;
            }

            const parsed = JSON.parse(raw);
            if (!parsed || typeof parsed !== 'object' || !parsed.payload || typeof parsed.timestamp !== 'number') {
                this.delete(normalized);
                return null;
            }

            if (!allowStale && now - parsed.timestamp > this.ttlMs) {
                this.delete(normalized);
                return null;
            }

            this.memory.set(normalized, parsed);
            this.touch(normalized, parsed.timestamp);
            return parsed.payload;
        } catch (error) {
            this.delete(normalized);
            return null;
        }
    }

    set(url, payload) {
        const normalized = this.normalize(url);
        const entry = {
            timestamp: Date.now(),
            payload,
        };

        this.memory.set(normalized, entry);
        this.touch(normalized, entry.timestamp);

        try {
            sessionStorage.setItem(this.getStorageKey(normalized), JSON.stringify(entry));
        } catch (error) {
            // Ignore storage failures; memory cache still works.
        }

        this.prune();
    }

    touch(normalized, timestamp) {
        this.index = this.index.filter((entry) => entry.url !== normalized);
        this.index.unshift({ url: normalized, timestamp });
        this.saveIndex();
    }

    delete(url) {
        const normalized = this.normalize(url);
        this.memory.delete(normalized);
        this.index = this.index.filter((entry) => entry.url !== normalized);
        this.saveIndex();

        try {
            sessionStorage.removeItem(this.getStorageKey(normalized));
        } catch (error) {
            // Ignore storage failures.
        }
    }

    prune() {
        const now = Date.now();
        const nextIndex = [];

        this.index.forEach((entry) => {
            if (now - entry.timestamp > this.ttlMs) {
                this.delete(entry.url);
                return;
            }

            nextIndex.push(entry);
        });

        this.index = nextIndex.slice(0, this.maxEntries);
        this.saveIndex();

        while (this.index.length > this.maxEntries) {
            const oldest = this.index.pop();
            if (oldest) {
                this.delete(oldest.url);
            }
        }
    }
}

class LandingNavigation {
    constructor(shell) {
        this.shell = shell;
        this.main = shell.querySelector('[data-landing-content]');
        this.assets = shell.querySelector('[data-landing-page-assets]');
        this.status = shell.querySelector('[data-landing-status]');
        this.loader = shell.querySelector('[data-landing-loading]');
        this.navbar = shell.querySelector('[data-landing-navbar]');
        this.menu = shell.querySelector('#landing-nav-menu');
        this.toggle = shell.querySelector('#landing-navbar-toggle');
        this.navVersion = shell.dataset.navVersion || 'dev';
        this.currentUrl = new URL(shell.dataset.currentUrl || window.location.href, window.location.origin).toString();
        this.currentRequestId = 0;
        this.abortController = null;
        this.cleanupFns = [];
        this.idleCallback = window.requestIdleCallback || ((callback) => window.setTimeout(() => callback({ didTimeout: false, timeRemaining: () => 0 }), 1));
        this.cache = new LruPageCache({
            version: this.navVersion,
            ttlMs: 5 * 60 * 1000,
            maxEntries: 8,
        });
    }

    init() {
        if (!this.main || !this.assets || !this.navbar || !this.menu || !this.toggle) {
            return;
        }

        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }

        if (!(history.state && history.state.landingNav)) {
            history.replaceState({ landingNav: true, scroll: { x: window.scrollX, y: window.scrollY } }, '', this.currentUrl);
        }

        this.bindDocumentEvents();
        this.bindNavbarAccessibility();
        this.updateActiveNav(this.currentUrl);
        this.applyNavbarScrollState();
        this.initCurrentPage();
        this.initBackToTop();
        this.idleCallback(() => this.prefetchVisibleLinks());
    }

    bindDocumentEvents() {
        document.addEventListener('click', (event) => {
            const link = event.target.closest('a[data-nav-ajax="true"]');

            if (!link) {
                if (!event.target.closest('.landing-navbar .nav-left')) {
                    this.closeMenu();
                }
                return;
            }

            if (!this.shouldHandleNavigation(link, event)) {
                return;
            }

            event.preventDefault();
            this.navigate(link.href);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                this.closeMenu();
            }
        });

        window.addEventListener('scroll', () => {
            this.applyNavbarScrollState();
            this.updateBackToTop();
        }, { passive: true });

        window.addEventListener('pageshow', () => {
            this.applyNavbarScrollState();
            this.updateBackToTop();
        });

        window.addEventListener('popstate', async (event) => {
            const targetUrl = window.location.href;
            const state = event.state || {};

            if (!state.landingNav) {
                this.currentUrl = new URL(targetUrl, window.location.origin).toString();
                this.applyNavbarScrollState();
                return;
            }

            await this.navigate(targetUrl, {
                replace: true,
                popstate: true,
                restoreScroll: state.scroll || { x: 0, y: 0 },
            });
        });
    }

    bindNavbarAccessibility() {
        this.toggle.addEventListener('click', () => {
            if (this.menu.classList.contains('is-open')) {
                this.closeMenu();
            } else {
                this.openMenu();
            }
        });

        this.menu.querySelectorAll('a').forEach((link) => {
            link.addEventListener('focus', () => {
                this.closeOtherDropdowns();
            });
        });
    }

    initBackToTop() {
        this.backToTopButton = document.getElementById('backToTop');

        if (!this.backToTopButton || this.backToTopButton.dataset.bound === 'true') {
            this.updateBackToTop();
            return;
        }

        this.backToTopButton.dataset.bound = 'true';
        this.backToTopButton.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        this.updateBackToTop();
    }

    updateBackToTop() {
        if (!this.backToTopButton) {
            return;
        }

        this.backToTopButton.classList.toggle('is-visible', window.scrollY > 300);
    }

    shouldHandleNavigation(link, event) {
        if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return false;
        }

        if (link.target && link.target !== '_self') {
            return false;
        }

        const targetUrl = new URL(link.href, window.location.origin);

        if (targetUrl.origin !== window.location.origin || targetUrl.hash) {
            return false;
        }

        return true;
    }

    normalizeUrl(url) {
        const parsed = new URL(url, window.location.origin);
        parsed.hash = '';
        return parsed.toString();
    }

    openMenu() {
        this.menu.classList.add('is-open');
        this.toggle.setAttribute('aria-expanded', 'true');
    }

    closeMenu() {
        this.menu.classList.remove('is-open');
        this.toggle.setAttribute('aria-expanded', 'false');
    }

    closeOtherDropdowns() {
        this.shell.querySelectorAll('.dropdown.open').forEach((dropdown) => {
            dropdown.classList.remove('open');
        });
    }

    applyNavbarScrollState() {
        const isScrolled = (window.scrollY || document.documentElement.scrollTop || 0) > 50;
        this.navbar.classList.toggle('is-scrolled', isScrolled);
        this.navbar.classList.toggle('is-full-width', isScrolled);
    }

    announce(message) {
        if (this.status) {
            this.status.textContent = message;
        }
    }

    showLoader() {
        if (this.loader) {
            this.loader.hidden = false;
        }

        this.main.classList.add('is-loading');
    }

    hideLoader() {
        if (this.loader) {
            this.loader.hidden = true;
        }

        this.main.classList.remove('is-loading');
    }

    rememberScroll(url = this.currentUrl) {
        const normalized = this.normalizeUrl(url);
        const state = history.state || {};
        const scroll = { x: window.scrollX, y: window.scrollY };
        history.replaceState({ ...state, landingNav: true, scroll }, '', normalized);
    }

    async navigate(url, options = {}) {
        const targetUrl = this.normalizeUrl(url);
        const isSameUrl = targetUrl === this.currentUrl;

        if (isSameUrl && !options.popstate) {
            this.closeMenu();
            this.applyNavbarScrollState();
            return;
        }

        this.rememberScroll(this.currentUrl);

        if (this.abortController) {
            this.abortController.abort();
        }

        this.abortController = new AbortController();
        const requestId = ++this.currentRequestId;
        const restoreScroll = options.restoreScroll || { x: 0, y: 0 };

        this.showLoader();
        this.announce('Membuka halaman...');

        try {
            const payload = await this.loadPage(targetUrl, {
                signal: this.abortController.signal,
                requestId,
                retryCount: 1,
            });

            if (!payload || requestId !== this.currentRequestId) {
                return;
            }

            this.cleanupCurrentPage();
            this.swapPage(payload, targetUrl);

            if (!options.replace && !options.popstate && !isSameUrl) {
                history.pushState({ landingNav: true, scroll: { x: 0, y: 0 } }, '', targetUrl);
            } else if (options.replace) {
                history.replaceState({ landingNav: true, scroll: restoreScroll }, '', targetUrl);
            }

            this.currentUrl = targetUrl;
            this.shell.dataset.currentUrl = targetUrl;
            this.updateActiveNav(targetUrl);
            this.closeMenu();
            this.initCurrentPage();
            this.idleCallback(() => this.prefetchVisibleLinks());

            const nextScroll = options.popstate ? restoreScroll : { x: 0, y: 0 };
            window.scrollTo(nextScroll.x, nextScroll.y);
            this.main.focus({ preventScroll: true });
            this.applyNavbarScrollState();
            this.updateBackToTop();
            this.trackPageView(payload.meta, targetUrl);
            this.announce('Halaman berhasil dibuka.');
        } catch (error) {
            if (error.name === 'AbortError') {
                return;
            }

            this.announce('Gagal membuka halaman. Silakan coba lagi.');
        } finally {
            if (requestId === this.currentRequestId) {
                this.hideLoader();
            }
        }
    }

    async loadPage(url, { signal, requestId, retryCount }) {
        const cached = this.cache.get(url);
        if (cached) {
            return cached;
        }

        try {
            const response = await fetch(url, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html,application/xhtml+xml',
                },
                signal,
            });

            const contentType = response.headers.get('content-type') || '';
            if (!response.ok || !contentType.includes('text/html')) {
                throw new Error('Invalid navigation response');
            }

            const html = await response.text();

            if (requestId !== this.currentRequestId) {
                return null;
            }

            const payload = this.parsePage(html, url);
            if (!payload) {
                throw new Error('Invalid navigation payload');
            }

            this.cache.set(url, payload);
            return payload;
        } catch (error) {
            if (error.name === 'AbortError') {
                throw error;
            }

            if (retryCount > 0) {
                await new Promise((resolve) => window.setTimeout(resolve, 150));
                return this.loadPage(url, { signal, requestId, retryCount: retryCount - 1 });
            }

            const stale = this.cache.get(url, { allowStale: true });
            if (stale) {
                return stale;
            }

            throw error;
        }
    }

    parsePage(html, url) {
        const parser = new DOMParser();
        const documentNode = parser.parseFromString(html, 'text/html');
        const nextMain = documentNode.querySelector('[data-landing-content]');
        const nextAssets = documentNode.querySelector('[data-landing-page-assets]');

        if (!nextMain || !nextAssets) {
            return null;
        }

        const meta = {
            title: documentNode.title,
            description: documentNode.querySelector('meta[name="description"]')?.getAttribute('content') || '',
            canonical: documentNode.querySelector('link[rel="canonical"]')?.getAttribute('href') || url,
            ogTitle: documentNode.querySelector('meta[property="og:title"]')?.getAttribute('content') || documentNode.title,
            ogDescription: documentNode.querySelector('meta[property="og:description"]')?.getAttribute('content') || '',
            ogUrl: documentNode.querySelector('meta[property="og:url"]')?.getAttribute('content') || url,
            ogImage: documentNode.querySelector('meta[property="og:image"]')?.getAttribute('content') || '',
        };

        return {
            contentHtml: nextMain.innerHTML,
            contentClassName: nextMain.className,
            contentAttributes: Array.from(nextMain.attributes).reduce((attributes, attribute) => {
                attributes[attribute.name] = attribute.value;
                return attributes;
            }, {}),
            assetsHtml: nextAssets.innerHTML,
            meta,
        };
    }

    swapPage(payload, targetUrl) {
        this.updateMetadata(payload.meta, targetUrl);
        this.syncPageAssets(payload.assetsHtml);

        this.main.innerHTML = payload.contentHtml;
        this.main.className = payload.contentClassName;

        Array.from(this.main.attributes).forEach((attribute) => {
            if (attribute.name !== 'id') {
                this.main.removeAttribute(attribute.name);
            }
        });

        Object.entries(payload.contentAttributes || {}).forEach(([name, value]) => {
            if (name !== 'id') {
                this.main.setAttribute(name, value);
            }
        });
    }

    syncPageAssets(assetsHtml) {
        this.assets.innerHTML = assetsHtml;
        this.executeScripts(this.assets);
    }

    executeScripts(container) {
        container.querySelectorAll('script').forEach((script) => {
            const nextScript = document.createElement('script');

            Array.from(script.attributes).forEach((attribute) => {
                nextScript.setAttribute(attribute.name, attribute.value);
            });

            nextScript.textContent = script.textContent;
            script.replaceWith(nextScript);
        });
    }

    updateMetadata(meta, targetUrl) {
        document.title = meta.title || document.title;

        const mapping = [
            ['meta-description', meta.description],
            ['meta-og-title', meta.ogTitle],
            ['meta-og-description', meta.ogDescription],
            ['meta-og-url', meta.ogUrl || targetUrl],
            ['meta-og-image', meta.ogImage],
        ];

        mapping.forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element && value) {
                element.setAttribute('content', value);
            }
        });

        const canonical = document.getElementById('meta-canonical');
        if (canonical) {
            canonical.setAttribute('href', meta.canonical || targetUrl);
        }

        const titleTag = document.getElementById('page-title');
        if (titleTag && meta.title) {
            titleTag.textContent = meta.title;
        }
    }

    updateActiveNav(targetUrl) {
        const normalized = this.normalizeUrl(targetUrl);
        this.shell.querySelectorAll('a[data-nav-ajax="true"]').forEach((link) => {
            const isActive = this.normalizeUrl(link.href) === normalized;
            link.classList.toggle('active', isActive);

            if (isActive) {
                link.setAttribute('aria-current', 'page');
            } else {
                link.removeAttribute('aria-current');
            }
        });
    }

    cleanupCurrentPage() {
        this.cleanupFns.forEach((cleanup) => {
            try {
                cleanup();
            } catch (error) {
                // Ignore page cleanup failures.
            }
        });

        this.cleanupFns = [];
    }

    registerCleanup(cleanup) {
        if (typeof cleanup === 'function') {
            this.cleanupFns.push(cleanup);
        }
    }

    initCurrentPage() {
        const pageRoot = this.main.querySelector('[data-landing-page]');
        const pageKey = pageRoot?.dataset.landingPage || '';

        this.initAnimateObserver(pageRoot || this.main);

        if (pageKey === 'landing') {
            this.initSectionTitleObserver(pageRoot);
        }

        if (pageKey === 'sekolah') {
            this.initSekolahMap(pageRoot);
        }
    }

    initAnimateObserver(root) {
        if (!root) {
            return;
        }

        const animateElements = root.querySelectorAll('.animate');
        if (!animateElements.length) {
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('show');
                }
            });
        }, { threshold: 0.15 });

        animateElements.forEach((element) => {
            observer.observe(element);
            if (element.getBoundingClientRect().top < window.innerHeight) {
                element.classList.add('show');
            }
        });

        this.registerCleanup(() => observer.disconnect());
    }

    initSectionTitleObserver(root) {
        if (!root) {
            return;
        }

        const titles = root.querySelectorAll('.section-title');
        if (!titles.length) {
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                entry.target.classList.toggle('active', entry.isIntersecting);
            });
        }, { threshold: 0.5 });

        titles.forEach((title) => observer.observe(title));
        this.registerCleanup(() => observer.disconnect());
    }

    initSekolahMap(root) {
        if (!root) {
            return;
        }

        const mapSection = root.querySelector('#map-section');
        const mapElement = root.querySelector('#map');
        const dataScript = root.querySelector('[data-sekolah-map-locations]');
        if (!mapSection || !mapElement || !dataScript) {
            return;
        }

        let mapInitialized = false;
        let mapObserver = null;
        let locations = [];

        try {
            locations = JSON.parse(dataScript.textContent || '[]');
        } catch (error) {
            locations = [];
        }

        const loadStylesheetOnce = (href) => {
            if (document.querySelector(`link[href="${href}"]`)) {
                return Promise.resolve();
            }

            return new Promise((resolve, reject) => {
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = href;
                link.onload = resolve;
                link.onerror = reject;
                document.head.appendChild(link);
            });
        };

        const loadScriptOnce = (src) => {
            if (document.querySelector(`script[src="${src}"]`)) {
                return Promise.resolve();
            }

            return new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.src = src;
                script.async = true;
                script.onload = resolve;
                script.onerror = reject;
                document.body.appendChild(script);
            });
        };

        const initializeMap = async () => {
            if (mapInitialized) {
                return;
            }

            mapInitialized = true;

            try {
                await loadStylesheetOnce('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
                await loadScriptOnce('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js');

                if (!window.L || !document.body.contains(mapElement)) {
                    mapInitialized = false;
                    return;
                }

                const map = window.L.map(mapElement, { preferCanvas: true }).setView([-7.7956, 110.3695], 10);

                window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                }).addTo(map);

                let markerIndex = 0;
                const chunkSize = 20;

                const addMarkerChunk = () => {
                    const end = Math.min(markerIndex + chunkSize, locations.length);

                    for (; markerIndex < end; markerIndex += 1) {
                        const location = locations[markerIndex];
                        window.L.marker([location.lat, location.lng])
                            .addTo(map)
                            .bindPopup(`<b>${location.name}</b><br>${location.kabupaten}<br><a href="${location.detailUrl}">Lihat Detail</a>`);
                    }

                    if (markerIndex < locations.length) {
                        requestAnimationFrame(addMarkerChunk);
                    }
                };

                addMarkerChunk();
                this.registerCleanup(() => map.remove());
            } catch (error) {
                mapInitialized = false;
            }
        };

        mapObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    initializeMap();
                    if (mapObserver) {
                        mapObserver.disconnect();
                    }
                }
            });
        }, { rootMargin: '200px 0px' });

        mapObserver.observe(mapSection);
        this.registerCleanup(() => mapObserver && mapObserver.disconnect());
    }

    prefetchVisibleLinks() {
        this.shell.querySelectorAll('a[data-nav-ajax="true"]').forEach((link) => {
            if (link.dataset.prefetchBound === 'true') {
                return;
            }

            const prefetch = () => {
                const targetUrl = this.normalizeUrl(link.href);
                if (targetUrl === this.currentUrl || this.cache.get(targetUrl)) {
                    return;
                }

                const controller = new AbortController();
                this.loadPage(targetUrl, {
                    signal: controller.signal,
                    requestId: this.currentRequestId,
                    retryCount: 0,
                }).catch(() => {
                    // Ignore prefetch failures.
                });
            };

            link.addEventListener('mouseenter', prefetch, { passive: true });
            link.addEventListener('touchstart', prefetch, { passive: true, once: true });
            link.dataset.prefetchBound = 'true';
        });
    }

    trackPageView(meta, targetUrl) {
        if (typeof window.gtag === 'function') {
            window.gtag('event', 'page_view', {
                page_title: meta.title,
                page_location: targetUrl,
                page_path: new URL(targetUrl).pathname,
            });
        }

        if (typeof window.fbq === 'function') {
            window.fbq('track', 'PageView');
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const shell = document.querySelector('[data-landing-shell]');
    if (!shell) {
        return;
    }

    const navigation = new LandingNavigation(shell);
    navigation.init();
});

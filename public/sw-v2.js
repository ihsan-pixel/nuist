// NUIST Mobile PWA Service Worker
// Production-safe version.

const APP_VERSION = 'v2.3.3';
const CACHE_PREFIX = 'presensi-static-';
const CACHE_NAME = `${CACHE_PREFIX}${APP_VERSION}`;
const OFFLINE_URL = null;

const PRECACHE_ASSETS = [
    '/manifest.json',
    '/build/images/logo-light.png'
];

function isCacheableResponse(response) {
    return response && response.ok && response.status === 200;
}

async function saveToCache(request, response) {
    if (!isCacheableResponse(response)) {
        return;
    }

    // Clone before any await so the page cannot consume the response body first.
    let responseCopy;

    try {
        responseCopy = response.clone();
    } catch (error) {
        console.warn('[SW] Response clone failed:', error);
        return;
    }

    try {
        const cache = await caches.open(CACHE_NAME);
        await cache.put(request, responseCopy);
    } catch (error) {
        console.warn('[SW] Cache gagal disimpan:', error);
    }
}

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(PRECACHE_ASSETS))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys()
            .then(cacheNames => {
                const oldCaches = cacheNames.filter(cacheName => (
                    cacheName.startsWith(CACHE_PREFIX) &&
                    cacheName !== CACHE_NAME
                ));

                return Promise.all(
                    oldCaches.map(cacheName => {
                        console.log('[SW] Removing old cache:', cacheName);
                        return caches.delete(cacheName);
                    })
                );
            })
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', event => {
    const request = event.request;

    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);

    // Do not intercept third-party requests.
    if (url.origin !== self.location.origin) {
        return;
    }

    // Laravel pages are never stored. Use the offline page only as fallback.
    if (request.mode === 'navigate' || request.destination === 'document') {
        event.respondWith(
            fetch(request).catch(async () => {
                const offlinePage = OFFLINE_URL
                    ? await caches.match(OFFLINE_URL)
                    : null;

                return offlinePage || new Response(
                    'Tidak ada koneksi internet.',
                    {
                        status: 503,
                        headers: {
                            'Content-Type': 'text/plain; charset=utf-8'
                        }
                    }
                );
            })
        );

        return;
    }

    // Face models are large and stable, so cache them after the first download.
    if (url.pathname.startsWith('/models/')) {
        event.respondWith(
            caches.match(request).then(cachedResponse => {
                if (cachedResponse) {
                    return cachedResponse;
                }

                return fetch(request).then(networkResponse => {
                    saveToCache(request, networkResponse);
                    return networkResponse;
                });
            })
        );

        return;
    }

    // Application scripts and styles prefer the latest server version.
    if (
        request.destination === 'script' ||
        request.destination === 'style' ||
        url.pathname.startsWith('/js/') ||
        url.pathname.startsWith('/css/') ||
        url.pathname.startsWith('/build/')
    ) {
        event.respondWith(
            fetch(request)
                .then(networkResponse => {
                    saveToCache(request, networkResponse);
                    return networkResponse;
                })
                .catch(async () => {
                    const cached = await caches.match(request);
                    return cached || Response.error();
                })
        );

        return;
    }

    // Dynamic Laravel/API requests are intentionally left to the network.
});

self.addEventListener('message', event => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});

self.addEventListener('push', event => {
    if (!event.data) {
        return;
    }

    let data = {};

    try {
        data = event.data.json();
    } catch (error) {
        data = {
            title: 'NUIST',
            body: event.data.text()
        };
    }

    event.waitUntil(
        self.registration.showNotification(data.title || 'NUIST', {
            body: data.body || '',
            icon: '/build/images/logo-light.png',
            badge: '/build/images/logo-light.png',
            data: {
                url: data.url || '/mobile/dashboard',
                date: Date.now()
            }
        })
    );
});

self.addEventListener('notificationclick', event => {
    event.notification.close();

    const targetUrl = event.notification.data?.url || '/mobile/dashboard';

    event.waitUntil(
        self.clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        }).then(clientList => {
            for (const client of clientList) {
                if ('focus' in client) {
                    client.navigate(targetUrl);
                    return client.focus();
                }
            }

            if (self.clients.openWindow) {
                return self.clients.openWindow(targetUrl);
            }
        })
    );
});

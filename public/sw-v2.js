const APP_VERSION = 'v2.3.0';
const CACHE_NAME = `presensi-static-${APP_VERSION}`;

const STATIC_ASSETS = [
    '/mobile/offline.html',
    '/manifest.json',
    '/build/libs/leaflet/leaflet.css',
    '/build/libs/leaflet/leaflet.js',
    '/build/libs/sweetalert2/sweetalert2.min.css',
    '/build/libs/sweetalert2/sweetalert2.min.js',
    '/build/css/bootstrap.min.css',
    '/build/css/icons.min.css',
    '/build/css/app.min.css',
    '/build/images/logo-favicon-1.png'
];

self.addEventListener('install', (event) => {
    self.skipWaiting();

    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(STATIC_ASSETS))
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(keys.map((key) => caches.delete(key))))
            .then(() => self.clients.claim())
            .then(() => self.clients.matchAll({ includeUncontrolled: true }))
            .then((clients) => {
                clients.forEach((client) => {
                    client.postMessage({
                        type: 'NEW_VERSION',
                        version: APP_VERSION,
                    });
                });
            })
    );
});

self.addEventListener('fetch', (event) => {
    const req = event.request;
    const url = new URL(req.url);

    if (!url.pathname.startsWith('/mobile/')) {
        return;
    }

    if (req.destination === 'document') {
        event.respondWith(
            fetch(req).catch(() => caches.match('/mobile/offline.html'))
        );
        return;
    }

    if (['style', 'script', 'image', 'font'].includes(req.destination)) {
        event.respondWith(
            caches.match(req).then((cached) => {
                if (cached) {
                    return cached;
                }

                return fetch(req).then((networkRes) => {
                    caches.open(CACHE_NAME).then((cache) => {
                        try {
                            cache.put(req, networkRes.clone());
                        } catch (error) {
                        }
                    });

                    return networkRes;
                });
            })
        );
        return;
    }

    event.respondWith(
        fetch(req).catch(() => caches.match(req).then((res) => res || caches.match('/mobile/offline.html')))
    );
});

self.addEventListener('push', (event) => {
    let payload = {};

    if (event.data) {
        try {
            payload = event.data.json();
        } catch (error) {
            payload = {
                notification: {
                    body: event.data.text(),
                },
            };
        }
    }

    const notification = payload.notification || payload;
    const data = payload.data || {};
    const title = notification.title || data.title || 'NUIST Mobile';
    const body = notification.body || data.body || '';
    const targetUrl = data.url || '/mobile/notifications';

    event.waitUntil(
        self.registration.showNotification(title, {
            body: body,
            icon: '/build/images/logo-favicon-1.png',
            badge: '/build/images/logo-favicon-1.png',
            data: {
                url: targetUrl,
                notificationId: data.notification_id || null,
                receivedAt: Date.now(),
            },
        })
    );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const targetUrl = event.notification.data?.url || '/mobile/notifications';

    event.waitUntil(
        self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clients) => {
            const existingClient = clients.find((client) => {
                try {
                    return new URL(client.url).pathname === new URL(targetUrl, self.location.origin).pathname;
                } catch (error) {
                    return false;
                }
            });

            if (existingClient) {
                return existingClient.focus();
            }

            return self.clients.openWindow(targetUrl);
        })
    );
});

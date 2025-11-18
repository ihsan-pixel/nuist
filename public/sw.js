// Service Worker for NUIST Mobile PWA (FINAL — anti 419, auto-update)
const APP_VERSION = 'v2.2.0'; // <-- WAJIB diubah untuk paksa update
const CACHE_NAME = `presensi-static-${APP_VERSION}`;

// Static assets only
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

/* -----------------------------------------------------------
   🔥 FIX PENYEBAB 1: FORCE UPDATE & HAPUS SEMUA SW LAMA
----------------------------------------------------------- */

// Pastikan SW baru langsung mengambil alih
self.addEventListener('install', event => {
    console.log('SW Installing (force-update)…');
    self.skipWaiting(); // force replace old SW

    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => cache.addAll(STATIC_ASSETS))
    );
});

// Hapus semua cache lama dari semua versi
self.addEventListener('activate', event => {
    console.log('SW Activating (force cleanup)…');

    event.waitUntil(
        caches.keys().then(keys => {
            return Promise.all(
                keys.map(key => caches.delete(key)) // DELETE ALL old cache
            );
        })
        .then(() => self.clients.claim()) // ambil alih semua tab
        .then(() => {
            // Kirim pesan ke semua tab bahwa versi baru aktif
            return self.clients.matchAll({ includeUncontrolled: true })
                .then(clients => {
                    clients.forEach(client => {
                        client.postMessage({
                            type: 'NEW_VERSION',
                            version: APP_VERSION
                        });
                    });
                });
        })
    );
});

/* -----------------------------------------------------------
   NORMAL FETCH HANDLER (TIDAK DIUBAH)
----------------------------------------------------------- */

self.addEventListener('fetch', event => {
    const req = event.request;
    const url = new URL(req.url);

    // Limit scope ke /mobile/
    if (!url.pathname.startsWith('/mobile/')) {
        return; // ignore
    }

    // Do not cache HTML
    if (req.destination === 'document') {
        event.respondWith(
            fetch(req).catch(() => caches.match('/mobile/offline.html'))
        );
        return;
    }

    // Cache static asset
    if (['style','script','image','font'].includes(req.destination)) {
        event.respondWith(
            caches.match(req).then(cached => {
                if (cached) return cached;
                return fetch(req).then(networkRes => {
                    caches.open(CACHE_NAME).then(cache => {
                        try { cache.put(req, networkRes.clone()); } catch (e) {}
                    });
                    return networkRes;
                });
            })
        );
        return;
    }

    // Others: network first
    event.respondWith(
        fetch(req).catch(
            () => caches.match(req).then(r => r || caches.match('/mobile/offline.html'))
        )
    );
});

// Push notifications
self.addEventListener('push', event => {
    if (event.data) {
        const data = event.data.json();
        event.waitUntil(
            self.registration.showNotification(data.title, {
                body: data.body,
                icon: '/build/images/logo-favicon-1.png',
                badge: '/build/images/logo-favicon-1.png',
                data: { url: '/mobile/dashboard', date: Date.now() }
            })
        );
    }
});

// Notification click handler
self.addEventListener('notificationclick', event => {
    event.notification.close();
    event.waitUntil(clients.openWindow('/mobile/dashboard'));
});

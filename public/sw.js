// Service Worker for NUIST Mobile PWA
const APP_VERSION = 'v1.0.3'; // ubah versi setiap update aplikasi
const CACHE_NAME = `presensi-cache-${APP_VERSION}`;
const STATIC_CACHE = 'nuist-static-v1.0.0';
const DYNAMIC_CACHE = 'nuist-dynamic-v1.0.0';

// Static assets to cache
const STATIC_ASSETS = [
    '/',
    '/mobile/dashboard',
    '/mobile/presensi',
    '/mobile/jadwal',
    '/mobile/profile',
    '/manifest.json',
    '/build/libs/leaflet/leaflet.css',
    '/build/libs/leaflet/leaflet.js',
    '/build/libs/sweetalert2/sweetalert2.min.css',
    '/build/libs/sweetalert2/sweetalert2.min.js',
    '/build/css/bootstrap.min.css',
    '/build/css/icons.min.css',
    '/build/css/app.min.css',
    '/build/images/logo favicon 1.png'
];

// Install event - cache static assets
self.addEventListener('install', event => {
    console.log('Service Worker installing.');
    self.skipWaiting(); // aktifkan versi baru langsung
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll([
                '/', // halaman utama
                '/mobile/teaching-attendances',
                '/mobile/dashboard',
                '/mobile/presensi',
                '/mobile/jadwal',
                '/mobile/profile',
                '/manifest.json',
                '/build/libs/leaflet/leaflet.css',
                '/build/libs/leaflet/leaflet.js',
                '/build/libs/sweetalert2/sweetalert2.min.css',
                '/build/libs/sweetalert2/sweetalert2.min.js',
                '/build/css/bootstrap.min.css',
                '/build/css/icons.min.css',
                '/build/css/app.min.css',
                '/build/images/logo favicon 1.png'
            ]);
        })
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
    console.log('Service Worker activating.');
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(name => name.startsWith('presensi-cache-') && name !== CACHE_NAME)
                    .map(name => caches.delete(name))
            );
        })
    );
    self.clients.claim(); // langsung aktif di semua tab
});

// Fetch event - serve cached content when offline
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') return;

    // Skip external requests
    if (!url.origin.includes(self.location.origin)) return;

    // Jangan cache endpoint penting
    if (url.pathname.includes('/teaching-attendances') || url.pathname.includes('/login') || url.pathname.includes('/logout')) {
        event.respondWith(fetch(request));
        return;
    }

    // Handle API requests
    if (url.pathname.startsWith('/api/') || url.pathname.includes('/presensi/store')) {
        event.respondWith(
            fetch(request)
                .then(response => {
                    // Cache successful API responses
                    if (response.ok) {
                        const responseClone = response.clone();
                        caches.open(DYNAMIC_CACHE)
                            .then(cache => cache.put(request, responseClone));
                    }
                    return response;
                })
                .catch(() => {
                    // Return cached API response if available
                    return caches.match(request);
                })
        );
        return;
    }

    // Handle static assets and pages
    event.respondWith(
        caches.match(request)
            .then(cachedResponse => {
                if (cachedResponse) {
                    return cachedResponse;
                }

                return fetch(request)
                    .then(response => {
                        // Don't cache non-successful responses
                        if (!response.ok) return response;

                        const responseClone = response.clone();

                        // Cache pages and static assets
                        if (request.destination === 'document' ||
                            request.destination === 'style' ||
                            request.destination === 'script' ||
                            request.destination === 'image' ||
                            request.destination === 'font') {

                            caches.open(DYNAMIC_CACHE)
                                .then(cache => cache.put(request, responseClone));
                        }

                        return response;
                    })
                    .catch(() => {
                        // Return offline fallback for pages
                        if (request.destination === 'document') {
                            return caches.match('/mobile/dashboard');
                        }
                    });
            })
    );
});

// Background sync for offline presensi submissions
self.addEventListener('sync', event => {
    if (event.tag === 'presensi-sync') {
        event.waitUntil(syncPresensiData());
    }
});

// Push notifications
self.addEventListener('push', event => {
    if (event.data) {
        const data = event.data.json();
        const options = {
            body: data.body,
            icon: '/build/images/logo favicon 1.png',
            badge: '/build/images/logo favicon 1.png',
            vibrate: [100, 50, 100],
            data: {
                dateOfArrival: Date.now(),
                primaryKey: data.primaryKey
            }
        };
        event.waitUntil(
            self.registration.showNotification(data.title, options)
        );
    }
});

// Notification click handler
self.addEventListener('notificationclick', event => {
    event.notification.close();

    event.waitUntil(
        clients.openWindow('/mobile/dashboard')
    );
});

// Helper function to sync presensi data
async function syncPresensiData() {
    try {
        const cache = await caches.open(DYNAMIC_CACHE);
        const keys = await cache.keys();

        const presensiRequests = keys.filter(request =>
            request.url.includes('/presensi/store')
        );

        for (const request of presensiRequests) {
            try {
                await fetch(request);
                await cache.delete(request);
            } catch (error) {
                console.error('Failed to sync presensi data:', error);
            }
        }
    } catch (error) {
        console.error('Error during presensi sync:', error);
    }
}

// Periodic background sync for data updates
self.addEventListener('periodicsync', event => {
    if (event.tag === 'content-sync') {
        event.waitUntil(updateContent());
    }
});

async function updateContent() {
    try {
        const cache = await caches.open(DYNAMIC_CACHE);
        const keys = await cache.keys();

        // Update cached content in background
        for (const request of keys) {
            try {
                const response = await fetch(request);
                if (response.ok) {
                    await cache.put(request, response);
                }
            } catch (error) {
                console.log('Failed to update cached content:', error);
            }
        }
    } catch (error) {
        console.error('Error during content update:', error);
    }
}

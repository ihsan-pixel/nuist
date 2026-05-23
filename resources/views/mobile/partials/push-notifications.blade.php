@php
    $firebaseConfig = [
        'apiKey' => config('services.firebase.web_api_key'),
        'authDomain' => config('services.firebase.web_auth_domain'),
        'projectId' => config('services.firebase.project_id'),
        'storageBucket' => config('services.firebase.web_storage_bucket'),
        'messagingSenderId' => config('services.firebase.web_messaging_sender_id'),
        'appId' => config('services.firebase.web_app_id'),
        'measurementId' => config('services.firebase.web_measurement_id'),
    ];

    $pushEnabled = filled(config('services.firebase.web_api_key'))
        && filled(config('services.firebase.project_id'))
        && filled(config('services.firebase.web_messaging_sender_id'))
        && filled(config('services.firebase.web_app_id'))
        && filled(config('services.firebase.web_vapid_key'));
@endphp
<script>
    window.nuistPwaPushConfig = @json([
        'enabled' => $pushEnabled,
        'firebase' => $firebaseConfig,
        'vapidKey' => config('services.firebase.web_vapid_key'),
        'registerUrl' => route('mobile.push-token.store'),
        'unregisterUrl' => route('mobile.push-token.destroy'),
        'defaultUrl' => route('mobile.notifications'),
        'serviceWorkerUrl' => '/sw-v2.js?v=20260523-1',
        'deviceName' => request()->userAgent(),
        'platform' => 'pwa',
        'userId' => auth()->id(),
    ]);
</script>
<script>
    (function () {
        const config = window.nuistPwaPushConfig || {};

        if (!('serviceWorker' in navigator)) {
            return;
        }

        let swRegistrationPromise;

        function registerServiceWorker() {
            if (!swRegistrationPromise) {
                swRegistrationPromise = navigator.serviceWorker
                    .register(config.serviceWorkerUrl)
                    .then((registration) => {
                        console.log('SW loaded:', registration.scope);
                        return registration;
                    })
                    .catch((error) => {
                        console.error('SW failed:', error);
                        throw error;
                    });
            }

            return swRegistrationPromise;
        }

        window.nuistRegisterMobileServiceWorker = registerServiceWorker;
        registerServiceWorker().catch(() => {});
    })();
</script>
@if($pushEnabled)
    <script src="https://www.gstatic.com/firebasejs/10.13.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging-compat.js"></script>
    <script>
        (function () {
            const config = window.nuistPwaPushConfig || {};

            if (!config.enabled || !window.firebase || !('Notification' in window)) {
                return;
            }

            const tokenStorageKey = 'nuist_push_token_' + String(config.userId || 'guest');

            function csrfToken() {
                return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            }

            function jsonHeaders() {
                return {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                };
            }

            function updateBadge() {
                if (typeof window.updateNotificationBadge === 'function') {
                    window.updateNotificationBadge();
                }
            }

            function notificationUrlFromPayload(payload) {
                return payload?.data?.url || config.defaultUrl || '/mobile/notifications';
            }

            function showForegroundNotification(payload) {
                if (Notification.permission !== 'granted') {
                    return;
                }

                const title = payload?.notification?.title || payload?.data?.title || 'NUIST Mobile';
                const body = payload?.notification?.body || payload?.data?.body || '';
                const targetUrl = notificationUrlFromPayload(payload);
                const notification = new Notification(title, {
                    body: body,
                    icon: '/build/images/logo-favicon-1.png',
                    badge: '/build/images/logo-favicon-1.png',
                    data: {
                        url: targetUrl,
                    },
                });

                notification.onclick = function () {
                    window.focus();
                    window.location.href = targetUrl;
                };
            }

            async function persistToken(token) {
                await fetch(config.registerUrl, {
                    method: 'POST',
                    headers: jsonHeaders(),
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        token: token,
                        platform: config.platform,
                        device_name: config.deviceName,
                    }),
                });
            }

            async function unregisterToken(token) {
                await fetch(config.unregisterUrl, {
                    method: 'DELETE',
                    headers: jsonHeaders(),
                    credentials: 'same-origin',
                    keepalive: true,
                    body: JSON.stringify({
                        token: token,
                    }),
                });
            }

            async function syncPushToken() {
                if (Notification.permission === 'denied') {
                    return;
                }

                const permission = Notification.permission === 'granted'
                    ? 'granted'
                    : await Notification.requestPermission();

                if (permission !== 'granted') {
                    return;
                }

                if (!firebase.apps.length) {
                    firebase.initializeApp(config.firebase);
                }

                const messaging = firebase.messaging();
                const registration = await window.nuistRegisterMobileServiceWorker();
                const token = await messaging.getToken({
                    vapidKey: config.vapidKey,
                    serviceWorkerRegistration: registration,
                });

                if (!token) {
                    return;
                }

                const currentToken = localStorage.getItem(tokenStorageKey);
                if (currentToken !== token) {
                    await persistToken(token);
                    localStorage.setItem(tokenStorageKey, token);
                } else {
                    await persistToken(token);
                }

                messaging.onMessage((payload) => {
                    updateBadge();
                    showForegroundNotification(payload);
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                window.setTimeout(function () {
                    syncPushToken().catch((error) => {
                        console.error('Push token sync failed:', error);
                    });
                }, 800);

                const logoutForms = [
                    document.getElementById('logout-form'),
                    document.getElementById('siswaLogoutForm'),
                ].filter(Boolean);

                logoutForms.forEach(function (form) {
                    form.addEventListener('submit', function () {
                        const token = localStorage.getItem(tokenStorageKey);
                        if (!token) {
                            return;
                        }

                        unregisterToken(token).catch(() => {});
                        localStorage.removeItem(tokenStorageKey);
                    });
                });
            });
        })();
    </script>
@endif

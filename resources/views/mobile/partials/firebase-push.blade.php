<script type="module">
(() => {
    @php
        $firebaseWebConfig = [
            'apiKey' => config('services.firebase.web_api_key'),
            'authDomain' => config('services.firebase.web_auth_domain'),
            'projectId' => config('services.firebase.web_project_id'),
            'storageBucket' => config('services.firebase.web_storage_bucket'),
            'messagingSenderId' => config('services.firebase.web_messaging_sender_id'),
            'appId' => config('services.firebase.web_app_id'),
        ];
    @endphp
    const config = @json($firebaseWebConfig);
    const vapidKey = @json(config('services.firebase.web_vapid_key'));
    const registerUrl = @json(route('mobile.push-token.store'));
    const csrf = @json(csrf_token());

    const isConfigured = Object.values(config).every(value => typeof value === 'string' && value.length > 0)
        && typeof vapidKey === 'string' && vapidKey.length > 0;

    if (!isConfigured || !('serviceWorker' in navigator) || !('Notification' in window)) {
        return;
    }

    async function registerWebPush() {
        if (Notification.permission === 'denied') {
            return;
        }

        const permission = Notification.permission === 'granted'
            ? 'granted'
            : await Notification.requestPermission();
        if (permission !== 'granted') {
            return;
        }

        const [{ initializeApp }, { getMessaging, getToken, onMessage }, registration] = await Promise.all([
            import('https://www.gstatic.com/firebasejs/10.14.1/firebase-app.js'),
            import('https://www.gstatic.com/firebasejs/10.14.1/firebase-messaging.js'),
            navigator.serviceWorker.register('/firebase-messaging-sw.js', { scope: '/firebase-messaging/' }),
        ]);

        const app = initializeApp(config, 'nuist-web');
        const messaging = getMessaging(app);
        const token = await getToken(messaging, { vapidKey, serviceWorkerRegistration: registration });
        if (!token) {
            return;
        }

        await fetch(registerUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                token,
                platform: 'web',
                device_name: navigator.userAgent.slice(0, 255),
            }),
        });

        onMessage(messaging, payload => {
            const notification = payload.notification || {};
            if (notification.title && Notification.permission === 'granted') {
                new Notification(notification.title, {
                    body: notification.body || '',
                    icon: '/build/images/logo-light.png',
                });
            }
        });
    }

    registerWebPush().catch(error => {
        // Push is optional; login and presensi must continue if browser setup fails.
        console.warn('Web push initialization skipped:', error);
    });
})();
</script>

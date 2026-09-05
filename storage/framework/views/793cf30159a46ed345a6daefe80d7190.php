<script type="module">
(() => {
    <?php
        $firebaseWebConfig = [
            'apiKey' => config('services.firebase.web_api_key'),
            'authDomain' => config('services.firebase.web_auth_domain'),
            'projectId' => config('services.firebase.web_project_id'),
            'storageBucket' => config('services.firebase.web_storage_bucket'),
            'messagingSenderId' => config('services.firebase.web_messaging_sender_id'),
            'appId' => config('services.firebase.web_app_id'),
        ];
    ?>
    const config = <?php echo json_encode($firebaseWebConfig, 15, 512) ?>;
    const vapidKey = <?php echo json_encode(config('services.firebase.web_vapid_key'), 15, 512) ?>;
    const registerUrl = <?php echo json_encode(route('mobile.push-token.store'), 15, 512) ?>;
    const csrf = <?php echo json_encode(csrf_token(), 15, 512) ?>;

    const missingConfig = Object.entries(config)
        .filter(([, value]) => typeof value !== 'string' || value.length === 0)
        .map(([key]) => key);
    if (typeof vapidKey !== 'string' || vapidKey.length === 0) {
        missingConfig.push('vapidKey');
    }

    const isConfigured = missingConfig.length === 0;
    const canUseWebPush = 'serviceWorker' in navigator && 'Notification' in window;
    let firebaseMessaging = null;

    const log = (message, details = {}) => {
        console.info('[FCM WEB]', message, details);
    };

    const maskToken = token => {
        if (typeof token !== 'string' || token.length < 12) {
            return '[invalid/empty]';
        }

        return `${token.slice(0, 6)}...${token.slice(-6)}`;
    };

    if (!isConfigured) {
        console.warn('[FCM WEB] Firebase Web configuration is incomplete.', { missing: missingConfig });
    }

    if (!canUseWebPush) {
        console.warn('[FCM WEB] Browser does not support Service Worker or Notification API.');
        return;
    }

    function addPermissionTrigger() {
        if (document.getElementById('fcm-web-enable') || Notification.permission !== 'default') {
            return;
        }

        const button = document.createElement('button');
        button.id = 'fcm-web-enable';
        button.type = 'button';
        button.textContent = 'Aktifkan notifikasi';
        button.style.cssText = 'position:fixed;right:16px;bottom:88px;z-index:2000;border:0;border-radius:999px;padding:10px 16px;background:#0f766e;color:#fff;font:600 13px Poppins,sans-serif;box-shadow:0 8px 24px rgba(15,118,110,.25);cursor:pointer;';
        button.addEventListener('click', () => {
            button.disabled = true;
            registerWebPush(true).finally(() => button.remove());
        });
        document.body.appendChild(button);
    }

    async function waitForActiveWorker(registration) {
        if (registration.active) {
            return registration;
        }

        const worker = registration.installing || registration.waiting;
        if (!worker) {
            throw new Error('Firebase Messaging service worker belum aktif.');
        }

        await new Promise((resolve, reject) => {
            worker.addEventListener('statechange', () => {
                if (worker.state === 'activated') {
                    resolve();
                } else if (worker.state === 'redundant') {
                    reject(new Error('Firebase Messaging service worker menjadi redundant.'));
                }
            });
        });

        return registration;
    }

    async function registerWebPush(requestPermission = false) {
        if (!isConfigured) {
            return;
        }

        if (Notification.permission === 'denied') {
            log('Permission denied. Aktifkan notifikasi dari pengaturan browser.');
            return;
        }

        if (Notification.permission === 'default' && !requestPermission) {
            log('Permission masih default. Menunggu aksi user.');
            addPermissionTrigger();
            return;
        }

        const permission = Notification.permission === 'granted'
            ? 'granted'
            : await Notification.requestPermission();
        log('Permission: ' + permission);
        if (permission !== 'granted') {
            return;
        }

        const [{ initializeApp }, { getMessaging, getToken, onMessage }, registration] = await Promise.all([
            import('https://www.gstatic.com/firebasejs/10.14.1/firebase-app.js'),
            import('https://www.gstatic.com/firebasejs/10.14.1/firebase-messaging.js'),
            navigator.serviceWorker.register('/firebase-messaging-sw.js', { scope: '/firebase-messaging/' }),
        ]);
        await waitForActiveWorker(registration);
        log('Service worker registered', { scope: registration.scope, state: registration.active?.state });

        if (!firebaseMessaging) {
            const app = initializeApp(config, 'nuist-web');
            log('Firebase initialized', { projectId: config.projectId });
            firebaseMessaging = getMessaging(app);
            onMessage(firebaseMessaging, payload => {
                const notification = payload.notification || {};
                log('Foreground message received', { type: payload.data?.type || null });
                if (notification.title && Notification.permission === 'granted') {
                    new Notification(notification.title, {
                        body: notification.body || '',
                        icon: '/build/images/logo-light.png',
                        data: payload.data || {},
                    });
                }
            });
        }

        const token = await getToken(firebaseMessaging, { vapidKey, serviceWorkerRegistration: registration });
        if (!token) {
            console.warn('[FCM WEB] getToken returned an empty token.');
            return;
        }
        log('Token generated', { token: maskToken(token) });

        const response = await fetch(registerUrl, {
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
        if (!response.ok) {
            throw new Error(`Token endpoint gagal (${response.status}).`);
        }
        log('Token saved', { endpoint: registerUrl, platform: 'web' });
    }

    window.addEventListener('focus', () => {
        if (Notification.permission === 'granted') {
            registerWebPush().catch(error => console.warn('[FCM WEB] Refresh failed:', error));
        }
    });

    /* Push is optional; login and presensi must continue if browser setup fails. */
    window.addEventListener('error', error => {
        if (String(error?.message || '').includes('firebase')) {
            console.warn('[FCM WEB] Runtime error:', error.message);
        }
    });
    /* Keep failures visible without exposing credentials or the full token. */
    registerWebPush(false).catch(error => {
        // Push is optional; login and presensi must continue if browser setup fails.
        console.warn('[FCM WEB] Initialization skipped:', error);
    });
})();
</script>
<?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/mobile/partials/firebase-push.blade.php ENDPATH**/ ?>
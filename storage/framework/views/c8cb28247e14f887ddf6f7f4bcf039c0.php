/* Firebase Web Messaging worker. Configuration contains public Firebase app values only. */
importScripts('https://www.gstatic.com/firebasejs/10.14.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.14.1/firebase-messaging-compat.js');

firebase.initializeApp(<?php echo json_encode($firebaseConfig, 15, 512) ?>);

const messaging = firebase.messaging();

messaging.onBackgroundMessage(payload => {
    const notification = payload.notification || {};
    const title = notification.title || 'NUIST';
    const options = {
        body: notification.body || 'Ada informasi baru dari NUIST.',
        icon: '/build/images/logo-light.png',
        data: payload.data || {},
    };

    self.registration.showNotification(title, options);
});

self.addEventListener('notificationclick', event => {
    event.notification.close();
    const target = event.notification.data?.url || '/mobile/notifications';
    event.waitUntil(clients.openWindow(target));
});
<?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/firebase-messaging-sw.blade.php ENDPATH**/ ?>
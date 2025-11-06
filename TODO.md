# TODO: Implement Realtime Active Users with Laravel Reverb

## 1. Install Laravel Reverb
- Add "laravel/reverb": "^1.0" to composer.json require section
- Run composer install

## 2. Configure Broadcasting
- Publish Reverb configuration: php artisan reverb:install
- Update config/broadcasting.php to use reverb driver
- Update config/reverb.php with appropriate settings for production

## 3. Create ActiveUsersUpdated Event
- Create app/Events/ActiveUsersUpdated.php
- Implement ShouldBroadcast interface
- Broadcast on 'active-users' channel with active users data

## 4. Fire Event on User Activity
- Update app/Http/Middleware/UpdateLastSeen.php to fire the event after updating last_seen

## 5. Update Frontend
- Add laravel-echo and pusher-js to package.json dependencies
- Run npm install
- Update resources/views/active-users/index.blade.php to use Echo for realtime updates instead of polling

## 6. Test and Deploy
- Test locally with php artisan reverb:start
- Ensure hosting supports WebSockets
- Deploy changes to hosting

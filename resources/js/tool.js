Nova.booting((Vue, router, store) => {
    Vue.component('notification-link', require('./components/NotificationLink'))
    Vue.component('notification-item', require('./components/NotificationItem'))
    Vue.component('notifications-dropdown', require('./components/NotificationsDropdown'))
})
import Echo from "laravel-echo"
window.Pusher = require('pusher-js');
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: false
});
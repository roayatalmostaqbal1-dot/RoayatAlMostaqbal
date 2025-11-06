import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';



import Echo from "laravel-echo";
import Pusher from 'pusher-js';


const echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: 443,
    wssPort: 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
// const echo = new Echo({
//     broadcaster: 'reverb',
//     host: window.location.hostname + '/reverb', // مسار Reverb في Nginx
//     wsHost: window.location.hostname,
//     wsPort: 443,
//     wss: true,
//     disableStats: true,                         // 🔹 مسار البروكسي الصحيح
// });

// Export as a Vue plugin with install function
export default {
  install(app) {
    app.config.globalProperties.$echo = echo;
  }
};

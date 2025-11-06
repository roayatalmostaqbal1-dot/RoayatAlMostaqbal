import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';



import Echo from "laravel-echo";
import Pusher from 'pusher-js';


// const echo = new Echo({
//     broadcaster: 'reverb',
//     key: import.meta.env.VITE_REVERB_APP_KEY,
//     wsHost: import.meta.env.VITE_REVERB_HOST,
//     wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
//     wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
const echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    host: import.meta.env.VITE_REVERB_HOST,      // فقط اسم النطاق
    wsHost: import.meta.env.VITE_REVERB_HOST,    // اسم النطاق أيضاً
    wsPort: 443,                                  // لأن Nginx سيعمل proxy على HTTPS
    wss: true,                                    // استخدام WSS
    path: '/reverb/app',                           // مسار البروكسي في Nginx
    forceTLS: true,
    enabledTransports: ['ws', 'wss'],
});
// Export as a Vue plugin with install function
export default {
  install(app) {
    app.config.globalProperties.$echo = echo;
  }
};

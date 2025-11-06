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
    key: import.meta.env.VITE_REVERB_APP_KEY,      // مفتاح Reverb
    wsHost: import.meta.env.VITE_REVERB_HOST,      // roayatalmostaqbal.net
    wsPort: 443,                                   // لأننا نستخدم HTTPS
    wss: true,                                     // البروتوكول الآمن
    forceTLS: true,                                // اجبار TLS
    enabledTransports: ['ws', 'wss'],
    path: '/reverb/app',                            // 🔹 مسار البروكسي الصحيح
});

// Export as a Vue plugin with install function
export default {
  install(app) {
    app.config.globalProperties.$echo = echo;
  }
};

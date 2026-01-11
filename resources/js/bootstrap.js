import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';



import Echo from "laravel-echo";
import Pusher from 'pusher-js';

const isHttps = window.location.protocol === 'https:';
const currentHost = window.location.hostname;

const echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: currentHost,
    wsPort: isHttps ? 443 : (import.meta.env.VITE_REVERB_PORT ?? 80),
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: isHttps,
    enabledTransports: ['ws', 'wss'],
    authorizer: (channel, options) => {
        return {
            authorize: (socketId, callback) => {
                console.log('Authorizing Echo channel:', channel.name);
                axios.post('/broadcasting/auth', {
                    socket_id: socketId,
                    channel_name: channel.name
                }, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                        Accept: 'application/json'
                    }
                })
                    .then(response => {
                        callback(false, response.data);
                    })
                    .catch(error => {
                        console.error('Echo Auth Error:', error.response?.data || error.message);
                        callback(true, error);
                    });
            }
        };
    },
});

window.Echo = echo;

export default {
    install(app) {
        app.config.globalProperties.$echo = echo;
    }
};

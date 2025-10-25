import './bootstrap';

import { createApp, markRaw } from "vue";
import { createPinia } from "pinia";
// import router from "@/vue/router/router";
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";
import { initFlowbite } from 'flowbite'
import App from "@/vue/app.vue";
// import { useAuthStore } from "@/vue/stores/auth";


const app = createApp(App);
const pinia = createPinia(piniaPluginPersistedstate);

// pinia.use(({ store }) => {
// //   store.router = markRaw(router);
// });
app.config.devtools = true


app.use(pinia);
// app.use(router);

initFlowbite();
// const auth = useAuthStore();

// if (auth.token) {
//     axios.defaults.headers.common['Authorization'] = `Bearer ${auth.token}`;
//     auth.handleGetUser();
// }

app.mount("#app");

import echo from './bootstrap';

import { createApp, markRaw } from "vue";
import { createPinia } from "pinia";
import router from "@/vue/router/router";
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";
import { initFlowbite } from 'flowbite'
import App from "@/vue/app.vue";


const app = createApp(App);
const pinia = createPinia();

pinia.use(piniaPluginPersistedstate);
pinia.use(({ store }) => {
  store.router = markRaw(router);
});

app.config.devtools = true

app.use(pinia);
app.use(router);
app.use(echo);

initFlowbite();

app.mount("#app");

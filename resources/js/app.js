import './bootstrap';
import { createApp } from 'vue';
import ProductList from './components/ProductList.vue';
import ProductForm from './components/ProductForm.vue';
import VideoPlayer from './components/VideoPlayer.vue';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';

import Alpine from 'alpinejs';


window.Alpine = Alpine;
Alpine.start();

const app = createApp({});

app.component('product-list', ProductList);
app.component('product-form', ProductForm);
app.component('video-player', VideoPlayer);



app.use(Toast);

app.mount('#app');

require('./bootstrap');
window.Vue = require('vue');

import Vue from 'vue'
import App from './components/App.vue'
import Navbar from './components/parts/Navbar.vue'
import Workers from './components/pages/workers/Index.vue'
import ValidationErrors from './components/parts/ValidationErrors.vue' // универсальный компонент для отображения ошибок валидации

// регистрирую глобально, чтобы не дублировать код,
// т.к. компоненты могут быть использованы в нескольких частях приложения
Vue.component('navbar', Navbar);
Vue.component('workers', Workers);
Vue.component('validation-errors', ValidationErrors)

const app = new Vue({
    el: '#app',
    render: h => h(App),
});

window.appData = {};

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

require('jquery-lazyload');
require('fuelux/js/tree.js');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('explorer', require('./components/Explorer.vue'));
Vue.component('rc', require('./components/Rc.vue'));
Vue.component('rename', require('./components/Rename.vue'));
Vue.component('copy', require('./components/Copy.vue'));

const app = new Vue({
    el: '#app',
    data: window.appData
});

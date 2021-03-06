window.appData = {
    app: {
        rcShow: function() {
            window.appData.rc.show();
        }
    }
};

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.decorator = require('./decorator');
require('./bootstrap');

require('jquery-lazyload');
require('bootstrap-fileinput');
require('bootstrap-fileinput/js/locales/ru.js');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('select_catalog', require('./components/SelectCatalog.vue'));
Vue.component('select_catalog_selected', require('./components/SelectCatalogSelected.vue'));
Vue.component('explorer', require('./components/Explorer.vue'));
Vue.component('rc', require('./components/Rc.vue'));
Vue.component('rename', require('./components/Rename.vue'));
Vue.component('copy', require('./components/Copy.vue'));
Vue.component('to_new_folder', require('./components/ToNewFolder.vue'));
Vue.component('new_folder', require('./components/NewFolder.vue'));
Vue.component('delete', require('./components/Delete.vue'));
Vue.component('carousel', require('./components/Carousel.vue'));
Vue.component('upload', require('./components/Upload.vue'));
Vue.component('torrent', require('./components/Torrent.vue'));
Vue.component('torrent_edit', require('./components/TorrentEdit.vue'));
Vue.component('torrent_edit_tree_item', require('./components/TorrentEditTreeItem.vue'));
Vue.component('editor_txt', require('./components/EditorTxt.vue'));
//Vue.component('ya_disk', require('./components/YaDisk.vue'));

const app = new Vue({
    el: '#app',
    data: window.appData.app
});

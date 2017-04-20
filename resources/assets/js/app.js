window.appData = {
    rc: {
        show: function() {
            jQuery('#tvModal').modal('show');
        },
        playFiles: function (files) {
            jQuery.ajax('/player-play/', {
                method: 'POST',
                data: {files: files},
                success: function (data) {
                    window.appData.rc.show();
                }
            });
        },
        pause: function (name) {
            jQuery.ajax('/player-pause/', {method: 'POST'});
        },
        quit: function (name) {
            jQuery.ajax('/player-quit/', {method: 'POST'});
        }
    },
    explorer: {
        uri: '/',
        items: [],
        itemsChecked: [],
        getData: function (uri) {
            jQuery.ajax('/dir-list/' + uri, {
                data: {},
                method: 'GET',
                success: function (data) {
                    for (var i in data.items) {
                        data.items[i].uri = data.uri ? data.uri + '/' + data.items[i].name : data.items[i].name;
                    }
                    window.appData.explorer.uri = data.uri;
                    window.appData.explorer.items = data.items;
                }
            });
        },
        download: function (uri) {
            window.location.href = '/dir-download/' + uri;
            return false;
        },
        playChecked: function () {
            var files = [];
            for (var i in window.appData.explorer.itemsChecked) {
                files.push(window.appData.explorer.itemsChecked[i].uri);
            }
            window.appData.rc.playFiles(files);
        }
    }
};

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('explorer', require('./components/Explorer.vue'));
Vue.component('rc', require('./components/Rc.vue'));

const app = new Vue({
    el: '#app',
    data: {}
});

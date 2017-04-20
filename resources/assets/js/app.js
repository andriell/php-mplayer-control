window.appData = {
    rc: {
        length: 0,
        timePos: 0,
        volume: 0,
        mute: false,
        show: function() {
            jQuery('#tvModal').modal('show');
        },
        playVideo: function (uri) {
            jQuery.ajax('/player-play-video/' + uri, {
                success: function (data) {
                    window.appData.rc.show();
                }
            });
        },
        pause: function () {
            jQuery.ajax('/player-pause/');
        },
        quit: function () {
            jQuery.ajax('/player-quit/');
        },
        getVolume: function () {
            jQuery.ajax('/player-get-volume/', {
                success: function (data) {
                    window.appData.rc.volume = data.volume;
                }
            });
        },
        setVolume: function () {
            jQuery.ajax('/player-set-volume/' + window.appData.rc.volume);
        },
        getTimePos: function () {
            jQuery.ajax('/player-get-time-pos/', {
                success: function (data) {
                    window.appData.rc.timePos = data.time_pos;
                    window.appData.rc.length = data.length;
                }
            });
        },
        setTimePos: function () {
            jQuery.ajax('/player-set-time-pos/' + window.appData.rc.volume);
        },
        getLength: function () {
            jQuery.ajax('/player-get-length/', {
                success: function (data) {
                    window.appData.rc.length = data.length;
                }
            });
        },
        switchMute: function () {
            window.appData.rc.mute = !window.appData.rc.mute;
            jQuery.ajax('/player-set-mute/' + window.appData.rc.mute ? 't' : 'f');
        },
        switchAudio: function () {
            jQuery.ajax('/player-switch-audio/');
        },
        switchVideo: function () {
            jQuery.ajax('/player-switch-video/');
        }
    },
    explorer: {
        uri: '/',
        items: [],
        itemsChecked: [],
        getData: function (uri) {
            jQuery.ajax('/dir-list/' + uri, {
                data: {},
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
        playVideo: function () {
            window.appData.rc.playVideo(window.appData.explorer.itemsChecked[0].uri);
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
    data: window.appData
});

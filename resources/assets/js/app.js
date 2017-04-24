window.appData = {
    rc: {
        length: 0,
        timePos: 0,
        timeP: 0,
        volume: 100,
        mute: false,
        show: function () {
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
                    window.appData.rc.timeP = Math.round((data.time_pos / data.length) * 1000000);
                }
            });
        },
        setTimePos: function () {
            jQuery.ajax('/player-get-time-pos/', {
                success: function (data) {
                    window.appData.rc.timePos = data.time_pos;
                    window.appData.rc.length = data.length;

                    jQuery.ajax('/player-set-time-pos/' + Math.round((window.appData.rc.timeP / 1000000) * data.length));
                }
            });
        },
        switchMute: function () {
            window.appData.rc.mute = !window.appData.rc.mute;
            jQuery.ajax('/player-set-mute/' + (window.appData.rc.mute ? 't' : 'f'));
        },
        switchAudio: function () {
            jQuery.ajax('/player-switch-audio/');
        },
        switchVideo: function () {
            jQuery.ajax('/player-switch-video/');
        }
    },
    rename: {
        dir: '',
        oldName: '',
        newName: '',
        status: '',
        show: function () {
            jQuery('#renameModal').modal('show');
        },
        hide: function () {
            jQuery('#renameModal').modal('hide');
        },
        fileRename: function () {
            jQuery.ajax('/dir-mv/', {
                method: 'POST',
                data: {
                    'uri_dir':  window.appData.rename.dir,
                    'old_name': window.appData.rename.oldName,
                    'new_name': window.appData.rename.newName
                },
                success: function (data) {
                    setTimeout(function() {
                        window.appData.rename.hide();
                        window.appData.explorer.reload();
                    }, 2000);
                    if (data.status) {
                        window.appData.rename.oldName = window.appData.rename.newName;
                        window.appData.rename.status = 'Переименовано.';
                    } else {
                        window.appData.rename.status = 'Ошибка.';
                    }
                }
            });
        }
    },
    explorer: {
        uri: '',
        path: [],
        items: [],
        itemsChecked: [],
        reload: function () {
            window.appData.explorer.getData(window.appData.explorer.uri);
        },
        getData: function (uri) {
            jQuery.ajax('/dir-list/' + uri, {
                data: {},
                success: function (data) {
                    var i;
                    for (i in data.items) {
                        data.items[i].uri = data.uri ? data.uri + '/' + data.items[i].name : data.items[i].name;
                        data.items[i].dir = data.uri;
                    }
                    window.appData.explorer.items = data.items;
                    window.appData.explorer.uri = data.uri;

                    var path = [], uriTmp = '', uriArr = data.uri.split('/');
                    for (i in uriArr) {
                        if (uriArr[i] == '') {
                            continue;
                        }
                        uriTmp += '' + uriArr[i];
                        path.push({
                            name: uriArr[i],
                            uri: uriTmp
                        });
                        uriTmp += '/';
                    }
                    window.appData.explorer.path = path;
                    window.appData.explorer.unchecked();
                }
            });
        },
        download: function (uri) {
            window.location.href = '/dir-download/' + uri;
            return false;
        },
        playVideo: function () {
            window.appData.rc.playVideo(window.appData.explorer.itemsChecked[0].uri);
        },
        unchecked: function() {
            window.appData.explorer.itemsChecked = [];
        },
        fileRename: function() {
            if (window.appData.explorer.itemsChecked.length != 1) {
                window.appData.rename.status = 'Не знаю как переименовывать ' + window.appData.explorer.itemsChecked.length + ' файлов.';
                window.appData.rename.show();
                return;
            }
            window.appData.rename.status = '';
            var item = window.appData.explorer.itemsChecked[0];
            window.appData.rename.oldName = item.name;
            window.appData.rename.newName = item.name;
            window.appData.rename.dir = item.dir;
            window.appData.rename.show();
        }
    }
};

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

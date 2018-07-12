<template>
    <div class="modal fade rc" id="tvModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="glyphicon glyphicon-remove"></span></button>
                    <h4 class="modal-title" id="myModalLabel">{{filename}}</h4>
                </div>
                <div class="modal-body">
                    <div class="row rc-row-play">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button type="button" class="btn btn-default" v-on:click="stepTimePos(-30)"><span class="glyphicon glyphicon-backward"></span></button>
                            <button type="button" class="btn btn-default btn-big" v-on:click="pause()"><span v-if="!paused" class="glyphicon glyphicon-pause"></span><span v-if="paused" class="glyphicon glyphicon-play"></span></button>
                            <button type="button" class="btn btn-default" v-on:click="stepTimePos(30)"><span class="glyphicon glyphicon-forward"></span></button>
                        </div>
                    </div>
                    <div class="row rc-row-sound">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <button type="button" class="btn btn-default rc-btn-sound" v-on:click="switchMute()">
                                <span class="glyphicon glyphicon-volume-off" v-if="!mute"></span>
                                <span class="glyphicon glyphicon-volume-up" v-if="mute"></span>
                                Звук
                            </button>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                            <input type="range" min="0" max="100" step="1" v-model="volume" v-on:change="setVolume()" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="range" min="0" max="1000000" step="1" v-model="timeP" v-on:change="setTimePos()" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">{{ f.seconds(timePosEmulation) }}</div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">{{ f.seconds(length) }}</div>
                    </div>
                    <div class="row rc-row-button1">
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                            <button type="button" class="btn btn-default" v-on:click="switchAudio()"><span class="glyphicon glyphicon-repeat"></span> <span class="glyphicon glyphicon-music"></span></button>
                            <button type="button" class="btn btn-default" v-on:click="switchVideo()"><span class="glyphicon glyphicon-repeat"></span> <span class="glyphicon glyphicon-facetime-video"></span></button>
                            <button type="button" class="btn btn-default" v-on:click="switchSubtitle()"><span class="glyphicon glyphicon-repeat"></span> <span class="glyphicon glyphicon-subtitles"></span></button>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <button type="button" class="btn btn-default" v-on:click="quit()"><span class="glyphicon glyphicon-off"></span></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><a v-on:click="showLastFiles()">Последнии файлы</a></div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-color-settings"><a v-on:click="showColorSettings()">Настройки изображения</a></div>
                    </div>
                    <div class="row rc-last-play" v-if="isLastFile">
                        <template v-for="(item, itemId) in lastFile">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 rc-last-play-name">
                                {{ item.name }}
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 rc-last-play-buttons">
                                <button type="button" class="btn btn-default" v-on:click="playVideo(item.uri)"><span class="glyphicon glyphicon-play"></span></button>
                                <button type="button" class="btn btn-default" v-on:click="playNextVideo(item.uri)"><span class="glyphicon glyphicon-step-forward"></span></button>
                            </div>
                        </template>
                    </div>
                    <div class="rc-row-color-settings" v-if="isColorSettings">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><label for="brightness" v-on:click="setBrightness(0)">Яркость</label></div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9"><input id="brightness" type="range" min="-100" max="100" step="1" v-model="brightness" v-on:change="setBrightness()" /></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><label for="contrast" v-on:click="setContrast(0)">Контраст</label></div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9"><input id="contrast" type="range" min="-100" max="100" step="1" v-model="contrast" v-on:change="setContrast()" /></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><label for="gamma" v-on:click="setGamma(0)">Гамма</label></div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9"><input id="gamma" type="range" min="-100" max="100" step="1" v-model="gamma" v-on:change="setGamma()" /></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><label for="hue" v-on:click="setHue(0)">Оттенок</label></div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9"><input id="hue" type="range" min="-100" max="100" step="1" v-model="hue" v-on:change="setHue()" /></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><label for="range" v-on:click="setSaturation(0)">Насыщенность</label></div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9"><input id="range" type="range" min="-100" max="100" step="1" v-model="saturation" v-on:change="setSaturation()" /></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            var localData = window.appData.rc = {
                length: 0,
                filename: '',
                timePos: 0,
                timePosEmulation: 0,
                timeP: 0,
                volume: 100,
                brightness: 0,
                contrast: 0,
                gamma: 0,
                hue: 0,
                saturation: 0,
                mute: false,
                run: false,
                paused: true,
                lastUpdate: new Date().getTime(),
                autoUpdate: true,
                f: window.decorator,
                isLastFile: false,
                isColorSettings: false,
                lastFile: [],
                show: function () {
                    localData.update();
                    jQuery('#tvModal').modal('show');
                },
                playVideo: function (uri) {
                    jQuery.ajax('/player-play-video/' + uri, {
                        success: function (data) {
                            localData.show();
                        }
                    });
                },
                playNextVideo: function (uri) {
                    jQuery.ajax('/player-play-next-video/' + uri, {
                        success: function (data) {
                            localData.show();
                        }
                    });
                },
                showLastFiles: function () {
                    localData.isLastFile = !localData.isLastFile;
                },
                showColorSettings: function () {
                    localData.isColorSettings = !localData.isColorSettings;
                },
                pause: function () {
                    jQuery.ajax('/player-pause/', {
                        success: function (data) {
                            localData.update();
                        }
                    });
                },
                stepTimePos: function (int) {
                    jQuery.ajax('/player-step-time-pos/' + int, {
                        success: function (data) {
                            localData.update();
                        }
                    });
                },
                quit: function () {
                    jQuery.ajax('/player-quit/', {
                        success: function (data) {
                            localData.update();
                        }
                    });
                },
                update: function () {
                    jQuery.ajax('/player-get-info/', {
                        success: function (data) {
                            if (data.run) {
                                localData.paused = data.pause == 'yes';
                                localData.mute = data.mute == 'yes';
                                localData.filename = data.filename;
                                localData.volume = parseFloat(data.volume);
                                localData.brightness = parseInt(data.brightness);
                                localData.contrast = parseInt(data.contrast);
                                localData.gamma = parseInt(data.gamma);
                                localData.hue = parseInt(data.hue);
                                localData.saturation = parseInt(data.saturation);
                                localData.length = parseFloat(data.length);
                                localData.timePos = parseFloat(data.time_pos);
                                localData.timePosEmulation = localData.timePos;
                                localData.timeP = Math.round((localData.timePosEmulation / localData.length) * 1000000);
                            } else {
                                localData.paused = true;
                                localData.mute = false;
                                localData.filename = '';
                                localData.volume = 100;
                                localData.length = 0;
                                localData.timePos = 0;
                                localData.timePosEmulation = 0;
                                localData.timeP = 0;
                                localData.brightness = 0;
                                localData.contrast = 0;
                                localData.gamma = 0;
                                localData.hue = 0;
                                localData.saturation = 0;
                            }
                            localData.lastFile = data.last_file;
                            localData.lastUpdate = new Date().getTime();
                        }
                    });
                },
                setVolume: function () {
                    jQuery.ajax('/player-set-volume/' + localData.volume, {
                        success: function (data) {
                            localData.update();
                        }
                    });
                },
                setTimePos: function () {
                    localData.autoUpdate = false;
                    var newTimePos = Math.round((localData.timeP / 1000000) * localData.length);
                    jQuery.ajax('/player-set-time-pos/' + newTimePos, {
                        success: function (data) {
                            localData.update();
                            localData.autoUpdate = true;
                        }
                    });
                },
                switchMute: function () {
                    localData.mute = !localData.mute;
                    jQuery.ajax('/player-set-mute/' + (localData.mute ? 't' : 'f'), {
                        success: function (data) {
                            localData.update();
                        }
                    });
                },
                switchAudio: function () {
                    jQuery.ajax('/player-switch-audio/', {
                        success: function (data) {
                            localData.update();
                        }
                    });
                },
                switchVideo: function () {
                    jQuery.ajax('/player-switch-video/', {
                        success: function (data) {
                            localData.update();
                        }
                    });
                },
                switchSubtitle: function () {
                    jQuery.ajax('/player-switch-subtitle/', {
                        success: function (data) {
                            localData.update();
                        }
                    });
                },
                setBrightness: function (v) {
                    if (typeof v !== 'undefined') {
                        localData.brightness = v;
                    }
                    jQuery.ajax('/player-set-brightness/' + localData.brightness, {
                        success: function (data) {
                            localData.update();
                        }
                    });
                },
                setContrast: function (v) {
                    if (typeof v !== 'undefined') {
                        localData.contrast = v;
                    }
                    jQuery.ajax('/player-set-contrast/' + localData.contrast, {
                        success: function (data) {
                            localData.update();
                        }
                    });
                },
                setGamma: function (v) {
                    if (typeof v !== 'undefined') {
                        localData.gamma = v;
                    }
                    jQuery.ajax('/player-set-gamma/' + localData.gamma, {
                        success: function (data) {
                            localData.update();
                        }
                    });
                },
                setHue: function (v) {
                    if (typeof v !== 'undefined') {
                        localData.hue = v;
                    }
                    jQuery.ajax('/player-set-hue/' + localData.hue, {
                        success: function (data) {
                            localData.update();
                        }
                    });
                },
                setSaturation: function (v) {
                    if (typeof v !== 'undefined') {
                        localData.saturation = v;
                    }
                    jQuery.ajax('/player-set-saturation/' + localData.saturation, {
                        success: function (data) {
                            localData.update();
                        }
                    });
                },
            };
            setInterval(function() {
                if (localData.paused || !localData.autoUpdate) {
                    return;
                }
                localData.timePosEmulation = localData.timePos + (new Date().getTime() - localData.lastUpdate) / 1000;
                if (localData.timePosEmulation >= localData.length) {
                    localData.update();
                }
                localData.timeP = Math.round((localData.timePosEmulation / localData.length) * 1000000);
            }, 1000);
            return localData;
        },
        mounted() {
        }
    }
</script>

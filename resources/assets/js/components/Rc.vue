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
                            <button type="button" class="btn btn-default" v-on:click="pause()"><span v-if="!paused" class="glyphicon glyphicon-pause"></span><span v-if="paused" class="glyphicon glyphicon-play"></span></button>
                        </div>
                    </div>
                    <div class="row rc-row-button1">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button type="button" class="btn btn-default" v-on:click="switchAudio()"><span class="glyphicon glyphicon-music"></span> Переключить звук</button>
                            <button type="button" class="btn btn-default" v-on:click="switchVideo()"><span class="glyphicon glyphicon-facetime-video"></span> Переключить видео</button>
                            <button type="button" class="btn btn-default" v-on:click="quit()"><span class="glyphicon glyphicon-off"></span></button>
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
                    <div class="row rc-row-time">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="range" min="0" max="1000000" step="1" v-model="timeP" v-on:change="setTimePos()" />
                        </div>
                    </div>
                    <div class="row rc-row-time">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">{{ f.seconds(timePosEmulation) }}</div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">{{ f.seconds(length) }}</div>
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
                mute: false,
                run: false,
                paused: true,
                lastUpdate: new Date().getTime(),
                autoUpdate: true,
                f: window.decorator,
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
                pause: function () {
                    jQuery.ajax('/player-pause/', {
                        success: function (data) {
                            if (!data.run) {
                                return;
                            }
                            localData.paused = data.pause == 'yes';
                            localData.timePos = parseFloat(data.time_pos);
                            localData.timePosEmulation = localData.timePos;
                            localData.timeP = Math.round((localData.timePosEmulation / localData.length) * 1000000);
                            localData.lastUpdate = new Date().getTime();
                        }
                    });
                },
                quit: function () {
                    jQuery.ajax('/player-quit/');
                },
                update: function () {
                    jQuery.ajax('/player-get-info/', {
                        success: function (data) {
                            if (data.run) {
                                localData.paused = data.pause == 'yes';
                                localData.mute = data.mute == 'yes';
                                localData.filename = data.filename;
                                localData.volume = parseFloat(data.volume);
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
                            }
                            localData.lastUpdate = new Date().getTime();
                        }
                    });
                },
                setVolume: function () {
                    jQuery.ajax('/player-set-volume/' + localData.volume);
                },
                setTimePos: function () {
                    localData.autoUpdate = false;
                    jQuery.ajax('/player-get-time-pos/', {
                        success: function (data) {
                            if (!data.run) {
                                return;
                            }
                            localData.length = parseFloat(data.length);
                            localData.timePos = parseFloat(data.time_pos);
                            localData.timePosEmulation = localData.timePos;
                            localData.lastUpdate = new Date().getTime();
                            var newTimePos = Math.round((localData.timeP / 1000000) * data.length);
                            jQuery.ajax('/player-set-time-pos/' + newTimePos, {success: function (data) {
                                localData.timePos = newTimePos;
                                localData.timePosEmulation = localData.timePos;
                                localData.timeP = Math.round((localData.timePosEmulation / localData.length) * 1000000);
                                localData.lastUpdate = new Date().getTime();
                                localData.autoUpdate = true;
                            }});
                        }
                    });
                },
                switchMute: function () {
                    localData.mute = !localData.mute;
                    jQuery.ajax('/player-set-mute/' + (localData.mute ? 't' : 'f'));
                },
                switchAudio: function () {
                    jQuery.ajax('/player-switch-audio/');
                },
                switchVideo: function () {
                    jQuery.ajax('/player-switch-video/');
                }
            };
            setInterval(function() {
                if (localData.paused || !localData.autoUpdate) {
                    return;
                }
                localData.timePosEmulation = localData.timePos + (new Date().getTime() - localData.lastUpdate) / 1000;
                localData.timeP = Math.round((localData.timePosEmulation / localData.length) * 1000000);
            }, 1000);
            return localData;
        },
        mounted() {
        }
    }
</script>

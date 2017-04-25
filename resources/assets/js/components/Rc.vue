<template>
    <div class="modal fade rc" id="tvModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="glyphicon glyphicon-remove"></span></button>
                    <h4 class="modal-title" id="myModalLabel">TV</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button type="button" class="btn btn-default" v-on:click="pause()"><span class="glyphicon glyphicon-pause"></span><span class="glyphicon glyphicon-play"></span></button>
                            <button type="button" class="btn btn-default" v-on:click="switchMute()"><span class="glyphicon glyphicon-volume-off" v-if="!mute"></span><span class="glyphicon glyphicon-volume-up" v-if="mute"></span></button>
                            <button type="button" class="btn btn-default" v-on:click="switchAudio()"><span class="glyphicon glyphicon-music"></span> Переключить звук</button>
                            <button type="button" class="btn btn-default" v-on:click="switchVideo()"><span class="glyphicon glyphicon-facetime-video"></span> Переключить видео</button>
                            <button type="button" class="btn btn-default" v-on:click="quit()"><span class="glyphicon glyphicon-off"></span></button>
                        </div>
                    </div>
                    <div class="row rc-row-sound">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <button type="button" class="btn btn-default rc-btn-sound" v-on:click="getVolume()"><span class="glyphicon glyphicon-volume-up"></span> Звук</button>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                            <input type="range" min="0" max="100" step="1" v-model="volume" v-on:change="setVolume()" />
                        </div>
                    </div>
                    <div class="row rc-row-time">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <button type="button" class="btn btn-default rc-btn-time" v-on:click="getTimePos()"><span class="glyphicon glyphicon-time"></span> Время</button>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                            <input type="range" min="0" max="1000000" step="1" v-model="timeP" v-on:change="setTimePos()" />
                        </div>
                    </div>
                    <div class="row text-center">
                        <div id="progress-slider" style="width:200px;"></div>
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
                            localData.show();
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
                            localData.volume = data.volume;
                        }
                    });
                },
                setVolume: function () {
                    jQuery.ajax('/player-set-volume/' + localData.volume);
                },
                getTimePos: function () {
                    jQuery.ajax('/player-get-time-pos/', {
                        success: function (data) {
                            localData.timePos = data.time_pos;
                            localData.length = data.length;
                            localData.timeP = Math.round((data.time_pos / data.length) * 1000000);
                        }
                    });
                },
                setTimePos: function () {
                    jQuery.ajax('/player-get-time-pos/', {
                        success: function (data) {
                            localData.timePos = data.time_pos;
                            localData.length = data.length;

                            jQuery.ajax('/player-set-time-pos/' + Math.round((localData.timeP / 1000000) * data.length));
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
            return localData;
        },
        mounted() {
        }
    }
</script>


<style>
    .rc .modal-content {
        width: 550px;
    }
    .rc .row {
        padding: 5px;
    }
    .rc .rc-btn-sound,
    .rc .rc-btn-time {
        width: 100px;
    }
</style>

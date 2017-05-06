<template>
    <div class="panel panel-default torrent">
        <torrent_edit></torrent_edit>
        <div class="panel-heading">
            <table class="table table-striped table-menu">
                <tr>
                    <td>
                        Торренты
                    </td>
                    <td>
                        <div class="pull-right">
                            <button type="button" class="btn btn-default" v-on:click="reload()"><span class="glyphicon glyphicon-refresh"></span> Обновить</button>
                            <span class="btn btn-default btn-file">
                                <span class="glyphicon glyphicon-plus"></span> Добавить
                                <input id="torrentInputFile" type="file" accept="application/x-bittorrent">
                            </span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="panel-body">
            <div class="list-group">
                <div v-for="(item, itemId) in items" class="list-group-item">
                    <table class="torrent-table">
                        <tr>
                            <td rowspan="3" class="t-td-btm">
                                <div class="dropdown">
                                    <template v-if="item.status == 0">
                                        <button class="btn btn-primary btn-lg dropdown-toggle" type="button" data-toggle="dropdown">
                                            <span class="glyphicon glyphicon-pause"></span><br>{{f.torrentStatus(item.status)}}
                                        </button>
                                    </template>
                                    <template v-else-if="item.status == 1 || item.status == 2 || item.status == 3">
                                        <button class="btn btn-info btn-lg dropdown-toggle" type="button" data-toggle="dropdown">
                                            {{item.status}}<span class="glyphicon glyphicon-time"></span><br>{{f.torrentStatus(item.status)}}
                                        </button>
                                    </template>
                                    <template v-else-if="item.status == 4 || item.status == 5 || item.status == 6">
                                        <button class="btn btn-success btn-lg dropdown-toggle" type="button" data-toggle="dropdown">
                                            <span class="glyphicon glyphicon-play"></span><br>{{f.torrentStatus(item.status)}}
                                        </button>
                                    </template>
                                    <template v-else="">
                                        <button class="btn btn-default btn-lg dropdown-toggle" type="button" data-toggle="dropdown">
                                            {{f.torrentStatus(item.status)}}
                                        </button>
                                    </template>
                                    <ul class="dropdown-menu">
                                        <li><a href="#" v-on:click="stop(item.id)"><span class="glyphicon glyphicon-pause"></span> Пауза</a></li>
                                        <li><a href="#" v-on:click="start(item.id)"><span class="glyphicon glyphicon-play"></span> Запустить</a></li>
                                        <li><a href="#" v-on:click="edit(item.id)"><span class="glyphicon glyphicon-edit"></span> Изменить</a></li>
                                        <li><a href="#" v-on:click="remove(item.id)"><span class="glyphicon glyphicon-trash"></span> Удалить</a></li>
                                    </ul>
                                </div>
                            </td>
                            <td colspan="4" class="t-td-name"><h5>{{ item.name }}</h5></td>
                            <td colspan="2" class="t-td-date"><small class="text-muted">{{ f.date(item.addedDate) }}</small></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="t-td-progress">
                                <div class="progress">
                                    <div class="progress-bar" :style="f.widthP(item.percentDone, 1)">{{ f.percent(item.percentDone, 1) }}</div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="t-td-size">{{ f.size(item.sizeWhenDone) }}</td>
                            <td class="t-td-eta">{{ f.seconds(item.eta) }}</td>
                            <td class="t-td-leechers">{{ item.peersGettingFromUs }}</td>
                            <td class="t-td-upload"><span class="glyphicon glyphicon-arrow-up"></span> {{ f.speed(item.rateUpload) }}</td>
                            <td class="t-td-seeders">{{ item.peersSendingToUs }}</td>
                            <td class="t-td-download"><span class="glyphicon glyphicon-arrow-down"></span> {{ f.speed(item.rateDownload) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            var localData = window.appData.torrent = {
                items: [],
                f: window.decorator,
                reload: function () {
                    jQuery.ajax('/torrent-list/', {
                        success: function (data) {
                            if (data['result'] != 'success') {
                                return;
                            }
                            localData.items = data['arguments']['torrents'];
                        }
                    });
                },
                add: function() {

                },
                remove: function(itemId) {
                    jQuery.ajax('/torrent-remove/', {
                        method: 'POST',
                        data: {
                            id: itemId
                        },
                        success: function (data) {
                            localData.reload();
                        }
                    });
                },
                stop: function(itemId) {
                    jQuery.ajax('/torrent-stop/', {
                        method: 'POST',
                        data: {
                            id: itemId
                        },
                        success: function (data) {
                            localData.reload();
                        }
                    });
                },
                start: function(itemId) {
                    jQuery.ajax('/torrent-start/', {
                        method: 'POST',
                        data: {
                            id: itemId
                        },
                        success: function (data) {
                            localData.reload();
                        }
                    });
                },
                edit: function(itemId) {
                    window.appData.torrentEdit.show(itemId);
                }
            };
            setInterval(localData.reload, 5000);
            localData.reload();
            return localData;
        },
        mounted() {
            window.appData.torrent.fileInput = jQuery('#torrentInputFile').on('change', function() {
                var fileData = jQuery('#torrentInputFile').prop('files')[0];
                var formData = new FormData();
                formData.append('file', fileData);

                jQuery.ajax('/torrent-add/', {
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        window.appData.torrent.reload();
                        if (data['result'] != 'success') {
                            return;
                        }
                        var id = data['arguments']['torrent-added']['id'];
                    }
                });
            });
        }
    }
</script>


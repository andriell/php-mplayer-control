<template>
    <div class="panel panel-default torrent">
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
            <table class="table table-striped table-data">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Название</th>
                    <th style="width: 100px">Размер</th>
                    <th>Готово</th>
                    <th>Статус</th>
                    <th>Сиды</th>
                    <th style="width: 100px">Загрузка</th>
                    <th style="width: 100px">Отдача</th>
                    <th>Осталось</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, itemId) in items">
                    <td>{{ item.id }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ f.size(item.haveValid) }}</td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar" :style="f.widthP(item.haveValid, item.sizeWhenDone)">{{ f.percent(item.haveValid, item.sizeWhenDone) }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                {{ f.torrentStatus(item.status) }}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#" v-on:click="stop(item.id)"><span class="glyphicon glyphicon-pause"></span> Пауза</a></li>
                                <li><a href="#" v-on:click="start(item.id)"><span class="glyphicon glyphicon-play"></span> Запустить</a></li>
                                <li><a href="#" v-on:click="edit(item.id)"><span class="glyphicon glyphicon-edit"></span> Изменить</a></li>
                                <li><a href="#" v-on:click="remove(item.id)"><span class="glyphicon glyphicon-trash"></span> Удалить</a></li>
                            </ul>
                        </div>
                    </td>
                    <td>{{ item.peersSendingToUs }}</td>
                    <td>{{ f.speed(item.rateDownload) }}</td>
                    <td>{{ f.speed(item.rateUpload) }}</td>
                    <td>{{ f.seconds(item.eta) }}</td>
                    <td>{{ f.date(item.addedDate) }}</td>
                </tr>
                </tbody>
            </table>
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

<style>
    .torrent .kv-upload-progress {
        display: none;
    }
    .torrent .table-data td {
        white-space: nowrap;
    }

    .torrent .progress .progress-bar {
        text-align: center;
        color: white;
        text-shadow: #3097D1 1px 1px 0, #3097D1 -1px -1px 0,
        #3097D1 -1px 1px 0, #3097D1 1px -1px 0;
    }
    .torrent .progress {
        margin-bottom: 0;
    }
    .torrent .table-menu {
        margin-bottom: 0;
    }
</style>
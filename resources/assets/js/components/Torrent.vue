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
                                <input type="file" accept="application/x-bittorrent">
                            </span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="panel-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Название</th>
                    <th>Статус</th>
                    <th>Дата</th>
                    <th>haveValid</th>
                    <th>totalSize</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, itemId) in items">
                    <td>{{ item.id }}</td>
                    <td>{{ item.name }}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                {{ item.status }}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#" v-on:click="stop(item.id)"><span class="glyphicon glyphicon-pause"></span> Пауза</a></li>
                                <li><a href="#" v-on:click="start(item.id)"><span class="glyphicon glyphicon-play"></span> Запустить</a></li>
                                <li><a href="#" v-on:click="remove(item.id)"><span class="glyphicon glyphicon-trash"></span> Удалить</a></li>
                            </ul>
                        </div>
                    </td>
                    <td>{{ item.doneDate }}</td>
                    <td>{{ item.haveValid }}</td>
                    <td>{{ item.totalSize }}</td>
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
                reload: function () {
                    jQuery.ajax('/torrent-list/', {
                        success: function (data) {
                            localData.items = data.items;
                        }
                    });
                },
                add: function() {
                    window.appData.torrentAdd.show();
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
                }
            };
            localData.reload();
            return localData;
        },
        mounted() {
        }
    }
</script>

<style>
    .torrent .table-menu {
        margin-bottom: 0;
    }
</style>
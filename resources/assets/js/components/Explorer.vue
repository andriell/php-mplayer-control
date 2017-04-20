<template>
    <div class="container">
        <div class="row">
            <div class="modal fade" id="tvModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">TV</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row text-center">
                                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-backward"></span></button>
                                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-play"></span></button>
                                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-forward"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Путь: {{uri}}</div>
                    <div class="panel-body">
                        <div class="explorer-item thumbnail" v-for="(item, itemId) in items">
                            <input type="checkbox" class="select" v-model="itemsChecked" :value="item">
                            <template v-if="item.type == 'dir'">
                                <div class="explorer-img-box">
                                    <img src="/img/dir.png">
                                </div>
                                <a href="#" v-on:click="getData(item.uri)" class="text">
                                    {{ item.name }}
                                </a>
                            </template>
                            <template v-else-if="item.type == 'image'">
                                <div class="explorer-img-box">
                                    <img :src="'/dir-img-100x100/' + uri + '/' + item.name" width="100" height="100">
                                </div>
                                <a href="#" v-on:click="download(item.uri)" class="text">
                                    {{ item.name }}
                                </a>
                            </template>
                            <template v-else>
                                <div class="explorer-img-box">
                                    <img src="/img/file.png"><br>
                                </div>
                                <a href="#">
                                    <a href="#" v-on:click="download(item.uri)" class="text">
                                        {{ item.name }}
                                    </a>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <div class="panel panel-default">
                    <div class="panel-heading">Объектов: {{itemsChecked.length}}</div>
                    <div class="panel-body">
                        <template v-if="itemsChecked.length > 0">
                            <template v-if="itemsChecked.length == 1">
                                <template v-for="item in itemsChecked">
                                    <h4>{{item.name}}</h4>
                                    <p>Размер: {{item.size}}</p>
                                    <p>Изменен: {{item.date}}</p>
                                    <p>Права: {{item.perms}}</p>
                                </template>
                            </template>
                            <div class="list-group">
                                <a href="#" class="list-group-item" v-on:click="playFile()"><span
                                        class="glyphicon glyphicon-film"></span>&nbsp;&nbsp;Воспроизвести</a>
                                <a href="#" class="list-group-item" v-on:click="pause()"><span
                                        class="glyphicon glyphicon-film"></span>&nbsp;&nbsp;pause</a>
                                <a href="#" class="list-group-item" v-on:click="quit()"><span
                                        class="glyphicon glyphicon-film"></span>&nbsp;&nbsp;quit</a>
                                <a href="#" class="list-group-item"><span
                                        class="glyphicon glyphicon-arrow-right"></span>&nbsp;&nbsp;Переместить</a>
                                <a href="#" class="list-group-item"><span class="glyphicon glyphicon-duplicate"></span>&nbsp;&nbsp;Копировать</a>
                                <a href="#" class="list-group-item"><span
                                        class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;В новую папку</a>
                                <a href="#" class="list-group-item"><span class="glyphicon glyphicon-trash"></span>&nbsp;&nbsp;Удалить</a>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            var componentData = {
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
                            componentData.uri = data.uri;
                            componentData.items = data.items;
                        }
                    });
                    return false;
                },
                download: function (uri) {
                    window.location.href = '/dir-download/' + uri;
                    return false;
                },
                playFile: function (name) {
                    var files = [];
                    for (var i in componentData.itemsChecked) {
                        files.push(componentData.itemsChecked[i].uri);
                    }
                    jQuery.ajax('/player-play/', {
                        method: 'POST',
                        data: {files: files},
                        success: function (data) {
                            jQuery('#tvModal').modal('show');
                        }
                    });
                    return false;
                },
                pause: function (name) {
                    jQuery.ajax('/player-pause/', {method: 'POST'});
                    return false;
                },
                quit: function (name) {
                    jQuery.ajax('/player-quit/', {method: 'POST'});
                    return false;
                }

            };
            componentData.getData('');
            return componentData;
        },
        mounted() {
        }
    }
</script>


<style>
    .explorer-item {
        display: inline-block;
        vertical-align: bottom;
        height: 140px;
        width: 128px;
        margin: 2px;
        overflow: hidden;
    }

    .explorer-item .select {
        position: absolute;
        margin-top: 5px;
        margin-left: 5px;
    }

    .explorer-item .text {
        display: block;
        text-align: center;
        font-size: 12px;
        color: black;
        font-family: Arial, sans-serif;
    }

    .explorer-img-box {
        display: block;
        margin: auto;
        width: 100px;
        height: 100px;
        overflow: hidden;
    }

    .explorer-img-box img {
        display: block;
        margin: auto;
    }
</style>

<template>
    <div class="explorer">
        <div class="row">
            <div class="explorer-path">
                <div class="panel panel-default panel-heading">
                    <template v-if="path.length == 0">
                        <span>Диск</span> /
                    </template>
                    <template v-else="">
                        <a href="#" v-on:click="getData('')">Диск</a> /
                    </template>
                    <template v-for="(p, i) in path">
                        <template v-if="path.length == i + 1">
                            <span>{{p.name}}</span> /
                        </template>
                        <template v-else="">
                            <a href="#" v-on:click="getData(p.uri)">{{p.name}}</a> /
                        </template>
                    </template>
                  </div>
            </div>
            <div class="col explorer-items">
                <div class="panel panel-default">
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
                                    <img src="/img/file.png" :data-original="'/dir-img-100x100/' + item.uri" width="100"
                                         height="100" class="lazy">
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
            <div class="explorer-info col">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Объектов: {{itemsChecked.length}}
                        <button type="button" class="btn btn-default btn-xs pull-right" v-on:click="unchecked()"><span class="glyphicon glyphicon-remove-circle"></span> Отменить</button>
                    </div>
                    <div class="panel-body">
                        <template v-if="itemsChecked.length > 0">
                            <div class="list-group">
                                <template v-if="itemsChecked.length == 1">
                                    <template v-for="item in itemsChecked">
                                        <h4>{{item.name}}</h4>
                                        <p>Размер: {{item.size}}</p>
                                        <p>Изменен: {{item.date}}</p>
                                        <p>Права: {{item.perms}}</p>
                                        <a href="#" class="list-group-item" v-on:click="playVideo()"
                                           v-if="item.type == 'movie'">
                                            <span class="glyphicon glyphicon-film"></span>&nbsp;&nbsp;Воспроизвести
                                        </a>
                                        <a href="#" class="list-group-item" v-on:click="fileRename()"><span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;Переименовать</a>
                                    </template>
                                </template>
                                <a href="#" class="list-group-item" v-on:click="fileCopy()"><span class="glyphicon glyphicon-arrow-right"></span>&nbsp;&nbsp;Переместить</a>
                                <a href="#" class="list-group-item" v-on:click="fileCopy()"><span class="glyphicon glyphicon-duplicate"></span>&nbsp;&nbsp;Копировать</a>
                                <a href="#" class="list-group-item"><span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;В новую папку</a>
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
            window.appData.explorer = {
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
                },
                fileCopy: function() {
                    window.appData.copy.show();
                }
            };
            window.appData.explorer.getData('');
            return window.appData.explorer;
        },
        mounted() {
        },
        watch: {
            items: function () {
                var i = 0;
                var interval = setInterval(function() {
                    jQuery('.lazy').lazyload({
                        threshold : 200
                    });
                    if (i++ >= 3) {
                        clearInterval(interval);
                    }
                }, 1000);
            }
        }
    }
</script>

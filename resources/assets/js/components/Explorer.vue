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
                        <template v-for="(item, itemId) in items">
                        <div class="explorer-item thumbnail" v-bind:class="{is_link: item.is_link }">
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
                                         height="100" class="lazy" v-on:click="carouselShow(itemId)">
                                </div>
                                <a href="#" v-on:click="carouselShow(itemId)" class="text">
                                    {{ item.name }}
                                </a>
                            </template>
                            <template v-else>
                                <div class="explorer-img-box">
                                    <img src="/img/file.png"><br>
                                </div>
                                <a href="#">
                                    <a href="#" class="text">
                                        {{ item.name }}
                                    </a>
                                </a>
                            </template>
                        </div>
                        </template>
                    </div>
                </div>
            </div>
            <div class="explorer-info col">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Объектов: {{itemsChecked.length}}
                        <button type="button" class="btn btn-default btn-xs pull-right" v-on:click="unchecked()"><span class="glyphicon glyphicon-remove-circle"></span> Отменить</button>
                        <button type="button" class="btn btn-default btn-xs pull-right" v-on:click="checkeAll()"><span class="glyphicon glyphicon-ok-circle"></span> Все</button>
                    </div>
                    <div class="panel-body">
                        <template v-if="itemsChecked.length > 0">
                            <div class="list-group">
                                <template v-if="itemsChecked.length == 1">
                                    <template v-for="item in itemsChecked">
                                        <h4>{{item.name}}</h4>
                                        <p v-if="item.is_link">&gt;&gt;{{item.real_path}}</p>
                                        <p>Размер: {{bytesToSize(item.size)}}</p>
                                        <p>Изменен: {{item.date}}</p>
                                        <p>Права: {{item.perms}}</p>
                                        <a href="#" class="list-group-item" v-on:click="playVideo()"
                                           v-if="item.type == 'movie'">
                                            <span class="glyphicon glyphicon-film"></span>&nbsp;&nbsp;Воспроизвести
                                        </a>
                                        <a href="#" class="list-group-item" v-on:click="slideShowDir()" v-if="item.type == 'dir'">
                                            <span class="glyphicon glyphicon-picture"></span>&nbsp;&nbsp;Слайд шоу
                                        </a>
                                        <a href="#" class="list-group-item" v-on:click="fileRename()"><span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;Переименовать</a>
                                        <a href="#" class="list-group-item" v-on:click="download()"><span class="glyphicon glyphicon-download-alt"></span>&nbsp;&nbsp;Скачать</a>
                                    </template>
                                </template>
                                <a href="#" class="list-group-item" v-on:click="fileCopy()"><span class="glyphicon glyphicon-arrow-right"></span>&nbsp;&nbsp;Переместить</a>
                                <a href="#" class="list-group-item" v-on:click="toNewFolder()"><span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;В новую папку</a>
                                <a href="#" class="list-group-item" v-on:click="doDelete()"><span class="glyphicon glyphicon-trash"></span>&nbsp;&nbsp;Удалить</a>
                            </div>
                        </template>
                        <template v-else="">
                            <h4>{{dirName}}</h4>
                            <p>Файлов: {{items.length}}</p>
                            <p>Общий размер: {{bytesToSize(itemsSize)}}</p>
                            <div class="list-group">
                                <a href="#" class="list-group-item" v-on:click="newFolder()"><span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;Новая папка</a>
                                <a href="#" class="list-group-item" v-on:click="slideShowDir()"><span class="glyphicon glyphicon-picture"></span>&nbsp;&nbsp;Слайд шоу</a>
                                <a href="#" class="list-group-item" v-on:click="upload()"><span class="glyphicon glyphicon-open"></span>&nbsp;&nbsp;Загрузить</a>
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
            var localData = window.appData.explorer = {
                dirName: 'Диск',
                path: [],
                items: [],
                itemsChecked: [],
                itemsSize: 0,
                search: '',
                getUri: function () {
                    return window.location.hash.replace('#', '');
                },
                setUri: function (uri) {
                    window.location.hash = uri;
                },
                reload: function () {
                    localData.getData(localData.getUri());
                },
                bytesToSize: function(bytes) {
                    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                    if (bytes == 0) return '0 Byte';
                    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
                    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
                },
                getData: function (uri) {
                    jQuery.ajax('/dir-list/' + uri, {
                        data: {
                            search: localData.search
                        },
                        success: function (data) {
                            var i;
                            localData.itemsSize = 0;
                            for (i in data.items) {
                                data.items[i].uri = data.uri ? data.uri + '/' + data.items[i].name : data.items[i].name;
                                data.items[i].dir = data.uri;
                                localData.itemsSize += data.items[i].size;
                            }
                            localData.items = data.items;
                            localData.setUri(data.uri);
                            localData.dirName = 'Диск';
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
                                localData.dirName = uriArr[i];
                            }
                            localData.path = path;
                            localData.unchecked();
                        }
                    });
                },
                download: function () {
                    window.location.href = '/dir-download/' + localData.itemsChecked[0].uri;
                    return false;
                },
                playVideo: function () {
                    window.appData.rc.playVideo(localData.itemsChecked[0].uri);
                },
                checkeAll: function() {
                    localData.itemsChecked = localData.items;
                },
                unchecked: function() {
                    localData.itemsChecked = [];
                },
                fileRename: function() {
                    if (localData.itemsChecked.length != 1) {
                        window.appData.rename.status = 'Не знаю как переименовывать ' + localData.itemsChecked.length + ' файлов.';
                        window.appData.rename.show();
                        return;
                    }
                    window.appData.rename.status = '';
                    var item = localData.itemsChecked[0];
                    window.appData.rename.oldName = item.name;
                    window.appData.rename.newName = item.name;
                    window.appData.rename.dir = item.dir;
                    window.appData.rename.show();
                },
                fileCopy: function() {
                    if (localData.itemsChecked.length <= 0) {
                        return;
                    }
                    window.appData.copy.currentDir = localData.getUri();
                    window.appData.copy.status = '';
                    window.appData.copy.items = [];
                    for(var i in localData.itemsChecked) {
                        window.appData.copy.items.push(localData.itemsChecked[i].uri);
                    }
                    window.appData.copy.show();
                },
                toNewFolder: function() {
                    if (localData.itemsChecked.length <= 0) {
                        return;
                    }
                    window.appData.toNewFolder.currentDir = localData.getUri();
                    window.appData.toNewFolder.status = '';
                    window.appData.toNewFolder.items = [];
                    for(var i in localData.itemsChecked) {
                        window.appData.toNewFolder.items.push(localData.itemsChecked[i].uri);
                    }
                    window.appData.toNewFolder.show();
                },
                newFolder: function() {
                    window.appData.newFolder.currentDir = localData.getUri();
                    window.appData.newFolder.show();
                },
                doDelete: function() {
                    if (localData.itemsChecked.length <= 0) {
                        return;
                    }
                    window.appData.delete.items = [];
                    window.appData.delete.status = '';
                    for(var i in localData.itemsChecked) {
                        window.appData.delete.items.push(localData.itemsChecked[i].uri);
                    }
                    window.appData.delete.show();
                },
                slideShowDir: function() {
                    var dir = false;
                    if (localData.itemsChecked.length == 0) {
                        dir = localData.getUri();
                    } else if (localData.itemsChecked.length == 1) {
                        dir = localData.itemsChecked[0].uri;
                    } else {
                        return;
                    }
                    jQuery.ajax('/dir-slide-show/' + dir);
                },
                carouselShow: function(itemIndex) {
                    var imgUri = [], position = 0;
                    for (var i in localData.items) {
                        if (localData.items[i].type == 'image') {
                            if (itemIndex == i) {
                                position = imgUri.length;
                            }
                            imgUri.push(localData.items[i]);

                        }
                    }
                    window.appData.carousel.show(imgUri, position);
                },
                upload: function() {
                    window.appData.upload.uri = localData.getUri();
                    window.appData.upload.show();
                }
            };
            localData.getData(localData.getUri());
            return localData;
        },
        mounted() {
            jQuery('#app-search').search().on('searched.fu.search', function (evt, data) {
                window.appData.explorer.search = data;
                window.appData.explorer.reload();
            }).on('cleared.fu.search', function() {
                window.appData.explorer.search = '';
                window.appData.explorer.reload();
            });
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

<template>
    <div class="modal fade torrentEdit" id="torrentEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            class="glyphicon glyphicon-remove"></span></button>
                    <h4 class="modal-title" id="myModalLabel">Торрент {{ info.name }}</h4>
                </div>
                <div class="modal-body fuelux">
                    Папка для загрузки
                    <select_dir></select_dir>
                    Содержимое торрента

                    <ul id="torrentFiles" class="tree tree-folder-select" role="tree">
                        <li class="tree-branch hide" data-template="treebranch" role="treeitem" aria-expanded="false">
                            <div class="tree-branch-header">
                                <button class="glyphicon icon-caret glyphicon-play"><span class="sr-only">Open</span>
                                </button>
                                <button class="tree-branch-name">
                                    <span class="glyphicon icon-folder glyphicon-folder-close"></span>
                                    <span class="tree-label"></span>
                                </button>
                            </div>
                            <ul class="tree-branch-children" role="group"></ul>
                            <div class="tree-loader" role="alert">Loading...</div>
                        </li>
                        <li class="tree-item hide" data-template="treeitem" role="treeitem">
                            <button class="tree-item-name">
                                <span class="glyphicon icon-item fueluxicon-bullet"></span>
                                <span class="tree-label"></span>
                            </button>
                        </li>
                    </ul>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" v-on:click="cut()"><span
                            class="glyphicon glyphicon-arrow-right"></span> Переместить
                    </button>
                    <button type="button" class="btn btn-primary" v-on:click="copy()"><span
                            class="glyphicon glyphicon-duplicate"></span> Копировать
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            var localData = window.appData.torrentEdit = {
                info: {},
                files: {},
                addFile: function(name, size, loaded) {
                    var path = name.split('/');
                    var f = localData.files;
                    for (var i = 0; i < path.length; i++) {
                        if (!(path[i] in f)) {
                            f[path[i]] = {
                                size: 0,
                                loaded: 0,
                                c: {}
                            };
                        }
                        f[path[i]].size += size;
                        f[path[i]].loaded += loaded;
                        f = f[path[i]].c;
                    }
                },

                dataSource: function (openedParentData, callback) {
                    var childNodesArray = [];
                    var f = localData.files;
                    console.info(openedParentData);
                    if (typeof openedParentData['c'] == 'object') {
                        f = openedParentData['c'];
                    }

                    for (var name in f) {
                        var type = 'item';
                        for (var name2 in f[name].c) {
                            type = 'folder';
                            break;
                        }
                        childNodesArray.push({
                            name: name,
                            type: type,
                            c: f[name].c
                        });
                    }
                    callback({
                        data: childNodesArray
                    });
                },

                show: function (itemId) {
                    jQuery.ajax('/torrent-info/' + itemId, {
                        success: function (data) {
                            if (data['result'] != 'success') {
                                return;
                            }
                            var files = data['arguments']['torrents'][0]['files'];
                            data['arguments']['torrents'][0]['files'] = null;
                            localData.files = {};
                            for (var i in files) {
                                localData.addFile(files[i].name, files[i].length, files[i].bytesCompleted);
                            }
                            localData.info = data['arguments']['torrents'][0];

                            $('#torrentFiles').tree({
                                dataSource: localData.dataSource,
                                multiSelect: false,
                                folderSelect: true
                            });
                        }
                    });

                    jQuery('#torrentEdit').modal('show');
                },
                hide: function () {
                    jQuery('#torrentEdit').modal('hide');
                }
            };
            return localData;
        },
        mounted() {

        }
    }
</script>



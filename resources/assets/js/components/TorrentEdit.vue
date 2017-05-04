<template>
    <div class="modal fade torrent-edit" id="torrentEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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

                    <tree></tree>
                    <div id="newSelectFiles"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" v-on:click="save()">Сохранить</button>
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
                f: window.decorator,
                torrentFiles: false,
                addFile: function(name, size, loaded, id) {
                    var path = name.split('/');
                    var f = localData.files;
                    for (var i = 0; i < path.length; i++) {
                        if (!(path[i] in f)) {
                            f[path[i]] = {
                                size: 0,
                                loaded: 0,
                                c: {},
                                id: []
                            };
                        }
                        f[path[i]].id.push(id);
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
                            name: name + '&thinsp;&thinsp;&thinsp;&thinsp;<span class="size-info">' + localData.f.size(f[name].loaded) + '/' + localData.f.size(f[name].size) + '</span>',
                            type: type,
                            c: f[name].c
                        });
                    }
                    callback({
                        data: childNodesArray
                    });
                },

                show: function (itemId) {
                    localData.files = {};

                    jQuery.ajax('/torrent-info/' + itemId, {
                        success: function (data) {
                            if (data['result'] != 'success') {
                                return;
                            }
                            var files = data['arguments']['torrents'][0]['files'];
                            data['arguments']['torrents'][0]['files'] = null;
                            localData.files = {};
                            for (var i in files) {
                                localData.addFile(files[i].name, files[i].length, files[i].bytesCompleted, i);
                            }
                            localData.info = data['arguments']['torrents'][0];
                            if (localData.torrentFiles !== false) {
                                localData.torrentFiles.tree('destroy');
                            }
                            localData.torrentFiles = jQuery('#torrentFiles').clone();
                            jQuery('#newSelectFiles').html(localData.torrentFiles);
                            localData.torrentFiles.tree({
                                dataSource: window.appData.torrentEdit.dataSource,
                                multiSelect: true,
                                folderSelect: true
                            });
                        }
                    });

                    jQuery('#torrentEdit').modal('show');
                },
                hide: function () {
                    jQuery('#torrentEdit').modal('hide');
                },
                save: function() {
                    var items = localData.torrentFiles.tree('selectedItems');
                }
            };
            return localData;
        },
        mounted() {}
    }
</script>

<style>
    .torrent-edit .tree-item-name {
        text-align: left;
    }
    .torrent-edit .size-info {
        color: blue;
        float: right;
    }
</style>


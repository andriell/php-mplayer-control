<template>
    <div class="modal fade rc" id="copyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="glyphicon glyphicon-remove"></span></button>
                    <h4 class="modal-title" id="myModalLabel">Переместить</h4>
                </div>
                <div class="modal-body">
                    <ul class="list-group tree" role="tree" id="copyTree">
                        <li class="list-group-item tree-branch hide" data-template="treebranch" role="treeitem"
                            aria-expanded="false">
                            <div class="tree-branch-header">
                                <span class="glyphicon icon-caret glyphicon-play"></span>
                                <span class="tree-branch-name">
                                    <span class="glyphicon icon-folder glyphicon-folder-close"></span>
                                    <span class="tree-label"></span>
                                </span>
                            </div>
                            <ul class="tree-branch-children" role="group"></ul>
                            <div class="tree-loader" role="alert">Загрузка...</div>
                        </li>
                        <li class="list-group-item tree-item hide" data-template="treeitem" role="treeitem">
                            <span class="tree-item-name">
                                <span class="glyphicon icon-item fueluxicon-bullet"></span>
                                <span class="tree-label"></span>
                            </span>
                        </li>
                    </ul>
                    <div class="form-group">Элементов: {{items.length}}</div>
                    <div v-if="selectedUri" class="form-group">Переместить в: {{selectedUri}}</div>
                    <div class="form-group">{{status}}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" v-if="selectedUri & items.length > 0" v-on:click="fileRename()"><span class="glyphicon glyphicon-arrow-right"></span> Переместить</button>
                    <button type="button" class="btn btn-primary" v-if="selectedUri & items.length > 0" v-on:click="copy()"><span class="glyphicon glyphicon-duplicate"></span> Копировать</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            window.appData.copy = {
                items: [],
                selectedUri: false,
                status: false,
                getData: function (openedParentData, callback) {
                    var uri = (typeof openedParentData['uri'] == 'undefined') ? '' : openedParentData.uri;
                    jQuery.ajax('/dir-only-dir/' + uri, {
                        success: function (data) {
                            var r = [];
                            for (var i in data) {
                                r.push({
                                    'name': data[i],
                                    'type': 'folder',
                                    'uri': uri ? uri + '/' + data[i] : data[i]
                                });
                             }
                            callback({
                                data: r
                            });
                        }
                    });
                },
                show: function () {
                    jQuery('#copyModal').modal('show');
                },
                hide: function () {
                    jQuery('#copyModal').modal('hide');
                },
                copy: function() {
                    if (window.appData.copy.selectedUri == false) {
                        return;
                    }
                    jQuery.ajax('/dir-copy/', {
                        method: 'POST',
                        data: {
                            'uri_from': window.appData.copy.selectedUri,
                            'uri_to': window.appData.copy.items
                        },
                        success: function (data) {
                            setTimeout(function() {
                                window.appData.copy.hide();
                                window.appData.explorer.reload();
                            }, 2000);
                            if (data.status) {
                                window.appData.copy.status = 'Сделано.';
                            } else {
                                window.appData.copy.status = 'Ошибка.';
                            }
                        }
                    });
                }
            };
            jQuery(function () {
                jQuery('#copyTree').tree({
                    dataSource: window.appData.copy.getData,
                    multiSelect: false,
                    folderSelect: true
                }).on('selected.fu.tree', function (event, data) {
                    window.appData.copy.selectedUri = data.selected[0].uri;
                }).on('deselected.fu.tree', function (event, data) {
                    window.appData.copy.selectedUri = false;
                });
            });
            return window.appData.copy;
        },
        mounted() {
        }
    }
</script>
<style>
    .tree-selected > .tree-branch-header {
        color: #FFFFFF;
        background-color: #428bca;
    }
</style>


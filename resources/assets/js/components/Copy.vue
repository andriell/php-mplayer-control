<template>
    <div class="modal fade copy" id="copyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                    <div class="form-group">Переместить в: <template v-if="selectedUri">{{selectedUri}}</template></div>
                    <div class="form-group">{{status}}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" v-if="available()" v-on:click="cut()"><span class="glyphicon glyphicon-arrow-right"></span> Переместить</button>
                    <button type="button" class="btn btn-primary" v-if="available()" v-on:click="copy()"><span class="glyphicon glyphicon-duplicate"></span> Копировать</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            var localData = window.appData.copy = {
                items: [],
                selectedUri: false,
                status: false,
                available: function() {
                    return localData.selectedUri && localData.items.length > 0;
                },
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
                cut: function() {

                },
                copy: function() {

                },
                action: function(url) {
                    if (localData.selectedUri == false) {
                        return;
                    }
                    jQuery.ajax(url, {
                        method: 'POST',
                        data: {
                            'uri_from': localData.selectedUri,
                            'uri_to': localData.items
                        },
                        success: function (data) {
                            setTimeout(function() {
                                localData.hide();
                                window.appData.explorer.reload();
                            }, 2000);
                            if (data.status) {
                                localData.status = 'Сделано.';
                            } else {
                                localData.status = 'Ошибка.';
                            }
                        }
                    });
                }
            };
            jQuery(function () {
                jQuery('#copyTree').tree({
                    dataSource: localData.getData,
                    multiSelect: false,
                    folderSelect: true
                }).on('selected.fu.tree', function (event, data) {
                    localData.selectedUri = data.selected[0].uri;
                }).on('deselected.fu.tree', function (event, data) {
                    localData.selectedUri = false;
                });
            });
            return localData;
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


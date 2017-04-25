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
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            window.appData.copy = {
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
                }
            };
            jQuery(function () {
                jQuery('#copyTree').tree({
                    dataSource: window.appData.copy.getData,
                    multiSelect: false,
                    folderSelect: true
                });
            });
            return window.appData.copy;
        },
        mounted() {
        }
    }
</script>
<style>
    .tree-selected {
        color: #FFFFFF;
        background-color: #428bca;
    }
</style>


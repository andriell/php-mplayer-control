<template>
    <ul class="list-group tree select-dir" role="tree" id="selectDir">
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
</template>

<script>
    export default {
        data: function () {
            window.appData.selectDir = {
                selectedUri: false,
                getData: function (openedParentData, callback) {
                    if (typeof openedParentData['uri'] == 'undefined') {
                        callback({
                            data: [{
                                'name': 'Диск',
                                'type': 'folder',
                                'uri': ''
                            }]
                        });
                        return;
                    }
                    var uri = openedParentData.uri;
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
                }
            };
            return window.appData.selectDir;
        },
        mounted() {
            jQuery('#selectDir').tree({
                dataSource: window.appData.selectDir.getData,
                multiSelect: false,
                folderSelect: true
            }).on('selected.fu.tree', function (event, data) {
                window.appData.selectDir.selectedUri = data.selected[0].uri;
            }).on('deselected.fu.tree', function (event, data) {
                window.appData.selectDir.selectedUri = false;
            });
        }
    }
</script>



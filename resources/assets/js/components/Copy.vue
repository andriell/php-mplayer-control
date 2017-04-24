<template>
    <ul class="list-group tree" role="tree" id="copyTree">
        <li class="list-group-item tree-branch hide" data-template="treebranch" role="treeitem" aria-expanded="false">
            <div class="tree-branch-header">
            <span  class="tree-branch-name">
                <span class="glyphicon icon-caret glyphicon-play"></span>
                <span class="glyphicon icon-folder glyphicon-folder-close"></span>
                <span class="tree-label"></span>
            </span>
            </div>
            <ul class="tree-branch-children" role="group"></ul>
            <div class="tree-loader" role="alert">Loading...</div>
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
            return window.appData.rename;
        },
        mounted() {
            function staticDataSource(openedParentData, callback) {
                var childNodesArray = [
                    { "name": "Ascending and Descending", "type": "folder" },
                    { "name": "Sky and Water I", "type": "item" },
                    { "name": "Drawing Hands", "type": "folder" },
                    { "name": "waterfall", "type": "item" },
                    { "name": "Belvedere", "type": "folder" },
                    { "name": "Relativity", "type": "item" },
                    { "name": "House of Stairs", "type": "folder" },
                    { "name": "Convex and Concave", "type": "item" }
                ];

                callback({
                    data: childNodesArray
                });
            }

            $(function() {
                $('#copyTree').tree({
                    dataSource: staticDataSource,
                    multiSelect: false,
                    folderSelect: true
                });
            });
        }
    }
</script>


<template>
    <table class="tree-table">
        <template v-for="item in items">
            <template v-if="item.children.length > 0">
                <tr>
                    <td class="tree-table-id">
                        <input v-if="item.wanted" type="checkbox" name="ids[]" :value="item.id" checked v-on:change="checkChild(item.id)" class="tree-input-folder">
                        <input v-if="!item.wanted" type="checkbox" name="ids[]" :value="item.id" v-on:change="checkChild(item.id)" class="tree-input-folder">
                    </td>
                    <td class="tree-table-name" v-on:click="toggle(item.id)"><span class="glyphicon glyphicon-folder-open"></span> {{item.name}}</td>
                    <td class="tree-table-size">{{ f.size(item.size) }}</td>
                </tr>
                <tr :class="'tree-child-' + item.id" style="display: none;">
                    <td class="tree-table-id"></td>
                    <td colspan="2">
                        <torrent_edit_tree_item v-bind:items="item.children"></torrent_edit_tree_item>
                    </td>
                </tr>
            </template>
            <template v-else="">
                <tr>
                    <td class="tree-table-id">
                        <input v-if="item.wanted" type="checkbox" name="ids[]" :value="item.id" checked class="tree-input-item">
                        <input v-if="!item.wanted" type="checkbox" name="ids[]" :value="item.id" class="tree-input-item">
                    </td>
                    <td class="tree-table-name"><span class="glyphicon glyphicon-file"></span> {{item.name}}</td>
                    <td class="tree-table-size">{{ f.size(item.size) }}</td>
                </tr>
            </template>
        </template>
    </table>
</template>

<script>
    export default {
        data: function () {
            return {
                f: window.decorator,
                checkChild: function(itemId) {
                    window.appData.torrentEdit.fileCheckChild(itemId);
                },
                toggle: function(itemId) {
                    window.appData.torrentEdit.fileToggle(itemId);
                }
            };
        },
        mounted() {
        },

        props: ['items']
    }
</script>

<style>
    .tree-table {
        width: 100%;
    }
    .tree-table .tree-table-id {
        text-align: right;
        padding-right: 10px;
        width: 20px;
    }
    .tree-table .tree-table-name {
        cursor: pointer;
        white-space: nowrap;
    }
    .tree-table .tree-table-size {
        text-align: right;
    }
</style>

<template>
    <table class="tree-table">
        <template v-for="item in items">
            <template v-if="item.children.length > 0">
                <tr>
                    <td class="tree-table-id">
                        <input type="checkbox" name="ids[]" :value="item.id" v-on:change="checkChild(item.id)">
                    </td>
                    <td class="tree-table-name" v-on:click="toggle(item.id)"><span class="glyphicon glyphicon-folder-open"></span> {{item.name}}</td>
                    <td class="tree-table-size">{{item.size}}</td>
                </tr>
                <tr :class="'tree-child-' + item.id">
                    <td class="tree-table-id"></td>
                    <td colspan="2">
                        <torrent_edit_tree_item v-bind:data="item.children"></torrent_edit_tree_item>
                    </td>
                </tr>
            </template>
            <template v-else="">
                <tr>
                    <td class="tree-table-id">
                        <input type="checkbox" name="ids[]" :value="item.id">
                    </td>
                    <td class="tree-table-name"><span class="glyphicon glyphicon-file"></span> {{item.name}}</td>
                    <td class="tree-table-size">{{item.size}}</td>
                </tr>
            </template>
        </template>
    </table>
</template>

<script>
    export default {
        data: function () {
            return {
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
    .tree-table .tree-table-id {
        width: 20px;
    }
</style>

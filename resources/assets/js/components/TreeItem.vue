<template>
    <table class="tree-table">
        <template v-for="item in items">
            <template v-if="item.children">
                <tr>
                    <td class="tree-table-id">
                        <input type="checkbox" name="ids[]" :value="item.id" v-on:change="checkChild(item.id)">
                    </td>
                    <td class="tree-table-name">{{item.name}}</td>
                    <td class="tree-table-size">{{item.size}}</td>
                </tr>
                <tr :class="'tree-child-' + item.id">
                    <td class="tree-table-id"></td>
                    <td colspan="2">
                        <tree_item :data="item.children"></tree_item>
                    </td>
                </tr>
            </template>
            <template v-else="">
                <tr>
                    <td class="tree-table-id">
                        <input type="checkbox" name="ids[]" :value="item.id">
                    </td>
                    <td class="tree-table-name">{{item.name}}</td>
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
                items: this.data,
                checkChild: function(itemId) {
                    window.appData.torrentFiles.checkChild(itemId);
                }
            };
        },
        mounted() {
        },

        props: ['data']
    }
</script>

<style>
    .tree-table .tree-table-id {
        width: 20px;
    }
</style>

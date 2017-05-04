<template>
    <table class="tree-table">
        <template v-for="item in items">
            <tr>
                <td class="tree-table-id">
                    <input type="checkbox" name="ids[]" :value="item.id">
                </td>
                <td class="tree-table-name">{{item.name}}</td>
                <td class="tree-table-size">{{item.size}}</td>
            </tr>
            <tr v-if="item.children">
                <td class="tree-table-id"></td>
                <td colspan="2">
                    <tree_item :data="item.children"></tree_item>
                </td>
            </tr>
        </template>
    </table>
</template>

<script>
    export default {
        data: function () {
            return {
                items: this.data,
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

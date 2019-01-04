<template>
    <table class="tree-table">
        <template v-for="item in items">
            <tr>
                <td class="tree-table-name">
                    <template v-if="selectedItem == item">
                        <span class="glyphicon glyphicon-folder-open"></span>
                        <span class="select-catalog-selected">{{ item }}</span>
                        <select_catalog v-bind:uri="uri + '/' + item"></select_catalog>
                    </template>
                    <template v-else="">
                        <span class="glyphicon glyphicon-folder-close"></span>
                        <span v-on:click="selectItem(item)">{{ item }}</span>
                    </template>
                </td>
            </tr>
        </template>
    </table>
</template>

<script>
    export default {
        data: function () {
            var localData = {
                f: window.decorator,
                items: [],
                selectedItem: false,
                uri: "",
                getData: function () {
                    jQuery.ajax('/dir-only-dir/' + localData.uri, {
                        success: function (data) {
                            localData.items = data;
                        }
                    });
                },
                selectItem: function (itemName) {
                    localData.selectedItem = itemName;
                }
            };
            localData.getData();
            return localData;
        },
        mounted() {
        },

        props: ['uri']
    }
</script>


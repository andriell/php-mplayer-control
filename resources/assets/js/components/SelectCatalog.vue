<template>
    <table class="tree-table">
        <template v-for="item in items">
            <tr>
                <td class="tree-table-name">
                    <span class="glyphicon glyphicon-folder-open"></span>
                    {{ item }}
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
                uri: "",
                getData: function () {
                    jQuery.ajax('/dir-only-dir/' + localData.uri, {
                        success: function (data) {
                            localData.items = data;
                        }
                    });
                },
            };
            localData.getData();
            return localData;
        },
        mounted() {
        },

        props: ['uri']
    }
</script>


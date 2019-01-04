<template>
    <div class="select-catalog">
        <div class="select-catalog-first">
            <span class="glyphicon"
                  v-bind:class="[isOpen ? 'glyphicon-folder-open' : 'glyphicon-folder-close']"
                  v-on:click="open(uri)"
            ></span>
            <span v-on:click="select(uri)">{{ name }}</span>
        </div>
        <div v-if="isOpen" class="select-catalog-second">
            <template v-for="item in items">
                <select_catalog v-bind:name="item"  v-bind:uri="uri + item + '/'"></select_catalog>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            var localData = {
                f: window.decorator,
                isOpen: false,
                items: false,
                open: function(uri) {
                    localData.isOpen =! localData.isOpen;
                    if (localData.isOpen) {
                        localData.getData(uri);
                    }
                },
                select: function (uri) {
                    window.appData.selectCatalogSelected.selected = uri;
                },
                getData: function (uri) {
                     jQuery.ajax('/dir-only-dir/' + uri, {
                        success: function (data) {
                            localData.items = data;
                        }
                    });
                },
                selectItem: function (itemName) {
                    localData.selectedItem = itemName;
                }
            };
            return localData;
        },
        props: {
            'name': {
                type: String,
                default: 'Диск'
            },
            'uri': {
                type: String,
                default: '/'
            }
        }
    }
</script>


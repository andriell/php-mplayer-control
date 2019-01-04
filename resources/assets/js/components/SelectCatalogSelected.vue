<template>
    <span>
        {{ selected }}
    </span>
</template>

<script>
    export default {
        data: function () {
            var localData = window.appData.selectCatalogSelected = {
                selected: '',
                val: function () {
                    return localData.selected;
                }
            };
            return localData;
        }
    }
</script>

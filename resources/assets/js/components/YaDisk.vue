<template>
    <template v-if="status">
        Яндекс диск:
        <pre>{{status}}</pre>
    </template>
</template>

<script>
    export default {
        data: function () {
            var localData = window.appData.YaDisk = {
                status: '',
                update: function() {
                    jQuery.ajax('/yandex-disk-status', {
                        success: function (data) {
                            localData.status = data.status;
                        }
                    });
                }
            };
            localData.update();
            setInterval(localData.update, 5000);
            return localData;
        },
        mounted() {
        }
    }
</script>

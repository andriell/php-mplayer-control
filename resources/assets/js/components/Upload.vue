<template>
    <div>
        <label class="control-label">Выберите Файл</label>
        <input id="input-ru" name="inputru[]" type="file" multiple class="file-loading">
    </div>
</template>

<script>
    export default {
        data: function () {
            return {};
        },
        mounted() {
            jQuery('#input-ru').fileinput({
                language: 'ru',
                uploadUrl: '/dir-upload/'
            });
        }
    }
</script>


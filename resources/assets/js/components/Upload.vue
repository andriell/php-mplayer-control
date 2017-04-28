<template>
    <div>
        <label class="control-label">Select File</label>
        <input id="input-4" name="input4[]" type="file" multiple class="file-loading">
    </div>
</template>

<script>
    export default {
        data: function () {
            return {};
        },
        mounted() {
            jQuery("#input-4").fileinput({showCaption: false});
        }
    }
</script>


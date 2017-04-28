<template>
    <div class="modal fade upload" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            class="glyphicon glyphicon-remove"></span></button>
                    <h4 class="modal-title" id="myModalLabel">Загрузить</h4>
                </div>
                <div class="modal-body">
                    <label class="control-label">Выберите Файл</label>
                    <input id="uploadInput" name="file[]" type="file" multiple class="file-loading">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            var localData = window.appData.upload = {
                uri: '',
                fileInput: {},
                modal: {},
                show: function () {
                    localData.modal.modal('show');
                    localData.fileInput.fileinput('refresh', {
                        uploadUrl: '/dir-upload/' + localData.uri
                    })
                },
                hide: function () {
                    localData.modal.modal('hide');
                }
            };
            return localData;
        },
        mounted() {
            window.appData.upload.fileInput = jQuery('#uploadInput').fileinput({
                language: 'ru',
                uploadUrl: '/dir-upload/'
            });
            window.appData.upload.modal = jQuery('#uploadModal');
            window.appData.upload.modal.on('hidden.bs.modal', function () {
                window.appData.explorer.reload();
            });
        }
    }
</script>


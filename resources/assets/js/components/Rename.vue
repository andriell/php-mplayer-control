<template>
    <div class="modal fade rc" id="renameModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="glyphicon glyphicon-remove"></span></button>
                    <h4 class="modal-title" id="myModalLabel">Переименовать</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="renameModalFileName">Файл: {{oldName}}</label>
                        <input type="text" class="form-control" id="renameModalFileName" v-model="newName">
                    </div>
                    <div class="form-group">{{status}}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" v-if="available()" v-on:click="fileRename()">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            var localData = window.appData.rename = {
                dir: '',
                oldName: '',
                newName: '',
                status: '',
                run: false,
                available: function() {
                    return !localData.run && localData.oldName && localData.newName && localData.oldName != localData.newName;
                },
                show: function () {
                    localData.run = false;
                    jQuery('#renameModal').modal('show');
                },
                hide: function () {
                    jQuery('#renameModal').modal('hide');
                },
                fileRename: function () {
                    localData.run = true;
                    localData.status = 'Обработка...';
                    jQuery.ajax('/dir-mv/', {
                        method: 'POST',
                        data: {
                            'uri_dir':  localData.dir,
                            'old_name': localData.oldName,
                            'new_name': localData.newName
                        },
                        success: function (data) {
                            if (data.status) {
                                localData.oldName = localData.newName;
                                localData.status = 'Переименовано';
                            } else {
                                localData.status = 'Ошибка';
                            }
                        },
                        complete: function(jqXHR, textStatus ) {
                            setTimeout(function() {
                                localData.run = false;
                                localData.hide();
                                window.appData.explorer.reload();
                            }, 2000);
                        }
                    });
                }
            };
            return localData;
        },
        mounted() {
        }
    }
</script>


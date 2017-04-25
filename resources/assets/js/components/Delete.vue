<template>
    <div class="modal fade new-folder" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="glyphicon glyphicon-remove"></span></button>
                    <h4 class="modal-title" id="myModalLabel">Удалить</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        Вы уверены что хотите удалить файлы? Всего: {{items.length}} шт.
                    </div>
                    <div class="form-group">
                        <label for="deleteYesN">Введите Yes{{items.length}}:</label>
                        <input type="text" class="form-control" id="deleteYesN" v-model="verification">
                    </div>
                    <div class="form-group">{{status}}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Нет</button>
                    <button type="button" class="btn btn-danger" v-if="available()" v-on:click="doDelete()">Да</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            var localData = window.appData.delete = {
                items: [],
                verification: '',
                status: '',
                run: false,
                available: function() {
                    return !localData.run && localData.items.length > 0 && localData.verification == 'Yes' + localData.items.length;
                },
                show: function () {
                    localData.run = false;
                    localData.newDirName = '';
                    jQuery('#deleteModal').modal('show');
                },
                hide: function () {
                    jQuery('#deleteModal').modal('hide');
                },
                doDelete: function () {
                    if (!localData.available()) {
                        return;
                    }
                    localData.run = true;
                    localData.status = 'Обработка...';
                    jQuery.ajax('/dir-new-folder/', {
                        method: 'POST',
                        data: {
                            'items': localData.items
                        },
                        success: function (data) {
                            if (data.status) {
                                localData.status = 'Готово';
                            } else {
                                localData.status = 'Ошибка';
                            }
                        },
                        complete: function(jqXHR, textStatus ) {
                            setTimeout(function() {
                                localData.run = false;
                                localData.newDirName = '';
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


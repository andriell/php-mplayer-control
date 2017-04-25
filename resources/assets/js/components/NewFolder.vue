<template>
    <div class="modal fade new-folder" id="folderModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="glyphicon glyphicon-remove"></span></button>
                    <h4 class="modal-title" id="myModalLabel">Поместить в новую папку</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="renameModalFileName">Папка:</label>
                        <input type="text" class="form-control" id="renameModalFileName" v-model="name">
                    </div>
                    <div class="form-group">{{status}}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" v-if="available()" v-on:click="newFolder()">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            var localData = window.appData.folder = {
                currentDir: '',
                name: '',
                items: [],
                status: '',
                run: false,
                available: function() {
                    return !localData.run && localData.name;
                },
                show: function () {
                    localData.run = false;
                    jQuery('#folderModal').modal('show');
                },
                hide: function () {
                    jQuery('#folderModal').modal('hide');
                },
                newFolder: function () {
                    localData.run = true;
                    localData.status = 'Обработка...';
                    jQuery.ajax('/dir-cut/', {
                        method: 'POST',
                        data: {
                            'uri_from': localData.items,
                            'uri_to': localData.currentDir + '/' + localData.name
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


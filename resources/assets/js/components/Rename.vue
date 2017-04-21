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
                        <label for="renameModalFileName">Файл:</label>
                        <input type="text" class="form-control" id="renameModalFileName">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return window.appData.mv;
        },
        mounted() {
        }
    }
</script>


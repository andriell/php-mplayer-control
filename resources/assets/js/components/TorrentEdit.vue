<template>
    <div class="modal fade torrentEdit" id="torrentEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="glyphicon glyphicon-remove"></span></button>
                    <h4 class="modal-title" id="myModalLabel">Переместить</h4>
                </div>
                <div class="modal-body">
                    <select_dir></select_dir>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" v-on:click="cut()"><span class="glyphicon glyphicon-arrow-right"></span> Переместить</button>
                    <button type="button" class="btn btn-primary" v-on:click="copy()"><span class="glyphicon glyphicon-duplicate"></span> Копировать</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            var localData = window.appData.torrentEdit = {

                show: function () {
                    jQuery('#torrentEdit').modal('show');
                },
                hide: function () {
                    jQuery('#torrentEdit').modal('hide');
                }
            };
            return localData;
        },
        mounted() {
            /*jQuery('#downloadDirTree').tree({
                dataSource: localData.getData,
                multiSelect: false,
                folderSelect: true
            }).on('selected.fu.tree', function (event, data) {
                localData.selectedUri = data.selected[0].uri;
            }).on('deselected.fu.tree', function (event, data) {
                localData.selectedUri = false;
            });*/
        }
    }
</script>



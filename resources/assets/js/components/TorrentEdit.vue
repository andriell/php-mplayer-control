<template>
    <div class="modal fade torrent-edit" id="torrentEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            class="glyphicon glyphicon-remove"></span></button>
                    <h4 class="modal-title" id="myModalLabel">Торрент {{ info.name }}</h4>
                </div>
                <div class="modal-body fuelux">
                    Папка для загрузки
                    <select_dir></select_dir>
                    Содержимое торрента

                    <tree></tree>
                    <div id="newSelectFiles"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" v-on:click="save()">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            var localData = window.appData.torrentEdit = {
                info: {},
                f: window.decorator,

                show: function (itemId) {

                    jQuery.ajax('/torrent-info/' + itemId, {
                        success: function (data) {
                            if (data['result'] != 'success') {
                                return;
                            }
                            localData.info = data['arguments'];
                            window.appData.torrentFiles.items = localData.info.files;
                        }
                    });

                    jQuery('#torrentEdit').modal('show');
                },
                hide: function () {
                    jQuery('#torrentEdit').modal('hide');
                },
                save: function() {
                    var items = localData.torrentFiles.tree('selectedItems');
                }
            };
            return localData;
        },
        mounted() {}
    }
</script>

<style>
    .torrent-edit .tree-item-name {
        text-align: left;
    }
    .torrent-edit .size-info {
        color: blue;
        float: right;
    }
</style>


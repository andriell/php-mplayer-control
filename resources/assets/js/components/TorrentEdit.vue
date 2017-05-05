<template>
    <div class="modal fade torrent-edit" id="torrentEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            class="glyphicon glyphicon-remove"></span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ info.name }}</h4>
                </div>
                <div id="torrentEditFiles" class="modal-body fuelux">
                    <torrent_edit_tree_item v-bind:items="info.files"></torrent_edit_tree_item>
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
                info: {
                    files:[]
                },
                f: window.decorator,

                show: function (itemId) {

                    jQuery.ajax('/torrent-info/' + itemId, {
                        success: function (data) {
                            if (data['result'] != 'success') {
                                return;
                            }
                            localData.info = data['arguments'];
                        }
                    });

                    jQuery('#torrentEdit').modal('show');
                },
                hide: function () {
                    jQuery('#torrentEdit').modal('hide');
                },
                save: function() {
                    var items = localData.torrentFiles.tree('selectedItems');
                },
                fileChecked: function() {
                    var r = [];
                    jQuery('#torrentEditFiles input:checked.tree-input-item').each(function() {
                        r.push(jQuery(this).val());
                    });
                    return r;
                },
                fileCheckChild: function(itemId) {
                    jQuery('#torrentEditFiles .tree-child-' + itemId  + ' input').prop('checked', jQuery('#torrentEditFiles input[value=' + itemId  + ']').prop('checked'));
                },
                fileToggle: function(itemId) {
                    jQuery('#torrentEditFiles .tree-child-' + itemId).toggle();
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


<template>
    <div class="modal fade torrentEdit" id="torrentEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="glyphicon glyphicon-remove"></span></button>
                    <h4 class="modal-title" id="myModalLabel">Добавить торрент</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="example-text-input" class="col-md-2 col-form-label">Text</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" value="Artisanal kale" id="example-text-input">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

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
        }
    }
</script>



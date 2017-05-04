<template>
    <div class="modal fade copy" id="copyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="glyphicon glyphicon-remove"></span></button>
                    <h4 class="modal-title" id="myModalLabel">Переместить</h4>
                </div>
                <div class="modal-body">
                    <select_dir></select_dir>
                    <div class="form-group">Элементов: {{items.length}}</div>
                    <div class="form-group">Переместить в: <template v-if="selectedUri()">{{ selectedUri() }}</template></div>
                    <div class="form-group">{{status}}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" v-if="available()" v-on:click="cut()"><span class="glyphicon glyphicon-arrow-right"></span> Переместить</button>
                    <button type="button" class="btn btn-primary" v-if="available()" v-on:click="copy()"><span class="glyphicon glyphicon-duplicate"></span> Копировать</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            var localData = window.appData.copy = {
                currentDir: '',
                items: [],
                status: '',
                run: false,
                selectedUri: function () {
                    if (typeof window.appData.selectDir != 'object') {
                        return false;
                    }
                    return window.appData.selectDir.selectedUri;
                },
                available: function() {
                    return !localData.run && localData.selectedUri() !== false && localData.items.length > 0 && localData.selectedUri() != localData.currentDir;
                },
                show: function () {
                    localData.run = false;
                    jQuery('#copyModal').modal('show');
                },
                hide: function () {
                    jQuery('#copyModal').modal('hide');
                },
                cut: function() {
                    localData.action('/dir-cut/');
                },
                copy: function() {
                    localData.action('/dir-copy/');
                },
                action: function(url) {
                    if (!localData.available()) {
                        return;
                    }
                    localData.run = true;
                    localData.status = 'Обработка...';
                    jQuery.ajax(url, {
                        method: 'POST',
                        data: {
                            'uri_from': localData.items,
                            'uri_to': localData.selectedUri()
                        },
                        success: function (data) {
                            if (data.status) {
                                localData.status = 'Сделано';
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



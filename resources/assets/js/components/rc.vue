<template>
    <div class="modal fade" id="tvModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">TV</h4>
                </div>
                <div class="modal-body">
                    <div class="row text-center">
                        <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-backward"></span>
                        </button>
                        <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-play"></span>
                        </button>
                        <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-forward"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return window.appData;
        },
        mounted() {
        }
    }
</script>


<style>

</style>

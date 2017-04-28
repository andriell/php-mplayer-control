<template>
    <div class="container text-center my-carousel">
        <div id="carouselModal" class="modal" role="dialog">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="glyphicon glyphicon-remove"></span></button>

            <a class="left carousel-control" href="#" v-on:click="left()">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>

            <template v-if="item">
                <img class="img-responsive" :src="'/dir-img-1024x768/' + item.uri + '?sync=' + sync + '&action=' + action" alt="...">
            </template>

            <a class="right carousel-control" href="#" v-on:click="right()">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
            <div class="footer">
                <div class="button">
                    <label class="switch">
                        <input type="checkbox" v-model="sync" v-on:click="syncToggle()">
                        <div class="slider round"></div>
                    </label>
                    Дублировать на экране
                </div>
                <div class="button">
                    <a href="#" v-on:click="stop()">Остановить</a>
                </div>
                <div class="button">
                    <a href="#" v-on:click="download()">Скачать</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            var localData = window.appData.carousel = {
                position: 0,
                items: [],
                item: false,
                sync: false,
                action: '',
                show: function (items, position) {
                    if (!(Array.isArray(items) && items.length > position)) {
                        return;
                    }
                    localData.position = position;
                    localData.items = items;
                    localData.item = items[position];
                    localData.sync = false;
                    localData.action = '';

                    jQuery('#carouselModal').modal('show');
                },
                syncToggle: function () {
                    if (localData.sync) {
                        jQuery.ajax('/dir-slide/' + localData.item.uri);
                    } else {
                        jQuery.ajax('/dir-slide-stop/');
                    }
                },
                left: function () {
                    localData.position--;
                    if (localData.position < 0) {
                        localData.position = localData.items.length - 1;
                    }
                    localData.action = 'left';
                    localData.item = localData.items[localData.position];
                },
                right: function () {
                    localData.position++;
                    if (localData.position >= localData.items.length) {
                        localData.position = 0;
                    }
                    localData.action = 'right';
                    localData.item = localData.items[localData.position];
                },
                stop: function () {
                    jQuery.ajax('/dir-slide-stop/');
                },
                download: function () {
                    window.location.href = '/dir-download/' + localData.item.uri;
                }
            };
            return localData;
        },
        mounted() {
        }
    }
</script>

<style>

</style>


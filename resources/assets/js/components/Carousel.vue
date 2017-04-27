<template>
    <div class="container text-center my-carousel">
        <div id="carouselModal" class="modal" role="dialog">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="glyphicon glyphicon-remove"></span></button>

            <a class="left carousel-control" href="#" v-on:click="left()">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>

            <img class="img-responsive" :src="'/dir-img-1024x768/' + item.uri" alt="...">

            <a class="right carousel-control" href="#" v-on:click="right()">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
            <div class="footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" >Сохранить</button>
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
                item: '',
                show: function (items, position) {
                    if (!(Array.isArray(items) && items.length > position)) {
                        return;
                    }
                    localData.position = position;
                    localData.items = items;
                    localData.item = items[position];

                    jQuery('#carouselModal').modal('show');
                },
                left: function () {
                    localData.position--;
                    if (localData.position < 0) {
                        localData.position = localData.items.length - 1;
                    }
                    localData.item = localData.items[localData.position];
                },
                right: function () {
                    localData.position++;
                    if (localData.position >= localData.items.length) {
                        localData.position = 0;
                    }
                    localData.item = localData.items[localData.position];
                }
            };
            return localData;
        },
        mounted() {
        }
    }
</script>

<style>
    .my-carousel .modal .close {
        z-index: 1055;
        position: fixed;
        right: 20px;
        top: 20px;
        font-size: 40px;
    }
    .my-carousel .modal .footer {
        position: fixed;
        height: 50px;
        width: 100%;
        left: 0;
        right: 0;
        bottom: 0;
    }
    .my-carousel .modal img {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 1051;
    }
    .my-carousel .modal {
        margin: 50px 0 70px 0;
    }

    .my-carousel .carousel-control.left,
    .my-carousel .carousel-control.right {
        background-image: none;
        z-index: 1052;
    }

    .my-carousel .img-responsive {
        display: block;
        max-width: 100%;
        max-height: 100%;
        margin: auto;
    }
</style>


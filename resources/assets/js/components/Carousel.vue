<template>
    <div class="container text-center my-carousel">
        <h1> Click Me </h1>
        <!-- Large modal -->
        <button class="btn btn-default" data-toggle="modal" data-target=".bs-example-modal-lg">Large modal</button>

        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <!-- Controls -->
            <a class="left carousel-control" href="#carousel-example-generic" role="button">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>

            <img class="img-responsive" src="http://placehold.it/1200x2600/555/000&text=One" alt="...">

            <a class="right carousel-control" href="#carousel-example-generic" role="button">
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
                item: {},
                show: function (items) {
                    if (!(Array.isArray(items) && items.length > 0)) {
                        return;
                    }
                    localData.position = 0;
                    localData.items = items;
                    localData.item = items[0];
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


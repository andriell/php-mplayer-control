<template>
    <div class="explorer">
        <div class="row">
            <div class="col col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Путь: {{uri}}</div>
                    <div class="panel-body">
                        <div class="explorer-item thumbnail" v-for="(item, itemId) in items">
                            <input type="checkbox" class="select" v-model="itemsChecked" :value="item">
                            <template v-if="item.type == 'dir'">
                                <div class="explorer-img-box">
                                    <img src="/img/dir.png">
                                </div>
                                <a href="#" v-on:click="getData(item.uri)" class="text">
                                    {{ item.name }}
                                </a>
                            </template>
                            <template v-else-if="item.type == 'image'">
                                <div class="explorer-img-box">
                                    <img src="/img/file.png" :data-original="'/dir-img-100x100/' + item.uri" width="100" height="100" class="lazy">
                                </div>
                                <a href="#" v-on:click="download(item.uri)" class="text">
                                    {{ item.name }}
                                </a>
                            </template>
                            <template v-else>
                                <div class="explorer-img-box">
                                    <img src="/img/file.png"><br>
                                </div>
                                <a href="#">
                                    <a href="#" v-on:click="download(item.uri)" class="text">
                                        {{ item.name }}
                                    </a>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div class="explorer-info col col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <div class="panel panel-default">
                    <div class="panel-heading">Объектов: {{itemsChecked.length}}</div>
                    <div class="panel-body">
                        <template v-if="itemsChecked.length > 0">
                            <div class="list-group">
                            <template v-if="itemsChecked.length == 1">
                                <template v-for="item in itemsChecked">
                                    <h4>{{item.name}}</h4>
                                    <p>Размер: {{item.size}}</p>
                                    <p>Изменен: {{item.date}}</p>
                                    <p>Права: {{item.perms}}</p>
                                    <a href="#" class="list-group-item" v-on:click="playVideo()" v-if="item.type == 'movie'">
                                        <span class="glyphicon glyphicon-film"></span>&nbsp;&nbsp;Воспроизвести
                                    </a>
                                </template>
                            </template>
                                <a href="#" class="list-group-item"><span
                                        class="glyphicon glyphicon-arrow-right"></span>&nbsp;&nbsp;Переместить</a>
                                <a href="#" class="list-group-item"><span class="glyphicon glyphicon-duplicate"></span>&nbsp;&nbsp;Копировать</a>
                                <a href="#" class="list-group-item"><span
                                        class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;В новую папку</a>
                                <a href="#" class="list-group-item"><span class="glyphicon glyphicon-trash"></span>&nbsp;&nbsp;Удалить</a>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            setInterval(function() {
                jQuery("img.lazy").lazyload();
            }, 1000);
            window.appData.explorer.getData('');
            return window.appData.explorer;
        },
        mounted() {}
    }
</script>


<style>
    .explorer .row {
        margin-left: 0;
        margin-right: 0;
    }
    .explorer .col {
        padding-left: 1px;
        padding-right: 1px;
    }
    .explorer .explorer-info {
        position: fixed;
        right: 0;
    }
    .explorer-item {
        display: inline-block;
        vertical-align: bottom;
        width: 110px;
        height: 125px;
        margin: 2px;
        overflow: hidden;
    }

    .explorer-item .select {
        position: absolute;
        margin-top: 5px;
        margin-left: 5px;
    }

    .explorer-item .text {
        display: block;
        text-align: center;
        font-size: 12px;
        color: black;
        font-family: Arial, sans-serif;
    }

    .explorer-img-box {
        display: block;
        margin: auto;
        width: 100px;
        height: 100px;
        overflow: hidden;
    }

    .explorer-img-box img {
        display: block;
        margin: auto;
    }
</style>

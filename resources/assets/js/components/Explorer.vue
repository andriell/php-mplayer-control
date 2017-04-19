<template>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Путь: {{uri}}</div>
                    <div class="panel-body">
                        <div class="explorer-item thumbnail" v-for="(item, itemId) in items">
                            <input type="checkbox" class="select" v-model="itemsChecked" :value="item">
                            <template v-if="item.type == 'dir'">
                                <div class="explorer-img-box">
                                    <img src="/img/dir.png">
                                </div>
                                <a href="#" v-on:click="getData(item.name)" class="text">
                                     {{ item.name }}
                                </a>
                            </template>
                            <template v-else-if="item.type == 'image'">
                                <div class="explorer-img-box">
                                    <img :src="'/dir-img-100x100/' + uri + '/' + item.name" width="100" height="100">
                                </div>
                                <a href="#" v-on:click="download(item.name)" class="text">
                                    {{ item.name }}
                                </a>
                            </template>
                            <template v-else>
                                <div class="explorer-img-box">
                                    <img src="/img/file.png"><br>
                                </div>
                                <a href="#">
                                    <a href="#" v-on:click="download(item.name)" class="text">
                                        {{ item.name }}
                                    </a>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3"><div class="panel panel-default">
                <div class="panel-heading">Объектов: {{itemsChecked.length}}</div>
                <div class="panel-body">
                    <template v-if="itemsChecked.length > 0">
                        <template v-if="itemsChecked.length == 1">
                            <template v-for="item in itemsChecked">
                                <h4>{{item.name}}</h4>
                                <p>Размер: {{item.size}}</p>
                                <p>Изменен: {{item.date}}</p>
                                <p>Права: {{item.perms}}</p>
                            </template>
                        </template>
                        <div class="list-group">
                            <a href="#" class="list-group-item"><span class="glyphicon glyphicon-film"></span>&nbsp;&nbsp;Воспроизвести</a>
                            <a href="#" class="list-group-item"><span class="glyphicon glyphicon-arrow-right"></span>&nbsp;&nbsp;Переместить</a>
                            <a href="#" class="list-group-item"><span class="glyphicon glyphicon-duplicate"></span>&nbsp;&nbsp;Копировать</a>
                            <a href="#" class="list-group-item"><span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;В новую папку</a>
                            <a href="#" class="list-group-item"><span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Удалить</a>
                        </div>
                    </template>
                </div>
            </div></div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            var componentData = {
                uri: '/',
                items: [],
                itemsChecked: [],
                getData: function(name) {
                    jQuery.ajax('/dir-list/' + componentData.uri +  (name ? '/' + name : ''), {
                        data: {},
                        method: 'GET',
                        success: function(data) {
                            componentData.uri = data.uri;
                            componentData.items = data.items;
                        }
                    });
                    return false;
                },
                download: function(name) {
                    window.location.href = '/dir-download/' + componentData.uri + (name ? '/' + name : '');
                    return false;
                }
            };
            componentData.getData();
            return componentData;
        },
        mounted() {}
    }
</script>


<style>
    .explorer-item {
        display: inline-block;
        vertical-align: bottom;
        height: 140px;
        width: 128px;
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

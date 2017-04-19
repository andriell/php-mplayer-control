<template>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Путь: {{uri}}</div>
                    <div class="panel-body">
                        <div class="explorer-item" v-for="item in items">
                            <template v-if="item.type == 'dir'">
                                <div class="explorer-img-box">
                                    <img src="/img/dir.png">
                                </div>
                                <a href="#" v-on:click="getData(item.name)">
                                     {{ item.name }}
                                </a>
                            </template>
                            <template v-else-if="item.type == 'image'">
                                <div class="explorer-img-box">
                                    <img :src="'/dir-img-100x100/' + uri + '/' + item.name" width="100" height="100">
                                </div>
                                <a href="#" v-on:click="download(item.name)">
                                    {{ item.name }}
                                </a>
                            </template>
                            <template v-else>
                                <div class="explorer-img-box">
                                    <img src="/img/file.png"><br>
                                </div>
                                <a href="#">
                                    <a href="#" v-on:click="download(item.name)">
                                        {{ item.name }}
                                    </a>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3"><div class="panel panel-default">
                <div class="panel-heading">Инфо:</div>
                <div class="panel-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item">First item</a>
                        <a href="#" class="list-group-item">Second item</a>
                        <a href="#" class="list-group-item">Third item</a>
                    </div>
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
        padding: 2px;
        text-align: center;
        overflow: hidden;
    }
    .explorer-img-box {
        display: block;
        margin: auto;
        width: 100px;
        height: 100px;
        overflow: hidden;
        text-align: center;
    }
    .explorer-img-box img {
        display: block;
        margin: auto;
    }
</style>

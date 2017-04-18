<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Путь: {{uri}}</div>
                    <div class="panel-body">
                        <div class="item" v-for="item in items">
                            <template v-if="item.is_dir">
                                <a href="#" v-on:click="getData(item.name)">
                                    <img src="/img/dir.png"><br>
                                    {{ item.name }}
                                </a>
                            </template>
                            <template v-else>
                                <a href="#">
                                    <a href="#" v-on:click="download(item.name)">
                                        <img src="/img/file.png"><br>
                                        {{ item.name }}
                                    </a>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
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
                    jQuery.ajax('/dir-list', {
                        data: {uri: componentData.uri + (name ? '/' + name : '')},
                        method: 'GET',
                        success: function(data) {
                            componentData.uri = data.uri;
                            componentData.items = data.items;
                        }
                    });
                    return false;
                },
                download: function(name) {
                    window.location.href = '/dir-download/?uri=' + componentData.uri + (name ? '/' + name : '');
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
    .item {
        display: inline-block;
        vertical-align: bottom;
        height: 140px;
        width: 128px;
        padding: 10px;
        text-align: center;
        overflow: hidden;
    }
</style>

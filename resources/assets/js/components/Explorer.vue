<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Путь: {{path}}</div>
                    <div class="panel-body">
                        <div class="item" v-for="item in items">
                            <a href="#" v-on:click="click">
                                {{ item.name }}
                            </a>
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
                path: '/',
                items: [
                    {name: 'name1'},
                    {name: 'name2'}
                ],
                getData: function() {
                    jQuery.ajax('/dir/list', {
                        data: {path: componentData.path},
                        method: 'GET',
                        success: function(data) {
                            componentData.path = data.path;
                            componentData.items = data.items;
                        }
                    });
                },
                click: function() {
                    componentData.getData();
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
    }
</style>

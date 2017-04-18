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
                                    {{ item.name }}
                                </a>
                            </template>
                            <template v-else>
                                {{ item.name }}
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
                items: [
                    {name: 'name1'},
                    {name: 'name2'}
                ],
                getData: function(name) {
                    jQuery.ajax('/dir/list', {
                        data: {uri: componentData.uri + (name ? '/' + name : '')},
                        method: 'GET',
                        success: function(data) {
                            componentData.uri = data.uri;
                            componentData.items = data.items;
                        }
                    });
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

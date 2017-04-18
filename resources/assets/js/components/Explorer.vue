<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Путь: {{path}}</div>
                    <div class="panel-body">
                        <div class="item" v-for="item in items">
                            {{ item.name }}
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
            return {
                path: '/',
                items: [
                    {name: 'name1'},
                    {name: 'name2'}
                ]
            }
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

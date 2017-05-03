<template>
    <div class="panel panel-default">
        <div class="panel-heading">Торренты</div>
        <div class="panel-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Название</th>
                    <th>Статус</th>
                    <th>Дата</th>
                    <th>haveValid</th>
                    <th>totalSize</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, itemId) in items">
                    <td>{{ item.id }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.status }}</td>
                    <td>{{ item.doneDate }}</td>
                    <td>{{ item.haveValid }}</td>
                    <td>{{ item.totalSize }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            var localData = window.appData.torrent = {
                items: [],
                getData: function () {
                    jQuery.ajax('/torrent-list/', {
                        success: function (data) {
                            localData.items = data.items;
                        }
                    });
                }
            };
            localData.getData();
            return localData;
        },
        mounted() {
        }
    }
</script>


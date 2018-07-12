<template>
    <table class="editor-txt" v-if="run">
        <tr class="editor-txt-row1">
            <td colspan="4">{{dir}}/{{file}}</td>
        </tr>
        <tr class="editor-txt-row2">
            <td class="editor-txt-col1">
                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-save-file"></span> Сохранить</button>
            </td>
            <td class="editor-txt-col2">
                <select class="form-control" id="charsetIn" v-model="charsetIn">
                    <template v-for="(item, itemId) in charsets">
                        <option :value="itemId">{{item}}</option>
                    </template>
                </select>
            </td>
            <td class="editor-txt-col3">
                <select class="form-control" id="charsetOun" v-model="charsetOut">
                    <template v-for="(item, itemId) in charsets">
                        <option :value="itemId">{{item}}</option>
                    </template>
                </select>
            </td>
            <td class="editor-txt-col4">
                <button type="button" class="btn btn-default"  v-on:click="hide()"><span class="glyphicon glyphicon-remove"></span> Закрыть</button>
            </td>
        </tr>
        <tr class="editor-txt-row3">
            <td colspan="4">
                <textarea v-model="dataTxt" class="editor-txt-text"></textarea>
            </td>
        </tr>
    </table>
</template>

<script>
    export default {
        data: function () {
            var localData = window.appData.editorTxt = {
                run: false,
                file: '',
                dir: '',
                charsetIn: 'utf8',
                charsetOut: 'utf8',
                charsets: {
                    'utf8': 'UTF-8',
                    'ansi': 'ANSI',
                },
                dataTxt: '',
                show: function () {
                    localData.run = true;
                },
                hide: function () {
                    localData.run = false;
                },
            };
            return localData;
        },
        mounted() {
        }
    }
</script>


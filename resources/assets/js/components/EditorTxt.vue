<template>
    <table class="editor-txt" v-if="run">
        <tr class="editor-txt-row1">
            <td>
                <!-- Static navbar -->
                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#editor-txt-navbar" aria-expanded="false" aria-controls="navbar">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand">TxtEditor</a>
                        </div>
                        <div id="editor-txt-navbar" class="navbar-collapse collapse">
                            <ul class="nav navbar-nav">
                                <li><a v-on:click="save()"><span class="glyphicon glyphicon-save-file"></span> Сохранить</a></li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-log-in"></span> {{charsets[charsetIn]}} <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <template v-for="(item, itemId) in charsets">
                                            <li v-bind:class="[itemId==charsetIn ? 'active' : '']"><a v-on:click="setCharsetIn(itemId)">{{item}}</a></li>
                                        </template>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-log-out"></span> {{charsets[charsetOut]}} <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <template v-for="(item, itemId) in charsets">
                                            <li v-bind:class="[itemId==charsetOut ? 'active' : '']"><a v-on:click="setCharsetOut(itemId)">{{item}}</a></li>
                                        </template>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">NL - {{nlList[nl]}} <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <template v-for="(item, itemId) in nlList">
                                            <li v-bind:class="[itemId==nl ? 'active' : '']"><a v-on:click="setNl(itemId)">{{item}}</a></li>
                                        </template>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                <li><a v-on:click="hide()"><span class="glyphicon glyphicon-remove"></span> Закрыть</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </td>
        </tr>
        <tr class="editor-txt-row2">
            <td>{{dir}}/{{file}} <span class="text-info">{{message}}</span> <span class="text-danger">{{error}}</span></td>
        </tr>
        <tr class="editor-txt-row3">
            <td>
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
                charsetIn: 'UTF-8',
                charsetOut: 'UTF-8',
                nlList: {
                    'n':  'n',
                    'rn': 'rn',
                    'r':  'r'
                },
                nl: 'n',
                charsets: {
                    'UTF-8': 'utf-8',
                    'WINDOWS-1251': 'ansi',
                },
                dataTxt: '',
                message: '',
                error: '',
                setCharsetIn: function (v) {
                    localData.charsetIn = v;
                    localData.load();
                },
                setCharsetOut: function (v) {
                    localData.charsetOut = v;
                    if (v === 'UTF-8') {
                        localData.nl = 'n';
                    }
                    if (v === 'WINDOWS-1251') {
                        localData.nl = 'rn';
                    }
                },
                setNl: function (v) {
                    localData.nl = v;
                },
                show: function () {
                    localData.run = true;
                    localData.load();
                },
                hide: function () {
                    localData.run = false;
                },
                load: function () {
                    localData.showMessage('Загрузка');
                    var uri = localData.dir + '/' + localData.file;
                    jQuery.ajax('/editor-txt-load/' + uri, {
                        data: {
                            charset_in: localData.charsetIn
                        },
                        success: function (data) {
                            localData.dataTxt = data;
                            localData.showMessage('Загружено');
                        },
                        error: function (jqXHR, exception) {
                            localData.dataTxt = '';
                            localData.showError('Ошибка');
                        }
                    });
                },
                save: function () {
                    var uri = localData.dir + '/' + localData.file;
                    localData.showMessage('Сохраняю');
                    jQuery.ajax('/editor-txt-save/' + uri, {
                        method: 'POST',
                        data: {
                            charset_out: localData.charsetOut,
                            nl: localData.nl,
                            data: localData.dataTxt
                        },
                        success: function (data) {
                            localData.showMessage('Сохранено');
                        },
                        error: function (jqXHR, exception) {
                            localData.showError('Ошибка');
                        }
                    });
                },
                showMessage: function (m) {
                    localData.message = m;
                    setTimeout(function () {
                        localData.message = '';
                    }, 3000);
                },
                showError: function (m) {
                    localData.error = m;
                    setTimeout(function () {
                        localData.error = '';
                    }, 3000);
                }
            };
            return localData;
        },
        mounted() {
        }
    }
</script>


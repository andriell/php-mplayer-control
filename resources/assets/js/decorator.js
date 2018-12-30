/**
 * Created by Андрей on 04.05.2017.
 */

module.exports = {
    size: function (size, precision) {
        precision = typeof precision !== 'undefined' ? precision : 2;
        precision = Math.pow(10, precision);
        if (size > 1099511627776) {
            return Math.floor(size / 1099511627776 * precision) / precision + ' Тб';
        } else if (size > 1073741824) {
            return Math.floor(size / 1073741824 * precision) / precision + ' Гб';
        } else if (size > 1048576) {
            return Math.floor(size / 1048576 * precision) / precision + ' Мб';
        } else if (size > 1024) {
            return Math.floor(size / 1024 * precision) / precision + ' Кб';
        } else {
            return size + ' b';
        }
    },
    speed: function (size, precision) {
        precision = typeof precision !== 'undefined' ? precision : 2;
        precision = Math.pow(10, precision);
        if (size > 1099511627776) {
            return Math.round(size / 1099511627776 * precision) / precision + ' Тб/c';
        } else if (size > 1073741824) {
            return Math.round(size / 1073741824 * precision) / precision + ' Гб/c';
        } else if (size > 1048576) {
            return Math.round(size / 1048576 * precision) / precision + ' Мб/c';
        } else if (size > 1024) {
            return Math.round(size / 1024 * precision) / precision + ' Кб/c';
        } else {
            return size + ' b/c';
        }
    },
    date: function (timestamp) {
        var a = new Date(timestamp * 1000);
        return a.getFullYear() + '-' +
            (a.getMonth() > 8 ? a.getMonth() + 1 : '0' + a.getMonth()) + '-' +
            (a.getDate() > 9 ? a.getDate() : '0' + a.getDate()) + ' ' +
            (a.getHours() > 9 ? a.getHours() : '0' + a.getHours()) + ':' +
            (a.getMinutes() > 9 ? a.getMinutes() : '0' + a.getMinutes()) + ':' +
            (a.getSeconds() > 9 ? a.getSeconds() : '0' + a.getSeconds());
    },
    filePreview: function(uri, date) {
        var p = uri.lastIndexOf('.');
        return '/dir-img/100x100/' + uri.substring(0, p) + '-' + date + uri.substring(p, uri.length);
    },
    torrentStatus: function (status) {
        if (status == 0) {
            return 'Остановлен';
        } else if (status == 1) {
            return 'Ожидание проверки локальных файлов';
        } else if (status == 2) {
            return 'Проверка локальных файлов';
        } else if (status == 3) {
            return 'В очереди для скачивания';
        } else if (status == 4) {
            return 'Скачивание';
        } else if (status == 5) {
            return 'В очереди для раздачи';
        } else if (status == 6) {
            return 'Раздается';
        } else {
            return 'Неизвестно';
        }
    },

    widthP: function (val, max) {
        return 'width:' + (Math.round(val / max * 10000) / 100) + '%;'
    },

    percent: function (val, max, precision) {
        precision = typeof precision !== 'undefined' ? precision : 2;
        precision = Math.pow(10, precision);
        return (Math.round(val / max * 100 * precision) / precision) + '%'
    },

    seconds: function (sec) {
        if (sec < 0) {
            return '';
        }
        sec = Math.round(sec);
        var h = Math.floor(sec / 3600);
        var m = Math.floor((sec - (h * 3600)) / 60);
        var s = sec - (h * 3600) - (m * 60);
        return (h > 9 ? h : '0' + h) + ':' + (m > 9 ? m : '0' + m) + ':' + (s > 9 ? s : '0' + s);
    }

};
/**
 * Created by Андрей on 04.05.2017.
 */

window.decorator = {
    size: function (size, precision) {
        precision = typeof precision !== 'undefined' ? precision : 2;
        if (size > 1099511627776) {
            return Math.round(size / 1099511627776 * precision) / precision + ' Тб';
        } else if (size > 1073741824) {
            return Math.round(size / 1073741824 * precision) / precision + ' Гб';
        } else if (size > 1048576) {
            return Math.round(size / 1048576 * precision) / precision + ' Мб';
        } else if (size > 1024) {
            return Math.round(size / 1024 * precision) / precision + ' Кб';
        } else {
            return size + ' b';
        }
    },
    speed: function (size, precision) {
        precision = typeof precision !== 'undefined' ? precision : 2;
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
    date: function timeConverter(timestamp){
        var a = new Date(timestamp * 1000);
        return a.getFullYear() + '-' +
            (a.getMonth() > 9 ? a.getMonth() + 1 : '0' + a.getMonth()) + '-' +
            (a.getDate() > 10 ? a.getDate() : '0' + a.getDate()) + ' ' +
            (a.getHours() > 10 ? a.getHours() : '0' + a.getHours()) + ':' +
            (a.getMinutes() > 10 ? a.getMinutes() : '0' + a.getMinutes()) + ':' +
            (a.getSeconds() > 10 ? a.getSeconds() : '0' + a.getSeconds());
    }

};
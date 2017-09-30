let util = {

};
util.title = function (title) {
    title = title ? title + ' - Home' : 'iView project';
    window.document.title = title;
};

util.randomColorRgba = function(opacity){
    opacity = (typeof(opacity) == 'undefined') ? 1 : opacity;
    return 'rgba(' + util.random(0,256) + ',' + util.random(0,256) + ',' + util.random(0,256) + "," + opacity + ")";
};
/**
 * 获取随机数， 大于等于min， 小于max
 * @param min
 * @param max
 * @returns {Number}
 */
util.random = function(min, max){
    var num = Math.random()*(max-min) + min;
    return parseInt(num, 10);
};

//日期格式化
Date.prototype.format = function(format){
    if(!format){
        format = 'yyyy-MM-dd';//默认1997-01-01这样的格式
    }
    var o = {
        "M+" : this.getMonth()+1, //month
        "d+" : this.getDate(), //day
        "h+" : this.getHours() == 0 ? (new Date()).getHours() : this.getHours(), //hour
        "m+" : this.getMinutes() == 0 ? (new Date()).getMinutes() : this.getMinutes(), //minute
        "s+" : this.getSeconds() == 0 ? (new Date()).getSeconds() : this.getSeconds(), //second
        "q+" : Math.floor((this.getMonth()+3)/3), //quarter
        "S" : this.getMilliseconds() //millisecond
    }

    if(/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
    }

    for(var k in o) {
        if(new RegExp("("+ k +")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length==1 ? o[k] : ("00"+ o[k]).substr((""+ o[k]).length));
        }
    }
    return format;
};

export default util;
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

//数值显示格式转化
util.numToCurrency = function(val, dec) {
    val = parseFloat(val);
    dec = dec || 2;	//小数位
    if(val === 0 || isNaN(val)){
        return '0.00';
    }
    val = val.toFixed(dec).split('.');
    var reg = /(\d{1,3})(?=(\d{3})+(?:$|\D))/g;
    return val[0].replace(reg, "$1,") + '.' + val[1];
};
//数值显示
util.currencyToNum = function(val){
    var val = String(val);
    if ($.trim(val) == '') {
        return '';
    }
    val = val.replace(/,/g, '');
    val = parseFloat(val);
    return isNaN(val) ? 0 : val;
};
//浮点数加法运算
util.floatAdd = function(arg1,arg2){
    var r1,r2,m;
    try{r1=arg1.toString().split(".")[1].length}catch(e){r1=0}
    try{r2=arg2.toString().split(".")[1].length}catch(e){r2=0}
    m=Math.pow(10,Math.max(r1,r2))
    return (arg1*m+arg2*m)/m
};

//浮点数减法运算
util.floatSub = function(arg1,arg2){
    var r1,r2,m,n;
    try{r1=arg1.toString().split(".")[1].length}catch(e){r1=0}
    try{r2=arg2.toString().split(".")[1].length}catch(e){r2=0}
    m=Math.pow(10,Math.max(r1,r2));
    //动态控制精度长度
    n=(r1>=r2)?r1:r2;
    return ((arg1*m-arg2*m)/m).toFixed(n);
};

//浮点数乘法运算
util.floatMul = function(arg1,arg2)
{
    var m=0,s1=arg1.toString(),s2=arg2.toString();
    try{m+=s1.split(".")[1].length}catch(e){}
    try{m+=s2.split(".")[1].length}catch(e){}
    return Number(s1.replace(".",""))*Number(s2.replace(".",""))/Math.pow(10,m)
};

//浮点数除法运算
util.floatDiv = function(arg1,arg2){
    var t1=0,t2=0,r1,r2;
    try{t1=arg1.toString().split(".")[1].length}catch(e){}
    try{t2=arg2.toString().split(".")[1].length}catch(e){}
    r1=Number(arg1.toString().replace(".",""))
    r2=Number(arg2.toString().replace(".",""))
    return (r1/r2)*pow(10,t2-t1);
};

util.isArray = function (val) {
    return Object.prototype.toString.call(val) === '[object Array]';
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
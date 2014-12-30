var FCAPP = FCAPP || {};
FCAPP.HOUSE = FCAPP.HOUSE || {};
FCAPP.HOUSE.List = {
    RUNTIME: {
        LI: {}
    },
    init: function() {
        List.initElements();
        List.loadListData();
        var id = '';
        if (window.gQuery && gQuery.id) {
            id = gQuery.id;
        }
        FCAPP.Common.loadShareData(id);
    },
    initElements: function() {
        var R = List.RUNTIME;
        if (!R.actTop) {
            R.container = $('#roomDetails');
            R.actTop = $('#actTop');
            R.actTopShow = $('div.act_top_show img', R.actTop);
            R.template = FCAPP.Common.escTpl($('#template').html());
            R.isIOS = /Mac OS/i.test(navigator.userAgent);
        }
        FCAPP.Common.hideToolbar();
    },
    loadListData: function() {
        window.showRooms = List.showRooms;
        var datafile = window.gQuery && gQuery.id ? gQuery.id + '.': '',
        dt = new Date();
        datafile = datafile.replace(/[<>\'\"\/\\&#\?\s\r\n]+/gi, '');
        datafile += 'rooms.js?';
//        $.ajax({
//            url: 'data/' + datafile + dt.getDate() + dt.getHours(),
//            dataType: 'jsonp',
//            error: function() {
//                FCAPP.Common.msg(true, {
//                    msg: '无效的户型！'
//                });
//            }
//        });
        // $.ajax({
        //     //url: '/Webestate/Housedata/pid/'+PID+'/wechatid/'+WECHATID,
        //     url:"{pigcms::U('Estate/housetype',array('token'=>$token,id=>"+PID+",wecha_id=>"+WECHATID+"))}",
        //     dataType: 'jsonp',
        //     error: function() {
        //         FCAPP.Common.msg(true, {
        //             msg: '无效的户型！'
        //         });
        //     }
        // });
    },
    showRooms: function(res) {
        var R = List.RUNTIME;
        FCAPP.Common.hideLoading();
        var data = res.rooms,
        content = '',
        categories = List.sortCategory(data);
        FCAPP.Common.loadImg(res.banner, 'bannerPic',
        function(img) {
            img.width = 720;
            img.height = 130;
            img.id = img.idx;
        });
        content = $.template(R.template, {
            data: categories
        });
        R.container.html(content);
    },
    sortCategory: function(rooms) {
        var tmp = [],
        category = {},
        newCategory = [],
        key,
        other,
        i,
        defaultDesc = '其他户型';
        for (i = 0, il = rooms.length; i < il; i++) {
            if (!rooms[i].desc) {
                rooms[i].desc = defaultDesc;
            }
            if (rooms[i].desc in category) {
                category[rooms[i].desc].push(rooms[i]);
            } else {
                category[rooms[i].desc] = [rooms[i]];
            }
        }
        for (i in category) {
            tmp.push({
                key: i,
                len: category[i].length
            });
        }
        tmp.sort(function(a, b) {
            return b.len - a.len;
        });
        for (i = 0, il = tmp.length; i < il; i++) {
            key = tmp[i].key;
            if (key !== defaultDesc) {
                newCategory.push({
                    key: key,
                    rooms: category[key]
                });
            }
        }
        if (category[defaultDesc] && category[defaultDesc].length > 0) {
            newCategory.push({
                key: defaultDesc,
                rooms: category[defaultDesc]
            });
        }
        tmp = null;
        category = null;
        return newCategory;
    },
    //平面户型图
    showDetail: function(id) {
        FCAPP.Common.jumpTo('/index.php?g=Wap&m=Estate&a=album&token=', {
            houseid: id
        },
        true);
    },//3D户型图
    show3D: function(houseid) {
        var t = new Date();
        FCAPP.Common.jumpTo("{pigcms::U('Estate/3dalbum',array('token'=>$token))}", {
            houseid: houseid
        });
    },
    switchList: function(obj, len, idx) {
        var R = List.RUNTIME.LI,
        box = obj.parentNode;
        if (!R['boxLiBox' + idx]) {
            R['boxLiBox' + idx] = $('#box' + idx + ' div.box_type');
            R['boxPhoto' + idx] = $('#box' + idx + ' div.house_photo');
            R['boxLi' + idx] = $('#box' + idx + ' li');
        }
        if (box.className.indexOf("box_up") != -1) {
            R['boxLiBox' + idx].css('height', 'auto');
            box.className = "box";
            obj.innerHTML = '<span>收起</span>';
        } else {
            R['boxLi' + idx].removeClass('current');
            R['boxPhoto' + idx].hide();
            R['boxLiBox' + idx].css('height', '252px');
            box.className = "box box_up";
            obj.innerHTML = '查看全部户型(' + len + ')';
        }
    },
    toggleList: function(id, gid) {
        var R = List.RUNTIME.LI,
        height = 0,
        cls = '';
        if (!R['boxLi' + id]) {
            R['boxLi' + id] = $('#boxLi' + id);
            R['boxLiSlide' + id] = $('#boxLi' + id + ' div.house_photo');
        }
        if (!R['boxLiBox' + gid]) {
            R['boxLiBox' + gid] = R['boxLi' + id].parent().parent();
            R['boxPhoto' + gid] = $('#box' + gid + ' div.house_photo');
            R['boxLi' + gid] = $('#box' + gid + ' li');
        }
        cls = R['boxLiSlide' + id].css('display');
        height = parseInt(R['boxLiBox' + gid].css('height'));
        if (cls == 'none') {
            R['boxLiSlide' + id].css('display', '-webkit-box');
            R['boxLiBox' + gid].css('height', height + 52 + 'px');
            R['boxLiSlide' + id].addClass('house_arrow');
            R['boxLi' + id].addClass('current');
        } else {
            R['boxLi' + id].removeClass('current');
            R['boxLiBox' + gid].css('height', height - 52 + 'px');
            R['boxLiSlide' + id].css('display', 'none');
            R['boxLiSlide' + id].removeClass('house_arrow');
        }
    }
};
var HOUSE = FCAPP.HOUSE,
List = HOUSE.List;
$(document).ready(List.init);
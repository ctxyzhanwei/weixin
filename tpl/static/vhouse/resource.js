
window.Resource = {
    instance: {},
    create: function (option) {
        return new Builder(option)
    }

};
function Builder(opt) {
    this.option = {
        id: "res_block"
    };
    $.extend(this.option, opt);
    this.init();
    return this;
}
Builder.prototype.init = function () {
    var self = this;
    var opt = this.option;
    var $target = $("#" + opt.id);
    $target.find("#type").change(function () {
        $target.find(".r-module").hide();
        $("#r_" + $(this).val()).show();
        self._changeModule($(this).val())
    });
    this.loadModule = {};
    //$target.find(".selArticle").click(function () {
    //    parent.parent.showArticleChoice(self.allowMulArticle ? "all" : null);
    //    return false
    //});
    $target.find("#act").change(function () {
        self._loadAct()
    });
    $target.find("#business_func").change(function () {
        self._loadBusiness();
    });
    $target.find("#car_func").change(function () {
        self._loadCar();
    });
    $target.find("#estate_func").change(function () {
        self._loadEstate();
    });
    $("#r_activity").add("#r_business").add("#r_car").add("#r_estate").delegate("tr.data-row", "click", function () {
        $(this).closest("table").find("tr").removeClass("selected");
        $(this).addClass("selected").find(".act-sel").attr("checked", "checked")
    }); 
    $("form button[type='submit']").click(function () {
        return self.getResult() || false;
    })
    Resource.instance[opt.id] = self;
};
function alert(t) {
    G.ui.tips.err(t);
}
Builder.prototype.getResult = function () {
    var opt = this.option;
    var $target = $("#" + opt.id);
    var module = $target.find("#type").val();
    var data = null;
   
    switch (module) {
        case "text":
            var v = $("textarea[name='replyText']").val();
            if ($.trim(v) == "") {
                alert("文本内容不可以为空。")
            } else {
                if (v.length > 1000) {
                    alert("不超过1000个字符。")
                } else {
                    data = {
                        type: 1,
                        text: v
                    }
                }
            }
            break;
        case "article":
            if ($("div.choose").length > 0) {
                var v = $("#r_article input[type='hidden']");
                if (v.length == 0) {
                    alert("请选择图文");
                } else {
                    data = {
                        id: v.val()
                    }
                };
            } else {
                data = {
                    tyoe: "article"
                }
            }
            break;
        case "activity":
            var $selected = $("#r_activity .selected");
            if ($selected.length == 0) {
                alert("请至少选择一个正在进行中的活动。")
            } else {
                var t = $target.find("#act").val();
                var id = $selected.attr("data-id");
                if (t == $selected.attr("data-type")) {
                    data = {
                        type: t,
                        id: id,
                        title: $selected.find("td.title").text(),
                        extraData: $selected.find(".keyword").text()
                    }
                } else {
                    alert("网络异常，请尝试刷新本页面再进行操作！")
                }
            }
            break;
        case "business":
            var $selected = $target.find("#r_business :selected");
            if ($selected.length == 0) {
                alert("请选择一个业务模块。")
            } else {
                var t = $selected.data("list");
                var id = $selected.attr("data-id");
                if (t) {
                    var $selected_tr = $("#r_business .selected");
                    if ($selected_tr.length == 0) {
                        alert("请至少选择一个正在进行中的业务。")
                    } else {
                        data = {
                            type: $selected.val(),
                            id: id,
                            title: $selected.find("td.title").text(),
                            extraData: $selected.find(".keyword").text()
                        }
                    }

                } else {
                    data = {
                        type: $selected.val()
                    }
                }

            }
            break;
        case "car":
            var $selected = $target.find("#r_car :selected");
            if ($selected.length == 0) {
                alert("请选择一个。")
            } else {
                var t = $selected.data("list");
                var id = $selected.attr("data-id");
                if (t) {
                    var $selected_tr = $("#r_car .selected");
                    if ($selected_tr.length == 0) {
                        alert("请至少选择一个正在进行中的预约。")
                    } else {
                        data = {
                            type: $selected.val(),
                            id: id,
                            title: $selected.find("td.title").text(),
                            extraData: $selected.find(".keyword").text()
                        }
                    }

                } else {
                    data = {
                        type: $selected.val()
                    }
                }

            }
            break; 
        default:
            data = {
                type: module
            }
            break;

    }
    return data
};
Builder.prototype._changeModule = function (module) {
    var loaded = this.loadModule[module];
    if (!loaded) {
        switch (module) {
            case "article":
                if ($("div.choose").length > 0) {
                    var v = $("#r_article input[type='hidden']");
                    if (v.length == 0) {
                        _choose_init();
                    }
                };
                break;
            case "map":
                this._loadBaidu_map();
                break;
            case "activity":
                this._loadAct();
                break;
            case "business":
                this._loadBusiness();
                break;
            case "car":
                this._loadCar();
                break;
        }
    }
    this.loadModule[module] = 1
};
Builder.prototype._loadBusiness = function () {
    var self = this;
    var opt = this.option;
    var $target = $("#" + opt.id);
    var data = Resource.instance[opt.id].result;
    var select = $target.find("#business_func");
    var t = select.val();
    var tt = select.find("option:selected").data("list");
    if (tt) {
        $("#r_business div.margin-top").show();
        //var top = 0;
        $.post("/microsite/getbusiness", {
			wuid: data.wuid,
            type: t
        }, function (result) {
            if (result.success) {
                var data = result.data;
                $("#r_business tr.data-row").remove();
                if (data.length > 0) {
                    $("#r_business .no-record").hide();
                    var cont = [];
                    for (var i = 0; i < data.length; i++) {
                        var item = data[i];
                        cont.push('<tr data-type="' + t + '" data-id="' + item.id + '" class="data-row">');
                        cont.push('<td class="with-checkbox"><input type="radio" name="business_value" class="act-sel" value="' + item.id + '"/></td>');
                        cont.push('<td class="title">' + item.a_name + "</td>");
                        cont.push('<td class="keyword">' + item.keyword + "</td>");
                        cont.push("<td>" + item.start_time + "~" + item.end_time + "</td>");
                        cont.push("</tr>")
                    }
                    $("#r_business table").append(cont.join(""))
                    top += data.length;
                } else {
                    $("#r_business .no-record").show()
                }
                if (result.count > 10 && top < result.count) {
                    $("#r_business .record").show()
                } else {
                    $("#r_business .record").hide()
                }
            }
        }, "json");
    } else {
        $("#r_business div.margin-top").hide();

    }


};
Builder.prototype._loadCar = function () { 
    var self = this;
    var opt = this.option;
    var $target = $("#" + opt.id);
    var data = Resource.instance[opt.id].result;
    var select = $target.find("#car_func");
    var t = select.val(); 
    var tt = select.find("option:selected").data("list");
    if (tt) {
        $("#r_car div.margin-top").show();
        //var top = 0;
        $.post("/microsite/getbusiness", {
			wuid: data.wuid,
            type: t
        }, function (result) {
            if (result.success) {
                var data = result.data;
                $("#r_business tr.data-row").remove();
                if (data.length > 0) {
                    $("#r_business .no-record").hide();
                    var cont = [];
                    for (var i = 0; i < data.length; i++) {
                        var item = data[i];
                        cont.push('<tr data-type="' + t + '" data-id="' + item.id + '" class="data-row">');
                        cont.push('<td class="with-checkbox"><input type="radio" name="business_value" class="act-sel" value="' + item.id + '"/></td>');
                        cont.push('<td class="title">' + item.a_name + "</td>");
                        cont.push('<td class="keyword">' + item.keyword + "</td>");
                        cont.push("<td>" + item.start_time + "~" + item.end_time + "</td>");
                        cont.push("</tr>")
                    }
                    $("#r_business table").append(cont.join(""))
                    top += data.length;
                } else {
                    $("#r_business .no-record").show()
                }
                if (result.count > 10 && top < result.count) {
                    $("#r_business .record").show()
                } else {
                    $("#r_business .record").hide()
                }
            }
        }, "json");
    } else {
        $("#r_car div.margin-top").hide();
    }
};
Builder.prototype._loadAct = function () {
    var self = this;
    var opt = this.option;
    var $target = $("#" + opt.id);
    var data = Resource.instance[opt.id].result;
    var t = $target.find("#act").val();
    //var top = 0;
    $.post("/microsite/getactivity", {
		wuid: data.wuid,
        type: t
    }, function (result) {
        if (result.success) {
            var data = result.data;
            $("#r_activity tr.data-row").remove();
            if (data.length > 0) {
                $("#r_activity .no-record").hide();
                var cont = [];
                for (var i = 0; i < data.length; i++) {
                    var item = data[i];
                    cont.push('<tr data-type="' + t + '" data-id="' + item.id + '" class="data-row">');
                    cont.push('<td class="with-checkbox"><input type="radio" name="activity_value" class="act-sel" value="' + item.id + '"/></td>');
                    cont.push('<td class="title">' + item.a_name + "</td>");
                    cont.push('<td class="keyword">' + item.keyword + "</td>");
                    cont.push("<td>" + item.start_time + "~" + item.end_time + "</td>");
                    cont.push("</tr>")
                }
                $("#r_activity table").append(cont.join(""))
                top += data.length;
            } else {
                $("#r_activity .no-record").show()
            }
            if (result.count > 10 && top < result.count) {
                $("#r_activity .record").show()
            } else {
                $("#r_activity .record").hide()
            }
        }
    }, "json");

};
Builder.prototype._loadEstate =function () { 
    var self = this;
    var opt = this.option;
    var $target = $("#" + opt.id);
    var data = Resource.instance[opt.id].result;
    var select = $target.find("#estate_func");
    var t = select.val();
    var tt = select.find("option:selected").data("list");
    if (tt) {
        $("#r_estate div.margin-top").show();
        //var top = 0;
        $.post("/microsite/getbusiness", {
            wuid: data.wuid,
            type: t
        }, function (result) {
            if (result.success) {
                var data = result.data;
                $("#r_business tr.data-row").remove();
                if (data.length > 0) {
                    $("#r_business .no-record").hide();
                    var cont = [];
                    for (var i = 0; i < data.length; i++) {
                        var item = data[i];
                        cont.push('<tr data-type="' + t + '" data-id="' + item.id + '" class="data-row">');
                        cont.push('<td class="with-checkbox"><input type="radio" name="business_value" class="act-sel" value="' + item.id + '"/></td>');
                        cont.push('<td class="title">' + item.a_name + "</td>");
                        cont.push('<td class="keyword">' + item.keyword + "</td>");
                        cont.push("<td>" + item.start_time + "~" + item.end_time + "</td>");
                        cont.push("</tr>")
                    }
                    $("#r_business table").append(cont.join(""))
                    top += data.length;
                } else {
                    $("#r_business .no-record").show()
                }
                if (result.count > 10 && top < result.count) {
                    $("#r_business .record").show()
                } else {
                    $("#r_business .record").hide()
                }
            }
        }, "json");
    } else {
        $("#r_estate div.margin-top").hide();

    }


};
Builder.prototype._loadBaidu_map = function () {
    var self = this;
    var opt = this.option;
    var data = Resource.instance[opt.id].map;
    var map = new BMap.Map("l-map");
    var myGeo = new BMap.Geocoder();
    //map.addControl(new BMap.MapTypeControl({mapTypes: [BMAP_NORMAL_MAP,BMAP_HYBRID_MAP]}));     //2D图，卫星图

    //map.addControl(new BMap.MapTypeControl({anchor: BMAP_ANCHOR_TOP_LEFT}));    //左上角，默认地图控件

    //alert(city);
    var currentPoint;
    var marker1;
    var marker2;
    map.enableScrollWheelZoom();
    if (data) {
        var point = new BMap.Point(data.lng, data.lat);
        marker1 = new BMap.Marker(point);        // 创建标注
        map.addOverlay(marker1);
        var opts = {
            width: 220,     // 信息窗口宽度 220-730
            height: 60,     // 信息窗口高度 60-650
            title: ""  // 信息窗口标题
        }
        var infoWindow = new BMap.InfoWindow("原本位置 " + data.adr + " ,移动红点修改位置!你也可以直接修改上方位置系统自动定位!", opts);  // 创建信息窗口对象
        marker1.openInfoWindow(infoWindow);      // 打开信息窗口
        doit(point);
    } else {
        var point = new BMap.Point(116.331398, 39.897445);
        doit(point);
        window.setTimeout(function () {
            auto();
        }, 100);
    }
    map.enableDragging();
    map.enableContinuousZoom();
    map.addControl(new BMap.NavigationControl());
    map.addControl(new BMap.ScaleControl());
    map.addControl(new BMap.OverviewMapControl());



    function auto() {
        var geolocation = new BMap.Geolocation();
        geolocation.getCurrentPosition(function (r) {
            if (this.getStatus() == BMAP_STATUS_SUCCESS) {
                //var mk = new BMap.Marker(r.point);  
                //map.addOverlay(mk);  
                // point = r.point;  
                //map.panTo(r.point); 

                var point = new BMap.Point(r.point.lng, r.point.lat);
                marker1 = new BMap.Marker(point);        // 创建标注
                map.addOverlay(marker1);
                var opts = {
                    width: 220,     // 信息窗口宽度 220-730
                    height: 60,     // 信息窗口高度 60-650
                    title: ""  // 信息窗口标题
                }

                var infoWindow = new BMap.InfoWindow("定位成功这是你当前的位置!,移动红点标注目标位置，你也可以直接修改上方位置,系统自动定位!", opts);  // 创建信息窗口对象
                marker1.openInfoWindow(infoWindow);      // 打开信息窗口
                doit(point);

            } else {
                //alert('failed' + this.getStatus());
            }
        })
    }
    function doit(point) {

        //map.centerAndZoom(point,12);  


        //myGeo.getPoint(city, function(point){ 
        if (point) {
            //window.external.setlngandlat(point.lng,point.lat);
            //alert(point.lng + "  ddd " + point.lat);

            document.getElementById('lat').value = point.lat;
            document.getElementById('lng').value = point.lng;
            map.setCenter(point);
            map.centerAndZoom(point, 15);
            map.panTo(point);

            var cp = map.getCenter();
            myGeo.getLocation(point, function (result) {
                if (result) {
                    document.getElementById('suggestId').value = result.address;
                }
            });






            marker2 = new BMap.Marker(point);        // 创建标注  
            var opts = {
                width: 220,     // 信息窗口宽度 220-730
                height: 60,     // 信息窗口高度 60-650
                title: ""  // 信息窗口标题
            }
            var infoWindow = new BMap.InfoWindow("拖拽地图或红点，在地图上用红点标注您的店铺位置。", opts);  // 创建信息窗口对象
            marker2.openInfoWindow(infoWindow);      // 打开信息窗口

            map.addOverlay(marker2);                     // 将标注添加到地图中

            marker2.enableDragging();
            marker2.addEventListener("dragend", function (e) {
                document.getElementById('lat').value = e.point.lat;
                document.getElementById('lng').value = e.point.lng;
                myGeo.getLocation(new BMap.Point(e.point.lng, e.point.lat), function (result) {
                    if (result) {
                        $('suggestId').value = result.address;
                        //$('city').value = result.city;
                        //			alert(result.address)				
                        //	window.external.setaddress(result.address);//setarrea(result.address);//
                        //marker1.setPoint(new BMap.Point(e.point.lng,e.point.lat));        // 移动标注
                        marker2.setPoint(new BMap.Point(e.point.lng, e.point.lat));
                        map.panTo(new BMap.Point(e.point.lng, e.point.lat));
                        //window.external.setlngandlat(e.point.lng,e.point.lat);
                    }
                });
            });

            map.addEventListener("dragend", function showInfo() {
                var cp = map.getCenter();
                myGeo.getLocation(new BMap.Point(cp.lng, cp.lat), function (result) {
                    if (result) {
                        document.getElementById('suggestId').value = result.address;
                        document.getElementById('lat').value = cp.lat;
                        document.getElementById('lng').value = cp.lng;
                        //	window.external.setaddress(result.address);//setarrea(result.address);//
                        //alert(point.lng + "  ddd " + point.lat);
                        //marker1.setPoint(new BMap.Point(cp.lng,cp.lat));        // 移动标注
                        marker2.setPoint(new BMap.Point(cp.lng, cp.lat));
                        map.panTo(new BMap.Point(cp.lng, cp.lat));
                        //window.external.setlngandlat(cp.lng,cp.lat);
                    }
                });
            });

            map.addEventListener("dragging", function showInfo() {
                var cp = map.getCenter();
                //marker1.setPoint(new BMap.Point(cp.lng,cp.lat));        // 移动标注
                marker2.setPoint(new BMap.Point(cp.lng, cp.lat));
                map.panTo(new BMap.Point(cp.lng, cp.lat));
                map.centerAndZoom(marker2.getPoint(), map.getZoom());
            });


        }


        //}, province);


    }

    function loadmap() {
        //var province = document.getElementById('city').value;
        var city = document.getElementById('suggestId').value;
        var myCity = new BMap.LocalCity();
        // 将结果显示在地图上，并调整地图视野  
        myGeo.getPoint(city, function (point) {
            if (point) {
                //marker1.setPoint(new BMap.Point(point.lng,point.lat));        // 移动标注
                marker2.setPoint(new BMap.Point(point.lng, point.lat));
                //window.external.setlngandlat(marker2.getPoint().lng,marker2.getPoint().lat);
                //alert(point.lng + "  ddd " + point.lat);
                document.getElementById('lat').value = point.lat;
                document.getElementById('lng').value = point.lng;
                map.panTo(new BMap.Point(marker2.getPoint().lng, marker2.getPoint().lat));
                map.centerAndZoom(marker2.getPoint(), map.getZoom());
            }
        });//({},province)
        //var citys = new BMap.LocalSearch(map, { renderOptions: { map: map, autoViewport: true } });
        //citys.search(city)
    }

    function setarrea(address, city) {
        $('suggestId').value = address;
        //$('city').value=city;
        window.setTimeout(function () {
            loadmap();
        }, 2000);
    }

    function initarreawithpoint(lng, lat) {
        window.setTimeout(function () {
            //marker1.setPoint(new BMap.Point(lng,lat));        // 移动标注
            marker2.setPoint(new BMap.Point(lng, lat));
            //window.external.setlngandlat(lng,lat);
            map.panTo(new BMap.Point(lng, lat));
            map.centerAndZoom(marker2.getPoint(), map.getZoom());
        }, 2000);
    }
    $("#suggestId").change(function () { loadmap(); })
    $("#positioning").click(function () { loadmap(); });


}
window.ICON = function () {
    var list = ["icon-file-text", "icon-globe ", "icon-credit-card", "icon-hand-up", "icon-dashboard ", "icon-money ", "icon-reorder", "icon-comments-alt ", "icon-smile", "icon-thumbs-up", "icon-truck", "icon-shopping-cart", "icon-group", "icon-user-md", "icon-home", "icon-plane", "icon-gift", "icon-food", "icon-phone", "icon-tags", "icon-rocket", "icon-cloud", "icon-map-marker", "icon-music", "icon-trophy", "icon-android", "icon-apple", "icon-star", "icon-rss-sign", "icon-heart", "icon-envelope", "icon-bar-chart", "icon-picture", "icon-download", "icon-gamepad", "icon-comment", "icon-check", "icon-cog", "icon-camera", "icon-cloud", "icon-facetime-video", "icon-spinner", "icon-bullhorn", "icon-location-arrow", "icon-list-ul", "icon-weibo", "icon-windows", "icon-time", "icon-th", "icon-user", "icon-microphone", "icon-bookmark",
        "icon-flag-checkered", "icon-qrcode", "icon-glass", "icon-stethoscope", "icon-medkit", "icon-ambulance", "icon-hospital", "icon-foursquare", "icon-download-alt", "icon-coffee", "icon-building", "icon-edit", "icon-book", "icon-question-sign", "icon-legal", "icon-calendar-empty", "icon-ellipsis-horizontal", "icon-pencil", "icon-suitcase", "icon-warning-sign", "icon-jpy", "icon-list-alt", "icon-html5", "icon-gittip", "icon-search", "icon-wrench", "icon-lemon", "icon-indent-right", "icon-paste", "icon-archive", "icon-sun", "icon-bitbucket"
    ];
    var tmp = '<li class="tile-themed"> <i class="{0}"></i></li>';
    var ul = '<ul>{0}</ul>';
    var s = '';
    $.each(list, function (k, v) {
        s += tmp.format(v);
    })
    $("div.icons-cont").html(ul.format(s));
    $(".sel-icon").click(function () {
        $(".icons-cont").toggle();
    });
    $(".icons-cont li").live("click", function () {
        var $thisclass = $(this).children().attr("class");
        $("#icon_i").attr("class", $thisclass)
        $("#icon").val($thisclass);
        $(".icons-cont").hide();
    });
    $(".icons-cont").hover(function () {

    }, function () {
        $(".icons-cont").fadeOut();
    });
}
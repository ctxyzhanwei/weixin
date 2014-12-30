window.onload=function(){

    startT = setInterval(defData,loadTime);

    $(".pairup-btn").on("click",
        function() {
            if (!$(".pairup-btn").hasClass("runed")) {
                
                startRun();
                $(".pairup-btn").hide();
                setTimeout(function() {
                    $(".pairup-btn").fadeIn()
                },
                    1000)
            } else {
                stopRun()
            }
        clearInterval(startT);
    });

    $("#replay").on("click",function() {
        window.location.reload();
    });


}

function startRun(){
    if (!$.isEmptyObject(maleCache) && !$.isEmptyObject(femaleCache)) {
                $(".star,.gxImg").remove();
                $(".pairup-btn").addClass("runed").text("停止");
                $(".result-title h3 i").hide();
                $(".save-result").removeClass("disable");
                imgChanged()
    } else {
        zAlert.Alert({
            content: "剩余玩家不足以配对！",
            callback: function() {
                zAlert.Close()
            }
        })
    }

}

function stopRun(){
    /*     
    if (a) {
        a.pause()
    }*/
    nanT ? clearInterval(nanT) : "";
    nvT ? clearInterval(nvT) : "";
    $(".pairup-btn").removeClass("runed").text("开始对对碰");
    
    star()
}
function star() {
            for (var g = 0; g < 100; g++) {
                var k = Math.floor(Math.random() * 10 + 1);
                k = k > 5 ? 1 : k;
                var f = 15;
                if (k == 3 || k == 4) {
                    f = 8
                }
                var p = Math.floor(Math.random() * f + 5);
                var d = (Math.random() > 0.5 ? 1 : -1),
                o = d * (Math.random() * 400);
                var n = (Math.random() > 0.5 ? 1 : -1),
                j = n * (Math.random() * 200);
                var m = '<div class="star t' + k + '" size="' + p + '" w="' + o + '" h="' + j + '"></div>';
                $(".pLeft").append(m)
            }
            $(".star").each(function() {
                var q = $(this).attr("size"),
                i = $(this).attr("w"),
                r = $(this).attr("h");
                $(this).width(q).height(q).animate({
                    left: "+=" + i,
                    top: "+=" + r
                },
                300)
            });
            var e = '<div class="gxImg"></div>';
            $(".pLeft").append(e);
            $(".gxImg").animate({
                width: "350px",
                height: "133px",
                "margin-left": "-175px"
            },
            400,
            function() {
                $(this).animate({
                    width: "290px",
                    height: "110px",
                    "margin-left": "-145px"
                },
                200)
            });

    getResult()
}

function getResult() {
            var e = this;
            var d = e.listItem();
            $(d).height(0).prependTo(".result-list ul").animate({
                height: "126px"
            },
            500,
            function() {
                $(this).find(".result-item").fadeIn(1000);
                var h = 0,
                g = 0;
                for (var l in e.femaleCache) {
                    h++
                }
                for (var f in e.maleCache) {
                    g++
                }
                $(".pairup-title em.w").html(h);
                $(".pairup-title em.m").html(g)
            }).find(".result-item").hide();
            $(".result-title b").text(e.resultIndex)
}

function listItem() {
            resultIndex++;
            var e = getInd("male", $(".pairup-pic .nan").attr("lid")),
                d = getInd("female", $(".pairup-pic .nv").attr("lid"));
            var f = "";

            f += '<li nInd="' + e + '" nvInd="' + d + '"><i>' + resultIndex + '</i><div class="result-item"><div class="result-item-left"><div class="nan"><img src="' + maleArr[e].portrait + '" alt="" /></div><p>' + maleArr[e].nickname + '</p></div><div class="result-item-right"><div class="nv"><img src="' + femaleArr[d].portrait + '" alt="" /></div><p>' + femaleArr[d].nickname + "</p></div></div></li>";
            addLog(maleArr[e].id,femaleArr[d].id);
            return f
}

function addLog(nid,vid){
    $.post("/index.php?g=User&m=Scene&a=add_slog", {'sceneid':sceneid,'nid':nid,'vid':vid}, function(f){});
}

function getInd(h, k) {
            var j = this;
            var f = j[h + "Arr"],
            e = f.length;
            var d = -1;
            for (var g = 0; g < e; g++) {
                if (f[g].id == k) {
                    d = g;
                    delete j[h + "Cache"][g];
                    return d
                }
            }
            return d
}



function imgChanged() {
            if (!$.isEmptyObject(maleCache)) {
                var d = 0;
                nanT = setInterval(function() {
                    var h = [];
                    for (var k in maleCache) {
                        h.push(k)
                    }
                    var g = h.length;
                    var j = h[d];
                    $(".pairup-pic .nan").css({
                        "background-image": "url(" + maleCache[j].portrait + ")"
                    }).attr("lid", maleCache[j].id);
                    d++;
                    if (d >= g) {
                        d = 0
                    }
                },
                111)
            } else {
                zAlert.Alert({
                    content: "剩余玩家不足以配对！",
                    callback: function() {
                        zAlert.Close()
                    }
                })
            }
            setTimeout(function() {},
            100);
            if (!$.isEmptyObject(femaleCache)) {
                /*
                if (a) {
                    a.play()
                }*/
                var e = 0;
                nvT = setInterval(function() {
                    var g = [];
                    for (var j in femaleCache) {
                        g.push(j)
                    }
                    var k = g.length;
                    var h = g[e];
                    $(".pairup-pic .nv").css({
                        "background-image": "url("+femaleCache[h].portrait+")"
                    }).attr("lid", femaleCache[h].id);
                    e++;
                    if (e >= k) {
                        e = 0
                    }
                },
                100)
            } else {
                zAlert.Alert({
                    content: "剩余玩家不足以配对！",
                    callback: function() {
                        zAlert.Close()
                    }
                })
            }
}





function defData(){
     $.getJSON("/index.php?g=User&m=Scene&a=defUser", {'sceneid':sceneid}, function(f){
        if(f.err == 0  && f.data){
                    if (f.data.list) {
                        var g = f.data.list;
                        if (g.male && g.male.length) {
                            maleArr = g.male;
                            for (var e = 0; e < g.male.length; e++) {
                                maleCache[e] = g.male[e]
                            }
                        }
                        if (g.female && g.female.length) {
                            femaleArr = g.female;
                            for (var e = 0; e < g.female.length; e++) {
                                femaleCache[e] = g.female[e]
                            }
                        }
                    }

                    $(".pairup-title em.w").html(f.data.femaleCount);
                    $(".pairup-title em.m").html(f.data.maleCount);

                    if ($("body").hasClass("sameRound") && f.data.filter_list) {
                        var g = f.data.filter_list;
                        if (g.female.length && g.male.length) {
                            zAlert.Confirm({
                                closed: false,
                                title: "过滤中奖用户",
                                content: "已中奖用户，是否可再参与抽奖?",
                                sureTxt: "否",
                                sureCallback: function() {
                                    for (var k = 0; k < g.male.length; k++) {
                                        for (var h in d.maleCache) {
                                            if (g.male[k].mid == d.maleCache[h].mid) {
                                                delete d.maleCache[h]
                                            }
                                        }
                                    }
                                    for (var k = 0; k < g.female.length; k++) {
                                        for (var h in d.femaleCache) {
                                            if (g.female[k].mid == d.femaleCache[h].mid) {
                                                delete d.femaleCache[h]
                                            }
                                        }
                                    }
                                    $(".pairup-title em.w").html(f.data.female_count - g.female.length);
                                    $(".pairup-title em.m").html(f.data.male_count - g.male.length);
                                    zAlert.Close()
                                },
                                cancelTxt: "是",
                                cancelCallback: function() {
                                    zAlert.Close()
                                }
                            })
                        }
                    }

        }else{
            $(".pairup-title em.w").html(f.data.femaleCount);
            $(".pairup-title em.m").html(f.data.maleCount);
        }

     });

}


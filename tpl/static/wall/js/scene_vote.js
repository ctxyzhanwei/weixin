$('.begin').click(function(){
        vote_id = $(this).parent('.vl').attr('lid');
    if ($(this).hasClass("disable")) {
        zAlert.Alert({
            content: "投票已经结束!",
                callback: function() {
                zAlert.Close()
            }
        })
    }else{
        $.getJSON("/index.php?g=User&m=Scene&a=get_vote", {'vote_id':vote_id,'scene_id':scene_id}, function(data){
            if (data.err == 0) {
                $(".Panel.VoteList").css({
                    opacity: 0,
                    display: "none"
                });
                $(".Panel.Vote").css({
                    display: "block",
                    opacity: 1
                })             
                createItem(data,".panelBox ul");
                vcount = setInterval(getVcount, 2000);
            } else {
                zAlert.Alert({
                    content: "没有投票项目!",
                    callback: function() {
                        zAlert.Close()
                    }
                })
            }

        });
    }
});

    function getVcount(){
        $.getJSON("/index.php?g=User&m=Scene&a=vote_count", {'vote_id':vote_id}, function(data){
            $('.fz').html(data.count);
            $('.fm').html(data.fcount);
        });

    }
     
    $('.inf-btn').click(function(){
        if (!$(this).hasClass("begining") && !$(this).hasClass("endgame")) {
            $.getJSON("/index.php?g=User&m=Scene&a=vote_start", {'vote_id':vote_id}, function(data){
                if(data.err==0){
                    $(".inf-btn").addClass("begining").text("停止投票");
                    showVote();
                }
            });
        }else if($(this).hasClass("endgame")){
            window.location.reload();
        }else{
            $.getJSON("/index.php?g=User&m=Scene&a=vote_stop", {'vote_id':vote_id}, function(data){

                    if(data.err == 0){
                        createItem(data,".panelBox ul");
                        var c = $(".panelBox li").width();
                        $(".panelBox li .vote-item-box").animate({
                            left: c / 2 + "px",
                            width: "0px"
                        },
                        200,
                        function() {
                            $(".panelBox li .cardView").animate({
                                left: "0px",
                                width: c + "px"
                            },
                            200,
                            function() {
                                cardOverUp(c)
                            })
                        })

                    zAlert.Alert({
                        content: '投票已经结束',
                        callback: function() {
                            zAlert.Close()
                        }
                    })

                    $(".inf-btn").removeClass("begining").addClass("endgame").text("其他投票");

                    }
            });
        }

    });

        function cardOverUp(b) {
            var c = this;
            $(".cardView").on("click",
            function() {
                $(this).animate({
                    left: b / 2 + "px",
                    width: "0px"
                },
                200,
                function() {
                    $(this).prev().animate({
                        left: "0px",
                        width: b + "px"
                    },
                    200)
                })
            })
        }
    //$.getJSON("/index.php?g=User&m=Scene&a=vote_stop", {'vote_id':vote_id}, function(data){

    function showVote(){
            $.getJSON("/index.php?g=User&m=Scene&a=ajaxVcount", {'vote_id':vote_id}, function(data){
                if(data.flag == 1){
                        var e = data.res.length;
                            for (var f = 0; f < e; f++) {
                                var h = data.res[f].id,
                                d = $(".panelBox li[lid=" + h + "] .vote-item-num span");
                                if (d.html() != data.res[f].vcount) {
                                    //c.addNum(h, data.res[f].vcount - (d.html()));
                                    d.html(data.res[f].vcount)
                                }
                            }
                    setTimeout(showVote,1500);  
                }
            });
    }

        function createItem(b,id) {
            var c = "";
            if (b) {
                var len = b.res.length;
                for (var i = 0; i < len; i++) {    
                    c += '<li lid="' + b.res[i].id + '"><div class="vote-item-box"><img src="' + b.res[i].startpicurl+ '" alt="" /><div class="vote-item-states"><div class="vote-item-num"><i></i><span>' +b.res[i].vcount + '</span></div><span class="vote-item-name">' +b.res[i].item + '</span></div></div><div class="cardView"><div class="rank">第' +b.res[i].ranks +"名</div>";
                    if ( b.res[i].vcount != '') {
                        c += '<div class="ticketNum">' +b.res[i].vcount + "票</div>"
                    }
                c += "</div></li>"
                }
            }
            $(id).html(c)
        }


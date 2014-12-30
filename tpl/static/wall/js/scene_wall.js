$(function(){
    font_size();
});
function font_size(){
    $('#Msg .MsgItem .msgword').each(function(){
        var htm = $(this).html();
        var len = $.trim(htm).length;
        var size = $('.msgword').css('font-size');
        if(len){
            var size = 76;
            if(len >15 && len <= 60){
                size = 40;
            }else if(len >60 && len <= 147){
                size = 25;
            } else if(len >147 && len <= 272){
                size = 18;  
            } else if(len > 272){
                size = 14;
            }
            
            $(this).css('font-size',size+'px');
        }
    });
}

function screen_init(){
    setInterval(ajax_screen_for_pull, load_time);   
}

// AJAX拉取数据
function ajax_screen_pull(){
    var ajax_time = $('#ajax_time').val();
    $.getJSON(url, {'ajax_time':ajax_time,'sceneid':sceneid}, function(data){
       if(data.err == 0){
            var html = '';
            for (var i=0; i <= data.res.length-1; i++) {        
                html += "<div class='MsgItem'  msg_id='"+data.res[i].id+"'><div class='head' style='background-image: url("+data.res[i].portrait+");'></div><div class='nickname'>"+data.res[i].nickname+"</div><div class='msgword'>"+data.res[i].content+"</div></div>";
                $('#ajax_time').val(data.res[i].ajax_time);  
            }; 
            loadMsg(html);    
            status = 1;
        }else{ //无数据循环存储数据块循环特效 
            if($('#Msg').find(".MsgItem").length>=3){
                var tempMsg = $('#MsgBox').find(".MsgItem:last");
                var html    = getHtml(tempMsg);
                loadMsg(html);
            }
        }
    });  
}

// AJAX拉取数据
function ajax_screen_pic(){
    var ajax_time = $('#ajax_time').val();
    $.getJSON(url, {'ajax_time':ajax_time,'sceneid':sceneid}, function(data){

       if(data.err == 0){  
            var html = '';
            if(data.res.length >0){
                for (var i=0; i <= data.res.length-1; i++) {
                    html = '<li uid="'+data.res[i].id+'" class="" style="width: 1440px; float: left;"><img src="'+data.res[i].picture+'" draggable="false"></li>';
                    $('#ajax_time').val(data.res[i].ajax_time);
                };       
                var div  = $('.slides');
				var len  = div.children('li').length;
                if( len  < 10){
                    div.html(div.html()+html);
					if(len == 0){
						window.location.reload();
					}
                }else{
                    div.find("li").eq(0).remove();
                    div.html(div.html()+html);
                }
                status = 1;
            }
        }
    });  
}

//加载浮动特效
function loadMsg(html){
    var div  = $('#Msg');
    if(div.find(".MsgItem").length >=3){
        div.find(".MsgItem:first").fadeOut(500,function(){                 
            var liHeight = $(this).height();
            $(this).next().css({marginTop:liHeight});  
            $(this).next().animate({marginTop:4.5+"px"},500,function(){               
            var htm = div.html();
                div.html(htm+html);
            var first   = div.find(".MsgItem:first");
            var tempHtml= getHtml(first);
                appendMsg(tempHtml);
                first.remove();
                font_size();
                div.find(".MsgItem:last").fadeIn(500);

            });
        });
    }else{
        var htm = div.html();
        div.html(htm+html);
        font_size();
    }
    
    return true;
}
//添加临时存储块
function appendMsg(html){
    var box = $('#MsgBox');
    if(box.find(".MsgItem").length >= 10){
        box.find(".MsgItem:first").remove();
        box.append(html);
    }else{
        box.append(html);
    }
}
//获取当前节点html
function getHtml(obj){
    var html = $("<div></div>").append(obj.clone()).html();
    return html;
}

function ajax_screen_for_pull(){
    if(status){
       if(is_pic){
            ajax_screen_pic();
       }else{
            ajax_screen_pull();
       }
        
        Timeout = 0;
    }else{
        Timeout++;
    }
    // 当60*10＝10分钟没有数据里复位看门狗，直接拉取数据，可能是浏览器出错
    if(Timeout>60){
        status = 0;
        alert('请求超时，请检查网络');
    }
}
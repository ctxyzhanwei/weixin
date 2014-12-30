//author wuqingshan 2013
function getstore(){
    var cur_obj = $("#shoe_size_list .on")[0];
    var store = $(cur_obj).attr("store");
    store=store ? store : '';
    return store;
}
function clear(){
    if($("#show_msg").css('display')=='block'){
       $("#show_msg").hide('slow'); 
    }
}
function show_msg(text,speed){
    var show_msg=$("#show_msg");
    speed=speed ? speed : 'fast';
    show_msg.show(speed);
    show_msg.html(text);
}
$(document).ready(function(){	
    //尺码选择
	$("#shoe_size_list li").unbind("click").bind("click", function () {
        clear();
	    $('#shoe_size_list li').removeClass("on");
        $(this).addClass("on");
	    var pro_id=$(this).attr('name');
	    var buy_num=$("#buy_num");
	    if(parseInt(buy_num.val()) > getstore()){
	        buy_num.val(getstore());   
	    }
	    $("#stock").text(getstore());
		if(pro_id){
		  $("#product_id").val(pro_id);
		}
		return;
	});
	/*购买数量输入调节*/
	$("#buy_num").bind("keyup",function(){
         var cur_num=parseInt($(this).val());
         var stock=parseInt($("#stock").text());  
        if(cur_num > stock){
           $(this).val(stock);
        }
        if(cur_num<1){
            $(this).val(1); 
	    }
	});	
	/*购买数量调节*/
	var counttext=$("#buy_num");
    $(".numadjust").bind("click",function(){
        var stock=parseInt($("#stock").text());
        if(!counttext.val()){
            counttext.val(1);
        }
        if($(this).hasClass('add')){
            if(parseInt(counttext.val()) >= stock){
                counttext.val(stock);
            }else{
               counttext.val(parseInt(counttext.val())+1); 
            }
        }else{
            if(parseInt(counttext.val()) <= 1){
               counttext.val(1); 
            }else{
                counttext.val(parseInt(counttext.val())-1);  
            }
        }
        $(this).blur();
    });
    //加入购物车
    $("#btn_add_cart").bind("click",function(){
        var gid=$("#goods_id").val();
        var pro_id=$("#product_id").val();
        var buy_num=counttext.val();
        var reg_buy_num=/^[-\+]?\d+$/;
        pro_str=gid+'@'+pro_id;
        if((pro_id=='' || pro_id=='undefined') && bag_one_size==0){
           return floatNotify.simple('请选择尺码');
        }else{
            if(!reg_buy_num.test(buy_num)){
               return floatNotify.simple('请输入合法的购买数量');
            }
            $.ajax({
                type:"POST",
                data: {pro_str:pro_str,buy_num:buy_num},
                url:base_url_touch+"cart/add/",
                dataType: "json",
                success: function(data) {
                    tmp_str='<a href="'+base_url_touch+'cart/lists/">点击去购物车结算</a>';
                    if(data.status=='success'){
                       show_msg('已成功加入购物车，'+tmp_str) 
                    }else{
                        return floatNotify.simple(data.msg);
                    }
                    return floatNotify.simple('加入购物车成功');
                },
                error: function() {
                   return floatNotify.simple('加入失败');
                }
            }); 
        } 
     });
});
//加入收藏
function addFavorite(gid){
    var stop=true;
    if(stop==true){
        $.ajax({
            type:"POST",
            data: {gid:gid},
            url:base_url_touch+"member/addFavorite/",
            dataType: "json",
            success: function(data) {
              stop=false;
              if(data.status=='success'){
                 $(".act a.fav").toggleClass("in");
                 $(".act a.fav").removeAttr('onclick'); 
                 return floatNotify.simple('收藏成功！'); 
              }
            },
            error: function() {
                return floatNotify.simple('加入失败');
            }
        });
    }
}
function QuickBuy(){
    var pro_id=$("#product_id").val();
    if(pro_id=='' || pro_id=='undefined'){
        return floatNotify.simple('请选择尺码');
    }
    var buy_num=$("#buy_num").val();
    var reg_buy_num=/^[-\+]?\d+$/;
    if(!reg_buy_num.test(buy_num)){
        return floatNotify.simple('请输入合法的购买数量');
    }
    $("#quickbuy").val('true');
    $("#product_buy_form").attr("action",base_url_touch+"cart/checkout/");
    $("#product_buy_form").submit();
}
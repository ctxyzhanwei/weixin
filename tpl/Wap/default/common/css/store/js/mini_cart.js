/*购物车 购买数量调节*/
function upateNum(numinput,num,type){
    var form_num=parseInt(numinput.val());
    if(type!=''){
        form_num = (isNaN(form_num)?1:form_num)+parseInt(num);
    }
    if(form_num<=0){
        return false;
    }
    numinput.val(parseInt(form_num));
    var pro_str=numinput.parents('li').attr('updates');
    $('body').showLoading();
    $.ajax({
        type:"POST",
        data:{pro_str:pro_str,from_num:form_num},
        url:base_url_touch+"cart/updateCart/",
        success: function(data) {
           $('body').hideLoading();
           if(data=='reload'){
                location.reload();
           }else{
                pro_=pro_str.replace("@",'_');
                numinput.focus();
                cur_num=numinput.parents('li').attr('number');
                numinput.val(cur_num);
                return floatNotify.simple(data);
           }
        }
    });     
}
//删除
function removeItem(obj){
confirm =floatNotify.confirm("确实要删除该商品吗？", "",
    function(t, n) {
        if(n==true){
            _removeItem(obj);
        }
    	this.hide();
    }),
    confirm.show();
}
function _removeItem(obj){    
    var pro_str=obj.parents('li').attr('updates');
    $.ajax({
        type:"POST",
        data:{pro_str:pro_str},
        url:base_url_touch+"cart/removeCart/",
        success: function(data) {
            if(data=='reload'){
                obj.parents('li').css({'position':'relative','right':'-100%','-webkit-transition':'right 400ms linear'});
                obj.parents('li').animate({'height':'0','padding':'0'});
                setTimeout(function(){obj.parents('li').remove()},450);
                location.reload();
            }else{
                pro_=pro_str.replace("@",'_');
                return floatNotify.simple(data);
            }
        }
    });
}
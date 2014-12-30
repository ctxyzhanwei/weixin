$blank = /^(|\s+)$/; //空格
$regEmail = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w{2,3})*$/; //邮箱格式


$(function(){
	$(".btn").on("touchstart",function(){
		$(this).addClass("active");
	});
	$(".page a").on("touchstart",function(){
		$(".page a").removeClass("current");
		$(this).addClass("current");
	});
	/*$(".box-list-room").on("click", '.box-txt', function(){
		var self=$(this),
			parent=self.parents(".list-li"),
			ft=parent.find(".ft");
		$(".list-li .ft").hide();
		ft.toggle();
	});
	$(".box-list-room").on("click", '.bd', function(){
		var self=$(this),
			parent=self.parents(".list-li"),
			ft=parent.find(".ft");
		$(".list-li .ft").hide();
		ft.toggle();
	});
	$(".select-date").on("click",function(){
		var id=$(this).attr("id");
		$(".pop").show().find(".btn").attr("data-id", id);
	});
	$(".pop .btn").on("click",function(){
		var m=$("#date-m"),
			d=$("#date-d"),
			date=new Date(),
			year=date.getFullYear(),
			month=date.getMonth()+1,
			date=date.getDate(),
			leap=0,
			maxDate=31,
			id=$(this).attr("data-id");
		if(year%100==0){
			leap=1;
		}
		if(m.val()==2){
			if(leap==0){
				maxDate=28;
			}else{
				maxDate=29;
			}
		}else if(m.val()==4||m.val()==6||m.val()==9||m.val()==11){
			maxDate=30;
		}
		if(m.val()>12||m.val()<=0){
			m.val(month).focus();
		}else if(d.val()>maxDate||d.val()<=0){
			d.val(date).focus();
		}else{
			$("#"+id).parent(".table").find("span.td").html(m.val()+"月"+d.val()+"日");
			$(".pop").hide();
		}
		
	});*/
	$("#select-s").on("change",function(){
		$("#time-s").html($(this)[0].options[$(this)[0].selectedIndex].text);
	});
	$("#select-e").on("change",function(){
		$("#time-e").html($(this)[0].options[$(this)[0].selectedIndex].text);
	});
});

function getPopSelectA(selectedY){
	var dates=$(".pop-select span a");
	$.each(dates,function(i){
		var current=$(dates[i]),
			top=parseInt(current.offset().top),
			value=parseInt(selectedY-top);
		current.attr("data-y",top);
		if(value<=40||value>=-40){
			dates.removeClass("current");
			current.addClass("current");
		}
	});
}
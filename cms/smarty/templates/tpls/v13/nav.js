$(document).ready(function(){
	$(function(){
		$(".menu_but").click(function(){
			$(".menu").toggle(500);	//逐渐的显隐
		});
		
		$(".search").click(function(){
			$(".searchmain").toggle(500);	//逐渐的显隐
		});
		
		$(".view_menu span").click(function(){
			$(".view_menumain").toggle(500);	//逐渐的显隐
		});
		$(".navbg").hide();
		$(".classbtn").click(function(){
			$(".classbtn").css('display','none');
			$(".classbtn2").css('display','block');
			$(".subnav").show(500);	//逐渐的显隐
		});
		$(".classbtn2").click(function(){
			$(".classbtn").css('display','block');
			$(".classbtn2").css('display','none');
			$(".subnav").hide(500);	//逐渐的显隐
		});
	});
});

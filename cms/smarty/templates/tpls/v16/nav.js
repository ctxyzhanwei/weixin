$(document).ready(function() {
	$('.navbtn').click(function() {
		$('.navbg').toggle(500);
		$('.searchbox').hide(0);
	});
	$('.searchbtn').click(function() {
		$('.searchbox').toggle(500);
		$('.navbg').hide(0);
	});
	$('.navbg li').click(function() {
		$(this).children('ul').slideToggle(500)
	});
	$('.classbtn').click(function(){
		$(".classbtn").css('display','none');
		$(".classbtn2").css('display','block');
		$('.subnav').slideDown();		
	});
	$('.classbtn2').click(function(){
		$(".classbtn").css('display','block');
		$(".classbtn2").css('display','none');
		$('.subnav').slideUp();		
	});
	var ys = ["#94ce68","#fdb843","#cc5ba9","#fa6569","#61b5cc","#3c3c3c"];
   	$(".imgbox").each(function(index){
		$(".imgbox:eq(0)").css("background",ys[0]);
		$(".imgbox:eq(1)").css("background",ys[1]);
		$(".imgbox:eq(2)").css("background",ys[2]);
		$(".imgbox:eq(3)").css("background",ys[3]);
		$(".imgbox:eq(4)").css("background",ys[4]);
		$(".imgbox:eq(5)").css("background",ys[5]);
	});
});
	
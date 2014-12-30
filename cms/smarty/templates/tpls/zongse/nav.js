$(document).ready(function() {
	$('.navbtn').click(function() {
		$('.navbg').slideToggle(500);
		$('.searchbox').slideUp(0);
		$('.subnavbg').slideUp(0);
	});
	$('.searchbtn').click(function() {
		$('.searchbox').slideToggle(500);
		$('.navbg').hide(0);
	});
	$('.subnav').click(function(){
		$(".subnavbg").slideToggle(500);
		$(".navbg").slideUp(0);
	});
	var ys = ["#ed6e27","#2d6b9a","#258551"];
   	$(".imgbox").each(function(index){
		$(".imgbox:eq(0)").css("background",ys[0]);
		$(".imgbox:eq(1)").css("background",ys[1]);
		$(".imgbox:eq(2)").css("background",ys[2]);
	});
});
	
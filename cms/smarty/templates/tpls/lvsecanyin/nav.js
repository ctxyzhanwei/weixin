$(document).ready(function() {
	$('.iconnav').click(function() {
		$('.nav').slideToggle();
	});
	$('.backtop').click(function() {
		$('.nav').slideUp();
	});
	$('.iconsearch').click(function() {
		$('.search').slideToggle(500);
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
});

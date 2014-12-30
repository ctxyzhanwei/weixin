$(document).ready(function() {
	$('.navbtn').click(function() {
		$('.navbg').toggle(500);
	});
	$('.searchbtn').click(function() {
		$('.search').toggle(500);
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
	
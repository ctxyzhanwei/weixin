$(document).ready(function() {
	$('.navbtn').click(function() {
		$('.navbg').slideToggle();
		$('.search').slideUp();
	});
	$('.navbg li').click(function() {
		$(this).children('ul').slideToggle(500)
	});
	$('.searchbtn').click(function() {
		$('.search').slideToggle(500);
		$('.navbg').slideUp(500);
	});
	$('.ad3').click(function() {
		$('.search2').slideToggle(500);
		$('.navbg').slideUp(500);
	});
});

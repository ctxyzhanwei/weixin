$(document).ready(function() {
	$('.navbtn').click(function() {
		$('.navbg').slideToggle()
	});
	$('.navbg li').click(function() {
		$(this).children('ul').slideToggle(500)
	});
});

$(document).ready(function() {
	$('.navbtn').click(function() {
		$('.navbg').slideToggle(500);
		$('.subnavbg').slideUp();
		$('.search').slideUp();
	});
	$('.subnav').click(function(){
		$('.subnavbg').slideToggle();	
		$('.navbg').slideUp();	
	});
	$('.searchbtn').click(function(){
		$('.search').slideToggle();	
		$('.navbg').slideUp();	
	});
});
	
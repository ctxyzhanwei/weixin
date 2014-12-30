$(document).ready(function() {
	$('.navbtn').click(function() {
		$('.navbg').slideToggle(500);
		$('.subnav').slideUp();
	});
	$('.classbtn').click(function(){
		$('.subnav').slideToggle();	
		$('.navbg').slideUp();	
	});
});
	
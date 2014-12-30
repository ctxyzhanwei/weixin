$(document).ready(function() {
	$('.navbtn').click(function() {
		$('.nav').slideToggle();
		$('.search').slideUp();
	});
	$('.searchbtn').click(function() {
		$('.search').slideToggle(500);
		$('.nav').slideUp();
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
	$('.page').click(function(){
		$('.bg').css('height',$(document.body).height()+"px");
		$('.bg').show();
		$('.topages').show();	
		$('.close').show();	
		$('body').addClass("nomove");
		});
	$('.bg').click(function(){
		$('.bg').hide();
		$('.topages').hide();	
		$('.close').hide();	
		$('body').removeClass("nomove");
		});
});

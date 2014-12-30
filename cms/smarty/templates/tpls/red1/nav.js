$(document).ready(function() {
	$('.navbtn').click(function() {
		$('.nav').slideToggle()
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
//services
$(".serBox").hover(
  function () {
	 $(this).children().stop(false,true);
	 $(this).children(".serBoxOn").fadeIn("slow");
     $(this).children(".pic1").animate({right: -100},400);
     $(this).children(".pic2").animate({left: 75},400);
     $(this).children(".txt1").animate({left: -300},400);
     $(this).children(".txt2").animate({right: 10},400);	 
	 }, 
  function () {
	 $(this).children().stop(false,true);
	 $(this).children(".serBoxOn").fadeOut("slow");
	 $(this).children(".pic1").animate({right:75},400);
     $(this).children(".pic2").animate({left: -100},400);
     $(this).children(".txt1").animate({left: 10},400);
     $(this).children(".txt2").animate({right: -300},400);	
  }
);
$(document).ready(function(){
	var move=$('#tbNav');
		
	var window_w = $(window).width();
	
	if(window_w>1000){move.show();}

	$(window).scroll(function(){
		var scrollTop = $(window).scrollTop();
		// move.stop().animate({top:scrollTop+10});;		
		// if(scrollTop>1000)
		// {
			// move.stop().animate({top:scrollTop-250});
		// }
		// else{
			move.stop().animate({top:scrollTop + 0},500);;
		// }		
	});	
$('#tbNavLi1').click( function () {$('html,body').animate({scrollTop: $('#num1').offset().top - 140 + 'px'},'slow');});
$('#tbNavLi2').click( function () {$('html,body').animate({scrollTop: $('#num2').offset().top - 140 + 'px'},'slow');});
$('#tbNavLi3').click( function () {$('html,body').animate({scrollTop: $('#num3').offset().top - 140 + 'px'},'slow');});
$('#tbNavLi4').click( function () {$('html,body').animate({scrollTop: $('#num4').offset().top - 140 + 'px'},'slow');});
$('#tbNavLi5').click( function () {$('html,body').animate({scrollTop: $('#num5').offset().top - 140 + 'px'},'slow');});
$('#tbNavLi6').click( function () {$('html,body').animate({scrollTop: $('#num6').offset().top - 140 + 'px'},'slow');});
$('#tbNavLi7').click( function () {$('html,body').animate({scrollTop: $('#num7').offset().top - 140 + 'px'},'slow');});
$('#tbNavLi8').click( function () {$('html,body').animate({scrollTop: $('#num8').offset().top - 140 + 'px'},'slow');});
		
});


//case img scroll
$(document).ready(function(){
$(".case_img:has(span)").hover(function() {
$(this).children("span").animate({"top": "0px"}, 400, "swing");
},function() {
$(this).children("span").stop(true,false).animate({"top": "-180px"}, 400, "swing");
});
}); 

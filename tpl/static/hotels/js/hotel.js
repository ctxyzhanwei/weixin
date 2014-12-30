$(function(){
	$(".op-add").on("touchstart",function(){
		$(this).addClass("active");
		
	});
	$(".op-minus").on("touchend",function(){
		$(this).removeClass("active");
		
	});
	$("#input-minus").on("click",function(){
		var self=$(this),
			minus=parseInt(self.attr("data-min")),
			input=$("#input-number"),
			value=parseInt(input.val());
		if(value&&value!==""&&value>minus){
			input.val(value-1);
		}else{
			input.val(minus);
			self.addClass("disable");
		}
		$("#input-add").removeClass("disable");
		sum_price(input.val());
	});
	$("#input-add").on("click",function(){
		var self=$(this),
			max=parseInt(self.attr("data-max")),
			input=$("#input-number"),
			value=parseInt(input.val());
		if(value&&value!==""&&value<max){
			input.val(value+1);
		}else{
			input.val(max);
			self.addClass("disable");
		}
		$("#input-minus").removeClass("disable");
		sum_price(input.val());
	});
	$("#input-number").on("keyup",function(){
		var self=$(this),
			max=parseInt(self.attr("data-max")),
			minus=parseInt(self.attr("data-min")),
			value=parseInt(self.val());
			self.val(self.val().replace(/\D/g, ''));
		if(value>max){
			self.val(max);
		}else if(value<minus){
			self.val(minus);
		}
	});
	$("#btn-order").on("click",function(){
		$("#pop-order").show();
	});
	$(".pop .btn").on("click",function(){
		$(".pop").hide();
	});
	$(".list-li").click(function(e){
		var parent = e.target;
		if ($(parent).attr('class') == 'btn btn-small') {
			location.href = $(parent).attr('href');
			return false;
		}
		var html = '<div class="cui-hd hotel-hd-title">' + $(this).children('.name').val();
		html += '<div class="lab-close-area"><span class="cui-top-close">×</span></div>';
		html += '</div>';
		html += '<div class="cui-bd" style="overflow: hidden; position: relative; width: 100%; height: auto; background-color: rgb(250, 250, 250);">';
		html += '<div class="hotel-detail-layer" style="transition: 0ms cubic-bezier(0.1, 0.57, 0.1, 1); -webkit-transition: 0ms cubic-bezier(0.1, 0.57, 0.1, 1); transform: translate(0px, 0px) translateZ(0px); background-color: white;">';
		html += '<div class="js_pop_slide_container" style="width: 100%; height: 190px; margin: auto; overflow: hidden; position: relative;">';
		html += '<div class="cui-slide">';
		html += '<div class="cui-slide-imgsouter swiper-container">';
		html += '<div class="cui-slide-imgsinter swiper-wrapper" >';
		if ($(this).children('.image1').val() != null && $(this).children('.image1').val() != '') {
			html += '<div class="cui-slide-img-item swiper-slide" style="width: 280px;">';
			html += '<img src="' + $(this).children('.image1').val() + '">';
			html += '</div>';
		}
		if ($(this).children('.image2').val() != null && $(this).children('.image2').val() != '') {
			html += '<div class="cui-slide-img-item swiper-slide" style="width: 280px;">';
			html += '<img src="' + $(this).children('.image2').val() + '">';
			html += '</div>';
			
		}
		if ($(this).children('.image3').val() != null && $(this).children('.image3').val() != '') {
			html += '<div class="cui-slide-img-item swiper-slide" style="width: 280px;">';
			html += '<img src="' + $(this).children('.image3').val() + '">';
			html += '</div>';
			
		}
		if ($(this).children('.image4').val() != null && $(this).children('.image4').val() != '') {
			html += '<div class="cui-slide-img-item swiper-slide" style="width: 280px;">';
			html += '<img src="' + $(this).children('.image4').val() + '">';
			html += '</div>';
			
		}
		html += '</div>';
		html += '</div>';
		html += '<div class="cui-slide-nav pagination">';
		//html += '<div class="cui-slide-nav-padding"><span class="cui-slide-nav-item"></span></div>';
		html += '</div>';
		html += '</div>';
		html += '</div>';
		html += '<ul class="layer-hd p10 js_part1_info">';
		if (parseInt($(this).children('.area').val()) > 0) {
			html += '<li><i class="hotel-icon-area"></i><span>面积</span>' + $(this).children('.area').val() + '㎡</li>';
		}
		if (parseInt($(this).children('.num').val()) > 0) {
			html += '<li><i class="hotel-icon-people"></i><span>可住</span>' + $(this).children('.num').val() + '人</li>';
		}
		if ($(this).children('.bed').val() != null && $(this).children('.bed').val() != '') {
			html += '<li><i class="hotel-icon-bed2"></i>' + $(this).children('.bed').val() + '</li>';
		}
		if ($(this).children('.floor').val() != null && $(this).children('.floor').val() != '') {
			html += '<li><i class="hotel-icon-floor"></i><span>楼层</span>' + $(this).children('.floor').val() + '</li>';
		}
		if ($(this).children('.bedwidth').val() != null && $(this).children('.bedwidth').val() != '') {
			html += '<li><i class="hotel-icon-beds-width"></i><span>床宽</span><p>' + $(this).children('.bedwidth').val() + '</p></li>';
		}
		if ($(this).children('.network').val() != null && $(this).children('.network').val() != '') {
			html += '<li><i class="hotel-icon-browser"></i><span>宽带</span><p>' + $(this).children('.network').val() + '</p></li>';
		}
		if ($(this).children('.smoke').val() != null && $(this).children('.smoke').val() != '') {
			html += '<li><i class="hotel-icon-smoke"></i><span>无烟</span><p>' + $(this).children('.smoke').val() + '</p></li>';
		}
		
		html += '</ul>';
		html += '</div>';
		html += '</div>';
		html += '<div class="cui-ft-fixed">';
		html += '<div class="hotel-detail-layer">';
		html += '<ul class="layer-bd">';
		//html += '<li><button class="hotel-g-btn js_btn_book ">预订</button><em class="g-price"><small><small>¥</small></small>368</em>V</li>';
		html += '</ul>';
		html += '</div>';
		html += '</div>';
		$(".cui-pop-box").html(html);
		$(".cui-view").show();
		
		var focusPic = new Swiper('.swiper-container', {pagination: '.pagination',autoplay:3000})
	});
	$(".cui-top-close").live("click", function(){
		$(".cui-view").hide();
	});
});


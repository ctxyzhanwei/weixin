$(document).ready(function(){
	function item_set(){
		var item_height = $('.plate li').height();
		$('.plate-item').height(item_height-2);
	}

	var run_t = null,
		last_time = '';

	function run(){
		$('.plate li').off('click');
		var start = 1,
			time = 0;
		run_t = setInterval(function(){
			$('.plate li[item-order='+start+']').addClass('active').siblings().removeClass('active');
			start++;
			time += 100;
			if( start == 13 ){
				start = 1;
			}
			if( time == 5000 ){
				end(start);
			}
		},100)
	}

	function end(start){
		var result = parseInt(Math.random()*12)+1;
		if( (last_time == 'min' && result < 7) || (last_time == 'max' && result >= 7) ){
			result = parseInt(Math.random()*12)+1;
			if( result < 7 ){
				last_time = 'min';
			}else{
				last_time = 'max';
			}
		}
		clearInterval(run_t);
		var t = setInterval(function(){
			$('.plate li[item-order='+start+']').addClass('active').siblings().removeClass('active');
			if( start == result ){
				clearInterval(t);
				var txt = $('.plate li[item-order='+result+']').text();
				$('.plate-btn').removeClass('runing');
				use_num();
				pop_result(txt);
			}else{
				start++;
				if( start == 13 ){
					start = 1;
				}
			}
		},300);
	}

	function pop_info(txt){
		$('.pop-info .pop-con').text(txt);
		$('.pop-info,.pop-mask').show();
		$('.pop-info .pop-closed').on('click',function(){
			$('.pop-info,.pop-mask').hide();
		})
	}

	function pop_result(txt){
		$('.pop-result .pop-con p').text(txt);
		$('.pop-result,.pop-mask').show();
		$('.pop-result .pop-closed').on('click',function(){
			$('.pop-result,.pop-mask').hide();
			$('.plate li').removeClass('active');
			active();
		})
	}

	function active(){
		$('.plate li').on('click',function(){
			if( !$(this).hasClass('blank-item') ){
				if( $(this).hasClass('plate-btn') ){
					if( !$(this).hasClass('runing') ){
						$(this).addClass('runing');
						run();
					}
				}else{
					var txt = $(this).text();
					//pop_info(txt);
				}
			}
		})
	}

	function use_num(){
		$.ajax({
			url : _use_num,
			type : 'get',
			dataType : 'json',
			data : {}
		})
	}

	$.when(
		$.ajax({
			url : _global,
			type : 'get',
			dataType : 'json',
			data : {}
		})
	).done(function(res){
		console.log(res);
		if( res.status == true){
			var html = template('item-list', res.data);
			$('ul').html(html);
			item_set();
			$(window).resize(function(){
				item_set();
			})
			active();
		}
	})
})
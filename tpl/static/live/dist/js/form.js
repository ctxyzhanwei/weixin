
/**
 *  全局函数处理
 *  -----------------------------
 *  准则：Zpote、字面量对象
 *********************************************************************************************/
/**
 * 表单验证
 */
define(function __form(require, exports, module){
	// 加载其他模块
	var $ = require('./zepto');
	var global = require('./global');
	var page = require('./page');

	/**
	 * 表单提交验证
	 * -->提交按钮
	 * -->提交的form表单
	 * -->提示的纸条
	 */
	var __form_input = {
	 	// 我要报名表单验证函数
	 	signUpCheck_input	: function (form,note){
			var valid = true;
			var inputs = form.find('input');

			inputs.each(function(){
				if(this.name != '' && this.name != 'undefined'){
					//函数验证
					var name = this.name;
					var backData	= __form_input.regFunction(name);
						
					var empty_tip = backData.empty_tip,
						reg       = backData.reg,
						reg_tip   = backData.reg_tip;
							
					//根据结果处理
					if ($.trim($(this).val()) == '') {
						__form_input.showCheckMessage(note,empty_tip, false);
						// $(this).focus();
						$(this).addClass('z-error');
						valid = false;
						return false;
					}
					if (reg != undefined && reg != '') {
						if(!$(this).val().match(reg)){
							// $(this).focus();
							$(this).addClass('z-error');
							__form_input.showCheckMessage(note,reg_tip, false);
							valid = false;
							return false;		
						}
					}
					$(this).removeClass('z-error');
					$('.u-note-error').html('');	
					note.html('');
				}
			});
			if (valid == false) {
				return false;
			}else{
				return true;
			}
		},
		
		// 正则函数验证
		regFunction	: function(inputName){
			var empty_tip = '',
				reg_tip = '',
				reg = '';

			//判断
			switch (inputName) {
				case 'name':
					reg = /^[\u4e00-\u9fa5|a-z|A-Z|\s]{1,20}$/;
					empty_tip = '不能落下姓名哦！';
					reg_tip = '这名字太怪了！';
					break;
				case 'sex':
					empty_tip = '想想，该怎么称呼您呢？';
					reg_tip = '';
					break;
				case 'tel':
					reg = /^1[0-9][0-9]\d{8}$/;
					empty_tip = '有个联系方式，就更好了！';
					reg_tip = '这号码,可打不通... ';
					break;
				case 'email':
					reg = /(^[a-z\d]+(\.[a-z\d]+)*@([\da-z](-[\da-z])?)+(\.{1,2}[a-z]+)+$)/i;
					empty_tip = '都21世纪了，应该有个电子邮箱吧！';
					reg_tip = '邮箱格式有问题哦！';
					break;
				case 'company':
					reg = /^[\u4e00-\u9fa5|a-z|A-Z|\s|\d]{1,20}$/;
					empty_tip = '填个公司吧！';
					reg_tip = '这个公司太奇怪了！';
					break;
				case 'job':
					reg = /^[\u4e00-\u9fa5|a-z|A-Z|\s]{1,20}$/;
					empty_tip = '请您填个职位';
					reg_tip = '这个职位太奇怪了！';
					break;
				case 'date':
					empty_tip = '给个日期吧！';
					reg_tip = '';
					break;
				case 'time':
					empty_tip = '填下具体时间更好哦！' ;
					reg_tip = '' ;
					break;
				case 'age':
					reg = /^([3-9])|([1-9][0-9])|([1][0-3][0-9])$/;
					empty_tip = '有个年龄就更好了！';
					reg_tip = '这年龄可不对哦！' ;
					break;
			}
			return {
				empty_tip	:empty_tip,
				reg_tip		:reg_tip,
				reg 		:reg
			}
		},

		// ajax异步提交表单数据
		signUpCheck_submit	: function (form,note){
		 	global.loadingPageShow($('.u-pageLoading'));

			var url = '/auto/submit/'+$('#activity_id').val();

			// ajax提交数据
			$.ajax({
				url: url,
				cache: false,
				dataType: 'json',
				async: true,
				type:'POST',
				data: form.serialize(),
				success: function(msg){
					global.loadingPageHide($('.u-pageLoading'));
					
					if(msg.code==200){
						// 提示成功
						__form_input.showCheckMessage($('.u-note'),$('.u-note-sucess').data('type'),true)

						// 关闭窗口
						setTimeout(function(){
							$('.book-form').removeClass('z-show');
							$('.book-bg').removeClass('z-show');
							setTimeout(function(){
								$(document.body).css('height','100%');
								page.page_start();
								global._scrollStop();
								
								$('.book-bg').addClass('f-hide');
								$('.book-form').addClass('f-hide');
							},500)
						},3000)

						// 按钮变色
						$('.book-bd .bd-form .btn').addClass("z-stop");
						$('.book-bd .bd-form .btn').attr("data-submit",'true');
					}else if(msg.code==400){
						__form_input.showCheckMessage($('.u-note'),$('.u-note-error').data('type'),false);
					}
				},
				error : function (XMLHttpRequest, textStatus, errorThrown) {
					__form_input.showCheckMessage(note,errorThrown,false);
					setTimeout(function(){
						global.loadingPageHide($('.u-pageLoading'));
					},500)
				}
			})
		},

		// 显示验证信息
		showCheckMessage	: function (note,msg,vail) {
			if (!vail) {
				$('.u-note-error').html(msg);
				$(".u-note-error").addClass("on");
				$(".u-note-sucess").removeClass("on");

				setTimeout(function(){
					$(".u-note").removeClass("on");
				},2000);
			} else {
				$('.u-note-sucess').html(msg);
				$(".u-note-sucess").addClass("on");
				$(".u-note-error").removeClass("on");

				setTimeout(function(){
					$(".u-note").removeClass("on");
				},2000);
			}
		}
	}

	return __form_input;
})


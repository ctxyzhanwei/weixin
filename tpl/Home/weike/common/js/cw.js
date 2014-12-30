/**
 * ===========================================
 * CW 前台JS框架
 * 描述： 主要包含一些公用类库函数，工具函数的抽取。
 * 使用统一的命名空间管理  CW
 * @author vicode(vicode@collwe.com)
 * @modifiation  
 * ===========================================
 */

/**
 * @description CW 全局对象，负责前端的交互组织
 * @namespace 全局的命名空间
 */
CW = window.CW || {};

/**
 * @namespace GRI JS框架工具类型，里面会累积一下大家常用的工具类函数和对象方法
 */
CW.Util = {
	/**
	 * @description 显示提醒信息
	 * @param {string} type 提醒类型（error|warning|success）
	 * @param {string} text 提醒文本
	 */
	Alert : function(type, text){
		$('.alert_box').remove();
		var alertDiv = $('<div class="alert_box '+type+'">'+text+'</div>');
		alertDiv.prependTo($('body'));
		alertDiv.delay(3000);
		alertDiv.animate({opacity: '0'},1000);
		alertDiv.queue(function(){
			$(this).remove();
		})
	}
}
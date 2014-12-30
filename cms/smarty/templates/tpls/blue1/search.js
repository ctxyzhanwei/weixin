// JavaScript Document
//---------- 搜索表单验证
$( function() {
	var searchTxt = $("#search input:text[name='SeaStr']").val();
	$("#search").submit( function() {
		var s_searchTxt = $(this).find("input:text[name='SeaStr']").val();
		if ( s_searchTxt == "" || s_searchTxt == searchTxt || s_searchTxt == "输入关键词" ) {
			$(this).find("input:text[name='SeaStr']").val("输入关键词");
			return false;
		}
	} ).find("input:text[name='SeaStr']").focus( function() {
		var s_searchTxt = $(this).val();
		if ( s_searchTxt == searchTxt || s_searchTxt == "输入关键词" ) {
			$(this).val("");
		}
	} ).blur( function() {
		var s_searchTxt = $(this).val();
		if ( s_searchTxt == "" || s_searchTxt == "输入关键词" ) {
			$(this).val(searchTxt);
		}
	} );
} );
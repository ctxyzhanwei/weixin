<?php 
class CarsetModel extends Model {

	//自动验证
	protected $_validate = array(
			
			array('keyword','require','关键词不能为空',3),
			array('title','require','图文标题不能为空',3),
			array('head_url','require','标题图片不能为空',3),
			//array('url','require','图文外链不能为空',3),
			array('title1','require','经销车型不能为空',3),
			array('title2','require','销售顾问不能为空',3),
			array('title3','require','在线预约不能为空',3),
			array('title4','require','车主关怀不能为空',3),
			array('title5','require','实用工具不能为空',3),
			array('title6','require','型欣赏不能为空',3),
			array('head_url_1','require','经销车型图片不能为空',3),
			array('head_url_2','require','销售顾问图片不能为空',3),
			array('head_url_3','require','在线预约图片不能为空',3),
			array('head_url_4','require','车主关怀图片不能为空',3),
			array('head_url_5','require','实用工具图片不能为空',3),
			array('head_url_6','require','型欣赏图片不能为空',3),
			// array('url1','require','经销车型图文外链不能为空',3),
			// array('url2','require','销售顾问图文外链不能为空',3),
			// array('url3','require','在线预约图文外链不能为空',3),
			// array('url4','require','车主关怀图文外链不能为空',3),
			// array('url5','require','实用工具图文外链不能为空',3),
			// array('url6','require','型欣赏外链不能为空',3),
	 );


}
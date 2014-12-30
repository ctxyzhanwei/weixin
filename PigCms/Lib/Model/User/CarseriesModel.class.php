<?php 
class CarseriesModel extends Model {

	//自动验证
	protected $_validate = array(
			
			array('name','require','车系名不能为空',1),
			array('shortname','require','车系简称不能为空',1),
			array('picture','require','图片不能为空',1),
	 );


}
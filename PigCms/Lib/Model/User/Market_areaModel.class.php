<?php 
class Market_areaModel extends Model {

	//自动验证
	protected $_validate = array(	
			array('area_name','require','区域名称不能为空',3),
			array('manage','require','经营范围不能为空',3),
			array('area_pic','require','区域图片不能为空',3),
	 );

}
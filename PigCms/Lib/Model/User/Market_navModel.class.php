<?php 
class Market_navModel extends Model {

	//自动验证
	protected $_validate = array(
		array('nav_name','require','分类名称不能为空',3),
		array('nav_pic','require','分类图片不能为空',3),
		array('nav_link','require','分类链接不能为空',3),
	);


}
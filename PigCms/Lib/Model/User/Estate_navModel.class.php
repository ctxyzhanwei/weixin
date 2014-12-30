<?php 
class Estate_navModel extends Model {

	//自动验证
	protected $_validate = array(
		array('name','require','菜单名称不能为空',3),
		array('pic','require','菜单图片不能为空',3),
		array('url','require','菜单链接不能为空',3),
	);


}
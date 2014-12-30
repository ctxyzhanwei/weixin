<?php

class Estate_housetypeModel extends Model{
	protected $_validate  = array(
		array('name','require','户型名称不能为空'),
		array('floor_num','require','楼层不能为空'),
		array('area','require','建筑面积不能为空'),
		array('description','require','户型介绍不能为空'),
		array('type1','require','户型图不能为空'),
		//array('type2','require','户型图不能为空'),

	);
}
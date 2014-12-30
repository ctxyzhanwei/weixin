<?php

class EstateModel extends Model{

	protected $_validate  = array(
		array('title','require','标题不能为空'),
		array('keyword','require','触发关键词不能为空',3),
		array('cover','require','图文封面不能为空'),
		array('banner','require','楼盘头部图片不能为空'),
		array('house_banner','require','户型图不能为空',3),
		array('place','require','楼盘预约地址不能为空',3),
		array('estate_desc','require','楼盘简介不能为空',3),
		array('project_brief','require','项目简介不能为空',3),
		array('traffic_desc','require','交通配套不能为空',3)
	);
}
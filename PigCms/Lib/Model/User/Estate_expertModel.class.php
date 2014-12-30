<?php

class Estate_expertModel extends Model{
	protected $_validate  = array(
		array('title','require','标题不能为空'),
		array('name','require','专家姓名不能为空'),
		array('position','require','专家职位不能为空'),
		array('face','require','专家照片不能为空'),
		array('description','require','专家介绍不能为空'),
		array('comment','require','点评内容不能为空'),	
	);
}
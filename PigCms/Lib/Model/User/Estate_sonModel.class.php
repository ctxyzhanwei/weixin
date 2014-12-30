<?php

class Estate_sonModel extends Model{
	protected $_validate  = array(
		array('title','require','子楼盘名称不能为空',3),
	);
}
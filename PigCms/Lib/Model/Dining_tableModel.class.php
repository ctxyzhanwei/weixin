<?php
class Dining_tableModel extends Model{
	protected $_validate =array(
		array('name','require','餐桌名不能为空',1),
		array('num','number','容纳人数格式不对',1),
	);

}
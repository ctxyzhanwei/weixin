<?php
class Dish_sortModel extends Model{
	protected $_validate =array(
		array('name','require','分类名不能为空',1),
	);

}
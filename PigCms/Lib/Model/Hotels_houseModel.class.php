<?php
class Hotels_houseModel extends Model{
	protected $_validate =array(
		array('name', 'require', '房间名（编号）不能为空', 1),
		array('sid', 'require', '房间所属分类不能为空', 1),
	);
	
	protected $_auto = array (
		array('token', 'gettoken', self::MODEL_INSERT, 'callback'),
	);
	
	public function gettoken(){
		return session('token');
	}
	
	
}
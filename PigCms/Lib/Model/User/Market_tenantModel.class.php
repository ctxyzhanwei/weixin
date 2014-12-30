<?php 
class Market_tenantModel extends Model {

	//自动验证
	protected $_validate = array(	
			array('tenant_name','require','商家名称必须填写',3),
			array('tenant_token','require','商家token身份证必须填写',3),
			array('tenant_mark','require','商家二维码必须选择',3),
			array('cate_id','require','商家分类必须选择',3),
			array('area_id','require','商家区域必须选择',3),
	 );

}
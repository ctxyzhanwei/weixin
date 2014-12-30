<?php
class PlatformAction extends UserAction {
	public $pay_config_db;
	public function _initialize() {
		parent::_initialize();
		$this->pay_config_db = M('Alipay_config');
		if (!$this->token) {
			exit();
		}
	}
	public function index() {
		$database_platform_pay = D('Platform_pay');
		$condition_platform_pay['token'] = $this->token;
		$count = $database_platform_pay->where($condition_platform_pay)->count();
		$page = new Page($count, 25);
		$platform_list = $database_platform_pay->where($condition_platform_pay)->order('`time` DESC')->limit($page->firstRow . ',' . $page->listRows)->select();
		if (!empty($platform_list)) {
			foreach($platform_list as $key => $value) {
				$from = strtolower($value['from']);
				switch ($from) {
					case 'repast': $platform_list[$key]['from'] = '餐饮';
						break;
					case 'store': $platform_list[$key]['from'] = '店铺';
						break;
					case 'hotels': $platform_list[$key]['from'] = '酒店';
						break;
					case 'business': $platform_list[$key]['from'] = '商业';
						break;
				}
				$platform_count['all'] += $value['price'];
				if ($value['paid']) {
					$platform_count['ok'] += $value['price'];
				}else {
					$platform_count['none'] += $value['price'];
				}
			}
		}
		$this->assign('page', $page->show());
		$this->assign('platform_list', $platform_list);
		$this->assign('platform_count', $platform_count);
		$this->display();
	}
}

?>
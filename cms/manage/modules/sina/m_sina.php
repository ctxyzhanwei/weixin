<?php
bpBase::loadAppClass('manage','manage',0);
class m_sina extends manage {
	function __construct() {
		parent::__construct();
		$this->exitWithoutAccess('system','manage');
	}
	/**
	 * 配置
	 *
	 */
	public function init(){
		if(isset($_POST['doSubmit'])){
			$arr=var_export($_POST['info'],1);
			$str="<?php\r\n"."return ".$arr.";"."\r\n?>";
			file_put_contents(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'sina.config.php',$str);
			showMessage('设置成功','?m='.ROUTE_MODEL.'&c='.ROUTE_CONTROL.'&a='.ROUTE_ACTION);
		}else {
			include $this->showManageTpl('init');
		}
	}
}
?>
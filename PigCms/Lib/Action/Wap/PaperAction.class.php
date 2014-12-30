<?php
class PaperAction extends BaseAction{
	public $token;
	public $wecha_id;
	public $paper_model;
	public function __construct(){
		parent::__construct();
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if(!strpos($agent,"MicroMessenger")) {
			echo '此功能只能在微信浏览器中使用';exit;
		}

		$this->token = $this->_get('token');
		$this->assign('token',$this->token);
		$this->wecha_id	= $this->_get('wecha_id');
		if (!$this->wecha_id){
			$this->wecha_id='null';
		}
		$this->assign('wecha_id',$this->wecha_id);
		
		$this->paper_model=M('Paper');
		$this->assign('staticFilePath',str_replace('./','/',THEME_PATH.'common/css/paper'));
	}
	public function index(){
		$formid=intval($_GET['id']);
		$thisForm=$this->paper_model->where(array("id"=>$formid))->find();
		//这里可能需要判断		
		//dump($thisForm);
		$this->assign('Paper',$thisForm);
		$this->display();
	}
}
?>
<?php
bpBase::loadAppClass('front','front',0);
/**
 * 用户个人页面的公共信息
 *
 */
class userPageInfo extends front {
	public $uid;
	public function __construct(){
		parent::__construct();
		/***********uid*******************/
		$uid=isset($_GET['uid'])&&intval($_GET['uid'])>0?intval($_GET['uid']):0;//设置uid为request的数值
		$uid=$uid>0?$uid:$this->uid;
		$this->assign('uid',$uid);
		if (!$uid){
			header('Location:/');
			exit();
		}
		$thisUser=$this->user;
		$this->assign('user',$thisUser);
		/**********************************************************/
		if ($this->uid==$uid){
			$sub='我';
			$my=1;
		}else {
			$sub='他(她)';
			$my=0;
		}
		$this->uid=$uid;
		$this->assign('sub',$sub);
		$this->assign('my',$my);
		/*********************判断是不是各种经销商***************************/
		$storeUserIndependent=0;//经销商用户是否单独建表存储
		if (intval(loadConfig('store','storeUserIndependent'))){
			$storeUserIndependent=1;//经销商用户是否单独建表存储
		}
		if ($uid==$this->uid){
			$this->assign('canManage',1);
		}
		if ($uid==$this->uid&&!$storeUserIndependent){
			$store_db=bpBase::loadModel('store_model');
			$is4sStore=0;
			if ($store_db->select(array('storetype'=>1,'uid'=>$this->uid))){
				$is4sStore=1;
			}
			$this->assign('is4sStore',$is4sStore);
			//carRental
			$isRentalStore=0;
			if ($store_db->select(array('storetype'=>3,'uid'=>$this->uid))){
				$isRentalStore=1;
			}
			$this->assign('isRentalStore',$isRentalStore);
			//ucar
			$ucar_store_db=bpBase::loadModel('usedcar_store_model');
			$thisUcarStore=$ucar_store_db->select(array('uid'=>$this->uid));
			$this->assign('isUcarStore',$thisUcarStore?1:0);
		}
	}
}
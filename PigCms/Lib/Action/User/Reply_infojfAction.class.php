<?php
class Reply_infoAction extends UserAction{
	public $token;
	public $reply_infojf_model;
	public $infoTypes;
	public function _initialize() {
		parent::_initialize();
		$this->reply_info_model=M('reply_info');
		$this->token=session('token');
		$this->assign('token',$this->token);
		//
		$this->infoTypes=array(
		'Groupon'=>array('type'=>'Groupon','name'=>'团购','keyword'=>'团购','url'=>U('Wap/Groupon/grouponIndex',array('token'=>$this->token))),
		'Dining'=>array('type'=>'Dining','name'=>'订餐','keyword'=>'订餐','url'=>U('Wap/Dining/index',array('token'=>$this->token))),
		'Zuche'=>array('type'=>'Zuche','name'=>'租车','keyword'=>'租车','url'=>U('Wap/Zuche/index',array('token'=>$this->token))),
		'Shop'=>array('type'=>'Shop','name'=>'商城','keyword'=>'商城','url'=>U('Wap/Product/index',array('token'=>$this->token))),
		'panorama'=>array('type'=>'panorama','name'=>'全景','keyword'=>'全景','url'=>U('Wap/Panorama/index',array('token'=>$this->token))),
		'Liuyan'=>array('type'=>'Liuyan','name'=>'留言','keyword'=>'留言','url'=>U('Wap/Liuyan/index',array('token'=>$this->token))),
		'Scoregift'=>array('type'=>'Scoregift','name'=>'积分换礼','keyword'=>'积分换礼','url'=>U('Wap/Scoregift/index',array('token'=>$this->token))),
		);
		//是否是餐饮
		if (isset($_GET['infotype'])&&$_GET['infotype']=='Dining'){
			$this->isDining=1;
		}else {
			$this->isDining=0;
		}
		//是否是租车
		if (isset($_GET['infotype'])&&$_GET['infotype']=='Zuche'){
			$this->iszuche=1;
			$this->assign('iszuche',$this->iszuche);
		}
		$this->assign('isDining',$this->isDining);
	}
	public function set(){
        $infotype = $this->_get('infotype');
		$thisInfo = $this->reply_info_model->where(array('infotype'=>$infotype,'token'=>$this->token))->find();
		if ($thisInfo&&$thisInfo['token']!=$this->token){
			exit();
		}

		if(IS_POST){
			$row['title']=$this->_post('title');
			$row['info']=$this->_post('info');
			$row['picurl']=$this->_post('picurl');
			$row['token']=$this->_post('token');
			$row['infotype']=$this->_post('infotype');
			$row['homepic']=$this->_post('homepic');
			if ($row['infotype']=='Dining'){//订餐
				$diningyuding=intval($_POST['diningyuding']);
				$diningwaimai=intval($_POST['diningwaimai']);
				
				if (isset($_POST['diningyuding'])){
					$row['diningyuding']=intval($_POST['diningyuding']);
				}else {
					$row['diningyuding']=0;
				}
				if (isset($_POST['diningwaimai'])){
					$row['diningwaimai']=intval($_POST['diningwaimai']);
				}else {
					$row['diningwaimai']=0;
				}
				$row['config']=serialize(array('waimaiclose'=>$diningwaimai,'yudingclose'=>$diningyuding,'yudingdays'=>intval($_POST['yudingdays'])));
			}
			if($row['infotype']=='Zuche'){//
				$iszuche=intval($_POST['iszuche']);
				$zucheurl=$_POST['zucheurl'];
				$row['config']=serialize(array('iszuche'=>$iszuche,'zucheurl'=>$zucheurl));
			}
			if ($thisInfo){
				
				$where=array('infotype'=>$thisInfo['infotype'],'token'=>$this->token);
				$this->reply_info_model->where($where)->save($row);

				/*$keyword_model=M('Keyword');
				$keyword_model->where(array('token'=>$this->token,'pid'=>$thisInfo['id'],'module'=>'Reply_info'))->save(array('keyword'=>$_POST['keyword']));*/
				$this->success('修改成功',U('Reply_info/set',$where));
						
			}else {
				$this->all_insert('Reply_info','/set?infotype='.$infotype);
			}
		}else{
			$config=unserialize($thisInfo['config']);
			$this->assign('config',$config);
			$this->assign('infoType',$this->infoTypes[$infotype]);
			$this->assign('set',$thisInfo);
			$this->display();
		}
	}
}


?>
<?php
class EssayAction extends ImgBaseAction{
	public function _initialize() {
		parent::_initialize();
	}

	public function index(){
		parent::index();
		$this->display();

	}

	public function add(){
		parent::add();
		$this->display();
	}

	public function del(){
		$where['id']=$this->_get('id','intval');
		$where['token']=$this->token;
		$list=M('Img')->where($where)->find();
		if(D('Img')->where($where)->delete()){
			$this->handleKeyword(intval($_GET['id']),'Img','','',1);

			$this->success('操作成功',U('Essay'.'/index'));
		}else{
			$this->error('操作失败',U('Essay'.'/index'));
		}
	}
	
	public function edit(){
		$id=$this->_get('id','intval');
		parent::edit();
		$this->display();
	}
	public function insert(){
		$lastid = M("Img")->where(array('token'=>session('token')))->order('usort DESC')->limit(1)->getField('usort');
		$_POST['usort'] = $lastid+1;
		$_POST['info']=str_replace('\'','&apos;',$_POST['info']);
		$usersdata=M('Users');
		$usersdata->where(array('id'=>$this->user['id']))->setInc('diynum');

		$db=D('Img');
		if($db->create()===false){
			$this->error($db->getError());
		}else{
			$id=$db->add();
			if($id){
				$m_arr=array('Img','Text','Voiceresponse','Ordering','Lottery','Host','Product','Selfform','Panorama','Wedding','Vote','Estate','Reservation','Greeting_card');
				if(in_array($name,$m_arr)){
					//isset($_POST['precisions']) ? $precisions = 1: $precisions = 0 ;
					$this->handleKeyword($id,$name,$_POST['keyword'],intval($_POST['precisions']));

				}

				$this->success('操作成功',U('Essay/index'));
			}else{
				$this->error('操作失败',U('Essay/index'));
			}
		}
	}

	public function upsave(){
		$_POST['info']=str_replace('\'','&apos;',$_POST['info']);
		$db=D('Img');
		if($db->create()===false){
			$this->error($db->getError());
		}else{
			$id=$db->save();
			if($id==true){
				$this->success('操作成功',U('Essay/index'));
			}else{
				$this->error('操作失败',U('Essay/index'));
			}
		}
	}
}
?>
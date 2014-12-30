<?php
class WallAction extends UserAction{
	public $wall_model;
	public $token_where;
	public $keyword_model;
	public $stringFields;
	public $intFields;
	public $wall_info;

	public function _initialize() {

		parent::_initialize();
		$this->canUseFunction('wall');
		$this->wall_model=M('Wall');
		$this->token_where['token']=$this->token;
		$this->keyword_model=M('Keyword');
		$this->stringFields=array('title','keyword','background','startbackground','logo','qrcode','endbackground','firstprizename','secondprizename','thirdprizename','fourthprizename','fifthprizename','sixthprizename','firstprizepic','secondprizepic','thirdprizepic','fourthprizepic','fifthprizepic','sixthprizepic');
		$this->intFields=array('isopen','firstprizecount','secondprizecount','thirdprizecount','fourthprizecount','fifthprizecount','sixthprizecount','ck_msg');
	

		$info = $this->wall_model->where(array('token'=>$this->token,'id'=>$this->_get('id','intval')))->find();
		$info['wxuser']  = M('wxuser')->where(array('token'=>$this->token))->getField('weixin');
		$this->wall_info = $info;
		$this->assign('info',$info);
	}
	public function index(){
		$count=$this->wall_model->where($this->token_where)->count();
		$page=new Page($count,20);
		$info=$this->wall_model->where($this->token_where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$page->show());
		$this->assign('info',$info);
		$this->display();
	}
	
	public function add(){
		if (IS_POST){
			if (!trim($_POST['title'])){
				$this->error('请填写标题');
			}
			$fileds=$this->stringFields;
			$row=array();
			foreach ($fileds as $f){
				$row[$f]=$this->_post($f);
			}
			$intFields=$this->intFields;
			foreach ($intFields as $f){
				$row[$f]=intval($this->_post($f));
			}
			$row['token']=$this->token;
			$row['time']=time();
			$id=$this->wall_model->add($row);
			if ($id){
				$this->keyword_model->add(array('module'=>'Wall','pid'=>$id,'token'=>$this->token,'keyword'=>$row['keyword']));
				if ($row['isopen']){
					$this->setOtherClose($id);
				}
			}
			$this->success('添加成功',U('Wall/index',array('token'=>session('token'))));
		}else {
			$info=array();
			$info['isopen']=1;
			$this->assign('info',$info);
			$this->display('set');
		}
	}
	public function edit(){
		if (IS_POST){
			if (!trim($_POST['title'])){
				$this->error('请填写标题');
			}
			$fileds=$this->stringFields;
			$row=array();
			foreach ($fileds as $k=>$f){
				if($this->_post($f) != false){
					$row[$f]=$this->_post($f);
				}		
			}
			$intFields=$this->intFields;
			foreach ($intFields as $f){
				$row[$f]=intval($this->_post($f));
			}
			$updateWhere=array();
			$updateWhere['token']=$this->token;
			$updateWhere['id']=intval($_POST['id']);

			$rt=$this->wall_model->where($updateWhere)->save($row);
			if ($rt){
				$this->keyword_model->where(array('module'=>'Wall','pid'=>$updateWhere['id']))->save(array('keyword'=>$row['keyword']));
				if ($row['isopen']){
					$this->setOtherClose($id);
				}
			}
			$this->success('修改成功',U('Wall/index',array('token'=>session('token'))));
		}else {
			$where['token']=$this->token;
			$where['id']=$this->_get('id','intval');
			$info=$this->wall_model->where($where)->find();
			$this->assign('info',$info);
			$this->display('set');
		}
	}

	public function del(){

		$this->token_where['id']=intval($_GET['id']);
		$rt=$this->wall_model->where($this->token_where)->delete();
		if ($rt){
			$this->keyword_model->where(array('module'=>'Wall','pid'=>$this->token_where['id']))->delete();
			M('Wall_member')->where(array('act_id'=>$this->token_where['id'],'act_type'=>'1','token'=>$this->token))->delete();
			M('Wall_message')->where(array('wallid'=>$this->token_where['id'],'token'=>$this->token))->delete();
			$this->success('操作成功',U(MODULE_NAME.'/index'));
		}
	}
	public function setOtherClose($id){
		$where=array();
		$where['token']=$this->token;
		$where['id']=array('neq',$id);
		$this->wall_model->where($where)->save(array('isopen'=>0));
	}
	public function screen(){
		$this->token_where['isopen']=1;
		$info=$this->wall_model->where($this->token_where)->find();
		$this->assign('info',$info);
		//
		$members=M('Wall_member')->where(array('wallid'=>$info['id']))->select();
		$this->assign('members',$members);
		$this->display();
	}


	public function pullScreen(){
		$where=array();
		$where['token']=$this->token;
		$where['wallid']=intval($_GET['id']);
		$where['time']=array('gt',intval($_GET['dapingmu']));
		$choujiangTime=intval($_GET['choujiang']);
		$shujubaobiaoTime=intval($_GET['shujubaobiao']);
		$messages=M('Wall_message')->where($where)->order('id ASC')->select();
		$messageArr=array();
		$uids=array();
		if ($messages){
			foreach ($messages as $m){
				if (!in_array($m['uid'],$uids)){
					array_push($uids,$m['uid']);
				}
			}
			$membersArr=array();
			if ($uids){
				$memberWhere=array();
				$memberWhere['id']=array('in',$uids);
				$members=M('Wall_member')->where($memberWhere)->select();
				if ($members){
					foreach ($members as $me){
						$membersArr[$me['id']]=$me;
					}
				}
			}
			$maxTime=0;
			foreach ($messages as $m){
				$m['caudit']=$m['time'];
				$m['cmedia']=0;
				$m['cid']=$m['id'];
				if ($membersArr[$m['uid']]){
					$m['avatar']=$membersArr[$m['uid']]['portrait'];
					$m['nickname']=$membersArr[$m['uid']]['nickname'];
				}else {
					$m['avatar']='';
					$m['nickname']='';
				}
				$m['from_mark']='<i></i>';
				$m['remove']=0;
				if ($maxTime<$m['time']){
					$maxTime=$m['time'];
				}
				array_push($messageArr,$m);
			}
		}
		//
		$infoCount=M('Wall_message')->where(array('wallid'=>$where['wallid']))->count();
		$userCount=M('Wall_member')->where(array('wallid'=>$where['wallid']))->count();
		//
		$memberWhere=array();
		$memberWhere['wallid']=$where['wallid'];
		$memberWhere['time']=array('gt',time());
		$members=M('Wall_member')->where($memberWhere)->select();
		$membersArr=array();
		$maxMemberTime=$choujiangTime;
		if ($members){
			foreach ($members as $m){
				$m['avatar']=$m['portrait'];
				$m['awards']=0;
				$m['uid']=$m['id'];
				$m['from_mark']='<i></i>';
				if ($maxMemberTime<$m['time']){
					$maxMemberTime=$m['time'];
				}
				array_push($membersArr,$m);
			}
		}else {
			$membersArr='';
		}
		//
		$arr=array(
		'dapingmu'=>array('type'=>'dapingmu','update'=>$messageArr,'remove'=>0,'time'=>$maxTime?$maxTime:intval($_GET['dapingmu'])),
		'shujubaobiao'=>array('type'=>'shujubaobiao','update'=>array('info_all'=>$infoCount,'user_all'=>$userCount),'remove'=>0,'time'=>$maxMemberTime>$maxTime?$maxMemberTime:$maxTime),
		'choujiang'=>array('type'=>'choujiang','update'=>$membersArr,'remove'=>'','time'=>$maxMemberTime),
		);
		echo json_encode($arr);
	}

	public function insertPrizeRecord(){
		$db=M('Wall_prize_record');
		$wallid=intval($_GET['id']);
		$uids=explode(',',$_GET['uids']);
		if ($uids){
			foreach ($uids as $uid){
				if (intval($uid)){
					$uid=intval($uid);
					$check=$db->where(array('wallid'=>$wallid,'uid'=>$uid))->find();
					if (!$check){
						$db->add(array('wallid'=>$wallid,'uid'=>$uid,'time'=>time(),'prize'=>intval($_GET['prize'])));
					}
				}
			}
		}
	}

/*粉丝列表*/
	public function show_fens(){
		$id 		= $this->_get('id','intval');
		$keyword 	= $this->_post('keyword','trim');
		$where 		= array('token'=>$this->token,'act_id'=>$id,'act_type'=>'1');
		
		if(!empty($keyword)){
			$where['nickname|truename']	= array('like','%'.$keyword.'%');
		}

		$count		= M('Wall_member')->where($where)->count();
		$Page 		= new Page($count,15);
		$list 		= M('Wall_member')->where($where)->order('time desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		foreach ($list as $key => $value) {
			$mwhere  	= array('token'=>$this->token,'is_scene'=>'0','wallid'=>$id,'uid'=>$value['id']);
			$count 		= M('Wall_message')->where($mwhere)->count();
			$list[$key]['count'] 	= $count;
		}

		$this->assign('pid',$id);
		$this->assign('page',$Page->show());
		$this->assign('list',$list);
		$this->display();
	}

	public function del_fens(){
		$id 		= $this->_get('id','intval');
		$pid 		= $this->_get('pid','intval');
		$where 		= array('token'=>$this->token,'id'=>$id,'act_type'=>'1');
		$wecha_id  	= M('Wall_member')->where($where)->getField('wecha_id');
		if(M('Wall_member')->where($where)->delete()){
			M('Wall_message')->where(array('token'=>$this->token,'is_scene'=>'0','uid'=>$id))->delete();
			$this->success('删除成功',U('Wall/show_fens',array('token'=>$this->token,'id'=>$pid)));
		}
	}
/*信息审核*/
	public function check_msg(){
		$sceneid 	= $this->_get('sceneid','intval');
		$id 		= $this->_get('id','intval');
		$uid 		= $this->_get('uid','intval');
		$status 	= $this->_post('status','intval');
		$keyword 	= $this->_post('keyword','trim');
		$where 		= array('token'=>$this->token);
		if(empty($sceneid)){
			$where['is_scene'] 	= '0';
			$where['wallid'] 	= $id;
		}else{
			$where['is_scene']  = '1';
		}

		if($status == 1){
			$where['is_check'] 	= '0';
		}else if($status == 2){
			$where['is_check'] 	= '1';
		}

		if(!empty($keyword)){
			$where['content'] 	= array('like','%'.$keyword.'%');
		}

		if(!empty($uid)){
			$where['uid'] 		= $uid;
		}


		$list 		= M('Wall_message')->where($where)->order('time desc')->select();

		foreach ($list as $key => $value) {
			$user 	= M('Wall_member')->where(array('token'=>$this->token,'id'=>$value['uid']))->find();
			$list[$key]['username'] 	= $user['nickname'];
		}

		$ck_msg 	= M('Wall')->where(array('token'=>$this->token,'id'=>$id))->getField('ck_msg');

		$this->assign('id',$id);
		$this->assign('uid',$uid?$uid:0);
		$this->assign('ck_msg',$ck_msg);
		$this->assign('sceneid',$sceneid?$sceneid:0);
		$this->assign('now',time());
		$this->assign('list',$list);
		$this->display();
	}

	public function laodMsg(){
		$id 		= $this->_get('id','intval');
		$uid 		= $this->_get('uid','intval');
		$sceneid	= $this->_get('sceneid','intval');
		$loadtime 	= $this->_get('loadtime');
		//$loadtime  	= 140783510; //测试使用
		$where 		= array('token'=>$this->token,'wallid'=>$id,'time'=>array('gt',$loadtime));

		if(empty($sceneid)){
			$where['is_scene'] = '0';
		}else{
			$where['is_scene'] = '1';
		}

		if(!empty($uid)){
			$where['uid'] 		= $uid;
		}
		$result 	= array();
		$msg 		= M('Wall_message')->where($where)->order('time asc')->select();
		foreach ($msg as $key => $value) {
			$uwhere 	= array('token'=>$this->token,'id'=>$value['uid']);
			$msg[$key]['username'] 	= M('Wall_member')->where($uwhere)->getField('nickname');
			$msg[$key]['time'] 		= date('Y-m-d H:i:s',$value['time']);
		}
		if($msg){
			$result['err']	 	= 0;
			$result['loadtime'] = time();
			$result['res'] 		= $msg;
		}
		echo json_encode($result);
	}



	//修改审核状态
	function is_check(){
		$result = array();
		$wallid = $this->_post('wallid');
		$mid 	= $this->_post('mid','intval');
		$sceneid= $this->_post('sceneid','intval');
		$checked= $this->_post('checked','intval');
		$ck_msg = M('Wall')->where(array('token'=>$this->token,'id'=>$wallid))->getField('ck_msg');
		if($ck_msg == '0'){
			$result['err'] 	= 1;
			$result['info'] = '如需审核消息，请开启微信墙审核状态';
			echo json_encode($result);
			exit();
		}

		$idArr 		= explode(',', $mid);
		$where 		= array('token'=>$this->token,'wallid'=>$wallid,'id'=>array('in',$idArr));
		
		$msg_info 	= M('Wall_message')->where($where)->field('time,check_time')->find();
		$update 	= array('is_check'=>$checked);
		if(empty($sceneid)){
			$where['is_scene'] 	= '0';
		}else{
			$where['is_scene'] 	= '1';
		}

		if($checked == 1 && $msg_info['time'] == $msg_info['check_time']){
				$update['check_time'] 	= time();
		}

		if(M('Wall_message')->where($where)->save($update)) {
			$result['err'] 	= 0;
			$result['info'] = '';
			echo json_encode($result);
			exit();
		}

	}

	function del_msg(){

		$wallid = $this->_get('wallid','intval');
		$mid 	= $this->_get('mid','intval');
		$sceneid= $this->_get('sceneid','intval');

		$where 	= array('token'=>$this->token,'wallid'=>$wallid,'id'=>$mid);
		
		if(empty($sceneid)){
			$where['is_scene'] 	= '0';
		}else{
			$where['is_scene'] 	= '1';
		}

		if(M('Wall_message')->where($where)->delete()){
			echo true;
		}
	}

}

?>
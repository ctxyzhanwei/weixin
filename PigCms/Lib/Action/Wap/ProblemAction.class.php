<?php

class ProblemAction extends WapAction{
	public $pro_info;

	public function _initialize(){
		parent::_initialize();
		$id 		= $this->_get('id','intval');
		$where 		=  array('token'=>$this->token,'id'=>$this->_get('id','intval'));
		$this->pro_info 	= M('Problem_game')->where($where)->find();

		if(ACTION_NAME != 'index' && $this->pro_info['is_open'] == '0'){
			$this->error('活动可能还没有开启！',U('Problem/index',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'id'=>$id)));
			exit();
		}

		$this->assign('id',$id);
		$this->assign('pro_info',$this->pro_info);
	}
	
	/*开始页面*/
	public function index(){


		$this->display();
	}
 	/*用户信息登记*/
	public function users(){
		$id 	= $this->_get('id','intval');
		$user 	= $this->_ck_user_info($id);

		if($this->_get_play_day($this->pro_info['start_time'])  > $this->pro_info['end_day']){
			$this->error('游戏已过'.$this->pro_info['end_day'].'天，欢迎下次光临',U('Problem/question_status',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'id'=>$id)));
			exit();
		}
		//已有信息跳转答题
		if($user){
			header("Location:".U('Problem/question_status',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'id'=>$id)));
			exit();
		}

		$this->display();
	}

	public function add_user(){
		$_POST['add_time'] 	= time();
		$_POST['problem_id']= $this->_get('id','intval');
		$_POST['token'] 	= $this->token;
		$_POST['wecha_id']  = $this->wecha_id;

		$where= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'problem_id'=>$this->_get('id','intval'));

		$user = M('Problem_user')->where($where)->find();
		
		if($user){
			echo json_encode(array('err'=>2,'info'=>''));
			exit();
		}

		if(M('Problem_user')->add($_POST)){
			echo json_encode(array('err'=>0,'info'=>'提交成功'));
		}else{
			echo json_encode(array('err'=>1,'info'=>'登记失败，请重新填写资料'));
		}
	}
	/*答题*/
	public function requestion(){
		$id 	= $this->_get('id','intval');

		$user 	= $this->_ck_user_info($id);

		if($this->_get_play_day($this->pro_info['start_time'])  > $this->pro_info['end_day']){
			$this->error('游戏已过'.$this->pro_info['end_day'].'天，欢迎下次光临',U('Problem/question_status',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'id'=>$id)));
			exit();
		}

		if(empty($user['id'])){
			$this->error('请先填写个人信息',U('Problem/users',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'id'=>$id)));
			exit();
		}

		if($this->_ck_status($id,$user['id'])){
			$this->error('今日答题已结束',U('Problem/question_status',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'id'=>$id)));
			exit();
		}
		//答题数量  $this->pro_info['question_num'] 	
		$where 		= array('token'=>$this->token,'problem_id'=>$id,'is_show'=>'1');
		$qid 		= $this->_rand_question($id,$user['id']);
		$where['id']= $qid;
		$question 	= M('Problem_question')->where($where)->find(); 
		$question['now'] 		= time();
		$question['option'] 	= M('Problem_option')->where(array('question_id'=>$question['id']))->select();
		$question['number'] 	= $this->_ck_status($id,$user['id'],'count')+1;

		$this->assign('question',$question);
		$this->display();
	}

	public function add_rank(){
		$id 		= $this->_get('id','intval');
		$qid 		= $this->_get('qid','intval');
		$oid 		= $this->_post('oid','intval');
		$user 		= $this->_ck_user_info($id);
		$start_time = $this->_post('start','intval');
		$add_time 	= time();

		if(($pro_info['answer_time']*1000)>($add_time-$start_time)){
			echo 'out';
		}else{
			$log['problem_id']		= $id;
			$log['token']			= $this->token;
			$log['uid'] 			= $user['id'];
			$log['expend_count']	= $add_time-$start_time;
			$log['add_time']		= $add_time;
			$log['question_id'] 	= $qid;
			$log['option_id'] 		= $oid;
			$true_option 			= $this->_get_true_option($qid);
			if($true_option == $oid){
				$log['score']		= $this->pro_info['score'];

				if(M('Problem_question_log')->add($log)){
					$where = array('token'=>$this->token,'id'=>$log['uid'],'problem_id'=>$id);
					M('Problem_user')->where($where)->setInc('score_count',$log['score']);
					M('Problem_user')->where($where)->setInc('expend_count',$log['expend_count']);
				}
				echo 'ok';
			}else{
				$log['score']		= 0;
				M('Problem_question_log')->add($log);
				echo 'no';
			}
		}
	}
	/*答题状态*/
	public function question_status(){
		$id 	= $this->_get('id','intval');
		$user 	= $this->_ck_user_info($id);
		$data 	= array();
		$data['play_day']	= $this->_get_play_day($this->pro_info['start_time']);
		$data['score']		= M('Problem_user')->where(array('token'=>$this->token,'problem_id'=>$id,'id'=>$user['id']))->getField('score_count');
		
		if($data['play_day'] > $this->pro_info['end_day']){
			$data['status'] 	= 2;
			$data['end_day']	= $this->pro_info['end_day'];
		}else if($this->_ck_status($id,$user['id'])){
			$data['status'] 	= 0;
			$data['info']		= $this->pro_info['over_hint'];
		}else{
			$data['status'] 	= 1;
			$qCount 			= $this->_ck_status($id,$user['id'],'count');
			//如果今天已经开始答题直接跳转答题界面
			if($qCount != 0){
				header("Location:".U('Problem/requestion',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'id'=>$id)));
				exit();
			}

			
		}

		$this->assign('data',$data);
		$this->display();
	}
	/*排名*/
	public function rank(){
		$id 	= $this->_get('id','intval');
		$where	= array('token'=>$this->token,'problem_id'=>$id);
		$rank 	= M('Problem_user')->where($where)->order('score_count desc,expend_count asc')->limit(10)->select();
		$this->assign('rank',$rank);
		$this->display();
	}

	/*获取游戏天数
	*/
	public function _get_play_day($start_time){
		$start_time		= strtotime(date('Y-m-d',$start_time));
		$today_time 	= strtotime(date('Y-m-d',time()));
		$limit_time 	= $today_time - $start_time;
		if($limit_time == 0){
			$day = 1;
		}else{
			$day = $limit_time/(60*60*24)+1;
		}
		return $day;
	}
	/*随机获取题目id
	*/
	public function _rand_question($id,$uid){
		$where	= array('problem_id'=>$id,'token'=>$this->token,'is_show'=>'1');
		$dff	= M('Problem_question_log')->where(array('problem_id'=>$id,'uid'=>$uid,'token'=>$this->token))->getField('question_id',true);
		$id_arr = M('Problem_question')->where($where)->getField('id',true);

		$array  = array_diff($id_arr,$dff);

		if(empty($array)){
			$key	= array_rand($id_arr);
		}else{
			$key	= array_rand($array);
		}

		$id 	= $id_arr[$key];

		return $id;
	}

	/*获取正确答案id
	*/
	public function _get_true_option($qid){
		$where 	= array('question_id'=>$qid,'is_true'=>'1');
		$id 	= M('Problem_option')->where($where)->getField('id');
		return $id;
	}

	/*检查用户信息
	*/
	public function _ck_user_info($id){
		$where 	= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'problem_id'=>$id);
		$info 	= M('Problem_user')->where($where)->find();

		return $info;
	}

	/*检查游戏是状态
	*/
	public function _ck_status($id,$uid,$type=''){
		//当日时间
		$now_time	= strtotime(date('Y-m-d',time()));
		//次日时间
		$next_time 	= strtotime(date('Y-m-d',strtotime('+1 day')));

		$uwhere 	= array('token'=>$this->token,'problem_id'=>$id,'uid'=>$uid,'add_time'=>array(array('gt',$now_time),array('lt',$next_time)));

		// 活动次数 $this->pro_info['sub_limit']
		$q_count 	= 	M('Problem_question_log')->where($uwhere)->count('id');

		if($type == 'count'){
			return $q_count;
		}else{
			if($q_count < $this->pro_info['question_num']){
				return false;
			}else{
				return true;
			}
		}

	}

}

?>
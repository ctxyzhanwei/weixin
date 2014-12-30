<?php

class ProblemAction extends UserAction{
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('Problem');
	}

	/*活动设置*/
	public function index(){
		$keyword= $this->_post('searchkey','trim');
		$where 	= array('token'=>$this->token);
		if(!empty($keyword)){
			$where['name|title|keyword'] = array('like','%'.$keyword.'%');
		}
		$count	= M('Problem_game')->where($where)->count();
		$Page   = new Page($count,15);
		$list 	= M('Problem_game')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('list',$list);
		$this->assign('page',$Page->show());
		$this->display();
	}

	public function game_set(){
		$set_db 		= M('Problem_game');
		$keyword_db		= M('Keyword');
		$where  		= array('token'=>$this->token,'id'=>$this->_get('id','intval'));
		$problem_info 	= $set_db->where($where)->find();
		if(IS_POST){
			$_POST['add_time'] 	= time();
			$_POST['token'] 	= $this->token;

			if($set_db->create()){
				//添加
				if(empty($problem_info)){
					if($_POST['is_open'] == 1){
						$_POST['start_time'] = time();
					}
					$id = $set_db->add($_POST);
					if($id){
						$keyword['pid']		= $id;
	                	$keyword['module']	= 'Problem';
	               		$keyword['token']	= $this->token;
	               		$keyword['keyword']	= $this->_post('keyword','trim');
	                	$keyword_db->add($keyword);
					}
					$this->success('添加成功',U('Problem/index',array('token'=>$this->token)));
				//修改
				}else{
					if ($_POST['reset'] == '1') {
						if($_POST['is_open'] == 1){
							$times 	= time();
						}else{
							$times	='';
						}
						$where 	= array('token'=>$this->token,'problem_id'=>$problem_info['id']);
						M('Problem_game')->where(array('token'=>$this->token,'id'=>$problem_info['id']))->save(array('start_time'=>$times));					
						M('Problem_user')->where($where)->delete();
						M('Problem_question_log')->where($where)->delete();
						$this->success('游戏重置成功',U('Problem/index',array('token'=>$this->token)));
						exit;
					}
					if($problem_info['start_time'] == ''){
						if($_POST['is_open'] == 1){
							$_POST['start_time'] = time();
						}
					}
					$swhere = array('token'=>$this->token,'id'=>$this->_post('id','intval'));
					$offset = $set_db->where($swhere)->save($_POST);//更新设置表

					if($offset){
						$keyword['pid']		= $this->_POST('id','intval');
	                	$keyword['module']	= 'Problem';
	               		$keyword['token']	= $this->token;
	               		$keyword['keyword']	= $this->_post('keyword','trim');
	                	$keyword_db->where(array('token'=>$this->token,'pid'=>$this->_post('id','intval'),'module'=>'Problem'))->save($keyword);
					}
					$this->success('修改成功',U('Problem/index',array('token'=>$this->token)));
				}
			}else{

				$this->error($set_db->getError());
			}
		}else{

			$this->assign('set',$problem_info);
			$this->display();
		}

	}

	public function game_del(){
		$id 	= $this->_get('id','intval');
		$where 	= array('token'=>$this->token,'id'=>$id);
		if(M('Problem_game')->where($where)->delete()){
			$qid_arr 	= M('Problem_question')->where(array('token'=>$this->token,'problem_id'=>$id))->getField('id',true);
			M('Problem_question')->where(array('token'=>$this->token,'problem_id'=>$id))->delete();
			if(!empty($qid_arr)){
				M('Problem_option')->where(array('token'=>$this->token,'question_id'=>array('in',$qid_arr)))->delete();
			}
			M('Problem_question_log')->where(array('token'=>$this->token,'problem_id'=>$id))->delete();
			M('Problem_user')->where(array('token'=>$this->token,'problem_id'=>$id))->delete();
			M('Keyword')->where(array('token'=>$this->token,'pid'=>$id,'module'=>'Problem'))->delete();

			$this->success('删除成功',U('Problem/index',array('token'=>$this->token)));
		}
	}
	/*问题设置*/
	public function question(){
		$problem_id 	= $this->_get('problem_id','intval');
		$searchkey 		= $this->_post('searchkey','trim');
		$where 	= array('token'=>$this->token,'problem_id'=>$problem_id);	 
		if(!empty($searchkey)){
			$where['title'] 	= array('like','%'.$searchkey.'%');
		}

		$count	= M('Problem_question')->where($where)->count();
		$Page   = new Page($count,15);

		$question_info = M('Problem_question')->where($where)->order('sort desc,id desc')->limit($Page->firstRow.','.$Page->listRows)->select();


		foreach ($question_info as $key => $value) {
			$op_where 	= array('token'=>$this->token,'question_id'=>$value['id']);
			$question_info[$key]['option'] 	= M('Problem_option')->where($op_where)->select();
		}

		$ic = 'abcdefghijklmnopqrstuvwxyz';
		$this->assign('ic',$ic);
		$this->assign('question_info',$question_info);
		$this->assign('page',$Page->show());
		$this->assign('problem_id',$problem_id);
		$this->display();
	}

	public function question_add(){
		$problem_id 	= $this->_get('problem_id','intval');

		if(IS_POST){

			$question 	= $_REQUEST['question'];
			$sort   	= $_REQUEST['sort'];
			$is_show   	= $_REQUEST['is_show'];

			$option   	= $_REQUEST['option'];
			$is_true  	= $_REQUEST['is_true'];
			
			$flag = true;
			for ($i=0; $i < count($question); $i++) { 
				
				$qs['token'] 		= $this->token;
				$qs['problem_id'] 	= $problem_id;
				$qs['title'] 		= $question[$i];
				$qs['sort'] 		= $sort[$i]?$sort[$i]:50;
				$qs['is_show'] 		= $is_show[$i]?1:0;

				$question_id = M('Problem_question')->add($qs);

				if($question_id){
					for ($k=0; $k < count($option[$i]); $k++) { 
						$op['token'] 		= $this->token;
						$op['question_id'] 	= $question_id;
						$op['answer'] 		= $option[$i][$k];
						$op['is_true'] 		= $is_true[$i][$k]?$is_true[$i][$k]:0;
						$op['sort'] 		= 50;

						$option_id = M('Problem_option')->add($op);
						
						if(!$option_id){
							$flag = false;
							//执行错误删除已添加的题目
							M('Problem_question')->where(array('token'=>$this->token,'id'=>$question_id))->delete();
						}
					}	
				}else{
					$flag = false;
				}
			}

			if($flag){
				$this->success('添加成功',U('Problem/question',array('token'=>$this->token,'problem_id'=>$problem_id)));
			}else{
				$this->error('未知错误，请重新添加',U('Problem/question_add',array('token'=>$this->token,'problem_id'=>$problem_id)));
			}

		}else{

			$this->assign('problem_id',$problem_id);
			$this->display();
		}

	}

	public function question_edit(){
		$problem_id	= $this->_get('problem_id','intval');
		$id 		= $this->_get('id','intval');
		$where 		= array('token'=>$this->token,'problem_id'=>$problem_id,'id'=>$id);

		$quest_info = M('Problem_question')->where($where)->find();

		$op_where	= array('token'=>$token,'question_id'=>$quest_info['id']);
		
		$quest_info['op_data'] 	= M('Problem_option')->where($op_where)->order('sort desc')->select();
		if(IS_POST){
			$qs_id 			= $_REQUEST['qid'];
			$qs_title 		= $_REQUEST['title'];
			$qs_is_show		= $_REQUEST['is_show'];
			$qs_sort		= $_REQUEST['sort'];

			$qs['id']		= $qs_id[0];
			$qs['title']	= $qs_title[0];
			$qs['is_show']	= $qs_is_show[0];
			$qs['sort']		= $qs_sort[0];
			$qs_where 		= array('token'=>$this->token,'id'=>$qs['id']);
			
			M('Problem_question')->where($qs_where)->save($qs);
			if($qs['id']){	
				$op_id 		= $_REQUEST['oid'][0];
				$op_answer 	= $_REQUEST['answer'][0];
				$op_is_true = $_REQUEST['is_true'][0];
				for ($i=0; $i < count($op_answer); $i++) { 
					$op['token'] 		= $this->token;
					$op['question_id']	= $qs['id'];  
					$op['answer']		= $op_answer[$i];
					$op['sort']			= 50;
					$op['is_true']		= $op_is_true[$i]?$op_is_true[$i]:0;
						
					if(empty($op_id[$i])){
						M('Problem_option')->add($op);
					}else{
						$op_where 		= array('question_id'=>$op['question_id'],'id'=>$op_id[$i]);
						M('Problem_option')->where($op_where)->save($op);
					}
				}
			}
			$this->success('修改成功',U('Problem/question',array('token'=>$this->token,'problem_id'=>$problem_id)));
		}else{

			$this->assign('problem_id',$problem_id);
			$this->assign('quest_info',$quest_info);
			$this->display();
		}

	}
	//删除题目
	public function question_del(){
		$problem_id	= $this->_get('problem_id','intval');
		$id 		= $this->_get('id');

		$where 		= array('token'=>$this->token,'problem_id'=>$problem_id,'id'=>$id);
		if(M('Problem_question')->where($where)->delete()){
			$op_where = array('question_id'=>$id);
			M('Problem_option')->where($op_where)->delete();
			$this->success('删除成功',U('Problem/question',array('token'=>$this->token,'problem_id'=>$problem_id)));
		}
	}
	//删除选项
	public function option_del(){
		$problem_id	= $this->_get('problem_id','intval');
		$id 		= $this->_get('id');
		$where 		= array('token'=>$this->token,'id'=>$id);
		if(M('Problem_option')->where($where)->delete()){
			if($this->_get('is_ajax','intval') == 1){
				echo true;
			}else{
				$this->success('删除成功',U('Problem/question',array('token'=>$this->token,'problem_id'=>$problem_id)));
			}
		}
	}


	/*用户信息*/
	public function user_info(){
		$search 	= $this->_post('search','trim');
		$problem_id = $this->_get('problem_id','intval');
		$where 		= array('token'=>$this->token,'problem_id'=>$problem_id);
		if(!empty($search)){
			$where['user_name|nickname'] = array('like','%'.$search.'%');
		}
		$count		= M('Problem_user')->where($where)->count();
		$Page   	= new Page($count,15);
		$info 		= M('Problem_user')->where($where)->order('add_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('info',$info);
		$this->assign('problem_id',$problem_id);
		$this->assign('page',$Page->show());
		$this->display();
	}
	/*删除用户*/
	public function user_del(){
		$uid 		= $this->_get('uid','intval');
		$pid 		= $this->_get('problem_id','intval');
		$where 		= array('token'=>$this->token,'problem_id'=>$pid,'id'=>$uid);
		if(M('Problem_user')->where($where)->delete()){
			$logwhere = array('token'=>$this->token,'problem_id'=>$pid,'uid'=>$uid);

			M('problem_question_log')->where($logwhere)->delete();
			$this->success('删除成功',U('Problem/user_info',array('token'=>$this->token,'problem_id'=>$pid)));
		}
	}
	/*用户答题记录*/
	public function show_question_log(){
		$problem_id = $this->_get('problem_id','intval');
		$uid 		= $this->_get('uid','intval');
		$where 		= array('token'=>$this->token,'problem_id'=>$problem_id,'uid'=>$uid);
		$count		= M('problem_question_log')->where($where)->count();
		$Page   	= new Page($count,5);
		$log 		= M('problem_question_log')->where($where)->order('add_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		foreach ($log as $key => $value) {
			$log[$key]['q_name'] = M('Problem_question')->where(array('id'=>$value['question_id']))->getField('title');
			if($value['option_id'] != 0){
				$log[$key]['o_name'] = M('Problem_option')->where(array('id'=>$value['option_id']))->getField('answer');
			}else{
				$log[$key]['o_name'] = '超时错误';
			}
			
		}
		
		$this->assign('log',$log);
		$this->assign('page',$Page->show());
		$this->display();
	}
}


?>
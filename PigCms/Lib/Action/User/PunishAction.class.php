<?php
class PunishAction extends UserAction{
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('Punish');
	}

	
	public function index(){
		$keyword= $this->_post('search','trim');
		$where 	= array('token'=>$this->token);
		if(!empty($keyword)){
			$where['title|keyword'] = array('like','%'.$keyword.'%');
		}
		$count	= M('Punish')->where($where)->count();
		$Page   = new Page($count,15);
		$list 	= M('Punish')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$this->assign('list',$list);
		$this->assign('page',$Page->show());

		$this->display();
	}
	
	
	public function set(){
		$keyword_db 	= M('Keyword');
		$id 	= $this->_get('id','intval');
		$where 	= array('token'=>$this->token,'id'=>$id);
		$info 	= M('Punish')->where($where)->find();

		if(IS_POST){
			if(D('Punish')->create()){
				//添加
				if(empty($info)){
					$_POST['token'] 	= $this->token;
					$id = M('Punish')->add($_POST);
					if($id){
						$keyword['pid']		= $id;
						$keyword['module']	= 'Punish';
						$keyword['token']	= $this->token;
						$keyword['keyword']	= $this->_post('keyword','trim');
						$keyword_db->add($keyword);
					}
					if($id){
						$this->_default_item($id);
					}

					$this->success('添加成功',U('Punish/index',array('token'=>$this->token)));
					
					//修改
				}else{
					
					$where 	= array('token'=>$this->token,'id'=>$this->_post('id','intval'));
					$offset = M('Punish')->where($where)->save($_POST);//更新设置表
					if($offset){
						$keyword['pid']		= $this->_POST('id','intval');
						$keyword['module']	= 'Punish';
						$keyword['token']	= $this->token;
						$keyword['keyword']	= $this->_post('keyword','trim');
						$keyword_db->where(array('token'=>$this->token,'pid'=>$this->_post('id','intval'),'module'=>'Problem'))->save($keyword);
					}

					$this->success('修改成功',U('Punish/index',array('token'=>$this->token)));
				}

			}else{
			
				$this->error(D('Punish')->getError());
			}
			
		}else{
			
			$this->assign('set',$info);
			$this->display();
		}

	}

	public function _default_item($id){
		$item 	=	 array(
			'喝光你左右两边人杯内的酒',
			'你指定两个人喝完一整杯交杯酒',
			'选择玩真心话大冒险或连干两杯',
			'恭喜你再来一盘',
			'除你以外，所有人喝完一整杯',
			'神的眷顾完全通过',
			'连干两杯',
			'喝完一杯',
			'选择一个异性和自己喝完一整杯交杯酒',
			'指定一个人选择玩真心话大冒险或连干两整杯',
			'喝完半杯',
			'舔酒杯10下，并用自己的手机拍照发朋友圈',
		);
		$where	= array('token'=>$this->token,'pid'=>$id);
		$info 	= M('punish_item')->where($where)->find();
		if(empty($info)){
			$count 	= count($item);
			for ($i=0; $i < $count; $i++) { 
				M('punish_item')->add(array('pid'=>$id,'token'=>$this->token,'name'=>$item[$i],'is_show'=>'1'));
			}
		}
	}
	
	public  function item(){
		$pid 	= $this->_get('id','intval');
		$where 	= array('token'=>$this->token,'pid'=>$pid);
		
		$count	= M('Punish_item')->where($where)->count();
		$Page   = new Page($count,15);
		$list 	= M('Punish_item')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		

		$this->assign('list',$list);
		$this->assign('page',$Page->show());
		$this->assign('pid',$pid);
		$this->display();
	}
	
	
	public function item_set(){
		$id 	= $this->_get('id','intval');
		$pid 	= $this->_get('pid','intval');
		$where 	= array('token'=>$this->token,'id'=>$id,'pid'=>$pid);
		$info 	= M('Punish_item')->where($where)->find();

		if(IS_POST){
			if(empty($_POST['name'])){
				$this->error('选项名称不能为空');
			}
			
			if(empty($info)){
				
				$_POST['token'] 	= $this->token;
				$_POST['pid'] 		= $pid;
				M('Punish_item')->add($_POST);
				$this->success('添加成功',U('Punish/item',array('token'=>$this->token,'id'=>$pid)));
				//修改
			}else{
						
				$where 	= array('token'=>$this->token,'id'=>$this->_post('id','intval'));
				M('Punish_item')->where($where)->save($_POST);//更新设置表
				if(!$this->_get('ajax')){
					$this->success('修改成功',U('Punish/item',array('token'=>$this->token,'id'=>$pid)));
				}
			}
		}else{
				
			$this->assign('set',$info);
			$this->assign('pid',$pid);
			$this->display();
		}
	
	}	
	public function item_del(){
		$id 	= $this->_get('id','intval');
		$pid 	= $this->_get('pid','intval');
		
		$where 	= array('token'=>$this->token,'pid'=>$pid,'id'=>$id);
			
		if(M('Punish_item')->where($where)->delete()){
			$this->success('删除成功',U('Punish/item',array('token'=>$this->token,'id'=>$pid)));	
		}
		
	}	
	
	
	public function del(){
		$id 	= $this->_get('id','intval');
		$where 	= array('token'=>$this->token,'id'=>$id);
		
		if(M('Punish')->where($where)->delete()){
			
			M('Punish_item')->where(array('token'=>$this->token,'pid'=>$id))->delete();
			M('Keyword')->where(array('token'=>$this->token,'pid'=>$id,'module'=>'Punish'))->delete();
			
			$this->success('删除成功',U('Punish/index',array('token'=>$this->token)));
		}
	}
	
	
}

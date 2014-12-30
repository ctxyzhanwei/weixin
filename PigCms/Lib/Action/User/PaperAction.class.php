<?php
class PaperAction extends UserAction{
	public $token;
	public $paper_model;
	public function _initialize() {
		parent::_initialize();
		$token_open=M('token_open')->field('queryname')->where(array('token'=>session('token')))->find();
		if(!strpos($token_open['queryname'],'Paper')){
            	$this->error('您还开启该模块的使用权,请到功能模块中添加',U('Function/index',array('token'=>session('token'),'id'=>session('wxid'))));
		}
		$this->paper_model=M('Paper');
		$this->token=session('token');
		$this->assign('token',$this->token);
		$this->assign('module','Paper');
	}
	public function index(){
		$where=array('token'=>$this->token);
		if(IS_POST){
			$key = $this->_post('searchkey');
			if(empty($key)){
				$this->error("关键词不能为空");
			}

			$where['title|message|address'] = array('like',"%$key%");
			$list = $this->paper_model->where($where)->order('time DESC')->select();
			$count      = $this->paper_model->where($where)->count();
			$Page       = new Page($count,20);
			$show       = $Page->show();
			$this->assign('key',$key);
		}else {
			$count      = $this->paper_model->where($where)->count();
			
			$Page       = new Page($count,20);
			$show       = $Page->show();
			$list=$this->paper_model->where($where)->order('time DESC')->select();
		}
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
		
	}
	public function add(){ 
		if(IS_POST){
			$_POST['token'] = $this->token;
			$this->all_insert();
		}else{
			$set=array();
			$set['time']=date('Y-m-d');
			$this->assign('set',$set);
			$this->display('set');
		}
	}
	public function set(){
        $id = intval($this->_get('id')); 
		$checkdata = $this->paper_model->where(array('id'=>$id))->find();
		if(empty($checkdata)||$checkdata['token']!=$this->token){
            $this->error("没有相应记录.您现在可以添加.",U('Paper/add'));
        }
		if(IS_POST){ 
            $where=array('id'=>$this->_post('id'),'token'=>$this->token);
			$check=$this->paper_model->where($where)->find();
			if($check==false)$this->error('非法操作');
			if($this->paper_model->create()){
				if($this->paper_model->where($where)->save($_POST)){
					$this->success('修改成功',U('Paper/index',array('token'=>$this->token)));
					$keyword_model=M('Keyword');
					$keyword_model->where(array('token'=>$this->token,'pid'=>$id,'module'=>'Paper'))->save(array('keyword'=>$_POST['keyword']));
				}else{
					$this->error('操作失败');
				}
			}else{
				$this->error($this->paper_model->getError());
			}
		}else{
			$this->assign('isUpdate',1);
			
			$this->assign('set',$checkdata);
			$this->display();	
		
		}
	}
	public function del(){
		if($this->_get('token')!=$this->token){$this->error('非法操作');}
        $id = intval($this->_get('id'));
        if(IS_GET){                              
            $where=array('id'=>$id,'token'=>$this->token);
            $check=$this->paper_model->where($where)->find();
            if($check==false)   $this->error('非法操作');

            $back=$this->paper_model->where($where)->delete();
            if($back==true){
                $this->success('操作成功',U('Paper/index',array('token'=>$this->token)));
            }else{
                 $this->error('服务器繁忙,请稍后再试',U('Paper/index',array('token'=>$this->token)));
            }
        }        
	}

}


?>
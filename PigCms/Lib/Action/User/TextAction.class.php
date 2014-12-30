<?php
/**
 *文本回复
**/
class TextAction extends UserAction{
	public function index(){
		$db=D('Text');
		$where['uid']=session('uid');
		$where['token']=session('token');
		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$info=$db->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$page->show());
		$this->assign('info',$info);
		$this->display();
	}
	public function add(){
		$this->display();
	}
	public function edit(){
		$where['id']=$this->_get('id','intval');
		$where['uid']=session('uid');
		$where['token']=session('token');
		$res=D('Text')->where($where)->find();
		$this->assign('info',$res);
		$this->display();
	}
	public function del(){
		$where['id']=$this->_get('id','intval');
		$where['uid']=session('uid');
		if(D(MODULE_NAME)->where($where)->delete()){

			$this->handleKeyword(intval($_GET['id']),'Text','','',1);

			$this->success('操作成功',U(MODULE_NAME.'/index'));
		}else{
			$this->error('操作失败',U(MODULE_NAME.'/index'));
		}
	}
	public function insert(){
		
		$this->all_insert();
	}
	public function upsave(){
		$this->all_save();
	}
	public function clearKeywrods(){
		$keyword_model=M('Keyword');
		$count=$keyword_model->count();
		$keywords=$keyword_model->select();
		$i=intval($_GET['i']);
		$step=5;
		if ($i<$count){
			for ($j=0;$j<$step;$j++){
				$index=$i+$j;
				if ($keywords[$index]){
					$module_db=M($keywords[$index]['module']);
					if (!$module_db->where(array('id'=>$keywords[$index]['pid']))->find()){
						$keyword_model->where(array('id'=>$keywords[$index]['id']))->save(array('keyword'=>''));
					}
					
				}
			}
			$next=$i+$step;
			$this->success('正在刷新关键词 '.$i.'/'.$count,'/index.php?g=User&m=Text&a=clearKeywrods&i='.$next);
		}else {
			exit('操作成功了');
		}
	}
}
?>
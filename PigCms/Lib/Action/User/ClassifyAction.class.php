<?php
/**
 *语音回复
**/
class ClassifyAction extends UserAction{
	public $fid;
	public function _initialize() {
		parent::_initialize();
		$this->fid=intval($_GET['fid']);
		$this->assign('fid',$this->fid);
		if ($this->fid){
			$thisClassify=M('Classify')->find($this->fid);
			$this->assign('thisClassify',$thisClassify);
		}
	}
	public function index(){
		$db=D('Classify');
		$where['token']=session('token');
		$where['fid']=intval($_GET['fid']);
		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$info=$db->where($where)->order('sorts desc')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$page->show());
		$this->assign('info',$info);
		$this->display();
	}
	//	
	public function add(){
		include('./PigCms/Lib/ORG/index.Tpl.php');
		include('./PigCms/Lib/ORG/cont.Tpl.php');

		$this->assign('tpl',$tpl);
		$this->assign('contTpl',$contTpl);
		
		$queryname=M('token_open')->where(array('token'=>$this->token))->getField('queryname');
		if(strpos(strtolower($queryname),strtolower('website')) !== false){
			$this->assign('has_website',true);
		}
			
		$this->display();
	}
	//
	public function edit(){
		$id=$this->_get('id','intval');
		$info=M('Classify')->find($id);
		include('./PigCms/Lib/ORG/index.Tpl.php');
		include('./PigCms/Lib/ORG/cont.Tpl.php');
		
		foreach($tpl as $k=>$v){
			if($v['tpltypeid'] == $info['tpid']){
				$info['tplview'] = $v['tplview'];
			}
		}

				
		foreach($contTpl as $key=>$val){
			if($val['tpltypeid'] == $info['conttpid']){
				$info['tplview2'] = $val['tplview'];
			}
		}

		$this->assign('contTpl',$contTpl);
		$this->assign('tpl',$tpl);
		$this->assign('info',$info);
		$this->display();
	}
	
	public function del(){
		$where['id']=$this->_get('id','intval');
		$where['uid']=session('uid');
		if(D(MODULE_NAME)->where($where)->delete()){
			$fidwhere['fid']=intval($where['id']);
			D(MODULE_NAME)->where($fidwhere)->delete();
			$this->success('操作成功',U(MODULE_NAME.'/index',array('fid'=>$_GET['fid'])));
		}else{
			$this->error('操作失败',U(MODULE_NAME.'/index',array('fid'=>$_GET['fid'])));
		}
	}
	//
	public function insert(){
	     $name='Classify';
		$db=D($name);
		$fid = $this->_post('fid','intval');
		$_POST['info'] = str_replace('&quot;','',$_POST['info']);
		if($fid != ''){
			$f = $db->field('path')->where("id = $fid")->find();
			$_POST['path'] = $f['path'].'-'.$fid;
				
		}
		if($_POST['pc_show']){
			$database_pc_news_category = D('Pc_news_category');
			$data_pc_news_category['cat_name'] = $_POST['name'];
			$data_pc_news_category['token'] = session('token');
			$_POST['pc_cat_id'] = $database_pc_news_category->data($data_pc_news_category)->add();
		}
		if($db->create()===false){
			$this->error($db->getError());
		}else{
			
			$id=$db->add();
			if($id){
				$this->success('操作成功',U(MODULE_NAME.'/index',array('fid'=>$_POST['fid'])));
			}else{
				$this->error('操作失败',U(MODULE_NAME.'/index',array('fid'=>$_POST['fid'])));
			}
		}
	}
	public function upsave(){
		$_POST['info'] = str_replace('&quot;','',$_POST['info']);
		$fid = $this->_post('fid','intval');
		if($_POST['pc_show']){
			$_POST['pc_cat_id'] = 0;
		}
		if($fid == ''){
			$this->all_save();
		}else{
			$this->all_save('','/index?fid='.$fid);
		}
	}
	
	
	public function chooseTpl(){
	
		include('./PigCms/Lib/ORG/index.Tpl.php');
		include('./PigCms/Lib/ORG/cont.Tpl.php');
		$tpl = array_reverse($tpl);
$filter = $this->_get('filter');
		if(isset($filter) && $filter !== 'all' && $filter != 'mix'){
			foreach ($tpl as $kk => $vv){
				if(strpos($vv['attr'],$filter)){
					$filterTpl[$kk] = $vv;
				}
			}
			$tpl = $filterTpl;
		}
		$contTpl = array_reverse($contTpl);
		$tpid = $this->_get('tpid','intval');

				foreach($tpl as $k=>$v){
					$sort[$k] = $v['sort'];
					$tpltypeid[$k] = $v['tpltypeid'];
					
					if($v['tpltypeid'] == $tpid){
						$info['tplview'] = $v['tplview'];
					}
				}
			//array_multisort($sort, SORT_DESC , $tpltypeid , SORT_DESC ,$tpl);
				
			foreach($contTpl as $key=>$val){
				if($val['tpltypeid'] == $tpid){
					$info['tplview2'] = $val['tplview'];
				}
			}
				$this->assign('info',$info);
		

		
		
		$this->assign('contTpl',$contTpl);
		$this->assign('tpl',$tpl);

		$this->display();
	}
	
	public function changeClassifyTpl(){
	
		$tid = $this->_post('tid','intval');
		$cid = $this->_post('cid','intval');
		M('Classify')->where(array('token'=>$this->token,'id'=>$cid))->setField('tpid',$tid);
		echo 200;
	}
	
	public function changeClassifyContTpl(){
	
		$tid = $this->_post('tid','intval');
		$cid = $this->_post('cid','intval');
		M('Classify')->where(array('token'=>$this->token,'id'=>$cid))->setField('conttpid',$tid);
		echo 200;
	
	}
	public function flash(){
		$tip=$this->_get('tip','intval');
		$id=$this->_get('id','intval');
		$fid=$this->_get('fid','intval');
		if(empty($fid)){
			$fid=0;
		}
		$token=$this->token;

		$fl=M('Classify')->where(array('token'=>$this->token,'id'=>$id,'fid'=>$fid))->find();
		$db=D('Flash');

		$where['uid']=session('uid');
		$where['token']=session('token');
		$where['tip']=$tip;
		$where['did']=$id;
		$where['fid']=$fid;

		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$info=$db->where($where)->limit($page->firstRow.','.$page->listRows)->order('id DESC')->select();
		$this->assign('page',$page->show());
		$this->assign('fl',$fl);
		$this->assign('info',$info);
		$this->assign('id',$id);
		$this->assign('fid',$fid);
		$this->assign('tip',$tip);
		$this->display();
	}

	public function addflash(){
		$tip=$this->_get('tip','intval');
		$id=$this->_get('id','intval');
		$fid=$this->_get('fid','intval');
		$token=$this->token;
		$fl=M('Classify')->where(array('token'=>$this->token,'id'=>$id))->getField('name');
		$this->assign('fl',$fl);
		$this->assign('tip',$tip);
		$this->assign('id',$id);
		$this->assign('fid',$fid);
		$this->display();
	}

	public function inserts(){
		$flash=D('Flash');
		$arr=array();
		$arr['token']=$this->token;
		$arr['img']=$this->_post('img');
		$arr['url']=$this->_post('url');
		$arr['info']=$this->_post('info');
		$arr['tip']=$this->_get('tip','intval');
		$arr['did']=$this->_get('id','intval');
		$arr['fid']=$this->_get('fid','intval');

		if(empty($_POST['img'])){

			$this->error('请添加幻灯片图片');
		}
		if($flash->add($arr)){
			$this->success('操作成功',U(MODULE_NAME.'/flash',array('tip'=>$this->_GET('tip','intval'),'id'=>$this->_get('id'),'fid'=>$this->_get('fid'))));
		}else{
			$this->error('操作失败');
		}
		
	}

	public function editflash(){
		$tip=$this->_get('tip','intval');
		$where['id']=$this->_get('id','intval');
		$where['uid']=session('uid');
		$res=D('Flash')->where($where)->find();
		$this->assign('info',$res);

		$this->assign('tip',$tip);
		$this->assign('id',$this->_get('id','intval'));
		$this->display();
	}

	public function delflash(){
		$where['id']=$this->_get('id','intval');
		$where['token']=$this->token;
		if(D('Flash')->where($where)->delete()){
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}

	public function updeit(){
		$flash=D('Flash');
		$id=$this->_get('id','intval');
		$tip=$this->_get('tip','intval');
		$list=$flash->where(array('id'=>$id))->find();
		$arr=array();
		$arr['img']=$this->_post('img');
		$arr['url']=$this->_post('url');
		$arr['info']=$this->_post('info');
		$data=$flash->where(array('id'=>$id))->save($arr);
		if($data){
			$this->success('操作成功',U(MODULE_NAME.'/flash',array('tip'=>$tip,'id'=>$list['did'],'fid'=>$list['fid'])));
		}else{
			$this->error('操作失败');
		}
		
	}
	
	public function essay(){
		$token=$this->token;
		$classid=$this->_get('id','intval');
		$name=M('Classify')->where(array('id'=>$classid,'token'=>$token))->getField('name');
		$essay=M('Img')->where(array('classid'=>$classid,'token'=>$token))->order('usort DESC')->select();
		$this->assign('info',$essay);
		$this->assign('name',$name);
		$this->display();
	}
	
	public function editUsort(){
		$token = $this->_post('token',"htmlspecialchars");
		unset($_POST['__hash__']);
		foreach($_POST as $k=>$v){
			$k = str_replace('usort','',$k);
			$data[$k]=$v;
			M('Img')->where(array('token'=>$token,'id'=>$k))->setField('usort',$v);

		}

		$this->success('保存成功');
	}
}
?>
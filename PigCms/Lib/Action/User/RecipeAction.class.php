<?php
class RecipeAction extends UserAction{ 
	public function _initialize() { 
		parent::_initialize(); 
		$type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING); 
		$this->assign('type',$type); 
	} 
	public function index(){ 
		$data = D('recipe'); 
		$type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING); 
		$where = array('token'=>session('token'),'type'=>$type); 
		$count = $data->where($where)->count(); 
		$Page = new Page($count,20); 
		$show = $Page->show(); 
		$recipe = $data->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select(); 
		$this->assign('page',$show); 
		$this->assign('recipe',$recipe); 
		$this->display(); 
	} 
	public function index_add(){ 
		$t_recipe = D('recipe'); 
		$id = filter_var($this->_get('id'),FILTER_VALIDATE_INT); 
		$type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING); 
		$where = array('token'=>session('token'),'id'=>$id,'type'=>$type); 
		$recipe = $t_recipe->where($where)->find(); 
	if(IS_POST){ 
		$filters = array( 'keyword'=>array( 'filter'=>FILTER_SANITIZE_STRIPPED, 'flags'=>FILTER_SANITIZE_STRING, 'options'=>FILTER_SANITIZE_ENCODED ), 'title'=>array( 'filter'=>FILTER_SANITIZE_STRIPPED, 'flags'=>FILTER_SANITIZE_STRING, 'options'=>FILTER_SANITIZE_ENCODED),); 
		$_POST['begintime'] = strtotime(filter_var($this->_post('begintime'),FILTER_SANITIZE_STRING)); 
		$_POST['endtime'] = strtotime(filter_var($this->_post('endtime'),FILTER_SANITIZE_STRING)); 
		$_POST['type'] = filter_var($this->_post('type'),FILTER_SANITIZE_STRING); 
	if($_POST['begintime'] > $_POST['endtime']){ exit($this->error('您好,开始时间不能大于结束时间.',U("Recipe/index",array('token'=>session('token'),'type'=>$type)))); } $check = filter_var_array($_POST,$filters); 
	if(!$check){ exit($this->error('您好,包含敏感字符,或者是不允许字串!',U("Recipe/index",array('token'=>session('token'),'type'=>$type)))); } $_POST['monday'] = serialize($_REQUEST['monday']); 
	    $_POST['tuesday'] = serialize($_REQUEST['tuesday']); 
	    $_POST['wednesday'] = serialize($_REQUEST['wednesday']); 
	    $_POST['thursday'] = serialize($_REQUEST['thursday']); 
	    $_POST['friday'] = serialize($_REQUEST['friday']); 
	    $_POST['saturday'] = serialize($_REQUEST['saturday']); 
	    $_POST['sunday'] = serialize($_REQUEST['sunday']); 
	    $_POST['token'] = session('token'); 
	if(!$t_recipe->create()){ exit($this->error($t_recipe->getError()
		)
	); 
	  }else{ 
		$id = filter_var($this->_post('id'),FILTER_VALIDATE_INT); 
		$status = filter_var($this->_post('status'),FILTER_SANITIZE_STRING); 
	if('edit'==$status && $id != ''){ $o = $t_recipe->where(array('id'=>$id, 'token'=>session('token')))->save($_POST); 
	if($o){ $data2['keyword'] = filter_var($this->_post('keyword'),FILTER_SANITIZE_STRING); M('Keyword')->where(array('pid'=>$id,'token'=>session('token'),'module'=>'Recipe'))->data($data2)->save(); 
	   exit($this->success('修改成功',U("Recipe/index",array('token'=>session('token'),'type'=>$_POST['type']))
		 )
	); 
	  }else{ 
	   exit($this->error('修改失败',U("Recipe/index",array('token'=>session('token'),'type'=>$_POST['type']))
	    )
	  ); 
	 } 
  }else{ 
	  if($id=$t_recipe->data($_POST)->add()){ 
		  $data1['pid']=$id; 
		  $data1['module']='Recipe'; 
		  $data1['token']=session('token'); 
		  $data1['keyword']=filter_var($this->_post('keyword'),FILTER_SANITIZE_STRING); 
		    M('Keyword')->add($data1); 
		  $this->success('添加成功',U("Recipe/index",array('token'=>session('token'),'type'=>$_POST['type'])));
		  exit; 
	}else{ 
		exit($this->error('务器繁忙,添加失败,请稍候再试',U("Recipe/index",array('token'=>session('token'),'type'=>$_POST['type'])))); 
		 } 
	   } 
	  } 
	} 
		$this->assign('recipe',$recipe); 
		$this->display(); 
		} 
	public function index_del(){ 
		$type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING); 
		$id = filter_var($this->_get('id'),FILTER_VALIDATE_INT); 
		$t_recipe = M('recipe'); 
		$find = array('id'=>$id,'type'=>$type,'token'=>session('token')); 
		$result = $t_recipe->where($find)->find(); 
	if($result){ 
		$t_recipe->where(array('id'=>$result['id'],'type'=>$result['type'],'token'=>session('token')))->delete(); 
		exit($this->success('删除成功',U("Recipe/index",array('token'=>session('token'),'type'=>$result['type']))
		)
	 ); 
   }else{ exit($this->error('非法操作,请稍候再试',U("Recipe/index",array('token'=>session('token'),'type'=>$type))));} 
     } 
   }
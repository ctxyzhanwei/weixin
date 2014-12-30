<?php
class VcardAction extends UserAction{
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('Vcard');
	}
	public function index(){
		$where['token'] = session('token');
		$Data = M('VcardList');
		$count = $Data->where($where)->count();
		$page = new Page($count,25);
		$info = $Data->where($where)->order('sort DESC')->limit($page->firstRow.','.$page->listRows)->select();
		$this->info = $info;
		$this->page = $page->show();
		$this->display();
	}
	
	public function add(){
		if(IS_POST){
			$data['name'] 	= strip_tags($_POST['name']);
			$data['token'] 	= session('token');
			$data['image'] 	= strip_tags($_POST['image']);
			$data['tel']	= strip_tags($_POST['tel']);
			$data['mobile']	= strip_tags($_POST['mobile']);
			$data['work']	= strip_tags($_POST['work']);
			$data['email']	= strip_tags($_POST['email']);
			$data['qq']	= strip_tags($_POST['qq']);
			if(empty($data['name'])) $this->error('姓名不能够为空!');
			$insert = M('VcardList')->add($data);
			if($insert > 0){
				$this->success('名片添加成功!');
			}else{
				$this->error('名片添加失败!');
			}
		}else{
			$this->display();
		}
	}
	
	public function edit(){
		$id = $this->_get('id');
		$where['id'] = $id;
		$where['token'] = session('token');
		if(IS_POST){
			$data['name'] 	= strip_tags($_POST['name']);
			$data['image'] 	= strip_tags($_POST['image']);
			$data['tel']	= strip_tags($_POST['tel']);
			$data['mobile']	= strip_tags($_POST['mobile']);
			$data['work']	= strip_tags($_POST['work']);
			$data['email']	= strip_tags($_POST['email']);
			$data['qq']	= strip_tags($_POST['qq']);
			if(empty($data['name'])) $this->error('姓名不能够为空!');
			$up = M('VcardList')->where($where)->save($data);
			if($up){
				$this->success('名片更新成功!');
			}else{
				$this->error('名片更新失败!');
			}
		}else{
			$info = M('VcardList')->where($where)->find();
			$this->info = $info;
			$this->display();
		}
	}
	
	public function delete(){
		$where['id'] = $this->_get('id');
		$where['token'] = session('token');
		$info = M('VcardList')->where($where)->delete();
		if($info){
			$this->success('名片删除成功!');
		}else{
			$this->error('名片删除失败!');
		}
	}
	
	public function company(){
		$where['token'] = session('token');
		$Cdata = M('Vcard');
		$info = $Cdata->where($where)->find();
		$this->info = $info;
		if(IS_POST){
			$where['token'] = session('token');
			$data['company'] = strip_tags($_POST['company']);
			$data['company_tel'] = strip_tags($_POST['company_tel']);
			$data['baiduapi'] = strip_tags($_POST['baiduapi']);
			$data['address'] = strip_tags($_POST['address']);
			$data['info'] = strip_tags($_POST['info']);
			$data['fax'] = strip_tags($_POST['fax']);
			
			//$res = M('Vcard')->where($where)->find();
			if($info){
				$result = M('Vcard')->where($where)->save($data);
				if($result){
					$this->success('公司信息更新成功!');
				}else{
					$this->error('服务器繁忙 更新失败!');
				}
			}else{
				$data['token'] = session('token');
				$insert = M('Vcard')->add($data);
				if($insert > 0){
					$this->success('公司信息添加成功!');
				}else{
					$this->error('公司信息添加失败!');
				}
			}
		}else{
			$this->display();
		}
	}
}
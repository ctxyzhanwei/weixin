<?php
class PhotoAction extends WapAction{
	public $token;
	public function __construct(){
		parent::_initialize();
	}
	public function index(){
		$this->token=$this->_get('token');
		$reply_info_db=M('Reply_info');
		$config=$reply_info_db->where(array('token'=>$this->token,'infotype'=>'album'))->find();
		if ($config){
			$headpic=$config['picurl'];
		}else {
			$headpic='/tpl/Wap/default/common/css/Photo/banner.jpg';
		}
		$this->assign('headpic',$headpic);
		//
		$token=$this->_get('token');
		if($token==false){
			echo '数据不存在';exit;
		}
		$photo=M('Photo')->where(array('token'=>$token,'status'=>1))->order('id desc')->select();
		if($photo==false){ }
		$this->assign('photo',$photo);
		$this->display();
	}
	public function plist(){
		$this->token=$this->_get('token');
		$reply_info_db=M('Reply_info');
		$config=$reply_info_db->where(array('token'=>$this->token,'infotype'=>'album'))->find();
		if ($config){
			$headpic=$config['picurl'];
		}else {
			$headpic='/tpl/Wap/default/common/css/Photo/banner.jpg';
		}
		$this->assign('headpic',$headpic);
		//
		$token=$this->_get('token');
		if($token==false){
			echo '数据不存在';exit;
		}
		$info=M('Photo')->field('title')->where(array('token'=>$token,'id'=>$this->_get('id')))->find();
		$photo_list=M('Photo_list')->where(array('token'=>$token,'pid'=>$this->_get('id'),'status'=>1))->order('sort desc')->select();
		//dump($photo);
		$this->assign('info',$info);
		$this->assign('photo',$photo_list);
		$this->display();
	}
}
?>
<?php
class InviteReply
{	
	public $item;
	public $yid;
	public $siteUrl;
	public $token;
	public function __construct($token,$yid,$data,$siteUrl)
	{
		$this->item=M('Invite')->where(array('id'=>$data['pid']))->find();
		$this->yid=$yid;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
	}
	public function index(){
		$thisItem=$this->item;
		return array(array(array($thisItem['title'],$thisItem['content'],$thisItem['replypic'],$this->siteUrl.U('Wap/Invite/index',array('yid'=>$thisItem['id'],'token'=>$this->token,'wecha_id'=>$this->yid)))),'news');
	}
}
?>


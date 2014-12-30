<?php
class ShakeReply
{	
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token,$wechat_id,$data,$siteUrl)
	{
		$this->item=M('Shake')->where(array('id'=>$data['pid']))->find();
		$this->wechat_id=$wechat_id;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
	}
	public function index(){
		$thisItem=$this->item;
	
		if (!$thisItem['isopen']){
			return array('摇一摇活动已关闭','text');
		}else {
			$actid=$thisItem['id'];
			$acttype=2;
			$memberRecord=M('Wall_member')->where(array('act_id'=>$actid,'act_type'=>$acttype,'wecha_id'=>$this->wechat_id))->find();
			if (!$memberRecord){
				return array(array(array($thisItem['title'],'请点击这里完善信息后再参加此活动哦',$thisItem['thumb'],$this->siteUrl.U('Wap/Scene_member/index',array('token'=>$this->token,'wecha_id'=>$this->wechat_id,'act_type'=>$acttype,'id'=>$actid,'name'=>'shake')))),'news');
			}else {
				return array(array(array($thisItem['title'],'请点击这里确认个人信息',$thisItem['thumb'],$this->siteUrl.U('Wap/Scene_member/index',array('token'=>$this->token,'wecha_id'=>$this->wechat_id,'act_type'=>$acttype,'id'=>$actid,'name'=>'shake')))),'news');
				
			}
		}
	}
}
?>


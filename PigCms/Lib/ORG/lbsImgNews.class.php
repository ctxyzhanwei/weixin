<?php
final class lbsImgNews {
	public $token;
	public $wecha_id;
	public $siteUrl;
	public function __construct($token,$wecha_id,$siteUrl) {
		$this->token=$token;
		$this->wecha_id=$wecha_id;
		$this->siteUrl=$siteUrl;
	}
	public function news($lat,$lon){
		$return=array();
		$where=array();
		$where['token']=$this->token;
		$where['latitude']=array('gt','0');
		$news2=M('Img')->where($where)->order('ACOS(SIN(('.$lat.' * 3.1415) / 180 ) *SIN((latitude * 3.1415) / 180 ) +COS(('.$lat.' * 3.1415) / 180 ) * COS((latitude * 3.1415) / 180 ) *COS(('.$lon.' * 3.1415) / 180 - (longitude * 3.1415) / 180 ) ) * 6380 ASC')->limit(10)->select();
		if (!$news2){
			return array('没有对应的图文消息','text');
		}
		$ids=array();
		//img库中查出图文消息
		foreach($news2 as $keya=>$infot){
			//distance KM
			$distance=ACOS(SIN(($lat * 3.1415) / 180 ) *SIN(($infot['latitude'] * 3.1415) / 180 ) +COS(($lat * 3.1415) / 180 ) * COS(($infot['latitude'] * 3.1415) / 180 ) *COS(($lon * 3.1415) / 180 - ($infot['longitude'] * 3.1415) / 180 ) ) * 6380;
			//
			if($infot['url']!=false){
				//处理外链
				if(!(strpos($infot['url'], 'http') === FALSE)){
					$url=$this->getFuncLink(html_entity_decode($infot['url']));
				}else {//内部模块的外链
					$url=$this->getFuncLink($infot['url']);
				}
			}else{
				$url=rtrim($this->siteUrl,'/').U('Wap/Index/content',array('token'=>$this->token,'id'=>$infot['id'],'wecha_id'=>$this->wecha_id));
			}
			array_push($ids,$news2['id']);
			$return[]=array($infot['title'],$this->handleIntro($infot['text']),$infot['pic'],$url);
		}
		//点击数处理
		if ($back){
			M('Img')->where(array('id'=>array('in',$ids)))->setInc('click');
		}
		return array($return,'news');
	}
	//
	public function getFuncLink($u){
		$urlInfos=explode(' ',$u);
		$url=str_replace(array('{wechat_id}','{siteUrl}','&amp;'),array($this->wecha_id,$this->siteUrl,'&'),$urlInfos[0]);
		return $url;
	}
	public function handleIntro($str){
		$search=array('&quot;','&nbsp;');
		$replace=array('"','');
		return str_replace($search,$replace,$str);
	}
}
?>
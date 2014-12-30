<?php
class photoPrint {

	public $wxid;
	public $openid;
	public $serverUrl;
	public $key;
	public $topdomain;
	public $token;
	public function __construct($wxuser,$openid){
		$this->wxid=$wxuser['wxid'];
		$this->token=$wxuser['token'];
		$this->openid=$openid;
		$this->serverUrl='http://up.pigcms.cn/';
		$this->key=trim(C('server_key'));
		$this->topdomain=trim(C('server_topdomain'));
		if (!$this->topdomain){
			$this->topdomain=$this->getTopDomain();
		}
	}
	public function initUser(){
		$url=$this->serverUrl.'server.php?m=server&c=photoPrint&a=initUser&key='.$this->key.'&domain='.$this->topdomain.'&openid='.$this->openid.'&wxid='.$this->wxid;
		$rt=$this->curlGet($url);
	}
	public function uploadPic($picUrl){
		$downurl=urlencode($picUrl);
		$url=$this->serverUrl.'server.php?m=server&c=photoPrint&a=uploadPic&key='.$this->key.'&domain='.$this->topdomain.'&openid='.$this->openid.'&wxid='.$this->wxid.'&picUrl='.$downurl;
		$rt=$this->curlGet($url);
		//userinfo中设置一个字段photoprintopen.如需彻底进入打印模式请将 userinfo表中（根据$this->openid和$this->token 查询）比如photoprintopen 字段设为1，。用户输入"quit"可以退出打印模式
		//M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$this->openid))->save(array('photoprintopen'=>1));
		//
		return array($rt,'text');
		//$arr=json_decode($rt,1);
		/*
		if ($arr['msg']){
			return array($arr['msg'],'text');
		}
		return $arr;
		*/
	}
	//userinfo中photoprintopen=1 的时候，负责应答粉丝的输入
	public function reply($data){
		return array('text something......','text');
	}
	function curlGet($url){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
		
	}
	
	function getTopDomain(){
		$host=$_SERVER['HTTP_HOST'];
		$host=strtolower($host);
		if(strpos($host,'/')!==false){
			$parse = @parse_url($host);
			$host = $parse['host'];
		}
		$topleveldomaindb=array('com','edu','gov','int','mil','net','org','biz','info','pro','name','museum','coop','aero','xxx','idv','mobi','cc','me');
		$str='';
		foreach($topleveldomaindb as $v){
			$str.=($str ? '|' : '').$v;
		}
		$matchstr="[^\.]+\.(?:(".$str.")|\w{2}|((".$str.")\.\w{2}))$";
		if(preg_match("/".$matchstr."/ies",$host,$matchs)){
			$domain=$matchs['0'];
		}else{
			$domain=$host;
		}
		return $domain;
	}
}

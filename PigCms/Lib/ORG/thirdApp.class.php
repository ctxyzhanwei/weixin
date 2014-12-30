<?php
class thirdApp {
	public $name;
	public function __construct(){
		$this->serverUrl='http://up.pigcms.cn/';
		$this->key=trim(C('server_key'));
		$this->topdomain=trim(C('server_topdomain'));
		if (!$this->topdomain){
			$this->topdomain=$this->getTopDomain();
		}
		$this->token=$token;
	}
	public function modules(){
		return array('music','yinle','mengjian','kuaidi');
	}
	public function yinle($name){
		return $this->music($name);
	}
	public function music($name){
		$name=implode('',$name);
		$url=$this->serverUrl.'server.php?m=server&c=thirdApp&a=music&key='.$this->key.'&domain='.$this->topdomain.'&name='.$name;
		$rt=$this->curlGet($url);
		if (strpos($rt,'ttp')){
			return array(array($name,$name,$rt,$rt),'music');
		}else {
			return array('没找到相应音乐','text');
		}
	}
	public function mengjian($name){
		
		if(empty($name))return array('周公睡着了哦,无法解此梦,这年头神仙也偷懒','text');
		$name=implode('',$name);
		$url=$this->serverUrl.'server.php?m=server&c=thirdApp&a=dream&key='.$this->key.'&domain='.$this->topdomain.'&name=梦见'.$name;
		$rt=$this->curlGet($url);
		
		if ($rt){
			return array($rt,'text');
		}else {
			return array('周公睡着了啊,无法解此梦,这年头神仙也偷懒','text');
		}
	}
	
	public function kuaidi($param){
		if(empty($param[1]))return array('此单号暂无物流信息，请稍后再查','text');
		$url = 'http://m.kuaidi100.com/index_all.html?type='.strval($param[0]).'&postid='.trim($param[1]);
		$link = "<a href='{$url}'>您好，您查询的【".strval($param[0])."】单号为【".trim($param[1])."】请点击查看详情</a>";
		return array($link,'text');
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

<?php
class oa
{
	public $wxuser;
	public $topdomain;
	public function __construct($wxuser)
	{
		$this->wxuser=$wxuser;
		$this->topdomain=trim(C('server_topdomain'));
		if (!$this->topdomain){
			$this->topdomain=$this->getTopDomain();
		}
	}
	public function url(){
		return 'http://121.41.20.157/index.php?m=home&c=index&a=pigcmsSignin&domain='.$this->topdomain.'&id='.$this->wxuser['id'].'&key='.trim(C('server_key')).'&createtime='.$this->wxuser['createtime'];
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
?>


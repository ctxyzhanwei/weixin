<?php 
class WechatAddr 
{
	private $appId		= '';
	private $appSecret	= '';
	private $url 		= '';
	
	//构造函数获取access_token
	function __construct($appId,$appSecret){
		$this->appId		= $appId;
		$this->appSecret	= $appSecret;
		
	}

	public function addrSign(){
 		$url 	= "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		if(empty($_GET['code']) || (!empty($_GET['code']) && $_GET['state'] == 'oauth')){
			$url 		= $this->clearUrl($url);
			if(isset($_GET['wecha_id'])){
				$url .= '&wecha_id='.$_GET['wecha_id'];
			}	
			$authUrl 	= $this->get_auth_url($url);	
			header("Location: $authUrl"); 	
		}else{
			$this->url		= $url;
		}

		$tokenres		= $this->requestToken($_GET['code']);
		$accesstoken 	= $tokenres['access_token'];
		
		return $this->getSign($accesstoken); 
	}
	
	public function clearUrl($url){
		$param 	= explode('&', $url);
		for ($i=0,$count=count($param); $i < $count; $i++) {
			if(preg_match('/^(code=|state=|wecha_id=).*/', $param[$i])){
				unset($param[$i]);
			}
		}
		return join('&',$param);
	}
	
	public function getSign($accesstoken){
		$timeStamp = time();
		$nonceStr  = rand(100000,999999);
		$array 	= array(
				"appid" 		=> $this->appId,
				"url"			=> $this->url,
				"timestamp"		=> $timeStamp,
				"noncestr"		=> $nonceStr,
				"accesstoken"	=> $accesstoken,
		);
		
		ksort($array);
		$signPars	= '';
	
		foreach($array as $k => $v) {
			if("" != $v && "sign" != $k) {
				if($signPars == ''){
					$signPars .= $k . "=" . $v;
				}else{
					$signPars .=  "&". $k . "=" . $v;
				}
			}
		}
		
		$result = array(
			'appId' 	=> $this->appId,
			'url' 		=> $this->url,
			'timeStamp' => $timeStamp,
			'nonceStr'  => $nonceStr,
			'addrSign'  => SHA1($signPars),
		);
		
		return $result;
	}
	
	//获取token
	public function  requestToken($code){
		$tokenurl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appId."&secret=".$this->appSecret."&code=".$code."&grant_type=authorization_code";
		$tokenres = $this->https_request($tokenurl);
		return $tokenres;
	}
	
	//获取授权链接
	public function get_auth_url($redirect_uri = '', $scope = 'snsapi_base', $state = 'addr')
	{
		$redirect_uri = urlencode($redirect_uri);
		return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appId}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}&state={$state}#wechat_redirect";
	}
	
	//https请求（支持GET和POST）
	protected function https_request($url, $data = null)
	{
		$curl = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_SSLVERSION, 3);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		if (!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		$errorno= curl_errno($curl);
		if ($errorno) {
			return array('curl'=>false,'errorno'=>$errorno);
		}else{
			$res = json_decode($output,1);
			if (isset($res['errcode'])){
				return array('errcode'=>$res['errcode'],'errmsg'=>$res['errmsg']);
			}else{
				return $res;
			}
		}
		curl_close($curl);
	}
}

?>
<?php
class amap {
	public $key;
	public $tableid;
	public $sign;
	public function __construct(){
		$this->key='b6c2c19cf45cd3d2b82541bc857eed7c';
		$this->tableid='53d8516be4b0d455d8cae333';
		$this->sign='MIHGAgEAMIGoBgcqhkjOOAQBMIGcAkEA%2FKaCzo4Syrom78z3EQ5SbbB4sF7ey80etKII864WF64B81uRpH5t9jQTxeEu0ImbzRMqzVDZkVG9xD7nN1kuFwIVAJYu3cw2nLqOuyYO5rahJtk0bjjFAkBnhHGyepz0TukaScUUfbGpqvJE8FpDTWSGkx0tFCcbnjUDC3H9c9oXkGmzLik1Yw4cIGI1TQ2iCmxBblC%2BeUykBBYCFGioXwKhGpHHRs3qdWSGGE3r1yKs';
	}
	public function create($name,$location,$tel,$address){
		$url='http://yuntuapi.amap.com/datamanage/data/create';
		$data=array(
		'_name'=>$name,
		'_location'=>$location,
		'coordtype'=>'autonavi',
		'_address'=>$address,
		'tel'=>$tel,
		'domain'=>$_SERVER['HTTP_HOST'],
		);
		$arr=array(
		'key'=>$this->key,
		'tableid'=>$this->tableid,
		'loctype'=>1,
		'data'=>json_encode($data),
		//'sig'=>$this->sign
		);
		//
		$rt=$this->api_notice_increment($url,$arr);
		if (intval($rt['status'])==1) {
			return $rt['_id'];
		}
	}
	public function update($id,$name,$location,$tel,$address){
		$url='http://yuntuapi.amap.com/datamanage/data/update';
		$data=array(
		'_id'=>$id,
		'_name'=>$name,
		'_location'=>$location,
		'coordtype'=>'autonavi',
		'_address'=>$address,
		'tel'=>$tel,
		'domain'=>$_SERVER['HTTP_HOST'],
		);
		$arr=array(
		'key'=>$this->key,
		'tableid'=>$this->tableid,
		'loctype'=>1,
		'data'=>json_encode($data),
		//'sig'=>$this->sign
		);
		//
		$rt=$this->api_notice_increment($url,$arr);
		if (intval($rt['status'])==1) {
			//return $rt['_id'];
		}
	}
	public function delete($id){
		$url='http://yuntuapi.amap.com/datamanage/data/delete';
		$arr=array(
		'key'=>$this->key,
		'tableid'=>$this->tableid,
		'ids'=>$id
		);
		//
		$rt=$this->api_notice_increment($url,$arr);
		if (intval($rt['status'])==1) {
			//return $rt['_id'];
		}
	}
	public function coordinateConvert($longitude,$latitude,$omap='baidu'){
		$url='http://restapi.amap.com/v3/assistant/coordinate/convert?locations='.$longitude.','.$latitude.'&coordsys='.$omap.'&output=json&key='.$this->key;
		$rt=$this->api_notice_increment($url,array(),'GET');
		if ($rt){
			$locations=explode(',',$rt['locations']);
			return array('longitude'=>$locations[0],'latitude'=>$locations[1]);
		}else {
			return array('longitude'=>$longitude,'latitude'=>$latitude);
		}
		
	}
	public function staticMap($lng,$lat,$name,$width=360,$height=200,$arr=array()){
		return 'http://restapi.amap.com/v3/staticmap?location='.$lng.','.$lat.'&zoom=10&size=360*200&labels='.urlencode($name).',2,0,16,0xFFFFFF,0x008000:'.$lng.','.$lat.'&key='.$this->key;
	}
	//单点地图，直接打开手机高德，可以导航
	public function getPointMapLink($lng,$lat,$name){
		return 'http://mo.amap.com/?q='.$lat.','.$lng.'&name='.urlencode($name).'&dev=0';
	}
	function api_notice_increment($url, $params,$method='POST'){
		$data = '';
		foreach ($params as $k => $v) {
			$data .= $k.'='.$v.'&';
		}
		$data = rtrim($data, '&');
		$data=urldecode($data);
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		if ($method=='POST'){
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
			return array('rt'=>false,'errorno'=>$errorno);
		}else{
			$js=json_decode($tmpInfo,1);
			return $js;
		}
	}
	public function httppost($url,$params) {
		//需要提交的post数据
		$p = '';
		foreach ($params as $k => $v) {
			$p .= $k.'='.$v.'&';
		}
		$p = rtrim($p, '&');
		$header = "POST / HTTP/1.1\r\n";
		$header .= "Host:$url\r\n";
		$header .= "Content-Type: application/x-www-form-urluncoded\r\n";
		$header .= "Content-Length: " . strlen($p) . "\r\n";
		$header .= "Connection: Close\r\n\r\n";
		$header .= $p;

		$fp = fsockopen($host);
		var_export($fp);
		exit();
		fputs($fp, $header);
		while (!feof($fp)) {
			$str = fgets($fp);
		}
		fclose($fp);
		return $str;
	}
	//步行规划
	public function walking($origin,$destination){
		$origins=explode(',',$origin);
		$url='http://restapi.amap.com/v3/direction/walking?key='.$this->key.'&origin='.$origin.'&destination='.$destination;
		$rt=$this->api_notice_increment($url,array(),'GET');
		return $rt;
	}
	//驾车规划
	public function driving($origin,$destination){
		$url='http://restapi.amap.com/v3/direction/driving?key='.$this->key.'&origin='.$origin.'&destination='.$destination;
		$rt=$this->api_notice_increment($url,array(),'GET');
		return $rt;
	}
	//公共交通规划
	public function bus($origin,$destination){
		$origins=explode(',',$origin);
		$cityName=$this->cityName($origins[1].','.$origins[0]);
		$url='http://restapi.amap.com/v3/direction/transit/integrated?city='.$cityName.'&key='.$this->key.'&origin='.$origin.'&destination='.$destination;

		$rt=$this->api_notice_increment($url,array(),'GET');
		return $rt;
	}
	//轻地图、导航
	public function navi($origin,$destination,$name,$by='walk'){//by:car/bus/walk
		return $url='http://mo.amap.com/navi?key='.$this->key.'&start='.$origin.'&dest='.$destination.'&destName='.urlencode($name);
	}
	public function around($longitude,$latitude,$keyword,$radius=3000){
		$url='http://restapi.amap.com/v3/place/around?keywords='.$keyword.'&key='.$this->key.'&location='.$latitude.','.$longitude.'&radius='.$radius;
		$rt=$this->api_notice_increment($url,array(),'GET');
		$map=array();
		
		if ($rt['pois']){
			foreach ($rt['pois'] as $p){
				$url=$this->navi($longitude.','.$latitude,$p['location'],$p['name'],'car');
				$telStr='';
				if (!is_array($p['tel'])){
					$telStr="\r\n电话:".$p['tel'];
				}
				$map[]=array($p['name']."(".$p['distance']."米)".$telStr."\r\n地址:".$p['address'],'',C('site_url').'/tpl/static/images/home.jpg',$url);
			}
			return array($map,'news');
		}else {
			return array($rt['info'],'text');
		}

	}
	public function cityName($location){
		//查出起点所在城市
		$ocityArr=json_decode(file_get_contents('http://api.map.baidu.com/geocoder/v2/?ak='.C('baidu_map_api').'&location='.$location.'&output=json&pois=0'),1);
		$ocityName=$ocityArr['result']['addressComponent']['city'];
		return $ocityName;
	}
}

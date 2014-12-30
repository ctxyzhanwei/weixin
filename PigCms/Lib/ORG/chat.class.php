<?php
class chat
{	
	public $keyword;
	public $my;
	public function __construct($keyword)
	{
		$this->keyword=$keyword;
		$this->my=C('site_my');
	}
	public function index(){
		$name=$this->keyword;
		if (!(strpos($name,'你是') === FALSE)){
			return '咳咳，我是只能微信机器人';
		}
		if($name=="你叫什么"||$name=="你是谁"){
			return '咳咳，我是聪明与智慧并存的美女,人家刚交男朋友,你不可追我啦';
		}elseif($name=='糗事'){
			$name='笑话';
		}
		$str='http://www.lexun.cc/pgicms_api/api.php?key=free&server_key='.base64_encode(C('server_key')).'&server_topdomain='.C('server_topdomain').'&appid=0&msg='.urlencode($name);
		$json=Http::fsockopenDownload($str);
		//return $json;
		
		if($json==false){
			$json=file_get_contents($str);
		}
		$json=json_decode($json,true);
		$str=str_replace('菲菲',$this->my,str_replace('提示：',$this->my.'提醒您:',str_replace('{br}',"\n",$json['content'])));
		return $str;
	}
}
?>


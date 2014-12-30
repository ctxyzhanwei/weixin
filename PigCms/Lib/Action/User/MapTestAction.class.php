<?php
class MapTestAction extends BaseAction{
	public $token;
	public $apikey;
	public function setLatLng(){
		if(IS_POST){
			
		}else{
			$this->display();
		}
	}
	//公司静态地图
	public function index(){

			$radius=2000;
			$map=new baiduMap('酒店',31.844931631914,117.21469057536);
			$str=$map->echoJson();

			$array=json_decode($str);
			echo $str;
	}
}


?>
<?php
class VcardAction extends BaseAction{
	
	public function index(){
		$where['token'] = $this->_get('token');
		$where['id'] = intval($_GET['id']);
		$this->wecha_id = $this->_get('wecha_id');
		$company = M('Vcard')->where(array('token'=>$where['token']))->find();
		$info = M('VcardList')->where($where)->find();
		$this->company = $company;
		$this->info = $info;
		$this->ewm = $this->chl($where['id'],$where['token'],$this->_get('wecha_id'));

		$this->display();
	}
	
	public function show(){
		$where['id'] = $this->_get('id');
		$where['token'] = $this->_get('token');
		$this->wecha_id = $this->_get('wecha_id');
		$info = M('VcardList')->where($where)->find();
		$this->info = $info;
		$this->ewm = $this->chl($where['id'],$where['token'],$this->_get('wecha_id'));
		$this->display();
	}
	
	public function chl($id,$token,$wecha_id){
		$where['id'] = $id;
		$where['token'] = $token;
		$company = M('Vcard')->where(array('token'=>$where['token']))->find();
		$info = M('VcardList')->where($where)->find();
		$url = rtrim(C('site_url'),'/')."/index.php?g=Wap&m=Vcard&a=index&token=".$token."&wecha_id=".$wecha_id."&id=".$id."#mp.weixin.qq.com";
		/**
		$urls = $this->shortUrl($url);
		if($urls == false){
			$url = C('site_url');
		}else{
			$url = trim($urls);
		}
		**/
		//$url = "http://www.baidu.com";
		$chl = "BEGIN:VCARD\nVERSION:3.0". //vcard头信息  
       "\nFN:{$info['name']}".
	   "\nTEL;WORK;VOICE:{$company['company_tel']}".
	   "\nTEL;CELL;VOICE:{$info['mobile']}".
	   "\nEMAIL:{$info['email']}".
	   "\nORG:{$company['company']}".
	   "\nTITLE:{$info['work']}". 
	   "\nADR;WORK;POSTAL:{$company['address']}".
	   "\nURL;VALUE=uri:{$url}".
       "\nEND:VCARD"; //vcard尾信息
	   $widhtHeight = 350;
	   $EC_level='H';
	   $margin = 0;
	   //$widhtHeight = 150;
	   //$EC_level='M';
	   //$margin = 0;
	   $size = "100";
	   return '<img src="http://chart.apis.google.com/chart?chs='.$widhtHeight.'x'.$widhtHeight.'&cht=qr&chld='.$EC_level.'|'.$margin.'&chl='.urlencode($chl).'" alt="QR code" widhtHeight="'.$size.'" widhtHeight="'.$size.'" width="350px" height="350px;"/>';
	}
	
	public function shortUrl($url){
		$ch=curl_init();

		curl_setopt($ch,CURLOPT_URL,"http://dwz.cn/create.php");
		
		curl_setopt($ch,CURLOPT_POST,true);
		
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		
		$data=array('url'=>$url);
		
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		
		$strRes=curl_exec($ch);
		
		curl_close($ch);
		
		$arrResponse=json_decode($strRes,true);
		
		if($arrResponse['status']==0)
		
		{
		
		/**错误处理*/
		
		// iconv('UTF-8','GBK',$arrResponse['err_msg'])."\n";
		return false;
		
		}
		
		/** tinyurl */
		
		return $arrResponse['tinyurl'];

	}
}
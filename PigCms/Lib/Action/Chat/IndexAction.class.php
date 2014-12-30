<?php

class IndexAction extends ChatAction{
	public $thisWxUser;
	public function _initialize() {
		parent::_initialize();
		$this->assign('token',session('token'));
	}
	public function index(){
		//$end=time()+60*60*24*3;
		$data=M('Wehcat_member_enddate');
		$count      = $data->where(array('token'=>$_SESSION['token']))->count();
		$Page       = new Page($count,50);
		$show       = $Page->show();
        $list = $data->where(array('token'=>$_SESSION['token'],'uid'=>session('userId')))->order('enddate  desc')->limit($Page->firstRow.','.$Page->listRows)->select(); 
		foreach($list as $key=>$limen){
			$dataUp['joinUpDate']=time();
			$dataUp['uid']=session('userId');
			$dataUp['id']=$limen['id'];
			M('wehcat_member_enddate')->data($dataUp)->save();
			$userInfo=M('wechat_group_list')->order('id desc')->where(array('openid'=>$limen['openid']))->find();
			$list1[$key]['endtime']=$this->getTime($limen['enddate'],'mohu');
			$list1[$key]['nickname']=$userInfo['nickname'];
			$list1[$key]['headimgurl']=$userInfo['headimgurl'];
			$list1[$key]['id']=$userInfo['id'];
			$list1[$key]['city']=$userInfo['city'];
			$list1[$key]['openid']=$limen['openid'];
			$list1[$key]['subscribe_time']=$this->getTime($userInfo['subscribe_time'],'normal');
		}
		$where['token']=session('token');
		$where['id']=session('userId');
		$where['endJoinDate']=time();
		M('Service_user')->data($where)->save();
		$this->assign('page',$show);		
		$this->assign('list',$list1);
		$this->display();
	}
	function getTime(){
		if (!$sTime)
        return '';
    //sTime=源时间，cTime=当前时间，dTime=时间差
    $cTime      =   time();
    $dTime      =   $cTime - $sTime;
    $dDay       =   intval(date("z",$cTime)) - intval(date("z",$sTime));
    //$dDay     =   intval($dTime/3600/24);
    $dYear      =   intval(date("Y",$cTime)) - intval(date("Y",$sTime));
    //normal：n秒前，n分钟前，n小时前，日期
    if($type=='normal'){
        if( $dTime < 60 ){
            if($dTime < 10){
                return '刚刚';    //by yangjs
            }else{
                return intval(floor($dTime / 10) * 10)."秒前";
            }
        }elseif( $dTime < 3600 ){
            return intval($dTime/60)."分钟前";
        //今天的数据.年份相同.日期相同.
        }elseif( $dYear==0 && $dDay == 0  ){
            //return intval($dTime/3600)."小时前";
            return '今天'.date('H:i',$sTime);
        }elseif($dYear==0){
            return date("m月d日 H:i",$sTime);
        }else{
            return date("Y-m-d H:i",$sTime);
        }
    }elseif($type=='mohu'){
        if( $dTime < 60 ){
            return $dTime."秒前";
        }elseif( $dTime < 3600 ){
            return intval($dTime/60)."分钟前";
        }elseif( $dTime >= 3600 && $dDay == 0  ){
            return intval($dTime/3600)."小时前";
        }elseif( $dDay > 0 && $dDay<=7 ){
            return intval($dDay)."天前";
        }elseif( $dDay > 7 &&  $dDay <= 30 ){
            return intval($dDay/7) . '周前';
        }elseif( $dDay > 30 ){
            return intval($dDay/30) . '个月前';
        }
    //full: Y-m-d , H:i:s
    }elseif($type=='full'){
        return date("Y-m-d , H:i:s",$sTime);
    }elseif($type=='ymd'){
        return date("Y-m-d",$sTime);
    }else{
        if( $dTime < 60 ){
            return $dTime."秒前";
        }elseif( $dTime < 3600 ){
            return intval($dTime/60)."分钟前";
        }elseif( $dTime >= 3600 && $dDay == 0  ){
            return intval($dTime/3600)."小时前";
        }elseif($dYear==0){
            return date("Y-m-d H:i:s",$sTime);
        }else{
            return date("Y-m-d H:i:s",$sTime);
        }
    }
	}
	public function ajaxList(){
		if(IS_AJAX){
			$uid=session('userId');
			$sql="`token`='".session('token')."' and uid= ".$uid." and `enddate` > `joinUpDate`";
			//echo $sql;
			$data=M('wehcat_member_enddate')->where($sql)->limit(5)->select();
			if($data!=false){
				foreach($data as $key => $limen){
					$dataUp['joinUpDate']=time();
					$dataUp['uid']=session('userId');
					$dataUp['id']=$limen['id'];
					M('wehcat_member_enddate')->data($dataUp)->save();
					$userInfo=M('wechat_group_list')->order('id desc')->where(array('openid'=>$limen['openid']))->find();
					$list1[$key]['endtime']=$this->getTime($limen['enddate'],'mohu');
					$list1[$key]['nickname']=$userInfo['nickname'];
					$list1[$key]['headimgurl']=$userInfo['headimgurl'];
					$list1[$key]['id']=$userInfo['id'];
					$list1[$key]['city']=$userInfo['city'];
					$list1[$key]['openid']=$limen['openid'];
					$list1[$key]['subscribe_time']=$this->getTime($userInfo['subscribe_time'],'normal');
				}
				echo json_encode($list1);
				M('Service_user')->where(array('token'=>session('token'),'id'=>session('userId'),'endJoinDate'=>time()))->save();
			//dump(M('Service_user')->getLastSql());
			}
			
		}else{
			exit('erorr:3306');
		}
	
	}
	public function ajaxMain(){
		if(IS_AJAX){
			if(!$time=$this->_post('time','htmlspecialchars')){exit(1);}
			$where['token']=session('token');
			$where['openid']=$this->_post('openid','htmlspecialchars');
			$endtime=M('wehcat_member_enddate')->where($where)->find();
			//$sql='`token`=1 and `openid`=1 and `enddate` >11';
			$sql='`token`="'.$where['token'].'" and `openid`="'.$where['openid'].'" and `enddate` >'.$time;
			$list=M('Behavior')->where($sql)->order('id desc')->select();
			$SQL=M('Behavior')->getlastsql();
			if($list !=false){
				$list=array_reverse($list);
				$where['token']=session('token');
				$where['id']=session('userId');
				$where['endJoinDate']=time();
				M('Service_user')->data($where)->save();
				echo json_encode($list);
			}else{
				echo 1;
			}
			
		}else{
			exit('{eror:2031}');
		}
	
	}
	public function main(){
		$where['openid']=$this->_get('openid','htmlspecialchars');
		$where['token']=session('token');
		$msgList=M('behavior')->field('keyword,openid,enddate')->where($where)->limit(20)->order('id desc')->select();
		$logs=M('service_logs')->field('enddate,keyword,openid')->where($where)->limit(20)->order('id desc')->select();
		$userInfo=M('wechat_group_list')->field('nickname,headimgurl,openid')->where($where)->find();
		foreach($msgList as $key=>$List){
			$msgList[$key]['type']=1;
		
		}
		foreach($logs as $key=>$List){
			$logs[$key]['type']=2;
		
		}
		$newarray=array_merge($msgList,$logs);
		$enddata=$logs?$this->array2sort($newarray,'enddate'):$msgList;
		$this->assign('msgList',$enddata);
		$endtime=$msgList[0];
		//dump($endtime);
		
		$userInfo['openid']=$where['openid'];
		$this->assign('endtime',$endtime['enddate']);
		$this->assign('userInfo',$userInfo);
		$this->display();
	}
	//数组排序
	private function array2sort($a,$sort,$d='') {
		$num=count($a);
		if(!$d){
			for($i=0;$i<$num;$i++){
				for($j=0;$j<$num-1;$j++){
					if($a[$j][$sort] > $a[$j+1][$sort]){
						foreach ($a[$j] as $key=>$temp){
							$t=$a[$j+1][$key];
							$a[$j+1][$key]=$a[$j][$key];
							$a[$j][$key]=$t;
						}
					}
				}
			}
		}
		else{
			for($i=0;$i<$num;$i++){
				for($j=0;$j<$num-1;$j++){
					if($a[$j][$sort] < $a[$j+1][$sort]){
						foreach ($a[$j] as $key=>$temp){
							$t=$a[$j+1][$key];
							$a[$j+1][$key]=$a[$j][$key];
							$a[$j][$key]=$t;
						}
					}
				}
			}
		}
		return $a;
	}
	//伪造腾讯header头请求图片
	function showExternalPic(){
		$wecha_id=$this->_get('wecha_id');
		//S($this->token.'_'.$wecha_id,null);
		$token=$this->_get('token');
		$imgData = S($token.'_'.$wecha_id);
		$types = array(
			'gif'=>'image/gif',
			'jpeg'=>'image/jpeg',
			'jpg'=>'image/jpeg',
			'jpe'=>'image/jpeg',
			'png'=>'image/png',
			);
		if (!$imgData){
			$url=htmlspecialchars($_GET['url']);
			$dir = pathinfo($url);
			$host = $dir['dirname'];
			$refer = 'http://www.qq.com/';

			$ch = curl_init($url);
			curl_setopt ($ch, CURLOPT_REFERER, $refer);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
			$data = curl_exec($ch);
			curl_close($ch);
			$ext = strtolower(substr(strrchr($url,'.'),1,10));
			
			$ext='jpg';
			$type = $types[$ext] ? $types[$ext] : 'image/jpeg';
			header("Content-type: ".$type);
			echo  $data;
		}else {
			$ext='jpg';
			$type = $types[$ext] ? $types[$ext] : 'image/jpeg';
			header("Content-type: ".$type);
			echo  $imgData;
		}
	} 
	public function send(){
		$this->send_info("人工客服－".session('name').":\n\r".$this->_post('keyword'),$this->_get('openid','htmlspecialchars'),session('userId'));
	}
	public function send_info($content,$openid,$pid=1,$type=1){
		//查询appid appkey是否存在
		$api=M('Diymen_set')->where(array('token'=>session('token')))->find();
		//dump($api);
		if($api['appid']==false||$api['appsecret']==false){$this->error('必须先填写【AppId】【 AppSecret】');exit;}
		//获取微信认证
		$url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$api['appid'].'&secret='.$api['appsecret'];
		$json=json_decode($this->curlGet($url_get));
		$qrcode_url='https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$json->access_token;
		$data='{
			"touser":"'.$openid.'",
			"msgtype":"text",
			"text":
			{
				 "content":"'.$content.'"
			}
		}'; 
		$post=$this->api_notice_increment($qrcode_url,$data);
		$json_decode=json_decode($post);
		//
		$where['token']=session('token');
		$where['id']=session('userId');
		$where['endJoinDate']=time();
		M('Service_user')->data($where)->save();
		$checkRt=M('Wehcat_member_enddate')->where(array('token'=>$where['token'],'openid'=>$openid))->find();
		if ($checkRt){
			M('Wehcat_member_enddate')->where(array('token'=>$where['token'],'openid'=>$openid))->save(array('joinUpDate'=>time()));
		}else {
			
		}
		//
		if($json_decode->errmsg =='ok'){
			$GetDb=M('Service_logs');
			$add['enddate']=time();
			$add['keyword']=$content;
			$add['pid']=$pid;
			$add['openid']=$openid;
			$update=$GetDb->data($add)->add();
			echo 1;
		}else{
			echo 2;
		}
	}	
	function api_notice_increment($url, $data){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		if ($errorno) {
			return array('rt'=>false,'errorno'=>$errorno);
		}else{
			//dump($tmpInfo);
			return $tmpInfo;
			$js=json_decode($tmpInfo,1);
			return $js['ticket'];
		}
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

}
	?>
<?php
class MessageAction extends UserAction{
	public $thisWxUser;
	public function _initialize() {
		parent::_initialize();
		$where=array('token'=>$this->token);
		$this->thisWxUser=M('Wxuser')->where($where)->find();
		$this->canUseFunction('message');
		if (!$this->thisWxUser['appid']||!$this->thisWxUser['appsecret']){
			$diyApiConfig=M('Diymen_set')->where($where)->find();
			if (!$diyApiConfig['appid']||!$diyApiConfig['appsecret']){
				//$this->error('请先设置AppID和AppSecret再使用本功能，谢谢','?g=User&m=Index&a=edit&id='.$this->thisWxUser['id']);
			}else {
				$this->thisWxUser['appid']=$diyApiConfig['appid'];
				$this->thisWxUser['appsecret']=$diyApiConfig['appsecret'];
			}
		}
	}
	public function sendHistory(){
		$db=D('Send_message');
		$where['token']=$this->token;
		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$info=$db->where($where)->order('id DESC')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$page->show());
		$this->assign('info',$info);
		$this->display();
	}
	public function index(){
		$wechat_group_db=M('Wechat_group');
		$groups=$wechat_group_db->where(array('token'=>$this->token))->order('id ASC')->select();
		$this->assign('groups',$groups);
		if (IS_POST){
			$row=array();
			$row['msgtype']=$this->_post('msgtype');
			$row['mediasrc']=$this->_post('mediasrc');
			$row['text']=$this->_post('text');
			$row['imgids']=$this->_post('imgids');
			$row['token']=$this->token;
			$row['time']=time();
			if ($row['msgtype']!='text'&&strpos($_SERVER['HTTP_HOST'],'pigcms')){
				$this->error('演示站禁止文件上传，所以请测试文本消息的发送，谢谢您的支持');
			}
			//
			if (isset($_POST['mediasrc'])&&trim($_POST['mediasrc'])){
				$url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->thisWxUser['appid'].'&secret='.$this->thisWxUser['appsecret'];
				$json=json_decode($this->curlGet($url_get));
				if (!$json->errmsg){
					$postMedia=array();
					$postMedia['access_token']=$json->access_token;
					$postMedia['type']=$row['msgtype'];
					$postMedia['media']=$_SERVER['DOCUMENT_ROOT'].str_replace('http://'.$_SERVER['HTTP_HOST'],'',$row['mediasrc']);
					$rt=$this->curlPost('http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token='.$postMedia['access_token'].'&type='.$postMedia['type'],array('media'=>'@'.$postMedia['media']));
					if($rt['rt']==false){
						$this->error('操作失败,curl_error:'.$rt['errorno']);
					}else{
						$media_id=$rt['media_id'];
						$row['mediaid']=$media_id;
					}
				}else {
					$this->error('获取access_token发生错误：错误代码'.$json->errcode.',微信返回错误信息：'.$json->errmsg);
				}
			}
			$id=M('Send_message')->add($row);
			$this->success('添加成功，现在开始发送信息',U('Message/send',array('id'=>$id)));
		}else {
			
			$this->display();
		}
	}
	public function sendAll(){
		if (IS_POST){
			if (strpos($_SERVER['HTTP_HOST'],'pigcms')){
				$this->error('演示站禁止文件上传，所以请测试一下其他功能吧，谢谢您的支持');
			}
			$imgids=$this->_post('imgids');
			$oimgids=$imgids;
			$wechatgroupid=$_POST['wechatgroupid'];
			//
			$imgidsArr=explode(',',$imgids);
			$imgids=array();
			$imgID=0;
			if ($imgidsArr){
				foreach ($imgidsArr as $ii){
					if (intval($ii)){
						array_push($imgids,$ii);
					}
				}
			}

			if (count($imgids)){
				$imgs=M('Img')->where(array('id'=>array('in',$imgids)))->select();
			}
			if ($imgs){
				
			}else {
				$this->error('请选择图文消息',U('Message/index'));
			}
			//
			$url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->thisWxUser['appid'].'&secret='.$this->thisWxUser['appsecret'];
			$json=json_decode($this->curlGet($url_get));
			$mediaids='';

			if (!$json->errmsg){
				$postMedia=array();
				$postMedia['access_token']=$json->access_token;
				$postMedia['type']='image';
				
				foreach ($imgs as $img){
					file_put_contents(CONF_PATH.'img_'.$img['id'].'.jpg',file_get_contents($img['pic']));
					//$postMedia['media']=$_SERVER['DOCUMENT_ROOT'].str_replace('http://'.$_SERVER['HTTP_HOST'],'',$img['pic']);
					//$postMedia['media']=$_SERVER['DOCUMENT_ROOT'].str_replace('./','/',CONF_PATH.'img_'.$img['id'].'.jpg');
					$postMedia['media']=CONF_PATH.'img_'.$img['id'].'.jpg';
					$postMedia['media']=$_SERVER['DOCUMENT_ROOT'].str_replace(array('./'),array('/'),$postMedia['media']);
					$rt=$this->curlPost('http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token='.$postMedia['access_token'].'&type='.$postMedia['type'],array('media'=>'@'.$postMedia['media']));
					
					if($rt['rt']==false){
						$this->error('操作失败,curl_error:'.$rt['errorno']);
					}else{
						$mediaids.=$comma.$rt['media_id'];
						$comma=',';
					}
				}
			}else {
				$this->error('获取access_token发生错误：错误代码'.$json->errcode.',微信返回错误信息：'.$json->errmsg);
			}
			$this->success('图片素材上传完毕，现在开始发送信息',U('Message/tosendAll',array('imgids'=>$oimgids,'wechatgroupid'=>$wechatgroupid,'mediaids'=>$mediaids)));
		}
	}
	public function tosendAll(){
		if (IS_GET){
			$row=array();
			$row['msgtype']='news';
			$row['imgids']=$this->_get('imgids');
			$row['token']=$this->token;
			$row['time']=time();
			//
			$mediaids=explode(',',$_GET['mediaids']);
			$url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->thisWxUser['appid'].'&secret='.$this->thisWxUser['appsecret'];
			$json=json_decode($this->curlGet($url_get));
			if (!$json->errmsg){
				$postMedia=array();
				$postMedia['access_token']=$json->access_token;
				$imgidsArr=explode(',',$row['imgids']);
				$imgidsArr=array_unique($imgidsArr);
				$imgids=array();
				$imgID=0;
				if ($imgidsArr){
					foreach ($imgidsArr as $ii){
						if (intval($ii)){
							array_push($imgids,$ii);
						}
					}
				}
		
				if (count($imgids)){
					$imgs=M('Img')->where(array('id'=>array('in',$imgids)))->select();
				}
				if ($imgs){
					$str='{"articles": [';
					$comma='';
					$i=0;
					foreach ($imgs as $img){
						if ($img['url']){
							//$url=str_replace(array('{wechat_id}','{siteUrl}','&amp;'),array($fan['openid'],C('site_url'),'&'),$thisNews['url']);
						}else {
							//$url=C('site_url').U('Wap/Index/content',array('token'=>$this->token,'wecha_id'=>$fan['openid'],'id'=>$thisNews['id']));
						}
						
						$str.=$comma.'{"thumb_media_id":"'.$mediaids[$i].'","author":"","title":"'.$img['title'].'","content_source_url":"","content":"'.str_replace(array('"','"/upload'),array('\"','"'.C('site_url').'/upload'),html_entity_decode($img['info'])).'","digest":"'.$img['text'].'"}';
						$comma=',';
						$i++;
					}
					$str.=']}';
				}else {
					$this->error('请选择图文消息',U('Message/index'));
				}

				$rt=$this->curlPost('https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token='.$postMedia['access_token'],$str);
				if($rt['rt']==false){
					$this->error('操作失败,curl_error:'.$rt['errorno']);
				}else{
					$media_id=$rt['media_id'];
					$row['mediaid']=$media_id;
				}
			}else {
				$this->error('获取access_token发生错误：错误代码'.$json->errcode.',微信返回错误信息：'.$json->errmsg);
			}
			
			$id=M('Send_message')->add($row);
			//
			$sendrt=$this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$postMedia['access_token'],'{"filter":{"group_id":"'.$this->_get('wechatgroupid').'"},"mpnews":{"media_id":"'.$row['mediaid'].'"},"msgtype":"mpnews"}');
			if($sendrt['rt']==false){
				$this->error('操作失败,curl_error:'.$sendrt['errorno']);
			}else{
				$msg_id=$sendrt['msg_id'];
				M('Send_message')->where(array('id'=>$id))->save(array('msg_id'=>$msg_id));
				$this->success('发送任务已经启动，群发可能会在20分钟左右完成，您可以关闭该页面了',U('Message/sendHistory'));
			}
		}
	}
	public function item(){
		if (isset($_GET['id'])){
			$info=M('Send_message')->where(array('token'=>$this->token,'id'=>intval($_GET['id'])))->find();
			$this->assign('info',$info);
		}
		if ($info['msgtype']=='news'){
			$imgids=explode(',',$info['imgids']);
			$imgID=0;
			if ($imgids){
				foreach ($imgids as $ii){
					if (intval($ii)){
						$imgID=$ii;
					}
				}
			}
			$thisNews=M('Img')->where(array('id'=>$imgID))->find();
			$this->assign('img',$thisNews);
		}
		$this->display();
	}
	public function send(){
		$fans=M('Wechat_group_list')->where(array('token'=>$this->token))->order('id ASC')->select();
		//$fans=array(array('openid'=>'oCsUfuC0mqT4VM6JjbggaLvzGEXI'));
		$i=intval($_GET['i']);
		$count=count($fans);
		$thisMessage=M('Send_message')->where(array('id'=>intval($_GET['id'])))->find();
		if ($i<$count){
			$fan=$fans[$i];
			$url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->thisWxUser['appid'].'&secret='.$this->thisWxUser['appsecret'];
			$json=json_decode($this->curlGet($url_get));
			if (!$json->errmsg){
				
				switch ($thisMessage['msgtype']){
					case 'text':
						$data='{"touser":"'.$fan['openid'].'","msgtype":"text","text":{"content":"'.$thisMessage['text'].'"}}';
						break;
					case 'image':
					case 'voice':
						$data='{"touser":"'.$fan['openid'].'","msgtype":"'.$thisMessage['msgtype'].'","'.$thisMessage['msgtype'].'":{"media_id":"'.$thisMessage['mediaid'].'"}}';
						break;
					case 'video':
						$data='{"touser":"'.$fan['openid'].'","msgtype":"'.$thisMessage['msgtype'].'","'.$thisMessage['msgtype'].'":{"media_id":"'.$thisMessage['mediaid'].'","description":"'.$thisMessage['text'].'","title":"'.$thisMessage['title'].'"}}';
						break;
					case 'music':
						$data='{"touser":"'.$fan['openid'].'","msgtype":"'.$thisMessage['msgtype'].'","'.$thisMessage['msgtype'].'":{"media_id":"'.$thisMessage['mediaid'].'"}}';
						break;
					case 'news':
						$imgids=explode(',',$thisMessage['imgids']);
						$imgID=0;
						if ($imgids){
							foreach ($imgids as $ii){
								if (intval($ii)){
									$imgID=$ii;
								}
							}
						}
						$thisNews=M('Img')->where(array('id'=>$imgID))->find();
						if ($thisNews['url']){
							$url=str_replace(array('{wechat_id}','{siteUrl}','&amp;'),array($fan['openid'],C('site_url'),'&'),$thisNews['url']);
						}else {
							$url=C('site_url').U('Wap/Index/content',array('token'=>$this->token,'wecha_id'=>$fan['openid'],'id'=>$thisNews['id']));
						}
						$data='{"touser":"'.$fan['openid'].'","msgtype":"'.$thisMessage['msgtype'].'","news":{"articles":[{"title":"'.$thisNews['title'].'","description":"'.$thisNews['text'].'","url":"'.$url.'","picurl":"'.$thisNews['pic'].'"}]}}';
						break;
				}
				//
				$rt=$this->curlPost('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$json->access_token,$data,0);
				if($rt['rt']==false){
					//$this->error('操作失败,curl_error:'.$rt['errorno']);
				}else{
					M('Send_message')->where(array('id'=>intval($thisMessage['id'])))->setInc('reachcount');
				}
				$i++;
				$this->success('发送中:'.$i.'/'.$count,U('Message/send',array('id'=>$thisMessage['id'],'i'=>$i)));
			}else {
				$this->error('获取access_token发生错误：错误代码'.$json->errcode.',微信返回错误信息：'.$json->errmsg);
			}
		}else {
			$this->success('发送完成，发送成功'.$thisMessage['reachcount'].'条',U('Message/sendHistory'));
		}
	}
	
	public function img(){
		$db=M('Img');
		$where=array('token'=>$this->token);
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'pic'=>$item['pic'],'info'=>str_replace(array("&#039;",'\'',"\r\n","\r","\n",'"'),'',$item['text']),'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Index&a=content&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display();
	}
	
	function curlPost($url, $data,$showError=1){
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
		$errorno=curl_errno($ch);
		if ($errorno) {
			return array('rt'=>false,'errorno'=>$errorno);
		}else{
			$js=json_decode($tmpInfo,1);
			if (intval($js['errcode']==0)){
				return array('rt'=>true,'errorno'=>0,'media_id'=>$js['media_id'],'msg_id'=>$js['msg_id']);
			}else {
				if ($showError){
					$this->error('发生了Post错误：错误代码'.$js['errcode'].',微信返回错误信息：'.$js['errmsg']);
				}
			}
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
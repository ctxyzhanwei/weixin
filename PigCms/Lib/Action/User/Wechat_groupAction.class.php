<?php
class Wechat_groupAction extends UserAction{
	public $thisWxUser;
	public function _initialize() {
		parent::_initialize();
		$where=array('token'=>$this->token);
		$this->thisWxUser=M('Wxuser')->where($where)->find();
		if (!$this->thisWxUser['appid']||!$this->thisWxUser['appsecret']){
			$this->error('请先设置AppID和AppSecret再使用本功能，谢谢','?g=User&m=Index&a=edit&id='.$this->thisWxUser['id']);
		}
		if ($this->thisWxUser['winxintype']!=3){
			//$this->error('只有微信官方认证的高级服务号才能使用本功能','?g=User&m=Index&a=edit&id='.$this->thisWxUser['id']);
		}
	}
	public function index(){
		$showStatistics=1;
		if (isset($_GET['p'])||isset($_POST['keyword'])){
			$showStatistics=0;
		}
		$this->assign('showStatistics',$showStatistics);
		$group_list_db=M('Wechat_group_list');
		$where=array('token'=>$this->token);
		if (IS_POST&&strlen(trim($_POST['keyword']))){
			$keyword=htmlspecialchars(trim($_POST['keyword']));
			$where['nickname'] = array('like','%'.$keyword.'%');
			$list=$group_list_db->where($where)->order('id desc')->select();
		}else {
			if (isset($_GET['wechatgroupid'])){
				$where['g_id']=intval($_GET['wechatgroupid']);
			}
			$count=$group_list_db->where($where)->count();
			$page=new Page($count,10);
			
			$list=$group_list_db->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
			
			$this->assign('page',$page->show());
		}
		$wechat_group_db=M('Wechat_group');
		$access_token=$this->_getAccessToken();
		$url='https://api.weixin.qq.com/cgi-bin/groups/get?access_token='.$access_token;
		$json=json_decode($this->curlGet($url));
		$wechat_groups=$json->groups;
		$wechat_groups_ids=array();
		if ($wechat_groups){
			foreach ($wechat_groups as $g){
				$thisGroupInDb=$wechat_group_db->where(array('token'=>$this->token,'wechatgroupid'=>$g->id))->find();
				$arr=array('token'=>$this->token,'wechatgroupid'=>$g->id,'name'=>$g->name,'fanscount'=>$g->count);
				if (!$thisGroupInDb){
					$wechat_group_db->add($arr);
				}else {
					$wechat_group_db->where(array('id'=>$thisGroupInDb['id']))->save($arr);
				}
				array_push($wechat_groups_ids,$g->id);
			}
		}
		$wechat_group_db=M('Wechat_group');
		$groups=$wechat_group_db->where(array('token'=>$this->token))->order('id ASC')->select();
		$this->assign('groups',$groups);
		$groupsByWechatGroupID=array();
		if ($groups){
			foreach ($groups as $g){
				$groupsByWechatGroupID[$g['wechatgroupid']]=$g;
			}
		}
		if ($list){
				$i=0;
				foreach ($list as $item){
					$t=substr($item['headimgurl'],0,-1);
					$list[$i]['smallheadimgurl']=$item['headimgurl'];
					$list[$i]['groupName']=$groupsByWechatGroupID[$item['g_id']]['name'];
					$i++;
				}
			}
		$this->assign('list',$list);
		if ($showStatistics){
			$fansCount=$group_list_db->where($where)->count();
			$where['sex']=1;
			$maleCount=$group_list_db->where($where)->count();
			$where['sex']=2;
			$femaleCount=$group_list_db->where($where)->count();
			$this->assign('fansCount',$fansCount);
			$this->assign('maleCount',$maleCount);
			$this->assign('femaleCount',$femaleCount);
			$unknownSexCount=$fansCount-$maleCount-$femaleCount;
			$this->assign('unknownSexCount',$unknownSexCount);
			$xml='<chart borderThickness="0" caption="粉丝性别比例图" baseFontColor="666666" baseFont="宋体" baseFontSize="14" bgColor="FFFFFF" bgAlpha="0" showBorder="0" bgAngle="360" pieYScale="90"  pieSliceDepth="5" smartLineColor="666666"><set label="男性" value="'.$maleCount.'"/><set label="女性" value="'.$femaleCount.'"/><set label="未知性别" value="'.$unknownSexCount.'"/></chart>';
			$this->assign('xml',$xml);
		}
		$this->display();
	}
	public function  send(){
		if(IS_GET){
			$access_token=$this->_getAccessToken();
			$url='https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$access_token;
			if (isset($_GET['next_openid'])){
				$url.='&next_openid='.$_GET['next_openid'];
			}
			$json_token=json_decode($this->curlGet($url));
			$arrayData=$json_token->data->openid;
			$nextOpenID=$json_token->next_openid;
			$a=0;
			$b=0;
			foreach($arrayData as $data){
				$check=M('Wechat_group_list')->field('openid')->where(array('openid'=>$data,'token'=>$this->token))->find();
				if($check==false){
					M('Wechat_group_list')->data(array('openid'=>$data,'token'=>$this->token))->add();
					$a++;
				}else{
					$b++;
				}
			}
			if (strlen($nextOpenID)){
				$this->success('本次更新'.$a.'条,重复'.$b=$b==1?0:$b.'条，正在获取下一批粉丝数据','?g=User&m=Wechat_group&a=send&token='.$this->token.'&next_openid='.$nextOpenID);
			}else {
				$this->success('更新完成,现在获取粉丝详细信息','?g=User&m=Wechat_group&a=send_info&token='.$this->token);
			}
		}else{
			$this->error('非法操作');
		}
	}
	public function  send_info(){
		if(IS_GET){
			$refreshAll=isset($_GET['all'])?1:0;
			$access_token=$this->_getAccessToken();
			if ($refreshAll){
				$fansCount=M('Wechat_group_list')->where(array('token'=>session('token')))->count();
				$i=intval($_GET['i']);
				$step=20;
				$fans=M('Wechat_group_list')->where(array('token'=>session('token')))->order('id DESC')->limit($i,$step)->select();
				if ($fans){
					foreach($fans as $data_all){
						$url2='https://api.weixin.qq.com/cgi-bin/user/info?openid='.$data_all['openid'].'&access_token='.$access_token;
						$classData=json_decode($this->curlGet($url2));
						if ($classData->subscribe==1){
							$data['nickname']=str_replace(array("'","\\"),array(''),$classData->nickname);
							$data['sex']=$classData->sex;
							$data['city']=$classData->city;
							$data['province']=$classData->province;
							$data['headimgurl']=$classData->headimgurl;
							$data['subscribe_time']=$classData->subscribe_time;
							S($this->token.'_'.$data_all['openid'],null);
							$url3='https://api.weixin.qq.com/cgi-bin/groups/getid?access_token='.$access_token;
							$json2=json_decode($this->curlGet($url3,'post','{"openid":"'.$data['openid'].'"}'));
							$data['g_id']=$json->groupid;
							M('wechat_group_list')->where(array('id'=>$data_all['id']))->save($data);
							S('fans_'.$this->token.'_'.$data_all['openid'],NULL);
						}else {
							M('wechat_group_list')->delete($data_all['id']);
						}
					}
					$i=$step+$i;
					$this->success('更新中请勿关闭...进度：'.$i.'/'.$fansCount,'?g=User&m=Wechat_group&a=send_info&token='.$this->token.'&all=1&i='.$i);
				}else {
					$this->success('更新完毕','?g=User&m=Wechat_group&a=index&token='.$this->token);
					exit();
				}
			}else {
				$dataAll=M('Wechat_group_list')->field('openid,id')->where(array('token'=>session('token'),'subscribe_time'=>''))->order('id desc')->limit(20)->select();
				if($dataAll ==false){
					$this->success('更新完毕','?g=User&m=Wechat_group&a=index&token='.$this->token);
					exit();
				}
				$i=0;
				foreach($dataAll as $data_all){
					$url2='https://api.weixin.qq.com/cgi-bin/user/info?openid='.$data_all['openid'].'&access_token='.$access_token;
					$classData=json_decode($this->curlGet($url2));
					if ($classData->subscribe==1){
						$data['openid']=$classData->openid;
						$data['nickname']=str_replace("'",'',$classData->nickname);
						$data['sex']=$classData->sex;
						$data['city']=$classData->city;
						$data['province']=$classData->province;
						$data['headimgurl']=$classData->headimgurl;
						$data['subscribe_time']=$classData->subscribe_time;
						$data['token']=session('token');
						$data['id']=$data_all['id'];
						S($this->token.'_'.$data_all['openid'],null);
						$url3='https://api.weixin.qq.com/cgi-bin/groups/getid?access_token='.$access_token;
						$json2=json_decode($this->curlGet($url3,'post','{"openid":"'.$data['openid'].'"}'));
						$data['g_id']=$json->groupid;
						M('wechat_group_list')->save($data);
						$i++;
					}else {
						M('wechat_group_list')->delete($data_all['id']);
					}
				}
				$count=M('Wechat_group_list')->field('id')->where(array('token'=>session('token'),'subscribe_time'=>''))->count();
				$this->success('还有'.$count.'个粉丝信息没有更新,<br />请耐心等待',U('Wechat_group/send_info'));
			}
		}else{
			$this->error('非法操作');
		}
		
	}
	public function setGroup(){
		if (IS_POST){
			$wechat_group_list_db=M('wechat_group_list');
			$wechatgroupid=intval($this->_post('wechatgroupid'));
			$access_token=$this->_getAccessToken();
			foreach ($_POST as $k=>$v){
				if(!(strpos($k,'id_') === FALSE)){
					$id=intval(str_replace('id_','',$k));
					$thisFans=$wechat_group_list_db->where(array('id'=>$id,'token'=>$this->token))->find();
					$url='https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token='.$access_token;
					$json=json_decode($this->curlGet($url,'post','{"openid":"'.$thisFans['openid'].'","to_groupid":'.$wechatgroupid.'}'));
					$wechat_group_list_db->where(array('id'=>$id))->save(array('g_id'=>$wechatgroupid));
				}
			}
			$this->success('设置完毕','?g=User&m=Wechat_group&a=index&token='.$this->token);
		}
	}
    public function curlGet($url,$method='get',$data=''){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
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
    public function showExternalPic(){
		$types = array(
			'gif'=>'image/gif',
			'jpeg'=>'image/jpeg',
			'jpg'=>'image/jpeg',
			'jpe'=>'image/jpeg',
			'png'=>'image/png',
			);
		$wecha_id=$this->_get('wecha_id');
		$token=$this->_get('token');
		$imgData = S($token.'_'.$wecha_id);
		if (!$imgData){
			$url=$_GET['url'];
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
			S($token.'_'.$wecha_id,$data);
			header("Content-type: ".$type);
			echo  $data;
		}else {
			$ext='jpg';
			$type = $types[$ext] ? $types[$ext] : 'image/jpeg';
			header("Content-type: ".$type);
			echo  $imgData;
		}
	}
    public function groups(){
		$wechat_group_db=M('Wechat_group');
		$groups=$wechat_group_db->where(array('token'=>$this->token))->order('id ASC')->select();
		$this->assign('groups',$groups);
		$this->display();
	}
    public function sysGroups(){
		$wechat_group_db=M('Wechat_group');
		$access_token=$this->_getAccessToken();
		$url='https://api.weixin.qq.com/cgi-bin/groups/get?access_token='.$access_token;
		$json=json_decode($this->curlGet($url));
		$wechat_groups=$json->groups;
		$wechat_groups_ids=array();
		if ($wechat_groups){
			foreach ($wechat_groups as $g){
				$thisGroupInDb=$wechat_group_db->where(array('token'=>$this->token,'wechatgroupid'=>$g->id))->find();
				$arr=array('token'=>$this->token,'wechatgroupid'=>$g->id,'name'=>$g->name,'fanscount'=>$g->count);
				if (!$thisGroupInDb){
					$wechat_group_db->add($arr);
				}else {
					$wechat_group_db->where(array('id'=>$thisGroupInDb['id']))->save($arr);
				}
				array_push($wechat_groups_ids,$g->id);
			}
		}
		$groups=$wechat_group_db->where(array('token'=>$this->token))->order('id ASC')->select();
		if ($groups){
			foreach ($groups as $g){
				if (!in_array($g['wechatgroupid'],$wechat_groups_ids)){
					$wechat_group_db->where(array('id'=>$g['id']))->delete();
				}
			}
		}
		$this->success('操作成功',U('Wechat_group/groups'));
	}
    public function groupSet(){
		$wechat_group_db=M('Wechat_group');
		$thisGroup=$wechat_group_db->where(array('id'=>intval($_GET['id'])))->find();
		if ($thisGroup&&$thisGroup['token']!=$this->token){
			$this->error('非法操作');
		}
		if (IS_POST){
			$arr=array();
			$arr['name']=$this->_post('name');
			$arr['intro']=$this->_post('intro');
			$arr['token']=$this->token;
			$access_token=$this->_getAccessToken();
			if (isset($_POST['id'])){
				$url='https://api.weixin.qq.com/cgi-bin/groups/update?access_token='.$access_token;
				$json=json_decode($this->curlGet($url,'post','{"group":{"id":'.$thisGroup['wechatgroupid'].',"name":"'.$arr['name'].'"}}'));
				$wechat_group_db->where(array('id'=>intval($_POST['id'])))->save($arr);
			}else {
				$url='https://api.weixin.qq.com/cgi-bin/groups/create?access_token='.$access_token;
				$json=json_decode($this->curlGet($url,'post','{"group":{"name":"'.$arr['name'].'"}}'));
				$arr['wechatgroupid']=$json->group->id;
				$wechat_group_db->add($arr);
			}
			$this->success('操作成功',U('Wechat_group/groups'));
		}else {
			$this->assign('thisGroup',$thisGroup);
			$this->display();
		}
	}
    public function groupDelete(){
	}
    public function _getAccessToken(){
		$url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->thisWxUser['appid'].'&secret='.$this->thisWxUser['appsecret'];
		$json=json_decode($this->curlGet($url_get));
		if (!$json->errmsg){
		}else {
			$this->error('获取access_token发生错误：错误代码'.$json->errcode.',微信返回错误信息：'.$json->errmsg);
		}
		return $json->access_token;
	}

}
	?>
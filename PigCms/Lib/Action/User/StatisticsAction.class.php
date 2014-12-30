<?php
class StatisticsAction extends UserAction{
	public $thisWxUser;
	public $modules;
	public function _initialize() {
		parent::_initialize();
		$this->token='yicms';
		$where=array('token'=>$this->token);
		$this->thisWxUser=M('Wxuser')->where($where)->find();
		if (!$this->thisWxUser['appid']||!$this->thisWxUser['appsecret']){
			$this->error('请先设置AppID和AppSecret再使用本功能，谢谢','?g=User&m=Index&a=edit&id='.$this->thisWxUser['id']);
		}
		if ($this->thisWxUser['winxintype']!=3){
			//$this->error('只有微信官方认证的高级服务号才能使用本功能','?g=User&m=Index&a=edit&id='.$this->thisWxUser['id']);
		}
		$this->modules=array(
		'home'=>array('name'=>'微网站'),
		'text'=>array('name'=>'文本请求'),
		'member_card_set'=>array('name'=>'会员卡'),
		'lottery'=>array('name'=>'推广活动'),
		'help'=>array('name'=>'帮助'),
		'wedding'=>array('name'=>'婚庆喜帖'),
		'img'=>array('name'=>'图文消息'),
		'selfform'=>array('name'=>'万能表单'),
		'host'=>array('name'=>'通用订单'),
		'panorama'=>array('name'=>'全景'),
		'usernamecheck'=>array('name'=>'账号审核'),
		'album'=>array('name'=>'相册'),
		'vote'=>array('name'=>'投票'),
		'product'=>array('name'=>'商城'),
		'voiceresponse'=>array('name'=>'语音消息'),
		'estate'=>array('name'=>'房产'),
		);
	}
	public function index(){
		switch ($_GET['type']){
			case 1:
				$group_list_db=M('Wechat_group_list');
				$where=array('token'=>$this->token);
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
				break;
			case 2:
				$openid=$this->_get('openid');
				$where=array('token'=>$this->token);
				if ($openid){
					$where['openid']=$openid;
				}
				$behavior_db=M('Behavior');
				$items=$behavior_db->where($where)->order('num DESC')->select();
				$datas=array();
				if ($items){
					foreach ($items as $item){
						$module=strtolower($item['model']);
						if (key_exists($module,$datas)){
							$datas[$module]++;
						}else {
							$datas[$module]=1;
						}
					}
				}
				asort($datas);
				$xml='<chart borderThickness="0" caption="粉丝行为分析" baseFontColor="666666" baseFont="宋体" baseFontSize="14" bgColor="FFFFFF" bgAlpha="0" showBorder="0" bgAngle="360" pieYScale="90"  pieSliceDepth="5" smartLineColor="666666">';
				if ($datas){
					foreach ($datas as $k=>$v){
						$xml.='<set label="'.$this->modules[$k]['name'].'" value="'.$v.'"/>';
					}
				}
				$xml.='</chart>';
				$this->assign('items',$items);
				$this->assign('xml',$xml);
				break;
			case 3:
				$openid=$this->_get('openid');
				$where=array('token'=>$this->token);
				$where['enddate']=array('gt',time()-30*24*3600);
				$behavior_db=M('Behavior');
				$items=$behavior_db->where($where)->order('num DESC')->select();
				$datas=array();
				if ($items){
					foreach ($items as $item){
						$module=strtolower($item['model']);
						if (key_exists($module,$datas)){
							$datas[$module]++;
						}else {
							$datas[$module]=1;
						}
					}
				}
				asort($datas);
				$xml='<chart borderThickness="0" caption="30日内粉丝行为分析" baseFontColor="666666" baseFont="宋体" baseFontSize="14" bgColor="FFFFFF" bgAlpha="0" showBorder="0" bgAngle="360" pieYScale="90"  pieSliceDepth="5" smartLineColor="666666">';
				if ($datas){
					foreach ($datas as $k=>$v){
						$xml.='<set label="'.$this->modules[$k]['name'].'" value="'.$v.'"/>';
					}
				}
				$xml.='</chart>';
				$this->assign('items',$items);
				$this->assign('xml',$xml);
				break;
		}
		$this->display();
	}

}
	?>
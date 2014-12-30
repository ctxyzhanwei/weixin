<?php
class  Wechat_behaviorAction extends UserAction{
	public $token;
	private $data;
	private $openid;
	//private $data;
	public function _initialize(){
		parent::_initialize();
		$this->openid=$this->_get('openid','htmlspecialchars');
		if($this->openid==false){
			//$this->error('非法操作');
		}
		$this->token=session('token');
		$this->data=D('Behavior');
		
	}
	public function wechatList(){
		$this->modules=$this->_modules();
		$where['openid']=$this->openid;
		$userinfo=M('wechat_group_list')->where($where)->find();
		$this->assign('userinfo',$userinfo);
		$endtime=M('wehcat_member_enddate')->where($where)->find();
		//dump($endtime);
		$this->assign('endtime',$endtime['enddate']);
		$count=$this->data->where($where)->count();
		$page=new Page($count,25);
		$list=$this->data->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
		foreach($list as $key=>$vo){
			$list[$key]['behavior']=$this->modules[strtolower($vo['model'])]['name'];
			if (!$list[$key]['behavior']){
				$list[$key]['behavior']='其他';
			}
		}
		//dump($list);
		$this->assign('page',$page->show());
		$this->assign('list',$list);
		$this->assign('type','list');
		$this->display();
	}
	public function statisticsOfSingleFans(){
		$where['openid']=$this->openid;
		$userinfo=M('wechat_group_list')->where($where)->find();
		$this->assign('userinfo',$userinfo);
		$endtime=M('wehcat_member_enddate')->where($where)->find();
		$this->assign('endtime',$endtime['enddate']);
		//
		$this->modules=$this->_modules();
		$openid=$this->openid;
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
		$xml='<chart borderThickness="0" caption="粉丝行为统计分析" baseFontColor="666666" baseFont="宋体" baseFontSize="14" bgColor="FFFFFF" bgAlpha="0" showBorder="0" bgAngle="360" pieYScale="90"  pieSliceDepth="5" smartLineColor="666666">';
		if ($datas){
			foreach ($datas as $k=>$v){
				$xml.='<set label="'.$this->modules[$k]['name'].'" value="'.$v.'"/>';
			}
		}
		$xml.='</chart>';
		$this->assign('items',$items);
		$this->assign('xml',$xml);
		$this->display('wechatList');
	}
	public function statistics(){
		$days=7;
		$this->assign('days',$days);
		$this->modules=$this->_modules();
		$where=array('token'=>$this->token);
		$where['enddate']=array('gt',time()-$days*24*3600);
		$behavior_db=M('Behavior');
		$where['model']=array('neq','');
		$items=$behavior_db->where($where)->order('num DESC')->select();
		
		$datas=array();
		if ($items){
			foreach ($items as $item){
				$module=strtolower($item['model']);
				if (key_exists($module,$datas)){
					$datas[$module]+=$item['num'];
				}else {
					$datas[$module]=$item['num'];
				}
			}
		}
		asort($datas);
		
		$xml='<chart borderThickness="0" caption="'.$days.'日内粉丝行为分析" baseFontColor="666666" baseFont="宋体" baseFontSize="14" bgColor="FFFFFF" bgAlpha="0" showBorder="0" bgAngle="360" pieYScale="90"  pieSliceDepth="5" smartLineColor="666666">';
		if ($datas){
			foreach ($datas as $k=>$v){
				$xml.='<set label="'.$this->modules[$k]['name'].'" value="'.$v.'"/>';
			}
		}
		$xml.='</chart>';

		$this->assign('items',$items);
		$this->assign('xml',$xml);
		//
		$list=array();
		if ($datas){
			foreach ($datas as $k=>$d){
				if ($this->modules[$k]['detail']){
					array_push($list,array('module'=>$k,'count'=>$d,'name'=>$this->modules[$k]['name']));
				}
			}
		}
		$list=array_reverse($list);
		$this->assign('statisticsAll',1);
		$this->assign('list',$list);
		$this->assign('listinfo',1);
		$this->display();
	}
	public function statisticsTrend(){
		$now=time();
		$this->modules=$this->_modules();
		$where=array('token'=>$this->token);
		$days=7;
		$this->assign('days',$days);
		$where['enddate']=array('gt',$now-$days*24*3600);
		$behavior_db=M('Behavior');
		$items=$behavior_db->where($where)->order('num DESC')->select();
		$datas=array();
		$datas2=array();
		if ($items){
			foreach ($items as $item){
				$module=strtolower($item['model']);
				$datas2[$module]=0;
				if (key_exists($module,$datas)){
					$datas[$module]+=$item['num'];
				}else {
					$datas[$module]=$item['num'];
				}
			}
		}
		asort($datas);
		//
		
		$starttime=$now-2*$days*24*3600;
		$endtime=$now-$days*24*3600;
		$items2=$behavior_db->where('token=\''.$this->token.'\' AND enddate>'.$starttime.' AND enddate<'.$endtime)->select();
		if ($items2){
			foreach ($items2 as $item){
				$module=strtolower($item['model']);
				$datas2[$module]+=$item['num'];
			}
		}
		//
		$list=array();
		//
		$xml='<chart bgColor="ffffff" outCnvBaseFontColor="666666" caption="'.$days.'天趋势分析图" xAxisName="模块" yAxisName="数量" showNames="1" showValues="0" plotFillAlpha="50" numVDivLines="10" showAlternateVGridColor="1" bgAlpha="0" showBorder="0" bgColor="ffffff" AlternateVGridColor="e1f5ff" divLineColor="e1f5ff" vdivLineColor="e1f5ff" baseFontColor="666666" baseFontSize="12" borderThickness="0" canvasBorderThickness="0" showPlotBorder="0" plotBorderThickness="0" canvasBorderColor="eeeeee">';
		$categoryStr='<categories>';
		$dataStr1='<dataset seriesName="本周期" color="B1D1DC" plotBorderColor="B1D1DC">';
		if ($datas){
			$i=0;
			foreach ($datas as $k=>$v){
				$mName=$this->modules[$k]['name'];
				if (!$mName){
					$mName=$k;
				}
				$list[$i]=array('name'=>$mName,'count'=>$v,'lastcount'=>0);
				$categoryStr.='<category label="'.$mName.'"/>';
				$dataStr1.='<set value="'.$v.'"/>';
				$i++;
			}
		}
		$categoryStr.='</categories>';
		$dataStr1.='</dataset>';
		//
		$dataStr2='<dataset seriesName="上一周期" color="C8A1D1" plotBorderColor="C8A1D1">';
		if ($datas2){
			$i=0;
			foreach ($datas2 as $k=>$v){
				$list[$i]['lastcount']=$v;
				$dataStr2.='<set value="'.$v.'"/>';
				$i++;
			}
		}
		$dataStr2.='</dataset>';
		//
		
		$xml.=$categoryStr.$dataStr1.$dataStr2.'</chart>';
		$this->assign('xml',$xml);
		$this->assign('statisticsTrend',1);
		$this->assign('list',$list);
		
		$this->display('statistics');
	}
	public function statisticsOfModule(){
		$this->modules=$this->_modules();
		if (!$this->modules[$_GET['module']]){
			$this->error('非法操作');
		}
		$where=array('token'=>$this->token);
		$where['enddate']=array('gt',time()-30*24*3600);
		$where['model']=$_GET['module'];
		//
		$behavior_db=M('Behavior');
		$items=$behavior_db->where($where)->order('num DESC')->select();
		
		$list=array();
		$ids=array();
		if ($items){
			foreach ($items as $item){
				if (in_array($item['fid'],$ids)){
					$list[$item['fid']]['count']+=$item['num'];
				}else {
					$list[$item['fid']]=array('count'=>$item['num']);
					array_push($ids,$item['fid']);
				}
			}
		}
	
		asort($list);
			
		//
		$db=M($_GET['module']);
		$ns=$db->where(array('id'=>array('in',$ids)))->select();
		$nsByID=array();
		if ($ns){
			foreach ($ns as $n){
				$nsByID[$n['id']]=$n;
			}
		}
		
		if ($list){
			foreach ($list as $k=>$l){
				$list[$k]['fid']=$nsByID[$k]['id'];
				$list[$k]['name']=$nsByID[$k]['name']?$nsByID[$k]['name']:$nsByID[$k]['title'];
				if (!$list[$k]['fid']){
					unset($list[$k]);
				}
			}
		}
		$this->assign('list',$list);
		//
		$xml='<chart borderThickness="0" caption="'.$this->modules[$_GET['module']]['name'].'详细统计" baseFontColor="666666" baseFont="宋体" baseFontSize="14" bgColor="FFFFFF" bgAlpha="0" showBorder="0" smartLineColor="cccccc"  showValues="0" canvasBorderThickness="1" canvasBorderColor="eeeeee" decimalPrecision="0" plotFillAngle="30" plotBorderColor="999999" showAlternateVGridColor="1" divLineAlpha="0">';
		if ($list){
			foreach ($list as $k=>$v){
				$xml.='<set label="'.$v['name'].'" value="'.$v['count'].'"/>';
			}
		}
		$xml.='</chart>';
		$this->assign('xml',$xml);
		//
		$this->assign('detail',1);
		$this->assign('listinfo',1);
		$this->display('statistics');
	}
	private function getModel($model,$type='1'){
		$data['token']=session('token');
		$data['model']=$model;
		if($type==1){
			$data['openid']=$this->openid;
		}
		$sqlArray= $this->data->where($data)->select();
		return count($sqlArray);
		//return $data;
	}
	public function _modules(){
		return array(
		'home'=>array('name'=>'微网站'),
		'text'=>array('name'=>'文本请求','detail'=>1),
		'member_card_set'=>array('name'=>'会员卡'),
		'lottery'=>array('name'=>'推广活动','detail'=>1),
		'help'=>array('name'=>'帮助'),
		'wedding'=>array('name'=>'婚庆喜帖','detail'=>1),
		'img'=>array('name'=>'图文消息','detail'=>1),
		'selfform'=>array('name'=>'万能表单','detail'=>1),
		'host'=>array('name'=>'通用订单','detail'=>1),
		'panorama'=>array('name'=>'全景','detail'=>1),
		'usernamecheck'=>array('name'=>'账号审核'),
		'album'=>array('name'=>'相册'),
		'vote'=>array('name'=>'投票','detail'=>1),
		'product'=>array('name'=>'商城','detail'=>1),
		'voiceresponse'=>array('name'=>'语音消息'),
		'estate'=>array('name'=>'房产'),
		'follow'=>array('name'=>'关注'),
		);
	}
	public function modelName($str){
		$array=array(
			'3G微网站'=>'3G微网站',
			'Lottery'=>'1',
			'Member_card_set'=>'会员卡',
			'Wedding'=>'喜帖',
			'Img'=>'图文信息',
			'帮助'=>'帮助提示',
			'Selfform'=>'万能表单功能',
			'Text'=>'文本信息',
			'Host'=>'订单信息',
			'帐号审核'=>'帐号审核',
			'3g相册'=>'帐号审核',
			'Vote'=>'投票活动',
			'Product'=>'电商产品',
		);
		return $array[$str];
	}
	
}

?>
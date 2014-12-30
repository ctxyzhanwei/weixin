<?php
class FunctionAction extends UserAction{

	function index(){


		$array = trim($array['id']["uid"]);
		
		
		$id=$this->_get('id','intval');

		if (!$id){
			//$token=$this->token;
			$info=M('Wxuser')->find(array('where'=>array('token'=>$this->token)));
		}else {
			$info=M('Wxuser')->find($id);
		}
		
		$token=$this->_get('token','trim');	
		if($info==false||$info['token']!=$token){
			$this->error('非法操作',U('Home/Index/index'));
		}
		session('token',$token);
		session('wxid',$info['id']);
		session('companyid', null);
		//第一次登陆　创建　功能所有权
		$token_open=M('Token_open');
		
		$toback        =$token_open->field('id,queryname')->where(array('token'=>session('token'),'uid'=>session('uid')))->find();
		$open['uid']   =session('uid');
		$open['token'] =session('token');
		//遍历功能列表
		if (!C('agent_version') || !$this->agentid ){
			$group=M('User_group')->field('id,name,func')->where('status=1 AND id = '.session('gid'))->order('id ASC')->find();
			$funcs = M('Function')->where("1 = 1")->select();
		}else {
			$group=M('User_group')->field('id,name,func')->where('status=1 AND agentid='.$this->agentid.' AND id = '.session('gid'))->order('id ASC')->find();
			$funcs = M('Agent_function')->where(array('agentid'=>$this->agentid))->select();
		}

		$check=explode(',',trim($toback['queryname'],','));
/*
		foreach ($check as $ck => $cv){
			if(strpos($group['func'],$cv) === false){
				$group['func'] .= ','.$cv;
			}
		
		}

		
*/
		$group['func'] = explode(',',$group['func']);
		
			foreach ($group['func'] as $gk=>$gv){
				
					$open_func = M('Token_open')->where(array('token'=>session('token'),'uid'=>session('uid')))->getField('queryname');

					if(strpos($open_func,$gv) === false){
						$where = array('funname'=>$gv,'status'=>1);
					}else{
						$where = array('funname'=>$gv);
					}
					
					if (C('agent_version')&&$this->agentid){
						$where['agentid'] = $this->agentid;
						$group['func'][$gk] = M('Agent_function')->where($where)->field('id,funname,name,info')->find();
					}else{
						$group['func'][$gk] = M('Function')->where($where)->field('id,funname,name,info')->find();
					}
					
				if($group['func'][$gk] == NULL){
					unset($group['func'][$gk]);
				}
			}
			
			
		$this->assign('fun',$group);
		
		//
		$wecha=M('Wxuser')->field('wxname,wxid,headerpic,weixin')->where(array('token'=>session('token'),'uid'=>session('uid')))->find();
		$this->assign('wecha',$wecha);
		$this->assign('token',session('token'));
		$this->assign('check',$check);
		$this->display();
	}




	function welcome(){
		
		if(session('token') === NULL || session('token') != $this->_get('token','trim')){
			$token=$this->_get('token','trim');	
			session('token',$token);
		}
		$wecha=M('Wxuser')->field('wxname,wxid,headerpic,weixin')->where(array('token'=>session('token'),'uid'=>session('uid')))->find();
		$this->assign('wecha',$wecha);
		$this->assign('token',session('token'));
		// 模板 0 不让进
		if ($this->usertplid != 1) {
			$this->error('您需要选择使用新的模板才能进入此页');
		}

		$data = array();

		$data['mp']     = M('Wxuser')->where(array('uid'=>intval(session('uid'))))->count();
		$data['card']   = M('Member_card_create')->where(array('token'=>$this->token,'wecha_id'=>array('neq','')))->count();
		$data['active'] = M('Lottery')->where(array('token'=>$this->token))->count();
		$data['img']    = M('Img')->where(array('token'=>$this->token))->count();
		$this->assign('data',$data);

	

			
			$month=date('m');
			$year=date('Y');
		
		$this->assign('month',$month);
		$this->assign('year',$year);


	//关注和请求数统计
		$where=array('token'=>$this->token,'month'=>$month,'year'=>$year);
		$list=M('Requestdata')->where($where)->limit(31)->order('id ASC')->select();
		if($list){
			foreach ($list as $k => $v){
				$charts['xAxis']  .= '"'.$v['day'].'日",';
				$charts['follow'] .= '"'.$v['follownum'].'",';
				$charts['text']   .= '"'.$v['textnum'].'",';
				
			}
		}else{
			for($i=0;$i<30;$i++){
				$charts['xAxis']  .= '"'.($i+1).'日",';
				$charts['follow'] .= '"'.rand(1,100).'",';
				$charts['text']   .= '"'.rand(100,300).'",';

			}
				$charts['ifnull'] = 1;
		}
		

		function trim_map($val){
			return rtrim($val,',');
		}
		$charts = array_map('trim_map',$charts);
		$this->assign('charts',$charts);

	//粉丝行为数据统计

		$days=7;
		$this->assign('days',$days);
		$modules = R('User/Wechat_behavior/_modules');
		$where=array('token'=>$this->token);
		$where['enddate']=array('gt',time()-$days*24*3600);
		$behaviorDB=M('Behavior');
		$where['model']=array('neq','');
		$items=$behaviorDB->where($where)->order('num DESC')->select();

	if($items){
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

		if ($datas){
			foreach ($datas as $k=>$v){
				if($modules[$k]['name']){
					//$pie['legend'] .= "'".$modules[$k]['name']."',";
					$pie['series'] .= "{value:$v, name:'".$modules[$k]['name']."'},";
				}


			}
		}
		$pie = array_map('trim_map',$pie);
	}else{
		$pie = array(
					'ifnull' => 1,
					'series' => "{value:".rand(1,100).", name:'万能表单'},{value:".rand(1,100).", name:'商城'},{value:".rand(1,100).", name:'全景'},{value:".rand(1,100).", name:'关注'},{value:".rand(1,100).", name:'文本请求'},{value:".rand(1,100).", name:'图文消息'},{value:".rand(1,100).", name:'通用订单'},{value:".rand(1,100).", name:'投票'},{value:".rand(1,100).", name:'婚庆喜帖'},{value:".rand(1,100).", name:'会员卡'},{value:".rand(1,100).", name:'推广活动'}");
	}

		$this->assign('pie',$pie);

	//会员性别统计

	$man = M('Userinfo')->where(array('token'=>$this->token,'sex'=>1))->count();
	$woman = M('Userinfo')->where(array('token'=>$this->token,'sex'=>2))->count();
	$other = M('Userinfo')->where(array('token'=>$this->token,'sex'=>3))->count();

	if($man == 0 && $woman == 0 && $other == 0){
		$man	=	rand(1,50);
		$woman	=	rand(1,100);
		$other	=	rand(1,10);
		$sex_series['ifnull'] = 1;
	}
		$sex_series['series'] = "{value:$man, name:'男'},"."{value:$woman, name:'女'},"."{value:$other, name:'其他'}";
		
		$this->assign('sex_series',$sex_series);
		$this->display();
	}

	public function admin(){
		$this->display();
	}

}

?>
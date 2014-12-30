<?php

	class MarketAction extends WapAction{
		public $market_db;
		public $token;
		public $wecha_id;
		public $thisMarket;
		public $homeInfo;
		public function _initialize() {
        	parent::_initialize();

        	$this->market_db	= M('market'); 
       	 	$this->token 		= $this->_get('token');
        	$this->market_id 	= $this->_get('id');

			if (!defined('RES')){
				define('RES',THEME_PATH.'common');
			}
			$this->wecha_id		= $this->_get('wecha_id');
			if (!$this->wecha_id){
				$this->wecha_id	= 'null';
			}
			/*
			$catemenu = array(
				array(
					'url' 		=> "/index.php?g=Wap&m=Market&a=tenant&token=$this->token&wecha_id=$this->wecha_id",
					'picurl'	=> "/tpl/User/default/common/images/photo/plugmenu5.png",
					'name' 		=> '商户',
				),
				array(
					'url' 		=> "/index.php?g=Wap&m=Market&a=index&token=$this->token&wecha_id=$this->wecha_id",
					'picurl'	=> "/tpl/User/default/common/images/photo/plugmenu6.png",
					'name' 		=> '首页',
				),
				array(
					'url' 		=> "/index.php?g=Wap&m=Market&a=intro&token=$this->token&wecha_id=$this->wecha_id",
					'picurl'	=> "/tpl/User/default/common/images/photo/plugmenu4.png",
					'name' 		=> '简介',
				),

			);
$this->assign('catemenu',$catemenu);
			*/

			

			
        	$this->thisMarket	= $this->market_db->where(array('token'=>$this->token))->find(); //设置信息
        	$this->assign('siteCopyright',$this->siteCopyright);
        	$this->assign('thisMarket',$this->thisMarket);
        	$this->assign('token',$this->token);
        	$this->assign('wecha_id',$this->wecha_id);
        	$this->assign('addtype',$addtype);
    	}

/*--------------------------------商圈首页------------------------------------------*/
		public function index(){	
			$tplData 	= array(); 	//模板
			$arr 		= array();  

			//获取模板信息
			include('./PigCms/Lib/ORG/index.Tpl.php');

			foreach($tpl as $k=>$v){
				if($v['tpltypeid'] == $this->thisMarket['market_index_tpl']){
					$arr = $v;
				}
			}

			$tplData['color_id'] 	= 0;
			$tplData['tpltypeid'] 	= $arr['tpltypeid'];
			$tplData['tpltypename'] = $arr['tpltypename'];
			$tplData['wxname'] 		= $this->thisMarket['name'];

			$nav 	= M('Market_nav')->where(array('market_id'=>$this->thisMarket['market_id'],'token'=>$this->token,'is_show'=>'1'))->order('sort desc')->select();

			foreach($nav as $key=>$value){			
				$info[] 	= array('url'=>str_replace(array('{wechat_id}','{siteUrl}'),array($this->wecha_id,$this->siteUrl),$value['nav_link']),'name'=>$value['nav_name'],'img'=>$value['nav_pic']);
			}
			
			$this->assign('flash',$this->_getFlash());
			$this->assign('tpl',$tplData);
			$this->assign('info',$info);	

			$this->display('Index:'.$tplData['tpltypename']);	
		}



		/*商圈介绍*/
		public function intro(){

			$this->display();
		}

		/*商圈介绍*/
		public function maps(){

			$this->apikey	= C('baidu_map_api');
			$this->assign('apikey',$this->apikey);
			$this->display();
		}
/*-----------------------------商圈停车场-----------------------------------------*/
		public function park(){
			$park_db 	= M('market_park');
			$park_list 	= $park_db->where(array('market_id'=>$this->thisMarket['market_id'],'token'=>$this->token))->select();

			$this->assign('park_list',$park_list);
			$this->display();
		}

/*-----------------------------商户首页-------------------------------------------*/
		public function tenant(){

			$tplData 	= array(); 	//模板
			$arr 		= array();  
			//获取模板信息
			include('./PigCms/Lib/ORG/index.Tpl.php');

			foreach($tpl as $k=>$v){
				if($v['tpltypeid'] == $this->thisMarket['tenant_index_tpl']){
					$arr = $v;
				}
			}

			$tplData['color_id'] 	= 0;
			$tplData['tpltypeid'] 	= $arr['tpltypeid'];
			$tplData['tpltypename'] = $arr['tpltypename'];
			$tplData['wxname'] 		= $this->thisMarket['name'];




			//分类信息
			$info 		= array(
				array(
						'url'	=>"/index.php?g=Wap&m=Market&a=tenant&token=$this->token&wecha_id=$this->wecha_id",
						'name'	=>'最新加入',
						'img'	=>'',
						'sub'	=>$this->_getTenant('','',9)
					),
				array(
						'url'	=>"/index.php?g=Wap&m=Market&a=tenant&token=$this->token&wecha_id=$this->wecha_id",
						'name'	=>'按类别',
						'img'	=>'',
						'sub' 	=>$this->_getCate()
					),
				array(
						'url'	=>"/index.php?g=Wap&m=Market&a=tenant&token=$this->token&wecha_id=$this->wecha_id",
						'name'	=>'按区域',
						'img'	=>'',
						'sub'	=>$this->_getArea()
					),
				
				'sub'=>$sub,
			);  


			$this->assign('info',$info);
			$this->assign('flash',$this->_getFlash());
			$this->assign('tpl',$tplData);
			//$this->display('Index:'.$tplData['tpltypename']);
			$this->display('tenant_index');
		}

		public function tenant_list(){
			$cate_id 	= $this->_get('cate_id','intval');
			$area_id 	= $this->_get('area_id','intval');


			$tplData 	= array(); 	//模板
			$arr 		= array();  
			//获取模板信息
			include('./PigCms/Lib/ORG/index.Tpl.php');

			foreach($tpl as $k=>$v){
				if($v['tpltypeid'] == $this->thisMarket['tenant_list_tpl']){
					$arr = $v;
				}
			}

			if($cate_id){
				//$cate_info = M('Market_cate')->where(array('cate_id'=>$cate_id,'token'=>$this->token,'market_id'=>$this->thisMarket['market_id']))->find();
				/*
				$info = array(
						array(
							//'url'	=> "###",
							//'name'	=> $cate_info['cate_name'],
							//'img'	=> $cate_info['cate_pic'],
							'sub'	=> $this->_getTenant($cate_id)
						),
					);*/
				$info =$this->_getTenant($cate_id);
			}	

			if($area_id){
				//$area_info = M('Market_area')->where(array('area_id'=>$area_id,'token'=>$this->token,'market_id'=>$this->thisMarket['market_id']))->find();
				/*$info = array(
						array(
							//'url'	=> "###",
							//'name'	=> $area_info['area_name'],
							//'img'	=> $area_info['area_pic'],
							'sub'	=> $this->_getTenant('',$area_id)
						),
					);*/
				$info = $this->_getTenant('',$area_id);
			}

			$tplData['color_id'] 	= 0;
			$tplData['tpltypeid'] 	= $arr['tpltypeid'];
			$tplData['tpltypename'] = $arr['tpltypename'];
			$tplData['wxname'] 		= $this->thisMarket['name'].'-'.$info[0]['name'];


			$this->assign('flash',$this->_getFlash());
			$this->assign('info',$info);
			$this->assign('tpl',$tplData);
			//$this->assign('flash',$this->_getFlash());
			$this->display('Index:'.$tplData['tpltypename']);
		}


		/*商户详情页*/
		public function tenant_info(){
			$id 	= $this->_get('id','intval');

			$tenant = M('Company')->where(array('token'=>$this->token,'id'=>$id,'isbranch'=>'1'))->find();

			//dump($tenant);
			$this->assign('tenant',$tenant);
			$this->display();
		}		

		public function tenant_maps(){
			$tenant = M('Company')->where(array('token'=>$this->token,'id'=>$this->_get('id','intval'),'isbranch'=>'1'))->find();

			$this->assign('tenant',$tenant);
			$this->apikey	= C('baidu_map_api');
			$this->assign('apikey',$this->apikey);
			$this->display();
		}







/*-------------------------数据调用方法-----------------------------*/

		/*分类查询*/
		public function _getCate($num){

			$where	= array('token'=>$this->token,'market_id'=>$this->thisMarket['market_id'],'is_show'=>'1');
			$cate 	= M('market_cate')->where($where)->limit($num)->select(); 
			$sub = array();
			foreach($cate as $key=>$value){
				$sub[$key]['img'] 	= $value['cate_pic'];
				$sub[$key]['name'] 	= $value['cate_name'];
				$sub[$key]['url'] 	= "/index.php?g=Wap&m=Market&a=tenant_list&token=$this->token&wecha_id=$this->wecha_id&cate_id={$value['cate_id']}";
			}

			return $sub;
		}




		/*分类查询*/
		public function _getArea(){
			$where	= array('token'=>$this->token,'market_id'=>$this->thisMarket['market_id']);

			$area 	= M('market_area')->where($where)->limit($num)->select(); 
			$sub = array();
			foreach($area as $key=>$value){
				$sub[$key]['img'] 	= $value['area_pic'];
				$sub[$key]['name'] 	= $value['area_name'];
				$sub[$key]['url'] 	= "/index.php?g=Wap&m=Market&a=tenant_list&token=$this->token&wecha_id=$this->wecha_id&area_id={$value['area_id']}";
			}

			return $sub;
		}

		/*楼层查询*/
		/*查询商户信息*/
		public function _getTenant($cate_id="",$area_id="",$num=""){
			$where = array('token'=>$this->token,'market_id'=>$this->thisMarket['market_id'],'isbranch'=>'1');
			$order = 'add_time desc';
			if($cate_id){
				$where['cate_id'] 	= $cate_id;
			}
			if($area_id){
				$where['area_id'] 	= $area_id;
			}
			$tenant_list = M('Company')->where($where)->order($order)->limit($num)->select();
			$sub = array();
			foreach($tenant_list as $key=>$value){
				$sub[$key]['img'] 	= $value['logourl'];
				$sub[$key]['name'] 	= $value['name'];
				$sub[$key]['info'] 	= $value['shortname'];
				$sub[$key]['url'] 	= "/index.php?g=Wap&m=Market&a=tenant_info&token=$this->token&wecha_id=$this->wecha_id&id={$value['id']}";
			}
			return $sub;
		}	
		/*获取幻灯片*/
		public function _getFlash(){
			//幻灯片
			$flash 	= array();			
			$slide 	= M('Market_slide')->where(array('market_id'=>$this->thisMarket['market_id'],'slide_url'=>array('neq','')))->order('slide_id desc')->limit('5')->select(); 

			foreach($slide as $key=>$value){		
				$flash[$key]['info'] 	= $value['slide_title'];
				$flash[$key]['url'] 	= str_replace(array('{wechat_id}','{siteUrl}'),array($this->wecha_id,$this->siteUrl),$value['slide_link']);
				$flash[$key]['img'] 	= $value['slide_url'];
			}

			return $flash;
		}




	}
?>
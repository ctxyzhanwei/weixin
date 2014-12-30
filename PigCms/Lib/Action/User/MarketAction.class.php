<?php
class MarketAction extends UserAction{
	public $market_db;
	public $area_db;
	public $park_db;
	public $cate_db;
	public $tenant_db;
	public $token;
	public $market_id;
	public function _initialize() {
        parent::_initialize();
        $this->canUseFunction('Market');    
        $this->market_db	= D('Market');
        $this->area_db		= D('Market_area');
        $this->park_db		= D('Market_park');
        $this->cate_db		= D('Market_cate');
        $this->tenant_db	= D('Company');
        $market_id 	= $this->market_db->where(array('token'=>$this->token))->getField('market_id');
        if(empty($market_id) && ACTION_NAME != 'index'){
        	$this->error('设置商圈后才能使用',U('Market/index',array('token'=>$this->token)));
        }else{
        	$this->market_id =  $market_id;
        }

        $this->token 		= session('token');

    }


/*----------------------------------设置区-------------------------------------------*/
    /*商圈设置首页*/
    public function index(){
    	$keyword_db	= M('keyword'); //关键词
    	$where 		= array('token'=>$this->token);
    	$market 	= $this->market_db->where($where)->find();

    	if(IS_POST){

            $slide['slide_title']       = $this->_request('slid_title','trim')?$this->_request('slid_title','trim'):'';
            $slide['slide_link']        = $this->_request('link','trim');
            $slide['slide_url']         = $this->_request('slide','trim');

    		if($this->market_db->create()){
    			if($market){//修改	
    				$this->market_db->where($where)->save($_POST);                  
                    $this->_market_slide($this->_post('market_id','intval'),$slide,'update'); //更新幻灯片
					
                    $keyword['pid']		= $this->_post('market_id','intval');
                	$keyword['module']	= 'Market';
               		$keyword['token']	= $this->token;
               		$keyword['keyword']	= $this->_post('keyword','trim');
                	$keyword_db->where(array('token'=>$this->token,'pid'=>$this->_post('market_id','intval'),'module'=>'Market'))->save($keyword);//更新关键词表

                	$this->success('修改成功',U('Market/index',array('token'=>$this->token)));	
    			}else{ //添加
    				$_POST['token']		= $this->token;
    				$id 				= $this->market_db->add($_POST);
                    $this->_market_slide($id,$slide);   //插入幻灯片表
                    $this->_create_nav($id);   //生成默认菜单版块
					$keyword['pid']		= $id;
                	$keyword['module']	= 'Market';
               		$keyword['token']	= $this->token;
               		$keyword['keyword']	= $this->_post('keyword','trim');
                	$keyword_db->add($keyword);

                	$this->success('设置成功',U('Market/index',array('token'=>$this->token)));   			
    			}

    		}else{
    				$this->error($this->market_db->getError());
    		}

    	}else{
            $slide = $this->_get_slide($market['market_id']);
            $this->assign('default','./tpl/static/market/default.jpg');//默认图文消息图片
            $this->assign('slide',$slide);//幻灯片信息
    		$this->assign('market',$market);
    		$this->display();
    	}
    }

   public function _create_nav($id){
       $name = array(
             '商家',
             '停车场',
             '简介',
             '团购'     
        );
        $url = array(
            '{siteUrl}/index.php?g=Wap&m=Market&a=tenant&token='.$this->token.'&wecha_id={wechat_id}',
            '{siteUrl}/index.php?g=Wap&m=Market&a=park&token='.$this->token.'&wecha_id={wechat_id}',
            '{siteUrl}/index.php?g=Wap&m=Market&a=intro&token='.$this->token.'&wecha_id={wechat_id}',
            '{siteUrl}/index.php?g=Wap&m=Groupon&a=grouponIndex&token='.$this->token.'&wecha_id={wechat_id}',
            
        );

        $pic = array(
            './tpl/static/attachment/icon/white/1.png',
            './tpl/static/attachment/icon/white/4.png',
            './tpl/static/attachment/icon/white/9.png',
            './tpl/static/attachment/icon/white/7.png',
            './tpl/static/attachment/icon/white/14.png',
            './tpl/static/attachment/icon/white/15.png',
        );
        $system = array(
            '1',
            '1',
            '1',
            '1',
            '0',
            '0'
        );
        $Classify  = M('Classify')->where(array('token'=>$this->token,'path'=>'0','fid'=>0))->limit(2)->select();
        foreach ($Classify as $key => $value) {
            $name[] = $value['name'];
            $url[]  = $value['url'];
        }

        for ($i=0; $i < count($name) ; $i++) { 
            M('Market_nav')->add(array('nav_name'=>$name[$i],'nav_link'=>$url[$i],'nav_pic'=>$pic[$i],'is_show'=>'1','is_system'=>$system[$i],'sort'=>(100-$i),'token'=>$this->token,'market_id'=>$id));
        }
   }

    /*添加编辑幻灯片操作*/
    public function _market_slide($market_id,$data,$handle='insert'){
        $slide_db   = M('market_slide');
        if($handle == 'insert'){
            $count      = count($data['slide_url']); 
            for($i=0;$i<$count;$i++){
                $arr['market_id']   = $market_id;
                $arr['slide_url']   = $data['slide_url'][$i];
                $arr['slide_title'] = $data['slide_title'][$i];
                $arr['slide_link']  = $data['slide_link'][$i];
                $slide_db->add($arr);
                
            }
        }else{ 
            $slide_id   = $slide_db->where(array('market_id'=>$market_id))->getField('slide_id',true);
            $count      = count($data['slide_url']); 
            for($i=0;$i<$count;$i++){
                $arr['slide_url']   = $data['slide_url'][$i];
                $arr['slide_title'] = $data['slide_title'][$i];
                $arr['slide_link']  = $data['slide_link'][$i];
                $slide_db->where(array('market_id'=>$market_id,'slide_id'=>$slide_id[$i]))->save($arr);
            }
        }
    }
    /*获取幻灯片数据*/
    public function _get_slide($market_id){
        $slide_db   = M('market_slide');
        $arr        = array();
        $where      = array('market_id'=>$market_id);
        $list       = $slide_db->where($where)->order('slide_id asc')->select();

        $arr    = array();
        foreach($list as $key=>$value){
            if($value['slide_url'] != ''){
                $arr['slide_'.$key] = $value['slide_url'];
                $arr['link_'.$key]  = $value['slide_link'];
                $arr['title_'.$key] = $value['slide_title'];
            }    
        }
        if(empty($arr)){
            $arr['slide_0'] = './tpl/static/attachment/focus/default/2.jpg';
            $arr['slide_1'] = './tpl/static/attachment/focus/default/3.jpg';
            $arr['slide_2'] = './tpl/static/attachment/focus/default/4.jpg';
        }

        return $arr;
    }

/*------------------------------------------------------商圈区域管理区-------------------------------------------------------*/
     /*列表首页*/
    public function area(){
    	$where    = array('token'=>$this->token,'market_id'=>$this->market_id);

    	$count    = $this->area_db->where($where)->count();
		$Page     = new Page($count,15);
    	$area 	  = $this->area_db->where($where)->order('sort desc,add_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();

    	$this->assign('page',$Page->show());
    	$this->assign('area',$area);
    	$this->display();    	
    }
    
    public function area_set(){
    	$where 	= array('token'=>$this->token,'market_id'=>$this->market_id,'area_id'=>$this->_get('area_id','intval'));
    	$area 	= $this->area_db->where($where)->find();

    	if(IS_POST){
    		if($this->area_db->create()){
    			if($area){//修改	
                    $_POST['add_time']      = time();
                    $_POST['is_use']        = empty($_POST['is_use'])?'0':$_POST['is_use'];
                    $_POST['area_intro']    = $this->_post('area_intro','trim');   
    				$this->area_db->where($where)->save($_POST);
                	$this->success('修改成功',U('Market/area',array('token'=>$this->token)));	
    			}else{ //添加
    				$_POST['token'] 		= $this->token;
    				$_POST['market_id'] 	= $this->market_id;
    				$_POST['add_time'] 		= time();
                    $_POST['is_use']        = empty($_POST['is_use'])?'0':$_POST['is_use'];
                    $_POST['area_intro']    = $this->_post('area_intro','trim');
    				$this->area_db->add($_POST);
                	$this->success('添加成功',U('Market/area',array('token'=>$this->token)));
    			}

    		}else{
    				$this->error($this->area_db->getError());
    		}

    	}else{
    		$this->assign('area',$area);
    		$this->display();
    	}
    }

    public function area_del(){
        $where  = array('token'=>$this->token,'area_id'=>$this->_get('area_id','intval'));
        if($this->area_db->where($where)->delete()){
            $this->success('删除成功',U('Market/area',array('token'=>$this->token)));
        }

    }
/*------------------------------------------------------停车场管理区-------------------------------------------------------*/
    /*列表首页*/
    public function park(){
        $where  = array('token'=>$this->token,'market_id'=>$this->market_id);

        $count  = $this->park_db->where($where)->count();
        $Page   = new Page($count,15);
        $park   = $this->park_db->where($where)->order('add_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('page',$Page->show());
        $this->assign('park',$park);

    	$this->display();
    }

    public function park_set(){
        $where  = array('token'=>$this->token,'market_id'=>$this->market_id,'park_id'=>$this->_get('park_id','intval'));
        $park   = $this->park_db->where($where)->find();

        if(IS_POST){
            if($this->park_db->create()){
                if($park){//修改  
                    $_POST['add_time']      = time();  
                    $_POST['is_use']        = empty($_POST['is_use'])?'0':$_POST['is_use'];    
                    $_POST['park_intro']    = $this->_post('park_intro','trim');           
                    $this->park_db->where($where)->save($_POST);

                    $this->success('修改成功',U('Market/park',array('token'=>$this->token)));   
                }else{ //添加
                    $_POST['token']         = $this->token;
                    $_POST['market_id']     = $this->market_id;
                    $_POST['add_time']      = time();
                    $_POST['is_use']        = empty($_POST['is_use'])?'0':$_POST['is_use'];
                    $_POST['park_intro']    = $this->_post('park_intro','trim');  
                    $this->park_db->add($_POST);
                    $this->success('添加成功',U('Market/park',array('token'=>$this->token)));
                }

            }else{
                    $this->error($this->park_db->getError());
            }

        }else{
            $this->assign('park',$park);
            $this->display();
        }


    }

    public function park_del(){
        $where  = array('token'=>$this->token,'park_id'=>$this->_get('park_id','intval'));
        if($this->park_db->where($where)->delete()){
            $this->success('删除成功',U('Market/park',array('token'=>$this->token)));
        }
    }
/*------------------------------------------------------分类管理区-------------------------------------------------------*/
     /*分类首页*/
    public function cate(){
        $where      = array('token'=>$this->token,'market_id'=>$this->market_id);

        $count      = $this->cate_db->where($where)->count();
        $Page       = new Page($count,15);
        $cate       = $this->cate_db->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('page',$Page->show());
        $this->assign('cate',$cate);


    	$this->display();
    }

    public function cate_set(){
        $where  = array('token'=>$this->token,'market_id'=>$this->market_id,'cate_id'=>$this->_get('cate_id','intval'));
        $cate   = $this->cate_db->where($where)->find();

        if(IS_POST){
            if($this->cate_db->create()){
                if($cate){//修改   
                    $_POST['is_show']        = empty($_POST['is_show'])?'0':$_POST['is_show'];           
                    $this->cate_db->where($where)->save($_POST);

                    $this->success('修改成功',U('Market/cate',array('token'=>$this->token)));   
                }else{ //添加
                    $_POST['token']         = $this->token;
                    $_POST['market_id']     = $this->market_id;
                    $_POST['is_show']        = empty($_POST['is_show'])?'0':$_POST['is_show'];
                    $this->cate_db->add($_POST);
                    $this->success('添加成功',U('Market/cate',array('token'=>$this->token)));
                }

            }else{
                    $this->error($this->cate_db->getError());
            }

        }else{
            $this->assign('cate',$cate);
            $this->display();
        }       
    }

    function cate_del(){
        $where  = array('token'=>$this->token,'cate_id'=>$this->_get('cate_id','intval'));
        if($this->cate_db->where($where)->delete()){
            $this->success('删除成功',U('Market/cate',array('token'=>$this->token)));
        }
    }

/*------------------------------------------------------商家管理区-------------------------------------------------------*/
 	/*商家首页*/
    public function tenant(){
        //分类信息
        $cate_list = $this->cate_db->where(array('token'=>$this->token,'market_id'=>$this->market_id,'is_show'=>'1'))->field('cate_name,cate_id')->order('sort desc')->select();
        //区域信息
        $area_list = $this->area_db->where(array('token'=>$this->token,'market_id'=>$this->market_id,'is_use'=>'1'))->field('area_name,area_id')->order('sort desc,add_time desc')->select();


        $where      = array('token'=>$this->token,'market_id'=>$this->market_id,'isbranch'=>'1');

        $like    = $this->_post('like','trim');
        $cate_id = $this->_post('cate_id','intval');
        $area_id = $this->_post('area_id','intval');
        if($like){
            $where['name|shortname'] = array('like',"%$like%");
        }

        if($cate_id){
            $where['cate_id'] = $cate_id;
        }

        if($area_id){
            $where['area_id'] = $area_id;
        }

        $count      = $this->tenant_db->where($where)->count();
        $Page       = new Page($count,15);

        $tenant     = $this->tenant_db->where($where)->order('add_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($tenant as $key=>$value){  
            $tenant[$key]['cate_name'] = $this->cate_db->where(array('token'=>$this->token,'cate_id'=>$value['cate_id']))->getField('cate_name');
            $tenant[$key]['area_name'] = $this->area_db->where(array('token'=>$this->token,'area_id'=>$value['area_id']))->getField('area_name');
        }

        $this->assign('cate_list',$cate_list);
        $this->assign('area_list',$area_list);
        $this->assign('page',$Page->show());
        $this->assign('tenant',$tenant);
        $this->display();

    }


    public function tenant_set(){
        
        //分类信息
        $cate_list = $this->cate_db->where(array('token'=>$this->token,'market_id'=>$this->market_id,'is_show'=>'1'))->field('cate_name,cate_id')->order('sort desc')->select();

        //区域信息
        $area_list = $this->area_db->where(array('token'=>$this->token,'market_id'=>$this->market_id,'is_use'=>'1'))->field('area_name,area_id')->order('sort desc,add_time desc')->select();
        
        $this->assign('area_list',$area_list);
        $this->assign('cate_list',$cate_list);  

              
        $tenant_info  = $this->tenant_db->where(array('token'=>$this->token,'market_id'=>$this->market_id,'id'=>$this->_get('id','intval')))->find();


        if(IS_POST){

            if($this->tenant_db->create()){
                if($tenant_info){

                    $this->tenant_db->where(array('token'=>$this->token,'market_id'=>$this->market_id,'isbranch'=>'1','id'=>$this->_get('id','intval')))->save($_POST);     
                    $this->success('修改成功',U('Market/tenant',array('token'=>$this->token)));

                }else{
             
                    $_POST['add_time']  = time();
                    $_POST['market_id'] = $this->market_id;
                    $_POST['isbranch']  = 1;
                    $_POST['token']     = $this->token;
                    $_POST['token']     = $this->token;

                    $this->tenant_db->add($_POST);

                    $this->success('添加成功',U('Market/tenant',array('token'=>$this->token)));
                }  

            }else{
                $this->error($this->tenant_db->getError());
            }

        }else{


            $this->assign('tenant',$tenant_info);
            $this->assign('area_list',$area_list);
            $this->assign('cate_list',$cate_list);
            $this->display();
        }    
    }

    public function tenant_del(){
        $id     = $this->_get('id','intval');
        $where  = array('token'=>$this->token,'market_id'=>$this->market_id,'isbranch'=>'1','id'=>$id);
        if($this->tenant_db->where($where)->delete()){
            $this->success('删除成功',U('Market/tenant',array('token'=>$this->token)));
        }
    }
    //公共菜单
    public function menu(){

    	$this->display();
    }
/*------------------------------------------------------wap首页分类设置----------------------------------------------------------*/
    public function wap_nav(){
        $nav_db     = M('Market_nav');
        $cate       = $nav_db->where(array('token'=>$this->token,'market_id'=>$this->market_id))->order('sort desc')->select();

        $this->assign('cate',$cate);
        $this->display();
    }

    public function wap_nav_set(){
        $nav_db     = D('Market_nav');
        $nav_id     = $this->_get('nav_id','intval');
        $nav_info   = $nav_db->where(array('token'=>$this->token,'nav_id'=>$nav_id))->find();
        if(IS_POST){
            if($nav_db->create()){   

                if($nav_info){
                    $_POST['is_show']        = empty($_POST['is_show'])?'0':$_POST['is_show'];
                    $nav_db->where(array('token'=>$this->token,'nav_id'=>$this->_post('nav_id','intval')))->save($_POST);
                    $this->success('修改成功',U('Market/wap_nav',array('token'=>$this->token)));   
                
                }else{

                    $_POST['token']     = $this->token;
                    $_POST['market_id'] = $this->market_id;
                    $_POST['is_show']        = empty($_POST['is_show'])?'0':$_POST['is_show'];
                    if($nav_db->add($_POST)){
                        $this->success('添加成功',U('Market/wap_nav',array('token'=>$this->token)));
                    }
                }

            }else{
                $this->error($nav_db->getError());
            }
        }else{
            $this->assign('nav',$nav_info);
            $this->display();            
        }



    }

    public function wap_nav_del(){
        $nav_db = M('Market_nav');
        $where  = array('token'=>$this->token,'nav_id'=>$this->_get('nav_id','intval'));
        if($nav_db->where($where)->delete()){
            $this->success('删除成功',U('Market/wap_nav',array('token'=>$this->token)));
        }

    }
}




?>
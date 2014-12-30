<?php

class EstateAction extends WapAction{
    public $token;
    public $wecha_id;
    private $tpl;
    private $info;
    public $weixinUser;
    public $homeInfo;
    public $es_data;
    public $isamap;
    public $amap; 
       
    public function _initialize() {
        parent::_initialize();
       // if(!strpos($agent,"icroMessenger")) {
          //exit('此功能只能在微信浏览器中使用');
       //}
       
        $this->token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $this->wecha_id   = filter_var($this->_get('wecha_id'),FILTER_SANITIZE_STRING);
        $this->assign('token',$this->token);
        $this->assign('wecha_id',$this->wecha_id);
        $id 	= $this->_get('id','intval');
		
        /*兼容旧版*/
        if(empty($id)){
        	$this->es_data 	= M('Estate')->where(array('token'=>$this->token))->order('id desc')->limit(1)->find();
        	$is_nav 		= M('Estate_nav')->where(array('token'=>$this->token,'pid'=>$this->es_data['id']))->select();
        	if(empty($is_nav)){
        		$this->_create_nav($this->es_data['id']);
        		M('estate_album')->where(array('token'=>$this->token,'pid'=>0))->save(array('pid'=>$this->es_data['id']));
        		M('estate_expert')->where(array('token'=>$this->token,'pid'=>0))->save(array('pid'=>$this->es_data['id']));
        		M('estate_housetype')->where(array('token'=>$this->token,'pid'=>0))->save(array('pid'=>$this->es_data['id']));
        		M('estate_impress')->where(array('token'=>$this->token,'pid'=>0))->save(array('pid'=>$this->es_data['id']));
        		M('estate_impress_add')->where(array('token'=>$this->token,'pid'=>0))->save(array('pid'=>$this->es_data['id']));
        		M('estate_son')->where(array('token'=>$this->token,'pid'=>0))->save(array('pid'=>$this->es_data['id']));
        	}
        }else{
        	$this->es_data = M('Estate')->where(array('token'=>$this->token,'id'=>$id))->find();
        }
        
        
        if(empty($this->es_data)){
        	$this->error('参数错误，禁止非法访问');
        }
        

        if (C('baidu_map')){
            $this->isamap=0;
        }else {
            $this->isamap=1;
            $this->amap=new amap();
        }

        $this->assign('rid',$this->es_data['res_id']);
        $this->assign('estatindex',$this->es_data);
        $this->assign('info',$this->_getNav());   // 菜单相关,url(连接),img(菜单背景图),name(菜单名)
        $tpl    = $this->wxuser;
        $this->tpl=$tpl;
    }

    public function index(){
        $data = M("Estate");
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $wecha_id   = filter_var($this->_get('wecha_id'),FILTER_SANITIZE_STRING);
        $where      = array('token'=>$token);

        include('./PigCms/Lib/ORG/index.Tpl.php');

        foreach($tpl as $k=>$v){
            if($v['tpltypeid'] == $this->es_data['tpid']){
                 $tplinfo = $v;
            }
        }
        $this->tpl['tpltypeid'] = $tplinfo['tpltypeid'];

        $this->assign('estatindex',$this->es_data);

        $allflash   = M('Flash')->where(array('token'=>$token))->select();
        $flash   = array();
        $flashbg = array();
        for ($i=0; $i <5; $i++) { 
            if($this->es_data['slide'.($i+1)] != ''){
                 $flashbg[$i]['img'] = $this->es_data['slide'.($i+1)];
            }  
        }


        $homeInfo = M('home')->where(array('token'=>$token))->find();
        $this->assign('cateMenuFileName','');
        $this->assign('homeInfo',$homeInfo);
        $this->assign('so',$so);
        $this->assign('flash',$flash);      //home view
        $this->assign('flashbg',$flashbg);  //背景轮播图 img(图片地址)
        $this->assign('tpl',$this->tpl);
        
        if(!empty($tplinfo['tpltypename'])){
            $this->display('Index:'.$tplinfo['tpltypename']);
        }else{
            $this->display();
        }
    }
    
    public function _create_nav($id){
    	$name = array(
    			'楼盘首页',
    			'楼盘简介',
    			'楼盘相册',
    			'户型全景',
    			//'新闻动态',
    			'印象点评',
    			'预约看房',
    			'关于我们'
    
    	);
    
    	$url = array(
    			'{siteUrl}/index.php?g=Wap&m=Estate&a=index&token='.$this->token.'&wecha_id={wecha_id}&id={id}',
    			'{siteUrl}/index.php?g=Wap&m=Estate&a=introduce&token='.$this->token.'&wecha_id={wecha_id}&id={id}',
    			'{siteUrl}/index.php?g=Wap&m=Estate&a=photo&token='.$this->token.'&wecha_id={wecha_id}&id={id}',
    			'{siteUrl}/index.php?g=Wap&m=Estate&a=housetype&token='.$this->token.'&wecha_id={wecha_id}&id={id}',
    			//'{siteUrl}/index.php?g=Wap&m=Estate&a=news&token='.$this->token.'&wecha_id={wecha_id}&id={id}',
    			'{siteUrl}/index.php?g=Wap&m=Estate&a=impress&token='.$this->token.'&wecha_id={wecha_id}&id={id}',
    			'{siteUrl}/index.php?g=Wap&m=Reservation&a=index&token='.$this->token.'&wecha_id={wecha_id}&id={id}&rid={rid}',
    			'{siteUrl}/index.php?g=Wap&m=Estate&a=aboutus&token='.$this->token.'&wecha_id={wecha_id}&id={id}',
    	);
    
    	$pic = array(
    			'./tpl/User/default/common/images/photo/plugmenu6.png',
    			'./tpl/User/default/common/images/photo/plugmenu4.png',
    			'./tpl/User/default/common/images/photo/plugmenu7.png',
    			'./tpl/User/default/common/images/photo/plugmenu17.png',
    			// './tpl/User/default/common/images/photo/plugmenu10.png',
    			'./tpl/User/default/common/images/photo/plugmenu15.png',
    			'./tpl/User/default/common/images/photo/plugmenu8.png',
    			'./tpl/User/default/common/images/photo/plugmenu19.png',
    	);
    	/*
    	$Classify  = M('Classify')->where(array('token'=>$this->token,'path'=>'0','fid'=>0))->limit(2)->select();
    	foreach ($Classify as $key => $value) {
	    	$name[] = $value['name'];
	    	$url[]  = $value['url'];
    	}
    	*/
    
    	for ($i=0; $i < count($name) ; $i++) {
    		M('Estate_nav')->add(array('name'=>$name[$i],'url'=>$url[$i],'pic'=>$pic[$i],'is_show'=>'1','is_system'=>'1','sort'=>(100-$i),'token'=>$this->token,'pid'=>$id));
    	}
    }
    public function _getNav(){
        $info       = array();
        $nav        = M('Estate_nav')->where(array('token'=>$this->token,'pid'=>$this->es_data['id'],'is_show'=>'1'))->order('sort desc')->select();
        foreach ($nav as $key => $value) {
            $info[$key]['url']  = str_replace(array('{id}','{wecha_id}','{siteUrl}','{rid}'),array($this->es_data['id'],$this->wecha_id,$this->siteUrl,$this->es_data['res_id']),$value['url']);
            $info[$key]['img']  = $value['pic'];
            $info[$key]['name'] = $value['name'];
        }
        return $info;
    }

    public function introduce(){

        $this->assign('isamap',$this->isamap);
        $this->display();
    }


    public function news(){
        $this->token=$this->_get('token');
        $where = array('token'=>$this->token);
        $cid = $this->es_data['classify_id'];
        if($cid != null){
            $t_classify = M('Classify');
            $where = array('token'=>$this->token,'id'=>$cid);
            $classify = $t_classify->where($where)->find();
        }
        $t_img = M('Img');
        $where = array('classid'=>$classify['id'],'token'=>$this->_get('token'));

        $count      = $t_img->where($where)->count();
        $Page       = new Page($count,5);
        $show       = $Page->show();
        $imgtxt     = $t_img->where($where)->field('id as mid,title,pic,createtime')->order('createtime desc,uptatetime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        $this->assign('imgtxt',$imgtxt);
        $this->assign('classify',$classify);
        $this->display();
    }

    public function  newlist(){
        $token = $this->_get('token');
        $mid = (int)$this->_get('mid');
        $t_img = M('Img');
        $where = array('id'=>$mid,'token'=>$token);
        $imgtxt = $t_img->where($where)->find();
        $this->assign('imgtxt',$imgtxt);

        $this->display();
    }


    public function housetype(){
        $id 		= $this->es_data['id'];
        $t_housetype = M('Estate_housetype');
        $where       = array('token'=>$this->_get('token'),'pid'=>$id);
        $count      = $t_housetype->where($where)->count();
        $Page       = new Page($count,5);
        $show       = $Page->show();
        $housetype  = $t_housetype->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        foreach ($housetype as $k => $v) {
            $son_type[] = M("Estate_son")->where(array('id'=>$v['son_id']))->field('id as sid,title,description as desc_son')->find();
        }
        foreach ($son_type as $key => $value) {
             foreach ($value as $k => $v) {
                  $housetype[$key][$k] = $v;
             }

        }
        $this->assign('housetype',$housetype);
        $data = M("Estate");
        $this->token=$this->_get('token');
        $where = array('token'=>$this->token);
        $this->assign('es_data',$this->es_data);
        $this->display();
    }

    public function house_pic(){
        $file       = './tpl/static/estate/js/data';  
        $json       = json_decode(file_get_contents($file));

        //dump($json->rooms[0]);
        $id         = $this->es_data['id'];
        $hid        = $this->_get('hid','intval');
        $wecha_id   = $this->_get('wecha_id','trim');
        $house_info = M('estate_housetype')->where(array('pid'=>$id,'id'=>$hid))->field()->find();


        $this->assign('house_info',$house_info);
        $this->display();
    }

    public function load_house_pic(){
        $hid    = $this->_get('hid','intval');
        $pid    = $this->es_data['id']; 
        $info   = M('Estate_housetype')->where(array('token'=>$this->token,'pid'=>$pid,'id'=>$hid))->find();
        //echo M('Estate_housetype')->getlastsql();
        $arr    = array(
            'banner' => $this->es_data['banner'],
            'rooms'  => array(
                array(
                    'id'    =>$this->es_data['id'],
                    'name'  =>$info['name'],
                    'desc'  =>$this->es_data['title'], 
                    'simg'  =>$info['type1'],
                    'bimg'  =>$info['type1'],
                    'rooms' =>$info['fang'].'房'.$info['ting'].'厅 ',
                    'area'  =>'约'.$info['area'].'平米',
                    'floor' =>$info['floor_num'].'层',
                    'width' =>1600,
                    'height'=>1600,
                    'dtitle'=>array('建筑面积约'.$info['area'].'平米'),
                    'dlist' =>array($info['description']),
                    'pics'  =>$this->get_pics($info),
                ),
            ),
        );
        echo 'showRooms('.json_encode($arr).')';
    }
    public function get_pics($info){
        $arr    = array();
        for($i=2;$i<=4;$i++){
            if(!empty($info['type'.$i])){
                $arr[] = array(
                            'img'       => $info['type'.$i],
                            'width'     => 760,
                            'height'    => 760,
                            'name'      => $info['name']
                        );
            }
        }
        return $arr;
    }

    public function album(){
        $id         = $this->es_data['id'];
        $this->token=$this->_get('token');
        
        /*
        $reply_info_db=M('Reply_info');
        $config=$reply_info_db->where(array('token'=>$this->token,'infotype'=>'album'))->find();
        if ($config){
            $headpic=$config['picurl'];
        }else {
            $headpic='/tpl/Wap/default/common/css/Photo/banner.jpg';
        }
        $this->assign('headpic',$headpic);

        */
        $Photo = M("Photo");
        $t_album = M('Estate_album');
        $album = $t_album->where(array('token'=>$this->_get('token'),'pid'=>$id))->field('id,poid')->select();
        $list_photo = array();
        foreach ($album as $k => $v) {
             $list_photo[] = $Photo->where(array('token'=>$this->_get('token'),'id'=>$v['poid']))->find();
        }
        
        $this->assign('photo',$list_photo);
        $this->display('Photo:index');
    }

    public function show_album(){
        $this->token=$this->_get('token');
        
        /*
        $reply_info_db=M('Reply_info');
        $config=$reply_info_db->where(array('token'=>$this->token,'infotype'=>'album'))->find();
        if ($config){
            $headpic=$config['picurl'];
        }else {
            $headpic='/tpl/Wap/default/common/css/Photo/banner.jpg';
        }
        $this->assign('headpic',$headpic);
        */
        $t_housetype = M('Estate_housetype');
        $id = (int)$this->_get('id');
        $where = array('token'=>$this->_get('token'),'id'=>$id);
        $housetype = $t_housetype->where($where)->order('sort desc')->find();
        $this->assign('shareid',$id);

        if(!empty($this->es_data)){
            $housetype = array_merge($housetype,$this->es_data);
        }
        $this->assign('housetype',$housetype);
        $this->display();
    }

    public function photo(){

        $this->display();
    }

    public function load_album_pic(){
        $id     = $this->es_data['id'];
        $album  = $this->get_album($id);
        $json   = 'showPics('.json_encode($album).')';
        echo $json;
    }
    /*获取相册*/
    public function get_album($id){
        $album_info     = M('Estate_album')->where(array('token'=>$this->token,'pid'=>$id))->select();  
        $pic    = array();
        foreach ($album_info as $key => $value) {
            $photo_info = M('Photo')->where(array('token'=>$value['token'],'id'=>$value['poid']))->find();
            $pic[$key]['title']     = $photo_info['title'];
            $pic[$key]['ps1']       = $this->_getPic($photo_info,'text');
            $pic[$key]['ps2']       = $this->_getPic($photo_info,'info');
        }
        return $pic;
    }
    /*格式化相册数据*/
    public function _getPic($photo_info,$type){

        $where  = array('pid'=>$photo_info['id'],'token'=>$this->token);
        $count  = M('Photo_list')->where($where)->count();
        $flag   = ceil($count/2);
        $data   = array();

        if($type == 'text'){
            $data   = M('Photo_list')->where($where)->order('sort desc')->limit(0,$flag)->field('title as name,picurl as img')->select();
            $arr  = array('title'=>$photo_info['title'],'type'=>"title",'subTitle'=>$photo_info['title']);
            array_splice($data, 0, 0, array($arr));
        }else if($type == 'info'){
        	/*'color'=>$color[array_rand($color)],*/
            $data   = M('Photo_list')->where($where)->order('sort desc')->limit($flag,$count)->field('title as name,picurl as img')->select();
            $arr   = array('content'=>$photo_info['info'],'type'=>'text');
            array_splice($data, 1, 0, array($arr));
        }
        foreach($data as $key=>$value){
            if(empty($value['type'])){
                $img_info = getimagesize($value['img']);
                $data[$key]['type'] = 'img';
                if($img_info){
                    $data[$key]['size']     = array(
                        '0'     => $img_info[0],
                        '1'     => $img_info[1],
                    );
                }else{
                   $data[$key]['size']     = array(
                        '0'     => 573,
                        '1'     => 571,
                    ); 
                }
            }      
        }
        return $data;
    }  
 
    

    public function impress(){
        $t_impress = M('Estate_impress');
        $where     = array('token'=>$this->token,'pid'=>$this->es_data['id']);
        $where2    = array('token'=>$this->token,'pid'=>$this->es_data['id'],'is_show'=>1);
        $impress   = $t_impress->where($where2)->order('sort desc')->select();
        $count2    = $t_impress->where($where2)->sum('comment');
        $Page2     = new Page($count2,12);
        $show2     = $Page2->show();
        $impress   = $t_impress->where($where2)->limit($Page2->firstRow.','.$Page2->listRows)->order('sort desc')->select();
        $i = 4;
        $k = 0;
        $color     = array('#fb641c','#aadc3b','#00b6ee','#c93127','#695d8f','#8c5e7a','#eea200','#125D74','#99770B','#80612B','#A417C7','#961156','#9EA394','#CAC6BB','#8D0144');

        foreach ($impress as $key => $value) {

            if(($key+1)%4==0){
                $i=4;
            }

            if(in_array($key, array(0,1,2))){
                $impress[$key]['class']     = 'piece'.($key+1);
            }else{
                $impress[$key]['class']     = 'piece'.$i;
            }
            
            $impress[$key]['comment']       = $this->percent($value['comment'],$count2);    
            $impress[$key]['color']         = $color[$k];
            if($k >= count($color)){
                $k=0;
            }
            $i++;
            $k++;
        }

        $user_comment = $this->_getComment($this->token,$this->_get('id'),$this->_get('wecha_id'));
        $this->assign('user_comment',$user_comment);

        $this->assign('impress',$impress);
        $this->assign('thiscount',$count2);
        $this->assign('page2',$show2);

        $t_expert   = M('Estate_expert');
        $count      = $t_expert->where($where)->count();
        $Page       = new Page($count,5);
        $show       = $Page->show();
        $expert     = $t_expert->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        
    
        $this->assign('page',$show);
        $this->assign('tcount',$count);
        $this->assign('expert',$expert);

        $this->display();
    }

    public function impress_add(){
        $t_impress  = M('Estate_impress'); //comment 统计数
        $t_imp_add  = M("Estate_impress_add");
        $imp_id     = (int)$this->_get('imp_id');
        $token      = $this->_get('token');
        $wecha_id   = $this->_get('wecha_id');
        $where      =  array('wecha_id'=>$wecha_id,'token'=>$token,'pid'=>$this->_get('id'));
        $check      = $t_imp_add->where($where)->find();
        $imp_user   = $this->_get('imp_user');
        
        if(empty($imp_id) && $imp_user != ''){
            $imp_id         = $t_impress->where(array('title'=>$imp_user))->getField('id');
        }

        $data['pid']        = $this->_get('id','intval');
        $data['token']      = $token;
        $data['wecha_id']   = $wecha_id;
        $data['imp_id']     = $imp_id?$imp_id:0;
        $data['imp_user']   = $this->_get('imp_user');

        if($check != null){
            $imp  = $t_impress->where(array('token'=>$check['token'],'id'=>$check['imp_id']))->find();
            $data = array('errno'=>2,'msg'=>"您已经添加过印象:",'thiscom'=>$imp['title']);
            echo json_encode($data);
            exit;
        }

        if($id=$t_imp_add->add($data)){
            $t_impress->where(array('id'=>$imp_id,'token'=>$token))->setInc('comment');
            $user_comment = $this->_getComment($token,$this->_get('id'),$wecha_id);

            $result['name']     = $user_comment['imp_user'];
            $result['comment']  = $user_comment['comment'];
            
            $data   = array('errno'=>1,'msg'=>"添加印象成功。",'res'=>$result);
            echo json_encode($data);
            exit;
        }else{
            $data=array('errno'=>0,'msg'=>"添加印象失败，请再来一次吧。");
            echo json_encode($data);
            exit;
        }

    }

    public function  aboutus(){
        $id      = $this->es_data['id'];
        $company = M('Company');
        $about = $company->where(array('token'=>$this->token,'shortname'=>'loupan'.$id,'isbranch'=>1))->find();
        $this->assign('about',$about);

        $this->assign('isamap',$this->isamap);
        $this->display();
    }


    function _getComment($token,$pid,$wecha_id){
        $users      = M('Estate_impress_add')->where(array('token'=>$token,'pid'=>$pid,'wecha_id'=>$wecha_id))->find();
        $count      = M('Estate_impress')->where(array('token'=>$token,'pid'=>$pid))->sum('comment');
        if(empty($users['imp_id'])){
            $u_count = M('Estate_impress_add')->where(array('token'=>$token,'pid'=>$pid,'imp_user'=>$users['imp_user']))->count('id');
        }else{
            $u_count = M('Estate_impress')->where(array('token'=>$token,'pid'=>$pid,'id'=>$users['imp_id']))->getField('comment');
        }
        if($users){
             $users['comment']   = $this->percent($u_count,$count);
        }
        return $users;
    }

    //百分比计算
    function percent($p,$t){
        if($t==0){
            $val = 1;
        }else{
            $val = round($p/$t,2);
        }
        $num = sprintf('%.0f%%',$val*100);
        return $num;
    }   
	
    public function maps(){
    	$type 	= $this->_get('type','trim');
    	
    	$this->apikey=C('baidu_map_api');
    	$this->assign('apikey',$this->apikey);
    	
    	$map 	= array();
    	if($type == 'about'){
    		$about = M('Company')->where(array('token'=>$this->token,'shortname'=>'loupan'.$this->es_data['id'],'isbranch'=>1))->find();
    		$map['name'] 		= $about['name'];
    		$map['longitude'] 	= $about['longitude'];
    		$map['latitude'] 	= $about['latitude'];
    		$map['tel'] 		= $about['tel'];
    		$map['logourl'] 	= $about['logourl'];
    		$map['address'] 	= $about['address'];
    	}else{
    		$map['name'] 		= $this->es_data['title'];
    		$map['longitude'] 	= $this->es_data['lng'];
    		$map['latitude'] 	= $this->es_data['lat'];
    		$map['address'] 	= $this->es_data['place'];
    		$map['logourl'] 	= $this->es_data['cover'];
    	}
    	
    	$this->assign('thisMap',$map);
    	$this->display();
    }

}



?>
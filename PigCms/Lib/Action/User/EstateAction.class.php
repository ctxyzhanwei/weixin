<?php
class EstateAction extends UserAction{
    public function _initialize() {
        parent::_initialize();
		
		$this->canUseFunction('estate');
    }

    public function index(){
        $where  = array('token'=>$this->_get('token'));
        $count  = M('Estate')->where($where)->count();

        $Page       = new Page($count,12);
        $estate = M('Estate')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$Page->show());
        $this->assign('estate',$estate);
        $this->display();
    }


	public function set(){
		$data = M("Estate");
        $where = array('token'=>session('token'));
        $es_data = $data->where(array('token'=>$this->token,'id'=>$this->_get('id','intval')))->find();
        $panorama = M('Panorama')->where(array('token'=>$this->token))->field('id as pid,name,keyword')->select();
        $this->assign('panorama',$panorama);
        $classify = M('Classify')->where(array('token'=>$this->token))->field('id as cid,name')->select();
        $this->assign('classify',$classify);
        $reslist = M('Reservation')->where(array('token'=>$this->token,'addtype'=>'house_book'))->field('id as rid ,title')->select();
        $this->assign('reslist',$reslist);
        if(IS_POST){

            if(!D('Estate')->create()){
                $this->error('表单提交错误！请检查是否有项为空！');exit();
            }

           if($es_data == null){

                    if($id=$data->add($_POST)){
                        $this->_create_nav($id);
                        $data1['pid']=$id;
                        $data1['module']='Estate';
                        $data1['token']=session('token');
                        $data1['keyword']=trim($_POST['keyword']);
                        M('Keyword')->add($data1);
                        //$user=M('Users')->where(array('id'=>session('uid')))->setInc('activitynum');
                        $this->success('添加成功',U('Estate/index',array('token'=>session('token'))));
                         exit;
                    }else{
                        $this->error('服务器繁忙,请稍候再试');exit;
                    }

           }else{
            $wh = array('token'=>session('token'),'id'=>$this->_post('id'));
             if($data->where($wh)->save($_POST)){

                    $data1['pid']=(int)$this->_post('id');
                    $data1['module']='Estate';
                    $data1['token']=session('token');
                    $da['keyword']=trim($this->_post('keyword'));
                    M('Keyword')->where($data1)->save($da);

                    $this->success('修改成功',U('Estate/index',array('token'=>session('token'))));exit;
                }else{
                    $this->error('操作失败');exit;
                }
           }
        }else{
            if(empty($es_data)){//默认背景图
                for ($i=1; $i <=3 ; $i++) { 
                    if($es_data['slide'.$i] == ''){
                        $es_data['slide'.$i]    = './tpl/static/attachment/background/view/'.$i.'.jpg';
                    }
                }
            }
            $this->assign('es_data',$es_data);
            $this->display();
        }



	}
    public function del(){
        $id = $this->_get('id','intval');
        $up = M('Estate')->where(array('token'=>$this->token,'id'=>$id))->delete();
        if($up){
            M('Estate_album')->where(array('token'=>$this->token,'pid'=>$id))->delete();
            M('Estate_expert')->where(array('token'=>$this->token,'pid'=>$id))->delete();
            M('Estate_expert')->where(array('token'=>$this->token,'pid'=>$id))->delete();
            M('Estate_impress')->where(array('token'=>$this->token,'pid'=>$id))->delete();
            M('Estate_impress_add')->where(array('token'=>$this->token,'pid'=>$id))->delete();
            M('Estate_nav')->where(array('token'=>$this->token,'pid'=>$id))->delete();
            M('Estate_son')->where(array('token'=>$this->token,'pid'=>$id))->delete();
            M('Company')->where(array('token'=>$this->token,'shortname'=>'loupan'.$pid))->delete();
            
            M('Keyword')->where(array('token'=>$this->token,'pid'=>$id,'module'=>'Estate'))->delete();
            $this->success('删除成功',U('Estate/index',array('token'=>session('token'))));
        }
    }


    public function nav(){
        $nav_db     = M('Estate_nav');
        $pid         = $this->_get('pid','intval');
        $cate       = $nav_db->where(array('token'=>$this->token,'pid'=>$pid))->order('sort desc')->select();
        $this->assign('pid',$pid);
        $this->assign('cate',$cate);
        $this->display();
    }

    public function nav_set(){
        $nav_db     = D('Estate_nav');
        $pid        = $this->_get('pid','intval');
        $id         = $this->_get('id','intval');
        $nav_info   = $nav_db->where(array('token'=>$this->token,'id'=>$id))->find();
        if(IS_POST){
            if($nav_db->create()){   
                if($nav_info){
                    $_POST['is_show']        = empty($_POST['is_show'])?'0':$_POST['is_show'];
                    $nav_db->where(array('token'=>$this->token,'id'=>$this->_post('id','intval'),'pid'=>$pid))->save($_POST);
                    $this->success('修改成功',U('Estate/nav',array('token'=>$this->token,'pid'=>$pid)));   
                }else{
                    $_POST['pid']       = $pid;
                    $_POST['token']     = $this->token;
                    $_POST['is_show']   = empty($_POST['is_show'])?'0':$_POST['is_show'];
                    if($nav_db->add($_POST)){
                        $this->success('添加成功',U('Estate/nav',array('token'=>$this->token,'pid'=>$pid)));
                    }
                }

            }else{
                $this->error($nav_db->getError());
            }
        }else{
            $this->assign('pid',$pid);
            $this->assign('nav',$nav_info);
            $this->display();            
        }
    }

    public function nav_del(){
        $nav_db = M('Estate_nav');
        $pid   = $this->_get('pid','intval');
        $where  = array('token'=>$this->token,'id'=>$this->_get('id','intval'),'pid'=>$pid);
        if($nav_db->where($where)->delete()){
            $this->success('删除成功',U('Estate/nav',array('token'=>$this->token,'pid'=>$pid)));
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
        }*/

        for ($i=0; $i < count($name) ; $i++) { 
            M('Estate_nav')->add(array('name'=>$name[$i],'url'=>$url[$i],'pic'=>$pic[$i],'is_show'=>'1','is_system'=>'1','sort'=>(100-$i),'token'=>$this->token,'pid'=>$id));
        }
   }

    public function son(){
        $pid   = $this->_get('pid','intval');
        $where = array('token'=>session('token'),'pid'=>$pid);
        $estate_son = M('Estate_son');

        $son_data = $estate_son->where($where)->order('sort desc')->select();

        $this->assign('son_data',$son_data);

        $this->assign('pid',$pid);
        $this->display();
    }

    public function son_add(){
        $t_son  = M('Estate_son');
        $id     = (int)$this->_get("id");
        $pid   = $this->_get('pid','intval');
        $token  = $this->_get('token');
        $where  = array('id'=>$id,'token'=>$token,'pid'=>$pid);
        $check  = $t_son->where($where)->find();
        if($check != null){
             $this->assign('son',$check);
        }
        if(IS_POST){
            if(!D('Estate_son')->create()){
                $this->error('表单提交错误！,请检查是否有项为空！');exit();
            }
            if($check == null){
                    $_POST['pid'] = $pid;
                    $_POST['token']= session('token');
                    if($t_son->add($_POST)){
                        $this->success('添加成功',U('Estate/son',array('token'=>session('token'),'pid'=>$pid)));
                         exit;
                    }else{
                        $this->error('服务器繁忙,请稍候再试');exit;
                    }
           }else{
             $wh = array('token'=>session('token'),'id'=>$this->_post('id'),'pid'=>$pid);

             if($t_son->where($wh)->save($_POST)){
                    $this->success('修改成功',U('Estate/son',array('token'=>session('token'),'pid'=>$pid)));
                    exit;
                }else{
                    $this->error('操作失败');
                    exit;
                }
           }
        }

        $this->display();

    }

    public function son_del(){
        $t_son = M('Estate_son');
        $id    = (int)$this->_get('id');
        $pid  = (int)$this->_get('pid');
        $token = $this->_get('token');
        $where = array('id'=>$id,'token'=>$token,'pid'=>$pid);
        $check = $t_son->where($where)->find();
        if($check == null){
            $this->error('操作失败');
        }else{
            $isok = $t_son->where($where)->delete();
            if($isok){
               $this->success('删除成功',U('Estate/son',array('token'=>session('token'),'pid'=>$pid)));
               exit;
           }else{
                $this->error('删除失败',U('Estate/son',array('token'=>session('token'),'pid'=>$pid)));
                exit();
           }
        }
    }


    public function housetype(){
        $pid            = $this->_get('pid','intval');
        $t_housetype    = M('Estate_housetype');
        $where          = array('token'=>session('token'),'pid'=>$pid);
        $housetype      = $t_housetype->where($where)->order('sort desc')->select();

        foreach ($housetype as $k => $v) {
            $son_type[] = M("Estate_son")->where(array('id'=>$v['son_id']))->field('id as sid,title')->find();
        }


        foreach ($son_type as $key => $value) {
             foreach ($value as $k => $v) {
                  $housetype[$key][$k] = $v;
             }

        }

        $this->assign('pid',$pid);
        $this->assign('housetype',$housetype);
        $this->display();
    }

    public function housetype_add(){
        $pid            = $this->_get('pid','intval');
        $t_housetype    = M('Estate_housetype');

        $id = (int)$this->_get("id");
        $token = $this->_get('token');
        $where =  array('id'=>$id,'token'=>$token,'pid'=>$pid);
        $check = $t_housetype->where($where)->find();

        $son_data = M("Estate_son")->where(array('token'=>session('token'),'pid'=>$pid))->field('id as sid,title')->select();
        $this->assign('son_data',$son_data);
        $panorama = M('Panorama')->where(array('token'=>session('token')))->field('id as pid,name,keyword')->select();
        $this->assign('panorama',$panorama);
        if($check != null){
             $this->assign('housetype',$check);
        }

        if(IS_POST){
            if(!D('Estate_housetype')->create()){
                $this->error('表单提交错误！,请检查是否有项为空！');exit();
            }
            if($check == null){
                    $_POST['pid']    = $pid;
                    $_POST['token']  = session('token');
                    if($t_housetype->add($_POST)){
                        $this->success('添加成功',U('Estate/housetype',array('token'=>session('token'),'pid'=>$pid)));
                         exit;
                    }else{
                        $this->error('服务器繁忙,请稍候再试');exit;
                    }
           }else{
             $wh = array('token'=>session('token'),'id'=>$this->_post('id'),'pid'=>$pid);

                if($t_housetype->where($wh)->save($_POST)){
                    $this->success('修改成功',U('Estate/housetype',array('token'=>session('token'),'pid'=>$pid)));
                    exit;
                }else{
                    $this->error('操作失败');exit;
                }
           }
        }

        $this->assign('pid',$pid);
        $this->display();
    }

    public function housetype_del(){
        $housetype  = M('Estate_housetype');
        $id         = (int)$this->_get('id');
        $pid        = $this->_get('pid','intval');
        $token = $this->_get('token');
        $where = array('id'=>$id,'token'=>$token,'pid'=>$pid);
        $check = $housetype->where($where)->find();
        if($check == null){
            $this->error('操作失败');
        }else{
            $isok = $housetype->where($where)->delete();
            if($isok){
               $this->success('删除成功',U('Estate/housetype',array('token'=>session('token'),'pid'=>$pid)));exit;
           }else{
                $this->error('删除失败',U('Estate/housetype',array('token'=>session('token'),'pid'=>$pid)));exit;
           }
        }
    }

    public function album(){
        $pid        = $this->_get('pid','intval');
        $Photo = M("Photo");
        $t_album = M('Estate_album');
        $album = $t_album->where(array('token'=>session('token'),'pid'=>$pid))->field('id,poid')->select();
        foreach ($album as $k => $v) {

             $list_photo[] = $Photo->where(array('token'=>session('token'),'id'=>$v['poid']))->order('id desc')->find();


        }
        foreach ($album as $key => &$value) {
            $list_photo[$key]['mid'] = $value['id'];
        }

        $this->assign('pid',$pid);
        $this->assign('album',$list_photo);
        $this->display();
    }

    public function album_add(){
        $pid        = $this->_get('pid','intval');
        $po_data=M('Photo');
        $list = $po_data->where(array('token'=>session('token')))->field('id,title')->select();
        $this->assign('photo',$list);
        $t_album = M('Estate_album');
        $poid = (int)$this->_get('poid');

        $check = $t_album->where(array('token'=>session('token'),'poid'=>$poid,'pid'=>$pid))->find();
        $this->assign('album',$check);

        if(IS_POST){
            if($check == NULL){
                $check_ex = $t_album->where(array('token'=>session('token'),'poid'=>$this->_post('poid'),'pid'=>$pid))->find();
                if($check_ex){
                     $this->error('您已经添加过改相册，请勿重复添加。');
                    exit;
                }
                    $_POST['pid']      = $pid;
                    $_POST['token']= session('token');
                    if($t_album->add($_POST)){
                        $this->success('添加成功',U('Estate/album',array('token'=>session('token'),'pid'=>$pid)));
                         exit;
                    }else{
                        $this->error('服务器繁忙,请稍候再试');exit;
                    }
           }else{
             $wh = array('token'=>session('token'),'id'=>$this->_post('id'),'pid'=>$pid);

             if($t_album->where($wh)->save($_POST)){
                    $this->success('修改成功',U('Estate/album',array('token'=>session('token'),'pid'=>$pid)));
                    exit;
                }else{
                    $this->error('操作失败');exit;
                }
           }
        }
        $this->display();

    }

    public function impress(){
        $pid = $this->_get('pid','intval');
        $t_impress = M('Estate_impress');
        $impress = $t_impress->where(array('token'=>session('token'),'pid'=>$pid))->order('sort desc')->select();

        $this->assign('impress',$impress);
        $this->assign('pid',$pid);
        $this->display();
    }

    public function impress_add(){
        $t_impress = M('Estate_impress');
        $id     = $this->_get("id");
        $pid    = $this->_get('pid','intval');
        
        $where =  array('id'=>$id,'token'=>$this->token,'pid'=>$pid);
        $check = $t_impress->where($where)->find();

        if($check != null){
             $this->assign('impress',$check);
        }

        if(IS_POST){
             $_POST['token'] = session('token');
            if($check == null){

                    if($t_impress->add($_POST)){
                        $this->success('添加成功',U('Estate/impress',array('token'=>session('token'),'pid'=>$pid)));
                        exit;
                    }else{
                        $this->error('服务器繁忙,请稍候再试');exit;
                    }
           }else{
             $wh = array('id'=>$this->_post('id'));

             if($t_impress->where($wh)->save($_POST)){
                    $this->success('修改成功',U('Estate/impress',array('token'=>session('token'),'pid'=>$pid)));
                    exit;
                }else{
                    $this->error('操作失败');exit;
                }
           }
        }

        $this->assign('pid',$pid);
        $this->display();
    }

    public function impress_del(){
        $impress = M('Estate_impress');
        $id     = $this->_get('id');
        $pid    = $this->_get('pid','intval');
        $where = array('id'=>$id,'token'=>$this->token,'pid'=>$pid);
        $check = $impress->where($where)->find();
        if($check == null){
            $this->error('操作失败');
        }else{
            $isok = $impress->where($where)->delete();
            if($isok){
               $this->success('删除成功',U('Estate/impress',array('token'=>session('token'),'pid'=>$pid))); 
               exit;
           }else{
                $this->error('删除失败',U('Estate/impress',array('token'=>session('token'),'pid'=>$pid)));
                exit;
           }
        }
    }

    public function expert(){
        $pid    = $this->_get('pid',$pid);

        $t_expert = M('Estate_expert');

        $expert = $t_expert->where(array('token'=>session('token'),'pid'=>$pid))->order('sort desc')->select();

        $this->assign('pid',$pid);

        $this->assign('expert',$expert);
        $this->display();
    }

    public function expert_add(){
        $pid    = $this->_get('pid',$pid);

        $t_expert = M('Estate_expert');
        $id = $this->_get("id");

        $where =  array('id'=>$id);
        $check = $t_expert->where($where)->find();

        if($check != null){
             $this->assign('expert',$check);
        }

        if(IS_POST){
             $_POST['token'] = session('token');
             if(!D('Estate_expert')->create()){
                $this->error('表单提交错误！,请检查是否有项为空！');exit();
             }
            if($check == null){

                    if($t_expert->add($_POST)){
                        $this->success('添加成功',U('Estate/expert',array('token'=>session('token'),'pid'=>$pid)));
                        exit;
                    }else{
                        $this->error('服务器繁忙,请稍候再试');exit();
                    }
           }else{
             $wh = array('id'=>$this->_post('id'));

             if($t_expert->where($wh)->save($_POST)){
                    $this->success('修改成功',U('Estate/expert',array('token'=>session('token'),'pid'=>$pid)));
                    exit;
                }else{
                    $this->error('操作失败');exit();
                }
           }
        }
        $this->assign('pid',$pid);
        $this->display();
    }

    public function expert_del(){
        $pid    = $this->_get('pid',$pid);
        $expert = M('Estate_expert');
        $id = $this->_get('id');
        $where = array('id'=>$id,'token'=>$this->token,'pid'=>$pid);
        $check = $expert->where($where)->find();
        if($check == null){
            $this->error('操作失败');
        }else{
            $isok = $expert->where($where)->delete();
            if($isok){
               $this->success('删除成功',U('Estate/expert',array('token'=>session('token'),'pid'=>$pid)));exit;
           }else{
                $this->error('删除失败',U('Estate/expert',array('token'=>session('token'),'pid'=>$pid)));exit();
           }
        }
    }

    public function  reservation(){
        //$pid    = $this->_get('pid',$pid);
        $data   = M("Reservation");
        $where  = array('token'=>session('token'),'addtype'=>'house_book');
        //$reslist =  $data->where($where)->select();
        $count      = $data->where($where)->count();
        $Page       = new Page($count,12);
        $show       = $Page->show();
        $reslist = $data->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('reslist',$reslist);   
        $this->assign('pid',$pid);
        $this->display();
    
    }

    public function reservation_add(){
        if(IS_POST){
            $data=D('Reservation');
            $_POST['token']=session('token');
            $_POST['addtype'] = 'house_book';
            if($data->create()!=false){
                if($id=$data->data($_POST)->add()){
                    $data1['pid']=$id;
                    $data1['module']='Reservation';
                    $data1['token']=session('token');
                    $data1['keyword']=trim($_POST['keyword']);
                    M('Keyword')->add($data1);
                    $this->success('添加成功',U('Estate/reservation',array('token'=>session('token'))));
                }else{
                    $this->error('服务器繁忙,请稍候再试');
                }
            }else{
                $this->error($data->getError());
            }
        }else{
            $this->display();
        }

    }

    public function reservation_total(){
        $this->display();
    }

    public function reservation_del(){
        $id = (int)$this->_get('id');
        $res = M('Reservation');
        $find = array('id'=>$id,'token'=>$this->_get('token'));
        $result = $res->where($find)->find();
         if($result){
            $res->where(array('id'=>$result['id'],'token'=>$this->token))->delete();
            M('Reservebook')->where(array('rid'=>$result['id'],'token'=>$this->token))->delete();
            $where = array('pid'=>$result['id'],'module'=>'Reservation','token'=>session('token'));
            M('Keyword')->where($where)->delete();
            $this->success('删除成功',U('Estate/reservation',array('token'=>session('token'))));
             exit;
         }else{
            $this->error('非法操作！');
             exit;
         }
    }

    public function reservation_edit(){
         if(IS_POST){
            $data=D('Reservation');
            $where=array('id'=>(int)$this->_post('id'),'token'=>session('token'));
            $check=$data->where($where)->find();

            if(empty($check)){
            $this->error('非法操作');
            }

            if($data->create()){
                $_POST['addtype'] = 'house_book';
                $_POST['token'] = session('token');
                if($data->where($where)->save($_POST)){
                    $data1['pid']=(int)$this->_post('id');
                    $data1['module']='Reservation';
                    $data1['token']=session('token');

                    $da['keyword']=trim($_POST['keyword']);
                    M('Keyword')->where($data1)->save($da);
                    $this->success('修改成功',U('Estate/reservation',array('token'=>session('token'))));
                }else{
                    $this->error('操作失败');
                }
            }else{
                $this->error($data->getError());
            }
        }else{
            $id=$this->_get('id');
            $where=array('id'=>$id,'token'=>session('token'));
            $data=M('Reservation');
            $check=$data->where($where)->find();
            if(empty($check))$this->error('非法操作');
            $reslist=$data->where($where)->find();
            $this->assign('reslist',$reslist);
            $this->display('reservation_add');
        }
    }

    public function reservation_manage(){
        $t_reservebook = M('Reservebook');
        $rid        = (int)$this->_get('id');
        $where      = array('token'=>session('token'),'rid'=>$rid,'type'=>'house_book');
        $count      = $t_reservebook->where($where)->count();
        $Page       = new Page($count,12);
        $show       = $Page->show();
        $books      = $t_reservebook->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
        $this->assign('page',$show);
        //var_dump($books);
        $this->assign('books',$books);
        $this->assign('count',$t_reservebook->where($where)->count());
        $this->assign('ok_count',$t_reservebook->where(array('token'=>session('token'),'remate'=>1,'rid'=>$rid,'type'=>'house_book'))->count());
        $this->assign('lose_count',$t_reservebook->where(array('token'=>session('token'),'remate'=>2,'rid'=>$rid,'type'=>'house_book'))->count());
        $this->assign('call_count',$t_reservebook->where(array('token'=>session('token'),'remate'=>0,'rid'=>$rid,'type'=>'house_book'))->count());
        $this->display();
    }

    public function album_del(){
        $t_album = M('estate_album');
        $id =filter_var($this->_get('id'),FILTER_VALIDATE_INT);
        $pid    = $this->_get('pid','intval');
        $where = array('id'=>$id,'token'=>session('token'),'pid'=>$pid);
        $check = $t_album->where($where)->find();
        if($check == null){
            $this->error('操作失败');
        }else{
            $isok = $t_album->where($where)->delete();
            if($isok){
               $this->success('删除成功',U('Estate/album',array('token'=>session('token'),'pid'=>$pid)));
                exit;
           }else{
                $this->error('删除失败',U('Estate/album',array('token'=>session('token'),'pid'=>$pid)));
                exit;
           }
        }
    }

    public function aboutus(){
        $t_company = M('Company');
        $token = session('token');
        $pid   = $this->_get('pid','intval');
        $where =  array('token'=>$token,'isbranch'=>1,'shortname'=>'loupan'.$pid);
        $check = $t_company->where($where)->find();

        if($check != null){
             $this->assign('set',$check);
        }

        if(IS_POST){

            if($check == null){
                    $_POST['shortname']     = 'loupan'.$pid;
                    if($t_company->add($_POST)){
                        $this->success('添加成功',U('Estate/aboutus',array('token'=>session('token'),'pid'=>$pid)));
                        exit;
                    }else{
                        $this->error('服务器繁忙,请稍候再试');exit;
                    }
           }else{
             $wh = array('id'=>$this->_post('id'),'token'=>session('token'));

             if($t_company->where($wh)->save($_POST)){
                    $this->success('修改成功',U('Estate/aboutus',array('token'=>session('token'),'pid'=>$pid)));
                    exit;
                }else{
                    $this->error('操作失败');exit;
                }
           }
        }

        $this->display();
    }




}



?>
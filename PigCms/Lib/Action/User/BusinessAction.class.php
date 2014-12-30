<?php
class BusinessAction extends UserAction{


    public function _initialize() {
        parent::_initialize();

        $type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $arrAllow = array('fitness','gover','food','travel','flower','property','ktv','bar','fitment','wedding','affections','housekeeper','lease','beauty');
        if(!in_array($type,$arrAllow)){
            $this->error('抱歉,您的参数不合法!',U('Function/index',array('token'=>$this->token)));
        }
        $this->assign('type',$type);
        $_POST['token'] = session('token');
		if( $type == 'wedding') $type = 'buswedd';
		$this->canUseFunction( $type );

    }

    public function index(){
        $data       = D('busines');
        $type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $where      = array('token'=>session('token'),'type'=>$type);
        $count      = $data->where($where)->count();
        $Page       = new Page($count,20);
        $show       = $Page->show();
        $busines     = $data->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        $this->assign('busines',$busines);
        $this->display();
    }


    public function index_add(){
        $Photo = M("Photo");
        $where = array('token'=>session('token'),'status'=>1);
        $photo = $Photo->where($where)->order('id desc')->select();
        $this->assign('photo',$photo);
        $type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $t_busines = D('busines');
        $bid  = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
        $where_2 = array('token'=>session('token'),'type'=>$type,'bid'=>$bid);
        $busines = $t_busines->where($where_2)->find();

        if(IS_POST){

            $filters = array(
                'keyword'=>array(
                    'filter'=>FILTER_SANITIZE_STRIPPED,
                    'flags'=>FILTER_SANITIZE_STRING,
                    'options'=>FILTER_SANITIZE_ENCODED
                ),
                'title'=>array(
                    'filter'=>FILTER_SANITIZE_STRIPPED,
                    'flags'=>FILTER_SANITIZE_STRING,
                    'options'=>FILTER_SANITIZE_ENCODED
                ),
                'picurl'=>array(
                    'filter'=>FILTER_VALIDATE_URL
                ),
                'business_desc'=>array(
                    'filter'=>FILTER_SANITIZE_STRIPPED,
                    'flags'=>FILTER_SANITIZE_STRING,
                    'options'=>FILTER_SANITIZE_ENCODED
                )
            );

            $check = filter_var_array($_POST,$filters);
            if(!$check){
                exit($this->error('包含敏感字符,或者是不允许字串!',U("Business/index",array('token'=>session('token'),'type'=>$type))));
            }else{
                $_POST['token'] = session('token');
                if(!$t_busines->create()){
                    exit($this->error($t_busines->getError()));
                }else{
                    $bid = filter_var($this->_post('bid'),FILTER_VALIDATE_INT);
                    $status = filter_var($this->_post('status'),FILTER_SANITIZE_STRING);

                    if('edit'==$status && $bid != ''){
                        $o =  $t_busines->where(array('bid'=>$bid, 'token'=>session('token'),'type'=>$type))->save($_POST);
                        if($o){
                            $data2['keyword'] = filter_var($this->_post('keyword'),FILTER_SANITIZE_STRING);
                            M('Keyword')->where(array('pid'=>$bid,'token'=>session('token'),'module'=>'Business'))->data($data2)->save();
                            exit($this->success('修改成功',U("Business/index",array('token'=>session('token'),'type'=>$type))));
                        }else{
                            exit($this->error('修改失败',U("Business/index",array('token'=>session('token'),'type'=>$type))));
                        }
                    }else{

                        if($id=$t_busines->data($_POST)->add()){
                            $data1['pid']=$id;
                            $data1['module']='Business';
                            $data1['token']=session('token');
                            $data1['keyword']=filter_var($this->_post('keyword'),FILTER_SANITIZE_STRING);
                            M('Keyword')->add($data1);
                            $this->success('添加成功',U("Business/index",array('token'=>session('token'),'type'=>$type)));exit;
                        }else{
                    exit($this->error('务器繁忙,添加失败,请稍候再试',U("Business/index",array('token'=>session('token'),'type'=>$type))));
                        }
                    }
                }

            }
        }
        $this->assign('busines',$busines);
        $this->display();
    }

    public function index_del(){
        $type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $bid  = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
        $t_busines = M('busines');
        $find = array('bid'=>$bid,'type'=>$type,'token'=>session('token'));
        $result = $t_busines->where($find)->find();
         if($result){
            $t_busines->where(array('bid'=>$result['bid'],'type'=>$result['type'],'token'=>session('token')))->delete();
            M('Keyword')->where(array('pid'=>$result['bid'],'module'=>'Business','token'=>session('token')))->delete();
            $this->success('删除成功',U("Business/index",array('token'=>session('token'),'type'=>$result['type'])));
             exit;
         }else{
         exit($this->error('非法操作,请稍候再试',U("Business/index",array('token'=>session('token'),'type'=>$type))));
         }
    }

    public function classify(){
        $data       = D('busines_main');
        $type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $where      = array('token'=>session('token'),'type'=>$type);
        $count      = $data->where($where)->count();
        $Page       = new Page($count,20);
        $show       = $Page->show();
        $busines_main     = $data->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $i = 0;
        foreach($busines_main  as $val){
            $busines = M("busines")->where(array('token'=>session('token'),'bid'=>$val['bid_id']))->field('mtitle')->find();
            array_push($busines_main[$i],$busines['mtitle']);
             unset($busines);
             ++$i;
        }
        //var_dump($busines_main);
        $this->assign('page',$show);
        $this->assign('busines_main',$busines_main);
        $this->display();
    }

    public function classify_add(){
        $t_busines = M("busines");
        $type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $where = array('token'=>session('token'),'type'=>$type);
        $busines_list = $t_busines->where($where)->order('sort desc')->field('bid,mtitle')->select();
        $this->assign('busines_list',$busines_list);
        $t_busines_main = D('busines_main');
        $mid  = filter_var($this->_get('mid'),FILTER_VALIDATE_INT);
        $where_2 = array('token'=>session('token'),'type'=>$type,'mid'=>$mid);
        $busines_main = $t_busines_main->where($where_2)->find();
        if(IS_POST){
            $filters = array(
                'name'=>array(
                    'filter'=>FILTER_SANITIZE_STRIPPED,
                    'flags'=>FILTER_SANITIZE_STRING,
                    'options'=>FILTER_SANITIZE_ENCODED
                ),
                'main_desc'=>array(
                    'filter'=>FILTER_SANITIZE_STRIPPED,
                    'flags'=>FILTER_SANITIZE_STRING,
                    'options'=>FILTER_SANITIZE_ENCODED
                )
            );

            $check = filter_var_array($_POST,$filters);
            if(!$check){
                exit($this->error('表单包含敏感字符!'));
            }else{
                $_POST['token'] = session('token');
                if(!$t_busines_main->create()){
                    exit($this->error($t_busines_main->getError()));
                }else{
                    $mid = filter_var($this->_post('mid'),FILTER_VALIDATE_INT);
                    $status = filter_var($this->_post('status'),FILTER_SANITIZE_STRING);
                    if('edit'==$status && $mid != ''){
                        $o =  $t_busines_main->where(array('mid'=>$mid, 'token'=>session('token'),'type'=>$type))->save($_POST);
                        if($o){
                            exit($this->success('修改成功',U("Business/classify",array('token'=>session('token'),'type'=>$type))));
                        }else{
                            exit($this->error('修改失败',U("Business/classify",array('token'=>session('token'),'type'=>$type))));
                        }
                    }else{

                        if($id=$t_busines_main->data($_POST)->add()){
                            $this->success('添加成功',U("Business/classify",array('token'=>session('token'),'type'=>$type)));exit;
                        }else{
                    exit($this->error('务器繁忙,添加失败,请稍候再试',U("Business/classify",array('token'=>session('token'),'type'=>$type))));
                        }
                    }
                }

            }
        }
        $this->assign('busines_main',$busines_main);
        $this->display();
    }

    public function classify_del(){
        $type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $mid  = filter_var($this->_get('mid'),FILTER_VALIDATE_INT);
        $t_busines_main = M('busines_main');

        $find = array('mid'=>$mid,'type'=>$type,'token'=>session('token'));
        $result = $t_busines_main->where($find)->find();
         if($result){
            $t_busines_main->where(array('mid'=>$result['mid'],'type'=>$result['type'],'token'=>session('token')))->delete();
            exit($this->success('删除成功',U("Business/classify",array('token'=>session('token'),'type'=>$result['type']))));
         }else{
            exit($this->error('非法操作,请稍候再试',U("Business/classify",array('token'=>session('token'),'type'=>$type))));
         }
    }

    public function project(){
        $data       = D('busines_second');
        $type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $where      = array('token'=>session('token'),'type'=>$type);
        $count      = $data->where($where)->count();
        $Page       = new Page($count,20);
        $show       = $Page->show();
        $busines_second     = $data->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $i = 0;
        foreach($busines_second  as $val){
            $busines = M("busines_main")->where(array('token'=>session('token'),'mid'=>$val['mid_id']))->field('name')->find();
            array_push($busines_second[$i],$busines['name']);
             unset($busines);
             ++$i;
        }
        $this->assign('page',$show);
        $this->assign('busines_second',$busines_second);
        $this->display();
    }

    public function project_add(){
        $t_busines_main = M("busines_main");
        $type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $where = array('token'=>session('token'),'type'=>$type);
        $busines_list = $t_busines_main->where($where)->order('sort desc')->field('mid,name')->select();
        $this->assign('busines_list',$busines_list);

        $t_busines_second = D('busines_second');
        $sid  = filter_var($this->_get('sid'),FILTER_VALIDATE_INT);
        $where_2 = array('token'=>session('token'),'type'=>$type,'sid'=>$sid);
        $busines_second = $t_busines_second->where($where_2)->find();
        if(IS_POST){
            $filters = array(
                'name'=>array(
                    'filter'=>FILTER_SANITIZE_STRIPPED,
                    'flags'=>FILTER_SANITIZE_STRING,
                    'options'=>FILTER_SANITIZE_ENCODED
                ),
                'main_desc'=>array(
                    'filter'=>FILTER_SANITIZE_STRIPPED,
                    'flags'=>FILTER_SANITIZE_STRING,
                    'options'=>FILTER_SANITIZE_ENCODED
                )
            );

            $check = filter_var_array($_POST,$filters);
            if(!$check){
                exit($this->error('表单包含敏感字符!'));
            }else{
                $_POST['token'] = session('token');
                if(!$t_busines_second->create()){
                    exit($this->error($t_busines_second->getError()));
                }else{
                    $sid = filter_var($this->_post('sid'),FILTER_VALIDATE_INT);
                    $status = filter_var($this->_post('status'),FILTER_SANITIZE_STRING);

                    if('edit'==$status && $sid != ''){
                        $o =  $t_busines_second->where(array('sid'=>$sid, 'token'=>session('token'),'type'=>$type))->save($_POST);
                        if($o){
                            exit($this->success('修改成功',U("Business/project",array('token'=>session('token'),'type'=>$type))));
                        }else{
                            exit($this->error('修改失败',U("Business/project",array('token'=>session('token'),'type'=>$type))));
                        }
                    }else{

                        if($id=$t_busines_second->data($_POST)->add()){
                            $this->success('添加成功',U("Business/project",array('token'=>session('token'),'type'=>$type)));exit;
                        }else{
                        exit($this->error('务器繁忙,添加失败,请稍候再试',U("Business/project",array('token'=>session('token'),'type'=>$type))));
                        }
                    }//edit & add
                }

            }
        }
        $this->assign('busines_second',$busines_second);
        $this->display();
    }

    public function project_del(){
        $type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $sid  = filter_var($this->_get('sid'),FILTER_VALIDATE_INT);
        $t_busines_main = M('busines_second');

        $find = array('sid'=>$sid,'type'=>$type,'token'=>session('token'));
        $result = $t_busines_main->where($find)->find();
         if($result){
            $t_busines_main->where(array('sid'=>$result['sid'],'type'=>$result['type'],'token'=>session('token')))->delete();
            exit($this->success('删除成功',U("Business/project",array('token'=>session('token'),'type'=>$result['type']))));
         }else{
             exit($this->error('非法操作,请稍候再试',U("Business/project",array('token'=>session('token'),'type'=>$type))));
         }
    }

    public function poster(){
        $data       = D('busines_pic');
        $type       = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $where      = array('token'=>session('token'),'type'=>$type);
        $count      = $data->where($where)->count();
        $Page       = new Page($count,20);
        $show       = $Page->show();
        $busines_pic= $data->where($where)->order('pid desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $i = 0;
        $j = 0;
        foreach($busines_pic  as $val){
            $busines = M("busines")->where(array('token'=>session('token'),'bid'=>$val['bid_id']))->field('mtitle')->find();
            $photo   = M('photo')->where(array('token'=>session('token'),'id'=>$val['ablum_id']))->field('title')->find();
            array_push($busines_pic[$i],$busines['mtitle']);
            array_push($busines_pic[$j],$photo['title']);
             unset($busines);
             unset($photo);
             ++$j;
             ++$i;
        }
        $this->assign('page',$show);
        $this->assign('busines_pic',$busines_pic);
        $this->display();
    }

    public function poster_add(){
        $t_busines = M("busines");
        $type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $where = array('token'=>session('token'),'type'=>$type);
        $busines_list = $t_busines->where($where)->order('sort desc')->field('bid,mtitle')->select();
        $this->assign('busines_list',$busines_list);
        $photo = M('photo')->where(array('token'=>session('token'),'status'=>1))->order('id desc')->field('id,title')->select();
        $this->assign('photo',$photo);
        $t_busines_second = D('busines_pic');
        $pid  = filter_var($this->_get('pid'),FILTER_VALIDATE_INT);
        $where_2 = array('token'=>session('token'),'type'=>$type,'pid'=>$pid);
        $busines_second = $t_busines_second->where($where_2)->find();
        if(IS_POST){
            $filters = array(
                'picurl_1'=>array(
                    'filter'=>FILTER_VALIDATE_URL
                ),
                'picurl_2'=>array(
                    'filter'=>FILTER_VALIDATE_URL
                ),
                'picurl_3'=>array(
                    'filter'=>FILTER_VALIDATE_URL
                ),
                'picurl_4'=>array(
                    'filter'=>FILTER_VALIDATE_URL
                ),
                'picurl_5'=>array(
                    'filter'=>FILTER_VALIDATE_URL
                )
            );

            $check = filter_var_array($_POST,$filters);
            if(!$check){
                exit($this->error('包含特殊字符,请检查后再提交.',U("Business/poster",array('token'=>session('token'),'type'=>$type))));
            }else{
                $_POST['token'] = session('token');
                if(!$t_busines_second->create()){
                    exit($this->error($t_busines_second->getError()));
                }else{
                    $pid = filter_var($this->_post('pid'),FILTER_VALIDATE_INT);
                    $status = filter_var($this->_post('status'),FILTER_SANITIZE_STRING);

                    if('edit'==$status && $pid != ''){
                        $o =  $t_busines_second->where(array('pid'=>$pid, 'token'=>session('token'),'type'=>$type))->save($_POST);
                        if($o){
                            exit($this->success('修改成功',U("Business/poster",array('token'=>session('token'),'type'=>$type))));
                        }else{
                            exit($this->error('修改失败',U("Business/poster",array('token'=>session('token'),'type'=>$type))));
                        }
                    }else{

                        if($id=$t_busines_second->data($_POST)->add()){
                            $this->success('添加成功',U("Business/poster",array('token'=>session('token'),'type'=>$type)));exit;
                        }else{

                exit($this->error('务器繁忙,添加失败,请稍候再试',U("Business/poster",array('token'=>session('token'),'type'=>$type))));
                        }
                    }//edit & add
                }

            }
        }
        $this->assign('busines_second',$busines_second);
        $this->display();
    }

    public function poster_del(){
        $type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $pid  = filter_var($this->_get('pid'),FILTER_VALIDATE_INT);
        $t_busines_main = M('busines_pic');
        $find = array('pid'=>$pid,'type'=>$type,'token'=>session('token'));
        $result = $t_busines_main->where($find)->find();
         if($result){
            $t_busines_main->where(array('pid'=>$result['pid'],'type'=>$result['type'],'token'=>session('token')))->delete();
            exit($this->success('删除成功',U("Business/poster",array('token'=>session('token'),'type'=>$result['type']))));
         }else{
           exit($this->error('非法操作！请稍候再试',U("Business/poster",array('token'=>session('token'),'type'=>$type))));
         }
    }

    public function comments(){
        $data       = D('busines_comment');
        $type       = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $where      = array('token'=>session('token'),'type'=>$type);
        $count      = $data->where($where)->count();
        $Page       = new Page($count,20);
        $show       = $Page->show();
        $comments= $data->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $i = 0;
        foreach($comments  as $val){
            $busines = M("busines")->where(array('token'=>session('token'),'bid'=>$val['bid_id']))->field('mtitle')->find();
            array_push($comments[$i],$busines['mtitle']);
            unset($busines);
             ++$i;
        }
        $this->assign('page',$show);
        $this->assign('comments',$comments);
        $this->display();

    }


    public function comments_add(){
        $t_busines = M("busines");
        $type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $where = array('token'=>session('token'),'type'=>$type);
        $busines_list = $t_busines->where($where)->order('sort desc')->field('bid,mtitle')->select();
        $this->assign('busines_list',$busines_list);
                $t_busines_comment = D('busines_comment');
        $cid  = filter_var($this->_get('cid'),FILTER_VALIDATE_INT);
        $where_2 = array('token'=>session('token'),'type'=>$type,'cid'=>$cid);
        $comments = $t_busines_comment->where($where_2)->find();
        if(IS_POST){
            $filters = array(
                'name'=>array(
                    'filter'=>FILTER_SANITIZE_STRIPPED,
                    'flags'=>FILTER_SANITIZE_STRING,
                    'options'=>FILTER_SANITIZE_ENCODED
                ),
                'face_picurl'=>array(
                    'filter'=>FILTER_VALIDATE_URL
                ),
                'position'=>array(
                    'filter'=>FILTER_SANITIZE_STRIPPED,
                    'flags'=>FILTER_SANITIZE_STRING,
                    'options'=>FILTER_SANITIZE_ENCODED
                ),
                'face_desc'=>array(
                    'filter'=>FILTER_SANITIZE_STRIPPED,
                    'flags'=>FILTER_SANITIZE_STRING,
                    'options'=>FILTER_SANITIZE_ENCODED
                ),
                'comment'=>array(
                    'filter'=>FILTER_SANITIZE_STRIPPED,
                    'flags'=>FILTER_SANITIZE_STRING,
                    'options'=>FILTER_SANITIZE_ENCODED
                )
            );

            $check = filter_var_array($_POST,$filters);
            if(!$check){
          exit($this->error('表单包含不允许字符.',U("Business/comments",array('token'=>session('token'),'type'=>$type))));
            }else{
                $_POST['token'] = session('token');
                if(!$t_busines_comment->create()){
                    exit($this->error($t_busines_comment->getError()));
                }else{
                    $cid = filter_var($this->_post('cid'),FILTER_VALIDATE_INT);
                    $status = filter_var($this->_post('status'),FILTER_SANITIZE_STRING);

                    if('edit'==$status && $cid != ''){
                        $o =  $t_busines_comment->where(array('cid'=>$cid, 'token'=>session('token'),'type'=>$type))->save($_POST);
                        if($o){
                            exit($this->success('修改成功',U("Business/comments",array('token'=>session('token'),'type'=>$type))));
                        }else{
                            exit($this->error('修改失败',U("Business/comments",array('token'=>session('token'),'type'=>$type))));
                        }
                    }else{

                        if($id=$t_busines_comment->data($_POST)->add()){
                            $this->success('添加成功',U("Business/comments",array('token'=>session('token'),'type'=>$type)));exit;
                        }else{
                    exit($this->error('服务器繁忙,添加失败,请稍候再试',U("Business/comments",array('token'=>session('token'),'type'=>$type))));
                        }
                    }//edit & add
                }

            }
        }
        $this->assign('comments',$comments);
        $this->display();
    }

    public function comments_del(){
        $type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $cid  = filter_var($this->_get('cid'),FILTER_VALIDATE_INT);
        $t_busines_main = M('busines_comment');
        $find = array('cid'=>$cid,'type'=>$type,'token'=>session('token'));
        $result = $t_busines_main->where($find)->find();
         if($result){
            $t_busines_main->where(array('cid'=>$result['cid'],'type'=>$result['type'],'token'=>session('token')))->delete();
            exit($this->success('删除成功',U("Business/comments",array('token'=>session('token'),'type'=>$result['type']))));
         }else{
            exit($this->error('非法操作！请稍候再试',U("Business/comments",array('token'=>session('token'),'type'=>$type))));
         }
    }

    public function orders(){
        $t_reservebook = M('Reservebook');
        $type          = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $where         = array('token'=>session('token'),'type'=>$type,"orderid!=''");
        $count         = $t_reservebook->where($where)->count();
        $Page          = new Page($count,50);
        $show          = $Page->show();
        $books = $t_reservebook->where($where)->order('booktime DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        $this->assign('books',$books);
        $this->assign('count',$t_reservebook->where($where)->count());
        $where2 = array('token'=>session('token'),'type'=>$type,'paid'=>1);
        $where3 = array('token'=>session('token'),'type'=>$type,'paid'=>0);
        $where4 = array('token'=>session('token'),'type'=>$type,'remate'=>0);
        $this->assign('ok_count',$t_reservebook->where($where2)->count());
        $this->assign('lose_count',$t_reservebook->where($where3)->count());
        $this->assign('call_count',$t_reservebook->where($where4)->count());
        $this->display();
    }

    public function order_del(){
        $id             = filter_var($this->_get('id'),FILTER_VALIDATE_INT);
        $type           = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $t_reservebook  = M('Reservebook');
        $where          = array('id'=>$id,'token'=>session('token'),'type'=>$type);
        $check          = $t_reservebook->where($where)->find();
        if(!empty($check)){
            $t_reservebook->where(array('id'=>$check['id'],'token'=>session('token'),'type'=>$type))->delete();
                $this->success('删除成功',U("Business/orders",array('token'=>session('token'),'type'=>$type)));
                exit;
        }else{
            $this->error('非法操作！',U("Business/orders",array('token'=>session('token'),'type'=>$type)));
            exit;
        }
    }

    public function orders_list(){
        $id             = filter_var($this->_get('id'),FILTER_VALIDATE_INT);
        $type           = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $token          = session('token');
        $where          = array('id'=>$id,'token'=>$token,'type'=>$type);
        $t_reservebook  = M('reservebook');
        $userinfo       = $t_reservebook->where($where)->find();
        $this->assign('userinfo',$userinfo);
        if(IS_POST){

            $id     = filter_var($this->_post('id'),FILTER_VALIDATE_INT);
            $type   = filter_var($this->_post('type'),FILTER_VALIDATE_INT);
            $token  = session('token');
            $where  =  array('id'=>$id,'token'=>$token);
            if((int)$this->_post('remate') == 1){
                $_POST['paid'] = 1;
            }
            $ok     = $t_reservebook->where($where)->save($_POST);
            if($ok){
              $this->assign('ok',1);
            }else{
                $this->assign('ok',2);
            }
            echo "<script type='text/javascript'>parent.location.reload();</script>";
        }
       $this->display();
    }



}
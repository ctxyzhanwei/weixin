<?php
class CarAction extends UserAction{


    public function _initialize() {
        parent::_initialize();
        //
        $function=M('Function')->where(array('funname'=>'car'))->find();
        if (intval($this->user['gid'])<intval($function['gid'])){
            $this->error('您还开启该模块的使用权,请到功能模块中添加',U('Function/index',array('token'=>$this->token)));
        }
        $this->canUseFunction('car');
    }
    public function index(){
        $data=M('Car');
        $where = array('token'=>session('token'));
        $count      = $data->where($where)->count();
        $Page       = new Page($count,12);
        $show       = $Page->show();
        $brands = $data->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        $this->assign('brands',$brands);
        $this->display();
    }

    // add car brand
    public function carbrand(){
        if(IS_POST){
            $data=D('Car');
            $_POST['token']=session('token');
            if($data->validate($validate)->create()!=false){
                if($id=$data->data($_POST)->add()){
                    $this->success('添加成功',U('Car/index',array('token'=>session('token'))));
                    exit;
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

    public function brand_edit(){
        if(IS_POST){
            $data=D('Car');
            $where=array('id'=>(int)$this->_get('bid'),'token'=>session('token'));
            $check=$data->where($where)->find();
            if($check==false)$this->error('非法操作');
            if($data->create()){
                $ok = $data->where($where)->save($_POST);
                if($ok){
                    $this->success('修改成功',U('Car/index',array('token'=>session('token'))));
                }else{
                    $this->error('操作失败');
                }
            }else{
                $this->error($data->getError());
            }
        }else{
            $id=(int)$this->_get('bid');
            $where=array('id'=>$id,'token'=>session('token'));
            $data=M('Car');
            $check=$data->where($where)->find();
            if($check==false)$this->error('非法操作');
            $brand=$data->where($where)->find();
            $this->assign('brand',$brand);
            $this->display('carbrand');
        }
    }

    public function brand_del(){
        $id = (int)$this->_get('bid');
        $res = M('Car');
        $find = array('id'=>$id,'token'=>$this->_get('token'));
        $result = $res->where($find)->find();

         if($result){
            $res->where(array('id'=>$result['id']))->delete();
            $this->success('删除成功',U('Car/index',array('token'=>session('token'))));
             exit;
         }else{
            $this->error('非法操作！');
             exit;
         }
    }

    public function series(){

        $t_series = M('carseries');
        $token =  session('token');
        $where = array('token'=>$token);
        $count      = $t_series->where($where)->count();
        $Page       = new Page($count,12);
        $show       = $Page->show();
        $series = $t_series->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('series',$series);
         $this->assign('page',$show);

        $this->display();
    }

    public function addseries(){
         $where=array('token'=>session('token'));
         $t_car=M('Car');
         $brands=$t_car->where($where)->field('id,name')->select();
         $this->assign('brands',$brands);
         if(IS_POST){
            $exp_brand = explode('@',$this->_post('brand'));
            $data=D('Carseries');
            $_POST['token'] = session('token');
            $_POST['brand_id'] = $exp_brand[0];
            if($data->create()!=false){
                if($id=$data->data($_POST)->add()){
                    $this->success('添加成功',U('Car/series',array('token'=>session('token'))));
                    exit;
                }else{
                    $this->error('服务器繁忙,请稍候再试');
                    exit;
                }
            }else{
                $this->error($data->getError());
            }
         }
        $this->display();
    }

    public function editseries(){
        if(IS_POST){
            $data=D('Carseries');
            $where=array('id'=>(int)$_POST['id'],'token'=>session('token'));
            $check=$data->where($where)->find();
            if($check==false)$this->error('非法操作');
            if($data->create()){
                $exp_brand = explode('@',$this->_post('brand'));
                $_POST['brand_id'] = $exp_brand[0];
                if($data->where($where)->save($_POST)){
                    $this->success('修改成功',U('Car/series',array('token'=>session('token'))));
                }else{
                    $this->error('操作失败');
                }
            }else{
                $this->error($data->getError());
            }
        }else{
             $t_car=M('Car');
             $where=array('token'=>session('token'));
             $brands=$t_car->where($where)->field('id,name')->select();
             $this->assign('brands',$brands);

            $id=(int)$this->_get('id');
            $where2=array('id'=>$id,'token'=>session('token'));
            $data=M('Carseries');
            $check=$data->where($where2)->find();
            if($check==false)$this->error('非法操作');
            $series=$data->where($where2)->find();
            $this->assign('series',$series);
            $bid  =  explode('@',$series['brand']);
            $this->assign('bid',$bid[0]);
            $this->display('addseries');
        }
    }

    public function delseries(){
        $id = (int)$this->_get('id');
        $res = M('Carseries');
        $find = array('id'=>$id,'token'=>$this->_get('token'));
        $result = $res->where($find)->find();
         if($result){
            $res->where(array('id'=>$result['id']))->delete();
            $this->success('删除成功',U('Car/series',array('token'=>session('token'))));
             exit;
         }else{
            $this->error('非法操作！');
             exit;
         }
    }

    public function carmodel(){
        $t_carmodel = M('Carmodel');
        $where = array('token'=>session('token'));
        $count      = $t_carmodel->where($where)->count();
        $Page       = new Page($count,12);
        $show       = $Page->show();
        $carmodel = $t_carmodel->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('carmodel',$carmodel);
         $this->assign('page',$show);
        $this->display();
    }

    public function add_carmodel(){
        $where = array('token'=>session('token'));
        $panorama = M('Panorama')->where($where)->field('id as pid,name,keyword')->select();
        $this->assign('panorama',$panorama);
         if(IS_POST){
            $data=D('Carmodel');
            $_POST['token'] = session('token');
            if($data->create()!=false){
                if($id=$data->data($_POST)->add()){
                    $this->success('添加成功',U('Car/carmodel',array('token'=>session('token'))));
                    exit;
                }else{
                    $this->error('服务器繁忙,请稍候再试');
                    exit;
                }
            }else{
                $this->error($data->getError());
            }
         }
        $this->display();
    }

    public function get_car_brand(){
        //品牌 和 车系
         $where=array('token'=>session('token'));
         $t_carseries=D('Carseries');
         $arr=$t_carseries->where($where)->field('id,brand_id,brand,name')->order('id asc')->group('name')->select();
         $this->assign('carseries',$arr);
         $t_car = M('Car')->where($where)->field('id,name')->select();
         $bcount = count($t_car);
         $count = count($arr);
         $data = array();
         for($i = 0; $i<$bcount; $i++){
            $data[$i+1]['name'] = $t_car[$i]['name'];
            for($j=0;$j<$count;$j++){

                if($t_car[$i]['id'] == $arr[$j]['brand_id']){

                    $data[$i+1]['cell'][$j+1]['name'] = $arr[$j]['name'];
                    $data[$i+1]['cell'][$j+1]['s_id'] = $arr[$j]['id'];

                }
            }
         }
       header( 'Content-Type: application/json; charset=UTF-8' );
        print json_encode($data);
    }

    public function edit_carmodel(){
        $panorama = M('Panorama')->where($where)->field('id as pid,name,keyword')->select();
        $this->assign('panorama',$panorama);
        if(IS_POST){
            $data=D('Carmodel');
            $where=array('id'=>(int)$this->_get('id'),'token'=>session('token'));
            $check=$data->where($where)->find();
            if($check==false)$this->error('非法操作');
            if($data->create()){
                if($data->where($where)->save($_POST)){
                    $this->success('修改成功',U('Car/carmodel',array('token'=>session('token'))));
                }else{
                    $this->error('操作失败');
                }
            }else{
                $this->error($data->getError());
            }
        }else{
            $id=(int)$this->_get('id');
            $where2=array('id'=>$id,'token'=>session('token'));
            $data=M('Carmodel');
            $check=$data->where($where2)->find();
            if($check==false)$this->error('非法操作');
            $carmodel=$data->where($where2)->find();
            $this->assign('carmodel',$carmodel);
            $this->display('add_carmodel');
        }
    }

    public function del_carmodel(){
        $id = (int)$this->_get('id');
        $res = M('Carmodel');
        $find = array('id'=>$id,'token'=>session('token'));
        $result = $res->where($find)->find();
         if($result){
            $res->where(array('id'=>$result['id'],'token'=>session('token')))->delete();
            $this->success('删除成功',U('Car/carmodel',array('token'=>session('token'))));
             exit;
         }else{
            $this->error('非法操作！');
             exit;
         }
    }

    //预约管理
    public function reservation(){
        $data = M("Reservation");
        $where = "`token`='".session('token')."' AND (`addtype`='drive' OR `addtype`='maintain')";
        $count      = $data->where($where)->count();
        $Page       = new Page($count,12);
        $show       = $Page->show();
        $reslist = $data->where($where)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        $drive_count = $data->where(array('addtype' => 'drive','token'=>session('token')))->count();
        $maintain_count = $data->where(array('addtype' => 'maintain','token'=>session('token')))->count();
        $this->assign('drive_count',$drive_count);
        $this->assign('maintain_count',$maintain_count);
        $this->assign('page',$show);
        $this->assign('reslist',$reslist);
        $this->display();
    }

    //预约订单管理
    public function res_manage(){
        $t_reservebook = M('Reservebook');
        $rid = (int)$this->_get('id');
        //预约类型，根据addtype类型判断
        $addtype = strval($this->_get('addtype'));
        $this->assign('addtype',$addtype);
        if($addtype == 'drive'){
            $where = array('token'=>session('token'),'rid'=>$rid,'type'=>$addtype);
        }elseif($addtype =='maintain'){
            $where = array('token'=>session('token'),'rid'=>$rid,'type'=>$addtype);
        }
        $count      = $t_reservebook->where($where)->count();
        $Page       = new Page($count,12);
        $show       = $Page->show();
        $books = $t_reservebook->where($where)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);

        $this->assign('books',$books);
        $this->assign('count',$t_reservebook->where($where)->count());
        $where2 = array('token'=>session('token'),'rid'=>$rid,'type'=>$addtype,'remate'=>1);
        $where3 = array('token'=>session('token'),'rid'=>$rid,'type'=>$addtype,'remate'=>2);
        $where4 = array('token'=>session('token'),'rid'=>$rid,'type'=>$addtype,'remate'=>0);
        $this->assign('ok_count',$t_reservebook->where($where2)->count());
        $this->assign('lose_count',$t_reservebook->where($where3)->count());
        $this->assign('call_count',$t_reservebook->where($where4)->count());
        $this->display();
    }

    public function add_res(){
        $this->assign('addtype',$this->_get('addtype'));
        $addtype = $this->_get('addtype');
        if(IS_POST){
            $data=D('Reservation');
            $_POST['token']=session('token');
            if($data->create()!=false){
                if($id=$data->data($_POST)->add()){
                    $data1['pid']=$id;
                    $data1['module']='Reservation';
                    $data1['token']=session('token');
                    $data1['keyword']=trim($_POST['keyword']);
                    M('Keyword')->add($data1);
                    //$user=M('Users')->where(array('id'=>session('uid')))->setInc('activitynum');
                    $this->success('添加成功',U('Car/reservation',array('token'=>session('token'))));
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

    public function res_edit(){
        $this->assign('addtype',$this->_get('addtype'));
         if(IS_POST){
            $data=D('Reservation');
            $where=array('id'=>(int)$this->_post('id'),'token'=>session('token'));
            $check=$data->where($where)->find();

            if($check==false)$this->error('非法操作');


            if($data->create()){
                if($data->where($where)->save($_POST)){
                    $data1['pid']=(int)$this->_post('id');
                    $data1['module']='Reservation';
                    $data1['token']=session('token');

                    $da['keyword']=trim($_POST['keyword']);
                    M('Keyword')->where($data1)->save($da);
                    $this->success('修改成功',U('Car/reservation',array('token'=>session('token'))));
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
            if($check==false)$this->error('非法操作');
            $reslist=$data->where($where)->find();
            $this->assign('reslist',$reslist);
            $this->display('add_res');
        }

    }

    public function res_del(){
        $id = (int)$this->_get('id');
        $res = M('Reservation');
        $find = array('id'=>$id,'token'=>$this->_get('token'));
        $result = $res->where($find)->find();
         if($result){
            $res->where('id='.$result['id'])->delete();
            $where = array('pid'=>$result['id'],'module'=>'Reservation','token'=>session('token'));
            M('Keyword')->where($where)->delete();
            $this->success('删除成功',U('Car/reservation',array('token'=>session('token'))));
             exit;
         }else{
            $this->error('非法操作！');
             exit;
         }
    }


    public function reservation_uinfo(){
        $id = $this->_get('id');
        $token = $this->_get('token');
        $where = array('id'=>$id,'token'=>$token);
        $t_reservebook = M('Reservebook');
        $userinfo = $t_reservebook->where($where)->find();
        $this->assign('userinfo',$userinfo);
       // var_dump($userinfo);
        if(IS_POST){
            //var_dump($_POST);
            $id = $this->_post('id');
            $token = session('token');
            $where =  array('id'=>$id,'token'=>$token);
            $ok = $t_reservebook->where($where)->save($_POST);
            if($ok){
                $this->assign('ok',1);
                //$this->success('成功',U('Reservation/manage',array('token'=>session('token'))));
            }else{
                     $this->assign('ok',2);
            }

        }
        $this->display();


    }

    public function manage_del(){

        $id = $this->_get('id');
        $t_reservebook = M('Reservebook');
        $where = array('id'=>$id,'token'=>$this->_get('token'));
        $check  = $t_reservebook->where($where)->find();
        $car = $this->_get('car');
        if(!empty($check)){
            $t_reservebook->where(array('id'=>$check['id'],'token'=>session('token')))->delete();
            //if($car == 'car'){
                $this->success('删除成功',U('Car/reservation',array('token'=>session('token'))));
                exit;
            // }else{
            //     $this->success('删除成功',U('Reservation/index',array('token'=>session('token'))));
            //     exit;
            // }

        }else{
            $this->error('非法操作！');
            exit;
        }
    }


    public function salers(){
        $data=M('Carsaler');
        $where = array('token'=>session('token'));
        $count      = $data->where($where)->count();
        $Page       = new Page($count,12);
        $show       = $Page->show();
        $salers = $data->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        $this->assign('salers',$salers);
        $this->display();
    }

    public function add_saler(){
        if(IS_POST){
            $data=D('Carsaler');
            $_POST['token'] = session('token');

            if($data->create()!=false){
                if($id=$data->data($_POST)->add()){
                    $this->success('添加成功',U('Car/salers',array('token'=>session('token'))));
                    exit;
                }else{
                    $this->error('服务器繁忙,请稍候再试');
                    exit;
                }
            }else{
                $this->error($data->getError());
            }
         }
        $this->display();
    }

    public function edit_salers(){
        if(IS_POST){
            $data=D('Carsaler');
            $where=array('id'=>(int)$this->_get('id'),'token'=>session('token'));
            $check=$data->where($where)->find();
            if($check==null)$this->error('非法操作');
            if($data->create()){
                $where2 = array('id'=>(int)$this->_post('id'),'token'=>session('token'));
                if($data->where($where2)->save($_POST)){
                    $this->success('修改成功',U('Car/salers',array('token'=>session('token'))));
                }else{
                    $this->error('操作失败');
                }
            }else{
                $this->error($data->getError());
            }
        }else{
            $id=(int)$this->_get('id');
            $where2=array('id'=>$id,'token'=>session('token'));
            $data=M('Carsaler');
            $check=$data->where($where2)->find();
            if($check==null)$this->error('非法操作');
            $salers=$data->where($where2)->find();
            $this->assign('salers',$salers);
            $this->display('add_saler');
        }
    }

    public function del_salers(){
        $id = (int)$this->_get('id');
        $res = M('Carsaler');
        $find = array('id'=>$id,'token'=>session('token'));
        $result = $res->where($find)->find();
         if($result){
            $res->where(array('id'=>$result['id'],'token'=>session('token')))->delete();
            $this->success('删除成功',U('Car/salers',array('token'=>session('token'))));
             exit;
         }else{
            $this->error('非法操作！');
             exit;
         }
    }

    public function carowner(){
        $data = M("Carowner");
        $where = array('token'=>session('token'));
        $carowner = $data->where($where)->find();
        if(IS_POST){
           if($carowner == null){
                    if($id=$data->add($_POST)){
                        $data1['pid']=$id;
                        $data1['module']='Carowner';
                        $data1['token']=session('token');
                        $data1['keyword']=trim($_POST['keyword']);
                        M('Keyword')->add($data1);
                        //$user=M('Users')->where(array('id'=>session('uid')))->setInc('activitynum');
                        $this->success('添加成功',U('Car/carowner',array('token'=>session('token'))));
                         exit;
                    }else{
                        $this->error('服务器繁忙,请稍候再试');
                    }

           }else{
            $id =filter_var($this->_post('id'),FILTER_VALIDATE_INT);
            $wh = array('token'=>session('token'),'id'=>$id);
             if($data->where($wh)->save($_POST)){
                    $data1['pid']=$id;
                    $data1['module']='Carowner';
                    $data1['token']=session('token');
                    $da['keyword']=trim($this->_post('keyword'));
                    M('Keyword')->where($data1)->save($da);

                    $this->success('修改成功',U('Car/carowner',array('token'=>session('token'))));
                }else{
                    $this->error('操作失败');
                }
           }
        }else{
          $this->assign('carowner',$carowner);
           $this->display();
        }
    }

    public function caronwers(){

        $t_caruser = M('Caruser');
        $token = session('token');
        $where = array('token'=>$token);

        $count      = $t_caruser->where($where)->count();
        $Page       = new Page($count,20);
        $show       = $Page->show();
        $onwers = $t_caruser->where($where)->order('car_insurance_lastCost desc,car_care_mileage desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        $this->assign('onwers',$onwers);
        $this->display();


    }

    public function owner_uinfo(){
        $id = $this->_get('id');
        $token = $this->_get('token');
        $where = array('id'=>$id,'token'=>$token);
        $t_caruser = M('Caruser');
        $userinfo = $t_caruser->where($where)->find();
        $this->assign('userinfo',$userinfo);

        if(IS_POST){
             $_POST['car_insurance_lastDate'] = $this->_post('insurance_Date');
             $_POST['car_insurance_lastCost'] = $this->_post('insurance_Cost');
             $_POST['car_care_mileage'] = $this->_post('care_mileage');
             $_POST['car_care_lastDate'] = $this->_post('car_care_Date');
             $_POST['car_care_lastCost'] = $this->_post('car_care_Cost');
            $id = $this->_post('id');
            $token = session('token');
            $where =  array('id'=>$id,'token'=>$token);
            $ok = $t_caruser->where($where)->save($_POST);
            if($ok){
                $this->assign('ok',1);
            }else{
                     $this->assign('ok',2);
            }

        }
        $this->display();
    }

    public function del_carowner(){
        $id = (int)$this->_get('id');
        $res = M('Caruser');
        $find = array('id'=>$id,'token'=>session('token'));
        $result = $res->where($find)->find();
         if($result){
            $res->where(array('id'=>$result['id'],'token'=>session('token')))->delete();
            $this->success('删除成功',U('Car/caronwers',array('token'=>session('token'))));
             exit;
         }else{
            $this->error('非法操作！');
             exit;
         }
    }

    public function utility(){
        $t_uti  = M('Car_utility');
        if(IS_POST){
            $id   = filter_var($this->_post('id'),FILTER_VALIDATE_INT);
            $check = $t_uti->where(array('id'=>$id,'token'=>session('token')))->find();

            if(!$check && $id == ''){
                $svaeone = $t_uti->data()->add($_POST);
                $this->success('添加成功',U('Car/utility',array('token'=>session('token'))));exit;
            }else{
                $svaethis = $t_uti->where(array('id'=>$id,'token'=>session('token')))->save($_POST);
                if($svaethis){
                    $this->success('修改成功',U('Car/utility',array('token'=>session('token'))));exit;
                }else{
                    exit($this->error('修改失败,请无重复提交'));
                }
            }


        }
        $utility = $t_uti->where(array('token'=>session('token')))->order('id desc')->select();
        $this->assign('utility',$utility);
        $this->assign('token',session('token'));
         $this->display();
    }

    public function item_del(){
        $type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $id  = filter_var($this->_get('id'),FILTER_VALIDATE_INT);
        $t_uti  = M('Car_utility');
        $find = array('id'=>$id,'token'=>session('token'));
        $result = $t_uti->where($find)->find();
         if($result && $type == 'del'){
            $t_uti->where(array('id'=>$result['id'],'token'=>session('token')))->delete();
            $this->success('删除成功',U("Car/utility",array('token'=>session('token'))));
             exit;
         }else{
            exit($this->error('非法操作,请稍候再试',U("Car/utility",array('token'=>session('token')))));
         }
    }


    public function carnews(){
        $t_classify = M('Classify');
        $token = session('token');
        $where = array('token'=>$token);
        $classify = $t_classify->where($where)->order('id desc')->select();
        $this->assign('classify',$classify);

        $Photo = M("Photo");
        $photo = $Photo->where($where)->order('id desc')->select();
        $this->assign('photo',$photo);

        $company = M('Company')->where($where)->field('id,name')->select();
        $this->assign('company',$company);
        $data = M('Carnews');
        //$where2 = array('token'=>$token,'id);
        $carnews = $data->where($where)->find();
        if(IS_POST){
           if($carnews == null){
                    if($id=$data->add($_POST)){
                        $this->success('添加成功',U('Car/carnews',array('token'=>session('token'))));
                         exit;
                    }else{
                        $this->error('服务器繁忙,请稍候再试');
                    }

           }else{
              $wh = array('token'=>session('token'),'id'=>$this->_post('id'));
              //var_dump($_POST);exit;
             if($data->where($wh)->save($_POST)){
                    $this->success('修改成功',U('Car/carnews',array('token'=>session('token'))));
                }else{
                    $this->error('操作失败');
                }
           }
        }else{
          $this->assign('carnews',$carnews);
          $this->display();
        }
    }

    public function carset(){
        $data = M('Carset');
        $where = array('token'=>session('token'));
        $carset = $data->where($where)->find();
        if(IS_POST){
            $_id    =filter_var($this->_post('id'),FILTER_VALIDATE_INT);
            $status = filter_var($this->_post('status'),FILTER_SANITIZE_STRING);

            if($status == 'editstatus' && $_id != ''){
                 $wh = array('token'=>session('token'),'id'=>$_id);
                 if($data->where($wh)->save($_POST)){
                        $data1['pid']=$_id;
                        $data1['module']='Carset';
                        $data1['token']=session('token');
                        $da['keyword']=trim($this->_post('keyword'));
                        M('Keyword')->where($data1)->save($da);
                        $this->success('修改成功',U('Car/carset',array('token'=>session('token'))));
                    }else{
                        $this->error('修改操作失败,请检查是否有空项');exit;
                    }

            }else{
                    if($data->create()!=false){
                        if($id=$data->data($_POST)->add()){
                                $data1['pid']=$id;
                                $data1['module']='Carset';
                                $data1['token']=session('token');
                                $data1['keyword']=trim($_POST['keyword']);
                                M('Keyword')->add($data1);
                                $this->success('添加成功',U('Car/carset',array('token'=>session('token'))));
                                 exit;
                        }else{
                            $this->error('添加操作失败,请检查是否有空项');exit;
                        }

                    }else{
                        $this->error($data->getError());
                    }

            }

        }else{
          $this->assign('carset',$carset);
           $this->display();
        }
    }
}


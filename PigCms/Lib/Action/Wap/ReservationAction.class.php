<?php
// 3G
class ReservationAction extends BaseAction{

    public $token;
    public $wecha_id;
    public function _initialize() {
        parent::_initialize();
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
           // exit('此功能只能在微信浏览器中使用');
        }
        $token=$this->_get('token');
        $wecha_id=$this->_get('wecha_id');
        $this->assign('token',$token);
        $this->assign('wecha_id',$wecha_id);
        //$get_ids = M('Estate')->where(array('token'=>$this->token))->field('res_id,classify_id')->find();
        //$this->assign('rid',$get_ids['res_id']);
        if(!isset($_SESSION)){
            session_start();
        }

    }

    public function index(){
        $data       = M("Reservation");
        $token      = $this->_get('token');
        $wecha_id   = $this->_get('wecha_id');
        $rid        = (int)$this->_get('rid');
        $this->assign('token',$token);
        $this->assign('wecha_id',$wecha_id);
        //$rid = M('Estate')->where($where)->getField('res_id');

        if($rid != ''){
            $this->assign('rid',$rid);
            $where2 = array('token'=>$token,'id'=>$rid);
            $reslist =  $data->where($where2)->find();
            if($reslist['addtype'] =='drive'){
                //exit($reslist['addtype']);
                $where_2 = array('token'=>$token,'addtype'=>'drive');
                $reser =  $data->where($where_2)->find();
                $this->assign('addtype','drive');
                $where3 = array('token'=>$token,'wecha_id'=>$wecha_id);
                $user = M('Caruser')->where($where3)->field('car_userName as truename,brand_serise,car_no as carnum,user_tel,car_care_mileage as km')->find();
                if(!empty($user)){
                     $reser = array_merge($reser,$user);
                }
                $this->assign('reser',$reser);
                $where4 = array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>$addtype);
                $count = M('Reservebook')->where($where4)->count();
                $this->assign('count',$count);
                $this->display("Car:CarReserveBook");
                exit;
            }
            if($reslist['addtype'] =='maintain'){
                $where_3  =  array('token'=>$token,'addtype'=>'maintain');
                $this->assign('addtype','maintain');
                $reser =  $data->where($where_3)->find();
                $where_5 = array('token'=>$token,'wecha_id'=>$wecha_id);
                $user = M('Caruser')->where($where_5)->field('car_userName as truename,brand_serise,car_no as carnum,user_tel,car_care_mileage as km')->find();
                if(!empty($user)){
                     $reser = array_merge($reser,$user);
                }
                $this->assign('reser',$reser);
                $where4_1 = array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>$addtype);
                $count = M('Reservebook')->where($where4_1)->count();
                $this->assign('count',$count);
                $this->display("Car:CarReserveBook");
                exit;
            }
            if($reslist['addtype'] =='house_book'){
                $t_housetype = M('Estate_housetype');
                $eid        = $this->_get('rid','intval');
                if(empty($eid)){
                    $this->error('参数错误！');
                    exit;
                }
                $where      = array('token'=>$token,'pid'=>$eid);
                $housetype  = $t_housetype->where($where)->order('sort desc')->field('id as hid,name')->select();
                $this->assign('housetype',$housetype);
                $this->assign('eid',$eid);
            }
        }

        $where3 = array('token'=>$token,'wecha_id'=>$wecha_id);
        $user = M('Userinfo')->where($where3)->field('truename,tel as user_tel')->find();
        if(!empty($user)){
            $reslist = array_merge($reslist,$user);
        }
        $this->assign('reslist',$reslist);


        $where4 = array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>'house_book','rid'=>$rid);
        $count = M('Reservebook')->where($where4)->count();
        $this->assign('count',$count);
        $this->display();

    }

    public function add(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
            //exit('此功能只能在微信浏览器中使用');
        }
        $da['token']      = strval($this->_get('token'));
        $da['wecha_id']   = strval($this->_post('wecha_id'));
        $da['rid']        = (int)$this->_post('rid');
        $da['truename']   = strval($this->_post("truename"));
        $da['dateline']   = strval($this->_post("dateline"));
        $da['timepart']   = strval($this->_post("timepart"));
        $da['info']       = strval($this->_post("info"));
        $da['tel']        = strval($this->_post("tel"));
        $da['type']       = strval($this->_post('type'));
        //$da['fieldsigle'] =$this->_post('fieldsigle');
        $da['housetype']  = $this->_post('housetype');
        $da['booktime']   = time();
        $das['id']        = (int)$this->_post('id');

        if($da['type'] =='maintain'){
            $da['carnum']   = strval($this->_post("carnum"));
            $da['km']       = (int)$this->_post('km');
        }
        $book   =   M('Reservebook');
    //   $where  = array('id'=>$das['id'],'token'=>$da['token']);
    //    $info   = M('Reservation')->where($where)->find();
    // $arr=array('errno'=>1,'msg'=>$da['type'],'housetype'=>$da['housetype']);
    // echo json_encode($arr);
    // exit;
         $token = strval($this->_get('token'));
         $wecha_id = strval($this->_get('wecha_id'));
         $url ='http://'.$_SERVER['HTTP_HOST'];
         $url .= U('Reservation/mylist',array('token'=>$token,'wecha_id'=>$wecha_id,'id'=>(int)$this->_post('rid')));

        if($das['id'] != ''){
            $o = $book->where(array('id'=>$das['id']))->save($da);
            if($o){

                $arr=array('errno'=>0,'msg'=>'修改成功','url'=>$url,'token'=>$token,'wecha_id'=>$wecha_id);
                echo json_encode($arr);
                exit;
            }else{
                $arr=array('errno'=>1,'msg'=>'修改失败','url'=>$url,'token'=>$token,'wecha_id'=>$wecha_id);
                echo json_encode($arr);
                exit;
            }
        }

        $ok = $book->data($da)->add();
        if(!empty($ok)){
            $model = new templateNews();
            if($da['type'] == 'house_book'){
                $estate     = M('Estate')->where(array('token'=>$token,'id'=>$this->_post('eid','intval')))->field('title,place')->find();
                $dataKey    = 'TM00130';
                $dataArr    = array(
                    'href'      => $url,
                    'wecha_id'  => $wecha_id,
                    'first'     => '您好，您已成功预约看房。' ,
                    'apartmentName' =>  $estate['title'],
                    'roomNumber' => $da['housetype'] ,
                    'address'   =>  $estate['place'],
                    'time'      =>  $da['dateline'].' '.$da['timepart'],
                    'remark'    => '请您准时到达看房。'
                );
            }
            $model->sendTempMsg($dataKey,$dataArr);

            $arr = array('errno'=>0,'msg'=>'恭喜预约成功','token'=>$token,'wecha_id'=>$wecha_id,'url'=>$url);
            echo json_encode($arr);
            exit;
        }else{
             $arr=array('errno'=>1,'msg'=>'预约失败，请重新预约','token'=>$token,'wecha_id'=>$wecha_id,'url'=>$url);
            echo json_encode($arr);
            exit;
        }

    }


    public function mylist(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
            //exit('此功能只能在微信浏览器中使用');
        }
        $token      = $this->_get('token');
        $wecha_id   = $this->_get('wecha_id');
        $id         = $this->_get('id','intval');
        $this->assign('token',$token);
        $this->assign('wecha_id',$wecha_id);
        $book   = M('Reservebook');
        $where  = array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>'house_book','rid'=>$id);
        $books  = $book->where($where)->order('id DESC')->select();
        $this->assign('books',$books);

        $data = M("Reservation");
        if($id != ''){
            $where3 = array('token'=>$token,'id'=>$id);
            $headpic =  $data->where($where3)->getField('headpic');
        }
        $this->assign('headpic',$headpic);
        $this->display();
    }

    public function edit(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
            //exit('此功能只能在微信浏览器中使用');
        }

        $rid = (int)$this->_get('rid');
        $this->assign('rid',$rid);
        $book = M('Reservebook');
        $id = (int)$this->_get('id');
        $token = $this->_get('token');
        $wecha_id = $this->_get('wecha_id');
        $where = array('id'=>$id,'token'=>$token,'wecha_id'=>$wecha_id);
        $reslist = $book->where($where)->field('id,rid,token,wecha_id,truename,tel as user_tel,housetype,dateline,timepart,info as userinfo,type,booktime')->find();
        $reservation = M('Reservation')->where(array('token'=>$token,'id'=>$rid))->field('picurl,info,address,place,lng,lat,title,tel')->find();

        if(!empty($reslist)){
            $reslist = array_merge($reservation,$reslist);
            //var_dump($reslist);
            $this->assign('reslist',$reslist);
            $t_housetype = M('Estate_housetype');
            $housetype = $t_housetype->where(array('token'=>$token))->order('sort desc')->field('id as hid,name')->select();
            $this->assign('housetype',$housetype);
        }else{
            $this->error('操作错误',U('Index/index',array('token'=>$token,'wecha_id'=>$wecha_id)));
        }
        $where4 = array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>'house_book');
        $count = M('Reservebook')->where($where4)->count();
        $this->assign('count',$count);
        $this->display('index');

    }


}?>
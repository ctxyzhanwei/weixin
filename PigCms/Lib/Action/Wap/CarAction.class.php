<?php
/*
3G 汽车
*/
class CarAction extends WapAction{
    public $token;
    public $wecha_id;
    public $addtype;
    private $tpl;
    private $info;
    public $weixinUser;
    public $homeInfo;
    public function _initialize() {
        parent::_initialize();
        if(!strpos($agent,"icroMessenger")) {
           // exit('此功能只能在微信浏览器中使用');
        }
        $this->token = $this->_get('token');
        $this->wecha_id =$this->_get('wecha_id');
        $this->addtype = $this->_get('addtype');
        $this->assign('token',$this->token);
        $this->assign('wecha_id',$this->wecha_id);
        $this->assign('addtype',$addtype);

        $tpl=$this->wxuser;
        $this->tpl=$tpl;
    }


    public function index(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
            //echo '此功能只能在微信浏览器中使用';exit;
        }
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $wecha_id   = filter_var($this->_get('wecha_id'),FILTER_SANITIZE_STRING);
        $t_carset   = M('carset');
        $where      = array('token'=>$token,'wecha_id'=>$wecha_id);
        $carset     = $t_carset->where($where)->find();
        include('./PigCms/Lib/ORG/index.Tpl.php');
        foreach($tpl as $k=>$v){
            if($v['tpltypeid'] == $carset['tpid']){
                 $tplinfo = $v;
            }
        }

$info = array();
$info[0]['url']  = "/index.php?g=Wap&m=Car&a=showcar&token=$token&wecha_id=$wecha_id";
$info[0]['img']  = '/tpl/static/attachment/icon/white/6.png';
$info[0]['name'] = '车型欣赏';

$info[1]['url']  = "/index.php?g=Wap&m=Car&a=aboutus&token=$token&wecha_id=$wecha_id";
$info[1]['img']  = '/tpl/static/attachment/icon/white/5.png';
$info[1]['name'] = '关于我们';

$info[2]['url']  = "/index.php?g=Wap&m=Panorama&a=index&token=$token&wecha_id=$wecha_id&sgssz=mp.weixin.qq.com";
$info[2]['img']  = '/tpl/static/attachment/icon/white/13.png';
$info[2]['name'] = '全景看车';

$info[3]['url']  = "/index.php?g=Wap&m=Car&a=tool&token=$token&wecha_id=$wecha_id";
$info[3]['img']  = '/tpl/static/attachment/icon/white/2.png';
$info[3]['name'] = '实用工具';

$info[4]['url']  = "/index.php?g=Wap&m=Car&a=brands&token=$token&wecha_id=$wecha_id";
$info[4]['img']  = $carset['head_url_1'];
$info[4]['name'] = $carset['title1'];

$info[5]['url']  = "/index.php?g=Wap&m=Car&a=salers&token=$token&wecha_id=$wecha_id";
$info[5]['img']  = $carset['head_url_2'];
$info[5]['name'] = $carset['title2'];

$info[6]['url']  = "/index.php?g=Wap&m=Car&a=CarReserveBook&token=$token&wecha_id=$wecha_id&type=drive";
$info[6]['img']  = $carset['head_url_3'];
$info[6]['name'] = $carset['title3'];

$info[7]['url']  = "/index.php?g=Wap&m=Car&a=owner&token=$token&wecha_id=$wecha_id";
$info[7]['img']  = $carset['head_url_4'];
$info[7]['name'] = $carset['title4'];

$info[8]['url']  = "/index.php?g=Wap&m=Car&a=tool&token=$token&wecha_id=$wecha_id";
$info[8]['img']  = $carset['head_url_5'];
$info[8]['name'] = $carset['title5'];

$info[9]['url']  = "/index.php?g=Wap&m=Car&a=news&token=$token&wecha_id=$wecha_id&type=pre";
$info[9]['img']  = '/tpl/static/attachment/icon/white/7.png';
$info[9]['name'] = '优惠促销';

$info[10]['url']  = "/index.php?g=Wap&m=Car&a=news&token=$token&wecha_id=$wecha_id";
$info[10]['img']  = '/tpl/static/attachment/icon/white/14.png';
$info[10]['name'] = '咨询动态';

$info[11]['url']  = "/index.php?g=Wap&m=Car&a=news&token=$token&wecha_id=$wecha_id&type=oldcar";
$info[11]['img']  = '/tpl/static/attachment/icon/white/15.png';
$info[11]['name'] = '尊选二手车';

//

        $allflash   = M('Flash')->where(array('token'=>$token))->select();
        $flash   = array();
        $flashbg  = array();
        foreach ($allflash as $af){
        if ($af['url']==''){
                $af['url']='javascript:void(0)';
            }
            if ($af['tip']==1){
                array_push($flash,$af);
            }elseif ($af['tip']==2) {
                array_push($flashbg,$af);
            }
        }

        $homeInfo=M('home')->where(array('token'=>$token))->find();
        $this->assign('homeInfo',$homeInfo);

        $this->assign('flash',$flash);
        $this->assign('info',$info);
        $this->assign('flashbg',$flashbg);
        $this->assign('tpl',$this->tpl);
        $this->display('Index:'.$tplinfo['tpltypename']);
    }

    public function brands(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
            //echo '此功能只能在微信浏览器中使用';exit;
        }
        $token = $this->_get('token');
        $t_brand = M('Car');
        $brand = $t_brand->where(array('token'=>$token))->order('sort desc')->select();
        $this->assign('brand',$brand);
        $this->display();
    }

    public function carseries(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
           // echo '此功能只能在微信浏览器中使用';exit;
        }
        $bid = (int)$this->_get('bid');
        $token = $this->_get('token');
        $this->assign('title',$this->_get('title'));
        $t_series = M('Carseries');
        $where = array('brand_id'=>$bid,'token'=>$token);
        $series = $t_series->where($where)->order('sort desc')->select();

        $logo = M('Car')->where(array('id'=>$bid))->getField('logo');
        $this->assign('logo',$logo);
        $this->assign('series',$series);
        $this->display();
    }

    public function smodelList(){
        $sid = (int)$this->_get('sid');
        $token = $this->_get('token');
        $where2 = array('s_id'=>$sid,'token'=>$token);
        $t_model = M('Carmodel');
        $model = $t_model->where($where2)->order('id DESC')->select();
        $this->assign('model',$model);
        $this->display();
    }

    public function salers(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
            //echo '此功能只能在微信浏览器中使用';exit;
        }
        $token = $this->_get('token');
        $t_carsaler = M('Carsaler');
        $saler = $t_carsaler->where(array('token'=>$token))->order('sort DESC')->select();
        $this->assign('saler',$saler);
        $this->display();
    }

    //预约保养 预约试驾
    public function CarReserveBook(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
            //echo '此功能只能在微信浏览器中使用';exit;
        }

        $addtype = $this->_get('addtype');
        $token   = $this->_get('token');
        $wecha_id = $this->_get('wecha_id');
        $this->assign('addtype',$addtype);
        if($addtype == 'drive'){//预约试驾
            $where = array('token'=>$token,'addtype'=>'drive');
            $this->assign('addtype','drive');
        }elseif($addtype == 'maintain'){//预约保养
            $where  =  array('token'=>$token,'addtype'=>'maintain');
            $this->assign('addtype','maintain');
        }else{//默认
            //$this->error('Sorry.请求错误！正在带您转到首页',U(MODULE_NAME.'/index',array('token'=>$token,'wecha_id'=>$wecha_id)));
            //exit;
             $where = array('token'=>$token,'addtype'=>'drive');
            $this->assign('addtype','drive');
        }
        $t_res = M('Reservation');
        $reser =  $t_res->where($where)->find();
        if($reser == null){
            $reser =  $t_res->where(array('token'=>$token,'addtype'=>'maintain'))->find();
            if($reser == null){
                exit('请在管理中心的汽车模块的->预约管理先添预约项.');
            }
        }
        $where3 = array('token'=>$token,'wecha_id'=>$wecha_id);
        $user = M('Caruser')->where($where3)->field('car_userName as truename,brand_serise,car_no as carnum,user_tel,car_care_mileage as km,brand_serise as housetype,carmodel as choose')->find();

        if(!empty($user)){
             $reser = array_merge($reser,$user);
        }
        $this->assign('reser',$reser);
        $where4 = array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>$addtype);
        $count = M('Reservebook')->where($where4)->count();
        $this->assign('count',$count);
        $this->display();
    }

     public function add(){

        $da['token']      = strval($this->_get('token'));
        $da['wecha_id']   = strval($this->_post('wecha_id'));
        $da['rid']        = (int)$this->_post('rid');
        $da['truename']   = strval($this->_post("truename"));
        $da['dateline']   = strval($this->_post("dateline"));
        $da['timepart']   = strval($this->_post("timepart"));
        $da['info']       = strval($this->_post("info"));
        $da['tel']        = strval($this->_post("tel"));
        $da['type']       = strval($this->_post('type'));
        $da['housetype']  = $this->_post('housetype');
        $da['choose']  = $this->_post('choose');
        $da['booktime']   = time();
        $das['id']        = (int)$this->_post('id');

        if($da['type'] =='maintain'){
            $da['carnum']   = strval($this->_post("carnum"));
            $da['km']       = (int)$this->_post('km');
        }

        $book   =   M('Reservebook');
         $token = strval($this->_get('token'));
         $wecha_id = strval($this->_get('wecha_id'));
         $addtype  = strval($this->_get('addtype'));
         $url ='http://'.$_SERVER['HTTP_HOST'];
         $url .= U('Car/ReserveBooking',array('token'=>$token,'wecha_id'=>$wecha_id,'addtype'=>$addtype));

        if($das['id'] != ''){
            $o = $book->where(array('id'=>$das['id']))->save($da);
            if($o){
                 $arr=array('errno'=>0,'msg'=>'修改成功','token'=>$token,'wecha_id'=>$wecha_id,'url'=>$url);
                echo json_encode($arr);
                exit;
            }else{
                 $arr=array('errno'=>1,'msg'=>'修改失败','token'=>$token,'wecha_id'=>$wecha_id,'url'=>$url);
                echo json_encode($arr);
                exit;
            }
        }
        $ok = $book->data($da)->add();
        if(!empty($ok)){
            $arr=array('errno'=>0,'msg'=>'恭喜预约成功','token'=>$token,'wecha_id'=>$wecha_id,'url'=>$url);
            echo json_encode($arr);
            exit;
        }else{
             $arr=array('errno'=>1,'msg'=>'预约失败，请重新预约','token'=>$token,'wecha_id'=>$wecha_id,'url'=>$url);
            echo json_encode($arr);
            exit;
        }

    }

    public function ReserveBooking(){

        $token      = $this->_get('token');
        $wecha_id   = $this->_get('wecha_id');
        $type   =   $this->_get('addtype');
        //$this->assign('token',$token);
        //$this->assign('wecha_id',$wecha_id);
        $book   =   M('Reservebook');
        $where = array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>$type);
        $books  = $book->where($where)->order('id desc')->select();
        $this->assign('books',$books);

        $data = M("Reservation");
        $where2 = array('token'=>$token,'addtype'=>$type);
        $reser = $data->where($where2)->field('headpic,addtype')->find();
        //var_dump($headpic);
        $this->assign('reser',$reser);
        $this->display();
    }


    public function func_post(){
            //$das['token']      = strval($this->_get('token'));
            $das['wecha_id']   = strval($this->_post('wecha_id'));
            //$da['rid']        = (int)$this->_post('rid');
            $da['truename']   = strval($this->_post("truename"));
            $da['tel']        = strval($this->_post("tel"));
            $da['dateline']   = strval($this->_post("dateline"));
            $da['timepart']   = strval($this->_post("timepart"));
            $da['info']       = strval($this->_post("info"));
            $da['type']       = strval($this->_post('booktype'));
            $da['housetype']  = $this->_post('housetype');
            $da['choose']     = $this->_post('choose');
            $da['booktime']   = time();
            $das['id']        = (int)$this->_post('mid');
            if($da['type'] =='maintain'){
                $da['carnum']   = strval($this->_post("carnum"));
                $da['km']       = (int)$this->_post('km');
            }
            $t_book   =   M('Reservebook');
            if($das['id'] != ''){
                $o = $t_book->where(array('id'=>$das['id'],'wecha_id'=>$das['wecha_id']))->save($da);
                if($o){
                     $arr=array('errno'=>0,'msg'=>'修改成功','token'=>$this->_get('token'),'wecha_id'=>$das['wecha_id'],'url'=>'');
                     echo json_encode($arr);
                     exit;
                }else{
                     $arr=array('errno'=>1,'msg'=>'修改失败','token'=>$this->_get('token'),'wecha_id'=>$das['wecha_id'],'url'=>'');
                    echo json_encode($arr);
                    exit;
                }
            }
    }

    public function delOrder(){

        $id = (int)$this->_get('id');
        $token = $this->_get('token');
        $wecha_id = $this->_get('wecha_id');
        $t_book   =   M('Reservebook');
        $check = $t_book->where(array('id'=>$id,'wecha_id'=>$wecha_id))->find();
        if($check){
            $t_book->where(array('id'=>$check['id']))->delete();
            $this->success('删除成功',U('Car/ReserveBooking',array('token'=>$token,'wecha_id'=>$wecha_id,'addtype'=>$this->_get('addtype'))));
             exit;
         }else{
            $this->error('非法操作！');
             exit;
         }

    }

    public function tool(){
        $t_uti  = M('Car_utility');
        $utility = $t_uti->order('id desc')->select();
        $this->assign('utility',$utility);
        $this->display();
    }


    public function aboutus(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
            //echo '此功能只能在微信浏览器中使用';exit;
        }
        $company = M('Company');
        $token=$this->_get('token');
        $company_id = M('Carnews')->getField('company_id');
        $about = $company->where(array('token'=>$token,'id'=>$company_id))->find();
        $this->assign('about',$about);

        $this->display();
    }

    //车主关怀
    public function owner(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
            //echo '此功能只能在微信浏览器中使用';exit;
        }
        $token = $this->_get('token');
        $owner = M('Carowner')->where(array('token'=>$token))->find();
        // /var_dump($owner);
        $this->assign('owner',$owner);
        $t_caruser = M('caruser');
        $wecha_id = $this->_get('wecha_id');
        $where = array('token'=>$token,'wecha_id'=>$wecha_id);
        $user = $t_caruser->where($where)->field('care_next_mileage,next_care_inspection,next_insurance_Date')->find();
        $this->assign('user',$user);

         $this->display();
    }

    //修改车信息
    public function changeCarinfo(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
            //echo '此功能只能在微信浏览器中使用';exit;
        }
        $t_caruser = M('caruser');
        $token = $this->_get('token');
        $wecha_id = $this->_get('wecha_id');
        $where = array('token'=>$token,'wecha_id'=>$wecha_id);
        $user = $t_caruser->where($where)->find();

       if (IS_POST) {
           $data['wecha_id'] = $this->_post('wecha_id');
           $data['token'] = $this->_post('token');
           $data['brand_serise'] = $this->_post('brand_serise'); //如果为空
           //if(empty($data['brand_serise'])){

                if($this->_post('carmodel') == ''){
                    $this->error('车型必须填写');exit;
                 }else{
                     $data['carmodel'] =  $this->_post('carmodel');
                }
           //}
            $data['car_buyTime'] = $this->_post('car_buyTime');
            $data['car_no'] =  strval($this->_post('car_no'));
            $data['car_userName'] = strval($this->_post('car_userName'));
            $data['car_startTime'] = $this->_post('car_startTime');
           // $data['car_insurance_lastDate'] = $this->_post('car_insurance_lastDate');
           // $data['car_insurance_lastCost'] = $this->_post('car_insurance_lastCost');
           // $data['car_care_mileage'] = $this->_post('car_care_mileage');
           // $data['car_care_lastCost'] = $this->_post('car_care_lastCost');
           // $data['car_care_lastDate'] = $this->_post('car_care_lastDate');
           $data['user_tel']     = $this->_post('user_tel');
           $id = (int)$this->_post('id');
           if(!empty($id)){
               $check =  $t_caruser->where(array('id'=>$id))->find();
               if($check != null){
                    $where2 = array('id'=>$id);
                    $t_caruser->where($where2)->save($data);
                    $this->success('修改成功',U('Car/owner',array('token'=>$token,'wecha_id'=>$wecha_id)));
                    exit;
               }
           }else{
                $ok = $t_caruser->data($data)->add();
                if($ok){
                     $this->success('保存成功',U('Car/owner',array('token'=>$token,'wecha_id'=>$wecha_id)));exit;
               }else{
                    $this->error('噢，保存出错了。');exit;

                }
           }
        }
        $this->assign('user',$user);
        $this->display();
    }

     public function get_car_brand(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
            //echo '此功能只能在微信浏览器中使用';exit;
        }
        //品牌 和 车系
         $token = $this->_get('token');
         $where=array('token'=>$token);
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

    public function showcar(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
            //echo '此功能只能在微信浏览器中使用';exit;
        }
        $this->token=$this->_get('token');
        $reply_info_db=M('Reply_info');
        $config=$reply_info_db->where(array('token'=>$this->token,'infotype'=>'album'))->find();
        if ($config){
            $headpic=$config['picurl'];
        }else {
            $headpic='/tpl/Wap/default/common/css/Photo/banner.jpg';
        }
        $this->assign('headpic',$headpic);

        $Photo = M("Photo");
        $photo_list = M('Photo_list');
        $t_carnews = M('Carnews');
        $carnews = $t_carnews->where(array('token'=>$this->_get('token')))->find();
        $photo = $Photo->where(array('token'=>$this->_get('token'),'id'=>$carnews['album_id']))->find();
        $photolist  =$photo_list->where(array('token'=>$this->_get('token'),'pid'=>$photo['id']))->select();
        $this->assign('photo',$photolist);
        $this->assign('info',$photo);
        $this->display('Photo:plist');


    }

    //最新车讯
    public function news(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
            //echo '此功能只能在微信浏览器中使用';exit;
        }
        $t_carnews = M('Carnews');
        $carnews = $t_carnews->where(array('token'=>$this->_get('token')))->find();
        $type  = strip_tags(trim($this->_get('type')));
        $token = $this->_get('token');
        $this->assign('type',$type);
        if($type == 'pre'){//最新优惠
            $where = array('token'=>$token,'classid'=>$carnews['pre_id']);
            $wh    = array('token'=>$token,'id'=>$carnews['pre_id']);
        }elseif($type == 'oldcar'){ //尊享二手车
            $where = array('token'=>$token,'classid'=>$carnews['usedcar_id']);
            $wh    = array('token'=>$token,'id'=>$carnews['usedcar_id']);
        }else{
            $where = array('token'=>$token,'classid'=>$carnews['news_id']);
            $wh    = array('token'=>$token,'id'=>$carnews['news_id']);
        }

        $classify = M('Classify')->where($wh)->find();
        $t_img = M('Img')->where($where)->order('id desc,createtime desc')->select();
        $this->assign('news',$t_img);
        $this->assign('classify',$classify);
        $this->display();
    }

    public function newlist(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
            //echo '此功能只能在微信浏览器中使用';exit;
        }
        $token = $this->_get('token');
        $mid = (int)$this->_get('mid');
        $t_img = M('Img');
        $where = array('id'=>$mid,'token'=>$token);
        $imgtxt = $t_img->where($where)->find();
        $this->assign('imgtxt',$imgtxt);
        $this->display();
    }

}


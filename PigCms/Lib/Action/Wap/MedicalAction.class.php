<?php
/*
3G 医疗
*/
class MedicalAction extends WapAction{
    public $token;
    public $wecha_id;
    private $tpl;
    private $info;
    public $weixinUser;
    public $homeInfo;
    public function _initialize() {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
            //echo '此功能只能在微信浏览器中使用';exit;
        }
        parent::_initialize();
        $data     =  M('company');
        $token    = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $where    = array('token'=>$token,'shortname'=>'Medical');
        $cominfo  = $data->where($where)->find();
        $this->token = $this->_get('token');
        $this->wecha_id =$this->_get('wecha_id');
        $this->assign('token',$token);
        $this->assign('wecha_id',$this->wecha_id);
        $this->assign('title',$cominfo['name']);
        $this->assign('cominfo',$cominfo);
        $tpl=$this->wxuser;
        $this->tpl=$tpl;
    }


    public function index(){
        $token    = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $wecha_id    = filter_var($this->_get('wecha_id'),FILTER_SANITIZE_STRING);
        $data=M('Medical_set');
        $where = array('token'=>$this->_get('token'));
        $setIndex = $data->where($where)->find();
        $bxslider = M('Photo_list')->where(array('pid'=>$setIndex['album_id'],'token'=>$this->_get('token')))->order('sort DESC')->select();
        $this->assign('bxslider',$bxslider);
        $this->assign('setIndex',$setIndex);

        include('./PigCms/Lib/ORG/index.Tpl.php');
        foreach($tpl as $k=>$v){
              if($v['tpltypeid'] == $setIndex['tpid']){
                   $tplinfo = $v;
              }
        }

        if(empty($tplinfo['tpltypename'])){
            $where2 =  array('token'=>$this->_get('token'),'shortname'=>'Medical');
            $this->assign('tel',M('Company')->where($where2)->getField('tel'));
            $cid = M("Medical_set")->where($where)->field('evants_id,symptoms_id')->find();
            if($cid != null){
                $t_classify = M('Classify');
                $where = array('token'=>$this->token,'id'=>$cid['evants_id']);
                $classify = $t_classify->where($where)->find();
                $where3 = array('token'=>$this->token,'id'=>$cid['symptoms_id']);
                $classify2 = $t_classify->where($where3)->find();
            }
            $t_img = M('Img');
            $where = array('classid'=>$classify['id'],'token'=>$this->_get('token'));
            $imgtxt = $t_img->where($where)->field('id,title,pic,createtime')->select();

            $where4 = array('classid'=>$classify2['id'],'token'=>$this->_get('token'));
            $imgtxt2 = $t_img->where($where4)->field('id,title,pic,createtime')->select();

            $this->assign('imgtxt',$imgtxt);
            $this->assign('classify',$classify);
            $this->assign('imgtxt2',$imgtxt2);
            $this->assign('classify2',$classify2);
            $this->display();
        }else{

          $flash   = array();
          $flashbg = array();

          foreach ($bxslider as $af){
              if ($af['url']==''|| empty($af['url'])){
                  $af['url']='javascript:void(0)';
              }
              if($af['picurl'] <> ''){
                  $af['img'] = $af['picurl'];
              }
              array_push($flash,$af);
              array_push($flashbg,$af);
              unset($af);

          }


$info = array();

$info[0]['url']  = "/index.php?g=Wap&m=Medical&a=Introduction&token=$token&wecha_id=$wecha_id";
$info[0]['img']  = $setIndex['picurl1'];
$info[0]['name'] = $setIndex['menu1'];

$info[1]['url']  = "/index.php?g=Wap&m=Medical&a=publicListTmp&token=$token&wecha_id=$wecha_id&type=hotfocus";
$info[1]['img']  = $setIndex['picurl2'];
$info[1]['name'] = $setIndex['menu2'];

$info[2]['url']  = "/index.php?g=Wap&m=Medical&a=publicListTmp&token=$token&wecha_id=$wecha_id&type=experts";
$info[2]['img']  = $setIndex['picurl3'];
$info[2]['name'] = $setIndex['menu3'];

$info[3]['url']  = "/index.php?g=Wap&m=Medical&a=publicListTmp&token=$token&wecha_id=$wecha_id&type=equipment";
$info[3]['img']  = $setIndex['picurl4'];
$info[3]['name'] = $setIndex['menu4'];

$info[4]['url']  = "/index.php?g=Wap&m=Medical&a=publicListTmp&token=$token&wecha_id=$wecha_id&type=rcase";
$info[4]['img']  = $setIndex['picurl5'];
$info[4]['name'] = $setIndex['menu5'];

$info[5]['url']  = "/index.php?g=Wap&m=Medical&a=publicListTmp&token=$token&wecha_id=$wecha_id&type=technology";
$info[5]['img']  = $setIndex['picurl6'];
$info[5]['name'] = $setIndex['menu6'];

$info[6]['url']  = "/index.php?g=Wap&m=Medical&a=publicListTmp&token=$token&wecha_id=$wecha_id&type=drug";
$info[6]['img']  = $setIndex['picurl7'];
$info[6]['name'] = $setIndex['menu7'];

$info[7]['url']  = "/index.php?g=Wap&m=Medical&a=registered&token=$token&wecha_id=$wecha_id";
$info[7]['img']  = $setIndex['picurl8'];
$info[7]['name'] = $setIndex['menu8'];

$info[8]['url']  = "/index.php?g=Wap&m=Medical&a=publicListTmp&token=$token&wecha_id=$wecha_id&type=symptoms";
$info[8]['img']  = $setIndex['picurl9'];
$info[8]['name'] = $setIndex['menu9'];

$info[9]['url']  = "/index.php?g=Wap&m=Medical&a=publicListTmp&token=$token&wecha_id=$wecha_id&type=official";
$info[9]['img']  = $setIndex['picurl10'];
$info[9]['name'] = $setIndex['menu10'];


          $homeInfo=M('home')->where(array('token'=>$token))->find();
          $this->assign('homeInfo',$homeInfo);
          $this->assign('flash',$flash);
          $this->assign('info',$info);
          $this->assign('flashbg',$flashbg);
          $this->assign('tpl',$this->tpl);
          $this->display('Index:'.$tplinfo['tpltypename']);
      }
    }

    public function Introduction(){
        $company = M('Company');
        $token=$this->_get('token');
        $about = $company->where(array('token'=>$token,'shortname'=>'Medical'))->find();
        //var_dump($about);
        $this->assign('about',$about);
        $this->display();
    }

    public function Messages(){
        $this->display();
    }

    public function publicListTmp(){
        $data = M("Medical_set");
        $this->token=$this->_get('token');
        $where = array('token'=>$this->token);
        $type = strval(trim($this->_get('type')));
        if($type == 'hotfocus'){
            $cid = $data->where($where)->getField('hotfocus_id');
        }elseif($type == 'experts'){
            $cid = $data->where($where)->getField('experts_id');
        }elseif($type == 'equipment'){
            $cid = $data->where($where)->getField('ceem_id');
        }elseif($type == 'rcase'){
            $cid = $data->where($where)->getField('Rcase_id');
        }elseif($type == 'technology'){
            $cid = $data->where($where)->getField('technology_id');
        }elseif($type == 'drug'){
            $cid = $data->where($where)->getField('drug_id');
        }elseif($type == 'symptoms'){
            $cid = $data->where($where)->getField('symptoms_id');
        }elseif($type == 'official'){
            $cid = $data->where($where)->getField('evants_id');
        }
        if($cid != null){
            $t_classify = M('Classify');
            $where = array('token'=>$this->token,'id'=>$cid);
            $classify = $t_classify->where($where)->find();
        }
        $t_img = M('Img');
        $where = array('classid'=>$classify['id'],'token'=>$this->_get('token'));
        $imgtxt = $t_img->where($where)->field('id,title,pic,createtime')->select();

        $this->assign('imgtxt',$imgtxt);
        $this->assign('classify',$classify);
        $this->display();
    }

     public function  newread(){
        $token = $this->_get('token');
        $id = (int)$this->_get('id');
        $t_img = M('Img');
        $where = array('id'=>$id,'token'=>$token);
        $imgtxt = $t_img->where($where)->find();
        $this->assign('imgtxt',$imgtxt);
        $this->display();
    }

    public function registered(){
        $data=M('Reservation');
        $where = array('token'=>$this->_get('token'),'addtype'=>'medical');
        $reser = $data->where($where)->find();

        $mdt_user = M('Medical_user');
        $where2  =  array('token'=>$this->_get('token'),'wecha_id'=>$this->_get('wecha_id'));
        $user = $mdt_user->where($where2)->find();

        //$where3  =  array('token'=>$this->_get('token'),'wecha_id'=>$this->_get('wecha_id'));
        $count = M('Medical_user')->where($where2)->count();
        $this->assign('count',$count);
        if((int)$reser['price'] > 0){
            $this->assign('gopay',1);
       }else{
            $this->assign('gopay',0);
       }
       if(!empty($user)){
            $reser = array_merge($reser,$user);
            $this->assign('reser',$reser);
       }else{
         $this->assign('reser',$reser);

       }


        $svalue1 = explode('|', trim($reser['svalue1'],'|'));
        $svalue2 = explode('|', trim($reser['svalue2'],'|'));
        $svalue3 = explode('|', trim($reser['svalue3'],'|'));
        $svalue4 = explode('|', trim($reser['svalue4'],'|'));
        $svalue5 = explode('|', trim($reser['svalue5'],'|'));
        $this->assign('svalue1',$svalue1);
        $this->assign('svalue2',$svalue2);
        $this->assign('svalue3',$svalue3);
        $this->assign('svalue4',$svalue4);
        $this->assign('svalue5',$svalue5);
        $this->display();
    }

    public function add(){
       if(IS_POST){
        $da['token']      = strval($this->_get('token'));
        $da['wecha_id']   = strval($this->_post('wecha_id'));
        $da['rid']        = intval($this->_post('rid'));
        $da['truename']   = strval($this->_post("truename"));
        $da['dateline']   = strval($this->_post("dateline"));
        $da['uinfo']      = strval($this->_post("uinfo"));
        $da['utel']       = strval($this->_post("utel"));
        $da['type']       = strval($this->_post('type'));
        $da['sex']       = intval($this->_post('sex'));
        $da['age']       = intval($this->_post('age'));
        $da['txt33']     = strval($this->_post('txt33'));
        $da['txt44']     = strval($this->_post('txt44'));
        $da['txt44']     = strval($this->_post('txt44'));
        $da['yyks']      = strval($this->_post('yyks'));
        $da['yyzj']      = strval($this->_post('yyzj'));
        $da['yybz']      = strval($this->_post('yybz'));
        $da['yy4']       = strval($this->_post('yy4'));
        $da['yy5']       = strval($this->_post('yy5'));

        $da['txt3name']     = strval($this->_post('txt3name'));
        $da['txt4name']     = strval($this->_post('txt4name'));
        $da['txt5name']     = strval($this->_post('txt5name'));
        $da['select4name']  = strval($this->_post('select4name'));
        $da['select5name']  = strval($this->_post('select5name'));

        $da['booktime']   = time();

         $book   =   M('Medical_user');
         $token = strval($this->_get('token'));
         $wecha_id = strval($this->_get('wecha_id'));
         $this->assign('token',$checkOrder['token']);
         $this->assign('wecha_id',$checkOrder['wecha_id']);

        $t_reservation = M('Reservation');
        $resdata       = $t_reservation->where(array('id'=>$da['rid'],'token'=>$token))->find();

        $da['price']        = $resdata['price'];
        $da['orderid']      = self::generateOrderSn();
        $da['orderName']    = $da['yyks'].':'.$da['yyzj'].':'.$da['yybz'].':';

        $insertdata = $book->data($da)->add();

        if((int)$resdata['price'] === 0){
            if(!empty($insertdata)){

Sms::sendSms($da['token'],
   "您好,您的会员 {$da['truename']},已经预约了 {$da['orderName']} ,订单号为 {$da['orderid']} 下单时间: " . date('Y-m-d H:i:s',time())
);
Sms::sendSms($da['token'],
"亲爱的 {$da['truename']},您预约的 {$da['orderName']} 已经成功,订单号为 {$da['orderid']}! 下单时间:". date('Y-m-d H:i:s',time()),$da['utel']
);
                $savedata['paid'] = 1;
                $book->where(array('id'=>$insertdata ,'token'=>$token))->save($savedata);
                //$arr=array('errno'=>0,'msg'=>'恭喜预约成功','token'=>$token,'wecha_id'=>$wecha_id,'url'=>$url);
                //echo json_encode($arr);
                $this->redirect('Medical/ReserveBooking', array('token'=>$token,
                    'wecha_id'=>$wecha_id), 1, '恭喜预约成功,请不要重复刷新页面...');
                exit;
            }else{

                $this->redirect('Medical/registered', array('token'=>$token,
                    'wecha_id'=>$wecha_id), 3, '您好,预约失败，请重新预约,请不要重复刷新页面,请耐心等待...');
                exit;
            }
        }else{
            header("Content-type: text/html; charset=utf-8");
            $this->redirect('Alipay/pay/', array('from' => 'Medical','orderName'=>'预约挂号费用',
                    'price'=>$resdata['price'],'token'=>$da['token'],'orderid'=>$da['orderid'],
                    'wecha_id'=>$da['wecha_id'],'type'=>$da['type'],'rid'=>$da['rid'],
                    'id'=> $insertdata), 3, '您好,准备跳转到支付页面,请不要重复刷新页面,请耐心等待...');
        }
     }

    }

    protected function payReturn(){

        $tb_resbook = D('Medical_user');
        $orderid    =   filter_var($this->_get('orderid'),FILTER_SANITIZE_STRING);
        $token      =   filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $checkOrder = $tb_resbook->where(array('orderid'=>$orderid,'token'=>$token))->find();
       //根据订单号查出$order
       if($checkOrder){//如果订单存在
            if($checkOrder['paid'] === 1){ //支付成功,发信息,跳转到订单别表页面
                $this->assign('token',$checkOrder['token']);
                $this->assign('wecha_id',$checkOrder['wecha_id']);

   Sms::sendSms($checkOrder['token'], "您的会员 {$checkOrder['truename']},已经预约了{$checkOrder['orderName']} 并付款成功,金额为{$checkOrder['price']},订单号为{$checkOrder['orderid']}. ". date('Y-m-d H:i:s',time()));
   Sms::sendSms($checkOrder['token'], " {$checkOrder['truename']},您购买的{$checkOrder['orderName']} 已经付款成功,金额为{$checkOrder['price']},订单号为{$checkOrder['orderid']}! ". date('Y-m-d H:i:s',time()),$checkOrder['utel']);

                $this->redirect('Medical/ReserveBooking', array('token'=>$token,
                    'wecha_id'=>$wecha_id), 1, '恭喜预约成功,请不要重复刷新页面...');
                exit;

            }else{
                    $this->redirect('Medical/registered', array('token'=>$token,
                    'wecha_id'=>$checkOrder['wecha_id']), 3, '您好,支付失败,请重新下单,请不要重复刷新页面,请耐心等待...');
            }

       }else{
          exit('订单不存在!');
        }

    }


  private function  generateOrderSn(){
        date_default_timezone_set('PRC');
        list($msec, $sec) = explode(' ',microtime());
        return date('ymdHis',$sec).substr($msec,2,6);
    }


    public function ReserveBooking(){
        $token      = $this->_get('token');
        $wecha_id   = $this->_get('wecha_id');
        $this->assign('token',$token);
        $this->assign('wecha_id',$wecha_id);
        $where = array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>'medical');
        $book   =   M('Medical_user');
        $count      = $book->where($where)->count();
        $Page       = new Page($count,3);
        $show       = $Page->show();
        $books      = $book->where($where)->order('iid desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        $this->assign('books',$books);
        $this->display();
    }

    function del(){
        $iid = (int)$this->_get('iid');
        if(!is_int($iid)){
            exit('请求类型错误.');
        }
        $token = $this->_get('token');
        $wecha_id = $this->_get('wecha_id');
        $t_book   =   M('Medical_user');
        $check = $t_book->where(array('iid'=>$iid,'wecha_id'=>$wecha_id))->find();
        if($check){
            $t_book->where(array('iid'=>$check['iid']))->delete();
            $this->success('删除成功',U('Medical/ReserveBooking',array('token'=>$token,'wecha_id'=>$wecha_id,'addtype'=>$this->_get('addtype'))));
             exit;
         }else{
            $this->error('非法操作！');
             exit;
         }
    }


}


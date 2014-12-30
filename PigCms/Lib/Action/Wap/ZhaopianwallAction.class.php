<?php

/**
 * 微信客户端访问照片墙的控制器类
 */
class ZhaopianwallAction extends BaseAction
{
    public $pagesize;    //每页获取数量
    public $wecha_id;     //实际意义是openid，如果直接菜单访问该链接需要进行 认证 授权的方式
    public $token;        //每个公众账号配置生成的值 （这个是指页面传递的 appid ）
    public $pageindex;    //获取的页索引
    public function _initialize() {
    	parent::_initialize();
        defined('RES') or define('RES', THEME_PATH);
       // $this->wecha_id = $this->_get('wecha_id');
        //$this->assign('wecha_id', $this->wecha_id);
        
        $this->token = $this->_get('appid');
        
    }
    
    /**
     * 显示照片墙
     *    直接跳转到对应的html页面，
     *    由页面异步完成照片墙数据的加载
     */
    public function index()
    {
    	$token = $this->_get('token');
    	
        $pic_info =M('pic_wall')->field('sttxt')->where(array('token'=>$token))->order('id desc')->find();
        
        $this->assign('sttxt',$pic_info['sttxt']);
        $this->display();
    }
    
    /**
     * 获取照片墙数据
     * 使用json的格式
     */
    public function getData(){
    	
    	$pageindex = $this->_get('pageindex');
    	$token = $this->_get('token');
    	$pagesize = $this->_get('pagesize');
      
    	$begin_row = ($pageindex - 1)*$pagesize;
    	$where = array(
                        'token'=>    $token,
                        'state'=> array('gt',0)             
                      );
        $pic_log_db =  M('pic_walllog');     
        $pic_log_list = $pic_log_db->where($where)->order(" create_time desc ")->limit($begin_row.','.$pagesize)->select();
        $pic_total = $pic_log_db->where($where)->count();
     
        $jsonstr = 'picResult(';
        $jsonstr .= '          {"msg":"ok",';
        $jsonstr .= '           "picture":[';
        foreach ( $pic_log_list as $k => $row ) {
           
           if($k !== 0){
           	$jsonstr .= ',';
           }
           $jsonstr .= '                    {';
           $jsonstr .= '                      "createts":'.$row['create_time'].',';
	       $jsonstr .= '                      "height":0,';
	       $jsonstr .= '                      "id":'.$row['id'].',';
	       $jsonstr .= '                      "nickname":"'.$row['username'].'",';
	       $jsonstr .= '                      "thumbnailurl":"",';
	       $jsonstr .= '                      "url":"'.$row['picurl'].'",';
	       $jsonstr .= '                      "width":0';
           $jsonstr .= '                    }';
        }
        $jsonstr .= '                    ],';
        $jsonstr .= '           "ret":0,';
        $jsonstr .= '           "total":'.$pic_total.'})';
        //picResult({"msg":"ok","picture":[{"createts":1377921402,"height":0,"id":2301,"nickname":"无名","thumbnailurl":"","url":"http://mmsns.qpic.cn/mmsns/2Z31ntbL6Wllke2rurqctP98mVibLJI61y57DpkhF7P6J70tkXA76ww/0","width":0},{"createts":1377920927,"height":0,"id":2300,"nickname":"","thumbnailurl":"","url":"http://mmsns.qpic.cn/mmsns/EEfvcfrYicW31aSMIe98KEYBN0uNeP2lxUud1fbbCib7vNUec5q5ZnSA/0","width":0},{"createts":1377920891,"height":0,"id":2299,"nickname":"","thumbnailurl":"","url":"http://mmsns.qpic.cn/mmsns/EEfvcfrYicW31aSMIe98KEYBN0uNeP2lxmM7U94VHtk820YMgqQiaUpw/0","width":0},{"createts":1377920701,"height":0,"id":2298,"nickname":"","thumbnailurl":"","url":"http://mmsns.qpic.cn/mmsns/EEfvcfrYicW31aSMIe98KEYBN0uNeP2lxa9Bbsx4LfDMRKh6Hc5aoJA/0","width":0},{"createts":1377920665,"height":0,"id":2297,"nickname":"","thumbnailurl":"","url":"http://mmsns.qpic.cn/mmsns/EEfvcfrYicW31aSMIe98KEYBN0uNeP2lx0aHKG61UfQFibiaBFX9uZkDA/0","width":0},{"createts":1377920644,"height":0,"id":2296,"nickname":"","thumbnailurl":"","url":"http://mmsns.qpic.cn/mmsns/EEfvcfrYicW31aSMIe98KEYBN0uNeP2lxyVmoGia4iago6iaoQJXtvj7Vg/0","width":0},{"createts":1377786569,"height":0,"id":2253,"nickname":"ZHP","thumbnailurl":"","url":"http://mmsns.qpic.cn/mmsns/WULxRSXtAY3NsOBQeTEiajPQSCt7BcTQ9yryNLKL2bhzy5U3uBUhZyQ/0","width":0},{"createts":1377758117,"height":0,"id":2251,"nickname":"刘燕群  ","thumbnailurl":"","url":"http://mmsns.qpic.cn/mmsns/paA2fsGnYVUR1IyibsucGJEm9HdWuM3tvTUH0H4Kyr1iaWpfz4aNJSFw/0","width":0}],"ret":0,"total":1084}
        
       // $result = 'picResult({"msg":"ok","picture":[{"createts":1377921402,"height":0,"id":2301,"nickname":"无名","thumbnailurl":"","url":"http://mmsns.qpic.cn/mmsns/2Z31ntbL6Wllke2rurqctP98mVibLJI61y57DpkhF7P6J70tkXA76ww/0","width":0},{"createts":1377920927,"height":0,"id":2300,"nickname":"","thumbnailurl":"","url":"http://mmsns.qpic.cn/mmsns/EEfvcfrYicW31aSMIe98KEYBN0uNeP2lxUud1fbbCib7vNUec5q5ZnSA/0","width":0},{"createts":1377920891,"height":0,"id":2299,"nickname":"","thumbnailurl":"","url":"http://mmsns.qpic.cn/mmsns/EEfvcfrYicW31aSMIe98KEYBN0uNeP2lxmM7U94VHtk820YMgqQiaUpw/0","width":0},{"createts":1377920701,"height":0,"id":2298,"nickname":"","thumbnailurl":"","url":"http://mmsns.qpic.cn/mmsns/EEfvcfrYicW31aSMIe98KEYBN0uNeP2lxa9Bbsx4LfDMRKh6Hc5aoJA/0","width":0},{"createts":1377920665,"height":0,"id":2297,"nickname":"","thumbnailurl":"","url":"http://mmsns.qpic.cn/mmsns/EEfvcfrYicW31aSMIe98KEYBN0uNeP2lx0aHKG61UfQFibiaBFX9uZkDA/0","width":0},{"createts":1377920644,"height":0,"id":2296,"nickname":"","thumbnailurl":"","url":"http://mmsns.qpic.cn/mmsns/EEfvcfrYicW31aSMIe98KEYBN0uNeP2lxyVmoGia4iago6iaoQJXtvj7Vg/0","width":0},{"createts":1377786569,"height":0,"id":2253,"nickname":"ZHP","thumbnailurl":"","url":"http://mmsns.qpic.cn/mmsns/WULxRSXtAY3NsOBQeTEiajPQSCt7BcTQ9yryNLKL2bhzy5U3uBUhZyQ/0","width":0},{"createts":1377758117,"height":0,"id":2251,"nickname":"刘燕群  ","thumbnailurl":"","url":"http://mmsns.qpic.cn/mmsns/paA2fsGnYVUR1IyibsucGJEm9HdWuM3tvTUH0H4Kyr1iaWpfz4aNJSFw/0","width":0}],"ret":0,"total":1084})';
          
        die($jsonstr);
    }
    
    
    
    
    /**
     * 编辑照片墙照片墙
     */
    public function edit()
    {
       // die();
        /*$member_card_create_db = M('Member_card_create');
        $cardsCount            = $member_card_create_db->where(array(
            'token' => $this->token,
            'wecha_id' => $this->wecha_id
        ))->count();
        $this->assign('cardsCount', $cardsCount);
        $this->apikey = C('baidu_map_api');
        $this->assign('apikey', $this->apikey);
        $company_model = M('Company');
        $where         = array(
            'token' => $this->token
        );
        if (isset($_GET['companyid'])) {
            $where['id'] = intval($_GET['companyid']);
        }
        $thisCompany = $company_model->where($where)->find();
        $this->assign('thisCompany', $thisCompany);
        $infoType = 'companyDetail';
        $this->assign('infoType', $infoType);*/
    
        $this->display("edit");
    }
    
     /**
     * 添加照片墙
     */
    public function add()
    {
       // die();
        /*$member_card_create_db = M('Member_card_create');
        $cardsCount            = $member_card_create_db->where(array(
            'token' => $this->token,
            'wecha_id' => $this->wecha_id
        ))->count();
        $this->assign('cardsCount', $cardsCount);
        $this->apikey = C('baidu_map_api');
        $this->assign('apikey', $this->apikey);
        $company_model = M('Company');
        $where         = array(
            'token' => $this->token
        );
        if (isset($_GET['companyid'])) {
            $where['id'] = intval($_GET['companyid']);
        }
        $thisCompany = $company_model->where($where)->find();
        $this->assign('thisCompany', $thisCompany);
        $infoType = 'companyDetail';
        $this->assign('infoType', $infoType);*/
    
        $this->display("index");
    }
    
}
?>
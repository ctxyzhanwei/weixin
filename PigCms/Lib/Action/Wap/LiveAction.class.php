<?php
class LiveAction extends WapAction{
    public $live_info;
    public function _initialize() {
        parent::_initialize();
        //if(!strpos($agent,"icroMessenger")) {
          //  exit('此功能只能在微信浏览器中使用');
        //}
        $id     = $this->_get('id','intval');
        $where  = array('token'=>$this->token,'is_open'=>'1','id'=>$id);
        $this->live_info  = M('Live')->where($where)->find();   
        if(empty($this->live_info)){
            $this->error('参数错误或者活动未开启');
        }

        $this->assign('live_info',$this->live_info);
    }

    public function index(){
        $where      = array('token'=>$this->token,'live_id'=>$this->live_info['id'],'is_show'=>'1');
        $content    = M('Live_content')->where($where)->order('sort desc,add_time desc')->select();
        $company    = M('Live_company')->where($where)->order('sort desc,id desc')->select();

        foreach($company as $key=>$value){
            $cwhere         = array('token'=>$this->token,'display'=>1,'id'=>$value['company_id']);
            $company_info   = M('Company')->where($cwhere)->find(); 
            
            $company[$key]['weixin']        = $this->wxuser['weixin'];
            $company[$key]['mp']            = $company_info['mp'];
            $company[$key]['tel']           = $company_info['tel'];
            $company[$key]['address']       = $company_info['address'];
            $company[$key]['latitude']      = $company_info['latitude'];
            $company[$key]['longitude']     = $company_info['longitude'];
        }

        $this->assign('content',$content);
        $this->assign('company',$company);
        $this->display();
    }

    public function get_list(){


        echo '{result:1,msg:"请求成功!!"}';
    }

    public function test(){

        $this->display();
    }

}

?>
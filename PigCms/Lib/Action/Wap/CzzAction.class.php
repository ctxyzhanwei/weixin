<?php
class CzzAction extends BaseAction{
    public $token;
    public function index(){
        $token      = $this->_get('token'); 
		$gamereply_info_db=M('czzreply_info');
		$info=$gamereply_info_db->where(array('token'=>$token))->find();
		$info[url]=str_replace('{siteUrl}','',$info[url]);
		$this->assign('info',$info);
      //  $this->assign('isAndroid',isAndroid());
        $this->display();
    }
}
?>
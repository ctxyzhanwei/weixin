<?php
class SystemAction extends BackAction{
	
	public function index(){
		$where['display']=1;
		$where['status']=1;
		$order['sort']='asc';
		$nav=M('node')->where($where)->order($order)->select();
		$this->assign('nav',$nav);
		$this->display();
	}
	
	public function menu(){
		if(empty($_GET['pid'])){
			$where['display']=2;
			$where['status']=1;
			$where['pid']=2;
			$where['level']=2;
			$order['sort']='asc';
			$nav=M('node')->where($where)->order($order)->select();
			$this->assign('nav',$nav);
		}
		$this->display();
	}
	
	public function main(){
	    //授权相关
		
		$version = './Conf/version.php';
        $ver = include($version);
        $ver = $ver['ver'];
		$hosturl= urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
		$updatehost = 'http://updata.pigcms.net.cn/update.php';
        $updatehosturl = $updatehost.'?a=client_check_time&v='.$ver.'&u='.$hosturl;
        $domain_time = file_get_contents($updatehosturl);

		if($domain_time=='0'){
           $domain_time='[授权版本：客服QQ:714696206]';
		}else{
		   $domain_time='授权版本：(高级版终生使用权)--免费更新服务截止：('.date("Y-m-d",$domain_time).')';
		}

		$this->assign('ver',$ver);
		$this->assign('domain_time',$domain_time);
	
		$this->display();
	}
}
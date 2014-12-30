<?php
class PrintAction extends BaseAction{
	public $token;
	public $wecha_id;
	public $product_model;
	public $product_cat_model;
	public $isDining;
	public $session_cart_name;
	public function __construct(){
		header("Content-type: text/html; charset=gbk");
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if(!strpos($agent,"MicroMessenger")) {
		//	echo '此功能只能在微信浏览器中使用';exit;
		}
		
		$this->token		= $this->_get('token');
		$this->session_cart_name='session_cart_products_'.$this->token;
		$this->assign('token',$this->token);
		$this->wecha_id	= $this->_get('wecha_id');
		if (!$this->wecha_id){
			//$this->wecha_id='';
		}
		$this->assign('wecha_id',$this->wecha_id);
		$this->product_model=M('Product');
		$this->product_cat_model=M('Product_cat');
		//define('RES',THEME_PATH.'common');
		//define('STATICS',TMPL_PATH.'static');
		$this->assign('staticFilePath',str_replace('./','/',THEME_PATH.'common/css/product'));
		//购物车
		/*
		$calCartInfo=$this->calCartInfo();
		$this->assign('totalProductCount',$calCartInfo[0]);
		$this->assign('totalProductFee',$calCartInfo[1]);
		*/
		//是否是餐饮
		if (isset($_GET['dining'])&&intval($_GET['dining'])){
			$this->isDining=1;
			$this->assign('isDining',1);
		}
	}
	
	public function a(){
		$where['token']=$this->token;
		$where['diningtype']=array('gt',0);
		

		$where['printed']=0;
		$this->product_cart_model=M('product_cart');
		$count      = $this->product_cart_model->where($where)->count();
		$orders=$this->product_cart_model->where($where)->order('time ASC')->limit(0,1)->select();
		
		$now=time();
		if ($orders){
			$thisOrder=$orders[0];
			switch ($thisOrder['diningtype']){
				case 1:
					$orderType='点餐';
					break;
				case 2:
					$orderType='外卖';
					break;
				case 3:
					$orderType='预定餐桌';
					break;
			}
			
			//订餐信息
			$product_diningtable_model=M('product_diningtable');
			if ($thisOrder['tableid']) {
				$thisTable=$product_diningtable_model->where(array('id'=>$thisOrder['tableid']))->find();
				$thisOrder['tableName']=$thisTable['name'];
			}else{
				$thisOrder['tableName']='未指定';
			}
			$str="订单类型：".$orderType."\r\n订单编号：".$thisOrder['id']."\r\n姓名：".$thisOrder['truename']."\r\n电话：".$thisOrder['tel']."\r\n地址：".$thisOrder['address']."\r\n桌台：".$thisOrder['tableName']."\r\n下单时间：".date('Y-m-d H:i:s',$thisOrder['time'])."\r\n打印时间：".date('Y-m-d H:i:s',$now)."\r\n--------------------------------\r\n";
			//
			$carts=unserialize($thisOrder['info']);

			//
			$totalFee=0;
			$totalCount=0;
			$products=array();
			$ids=array();
			foreach ($carts as $k=>$c){
				if (is_array($c)){
					$productid=$k;
					$price=$c['price'];
					$count=$c['count'];
					//
					if (!in_array($productid,$ids)){
						array_push($ids,$productid);
					}
					$totalFee+=$price*$count;
					$totalCount+=$count;
				}
			}
			if (count($ids)){
				$products=$this->product_model->where(array('id'=>array('in',$ids)))->select();
			}
			if ($products){
				$i=0;
				foreach ($products as $p){
					$products[$i]['count']=$carts[$p['id']]['count'];
					$str.=$p['name']."  ".$products[$i]['count']."份  单价：".$p['price']."元\r\n";
					$i++;
				}
			}
			$str.="\r\n--------------------------------\r\n合计：".$thisOrder['price']."元\r\n     谢谢惠顾，欢迎下次光临\r\n";
			//店铺信息
			$member_card_info_model=M('Member_card_info');
			$thisCompany=M('Company')->where(array('token'=>$this->token,'isbranch'=>0))->find();
			$str.="     ".$thisCompany['name']."\r\n
			";
			//
			$str=iconv('utf-8','gbk',$str);
			//设置为打印过了
			$this->product_cart_model->where(array('id'=>$thisOrder['id']))->save(array('printed'=>1));
			echo "CMD=01	FLAG=0	MESSAGE=success	DATETIME=".date('YmdHis',$now)."	ORDERCOUNT=".$count."	ORDERID=".$thisOrder['id']."	PRINT=".$str;
		}else {
			echo "CMD=01	FLAG=1	MESSAGE=no order now	DATETIME=".date('YmdHis',time())."\r\n
	";
		}
	}
}

?>
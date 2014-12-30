<?php
class GrouponAction extends ProductAction{
	public $token;
	public $wecha_id;
	public $product_model;
	public $product_cat_model;
	public $isDining;
	public $tplid;
	public $pageSize;
	public function _initialize(){
		parent::_initialize();
		$this->pageSize=8;
		$grouponConfig=S('grouponConfig'.$this->token);
		$grouponDetailConfig=unserialize($grouponConfig['config']);
		$this->tplid=intval($grouponDetailConfig['tplid']);
		$this->assign('pageSize',$this->pageSize);
		$this->assign('wecha_id',$this->_get('wecha_id'));
	}
	
	public function grouponIndex(){
		$where=array('token'=>$this->token,'groupon'=>1);
		$where['endtime']=array('gt',time());
		if (isset($_GET['catid'])){
			$catid=intval($_GET['catid']);
			$where['catid']=$catid;
			
			$thisCat=$this->product_cat_model->where(array('id'=>$catid))->find();
			$this->assign('thisCat',$thisCat);
		}
		if (IS_POST){
			$key = $this->_post('search_name');
            $this->redirect('?g=Wap&m=Groupon&a=grouponIndex&token='.$this->token.'&keyword='.$key);
		}
		if (isset($_GET['keyword'])){
            $where['name|intro|keyword'] = array('like',"%".$_GET['keyword']."%");
            $this->assign('isSearch',1);
		}
		$count = $this->product_model->where($where)->count();
		$this->assign('count',$count); 
		//排序方式
		$method=isset($_GET['method'])&&($_GET['method']=='DESC'||$_GET['method']=='ASC')?$_GET['method']:'DESC';
		$orders=array('time','discount','price','salecount');
		$order=isset($_GET['order'])&&in_array($_GET['order'],$orders)?$_GET['order']:'time';
		$this->assign('order',$order);
		$this->assign('method',$method);
		//
        	
		$products = $this->product_model->where($where)->order($order.' '.$method)->limit($this->pageSize)->select();
		$now=time();
		$i=0;
		if ($products){
			foreach ($products as $p){
				$products[$i]['new']=0;
				if ($now-$p['time']<3*24*3600){
					$products[$i]['new']=1;
				}
				$i++;
			}
		}
		$this->assign('products',$products);
		$this->assign('metaTitle','团购');
		$this->display('grouponIndex_'.$this->tplid);
	}
	public function ajaxGrouponProducts(){
		$where=array('token'=>$this->token,'groupon'=>1);
		$page=isset($_GET['page'])&&intval($_GET['page'])>1?intval($_GET['page']):2;
		$pageSize=isset($_GET['pagesize'])&&intval($_GET['pagesize'])>1?intval($_GET['pagesize']):$this->pageSize;
		$start=($page-1)*$pageSize;
		//排序方式
		$method=isset($_GET['method'])&&($_GET['method']=='DESC'||$_GET['method']=='ASC')?$_GET['method']:'DESC';
		$method=$method=='ASC'?'DESC':'ASC';
		$orders=array('time','discount','price','salecount');
		$order=isset($_GET['order'])&&in_array($_GET['order'],$orders)?$_GET['order']:'time';
		//
		$products = $this->product_model->where($where)->order($order.' '.$method)->limit($start.','.$pageSize)->select();
		/*
		$now=time();
		$i=0;
		if ($products){
			foreach ($products as $p){
				$products[$i]['new']=0;
				if ($now-$p['time']<3*24*3600){
					$products[$i]['new']=1;
				}
				$i++;
			}
		}
		*/
		$str='{"products":[';
		if ($products){
			$comma='';
			foreach ($products as $p){
				$membercount=intval($p['salecount'])+intval($p['fakemembercount']);
				$str.=$comma.'{"id":"'.$p['id'].'","catid":"'.$p['catid'].'","storeid":"'.$p['storeid'].'","name":"'.$p['name'].'","price":"'.$p['price'].'","token":"'.$p['token'].'","keyword":"'.$p['keyword'].'","salecount":"'.$p['salecount'].'","logourl":"'.$p['logourl'].'","time":"'.$p['time'].'","oprice":"'.$p['oprice'].'","fakemembercount":"'.$p['fakemembercount'].'","membercount":"'.$membercount.'","enddate":"'.date('Y-m-d',$p['endtime']).'"}';
				$comma=',';
			}
		}
		$str.=']}';
		$this->show($str);
	}
	public function product(){
		$where=array('token'=>$this->token);
		if (isset($_GET['id'])){
			$id=intval($_GET['id']);
			$where['id']=$id;
		}
		$product=$this->product_model->where($where)->find();
		$this->assign('product',$product);
		if ($product['endtime']){
			$leftSeconds=intval($product['endtime']-time());
			$this->assign('leftSeconds',$leftSeconds);
		}
		$this->assign('metaTitle',$product['name']);
		$product['intro']=str_replace(array('&lt;','&gt;','&quot;','&amp;nbsp;'),array('<','>','"',' '),$product['intro']);
		$intro=$this->remove_html_tag($product['intro']);
		$intro=mb_substr($intro,0,30,'utf-8');
		$this->assign('intro',$intro);
		//店铺信息
		$company_model=M('Company');
		$where=array('token'=>$this->token);
		$thisCompany=$company_model->where($where)->find();
		$this->assign('thisCompany',$thisCompany);
		//分店信息
		$branchStoreCount=$company_model->where(array('token'=>$this->token,'isbranch'=>1))->count();
		$this->assign('branchStoreCount',$branchStoreCount);
		//销量最高的商品
		$sameCompanyProductWhere=array('token'=>$this->token,'id'=>array('neq',$product['id']));
		if ($product['dining']){
			$sameCompanyProductWhere['dining']=1;
		}
		if ($product['groupon']){
			$sameCompanyProductWhere['groupon']=1;
		}
		if (!$product['groupon']&&!$product['dining']){
			$sameCompanyProductWhere['groupon']=array('neq',1);
			$sameCompanyProductWhere['dining']=array('neq',1);
		}
		$products=$this->product_model->where($sameCompanyProductWhere)->limit('salecount DESC')->limit('0,5')->select();
		$this->assign('products',$products);
		$this->display('product_'.$this->tplid);
	}
	public function detail(){
		$where=array('token'=>$this->token);
		if (isset($_GET['id'])){
			$id=intval($_GET['id']);
			$where['id']=$id;
		}
		$product=$this->product_model->where($where)->find();
		$product['intro']=html_entity_decode($product['intro']);
		$this->assign('product',$product);
		
		$this->assign('metaTitle',$product['name']);
		$this->display('detail_'.$this->tplid);
	}
	public function my(){
		$this->noaccess();
		$product_cart_model=M('product_cart');
		//$this->wecha_id
		$orders=$product_cart_model->where(array('token'=>$this->token,'groupon'=>1,'wecha_id'=>$this->wecha_id))->order('time DESC')->select();
		
		$unpaidCount=0;
		$unusedCount=0;
		if ($orders){
			foreach ($orders as $o){
				$products=unserialize($o['info']);
				//$firstProductID=$products
				if (!$o['paid']){
					$unpaidCount++;
				}
				if (!$o['handled']){
					$unusedCount++;
				}
			}
		}
		$this->assign('orders',$orders);
		$this->assign('unpaidCount',$unpaidCount);
		$this->assign('unusedCount',$unusedCount);
		$this->assign('ordersCount',count($orders));
		$this->assign('metaTitle','我的订单');
		//
		//是否要支付
		$alipay_config_db=M('Alipay_config');
		$alipayConfig=$alipay_config_db->where(array('token'=>$this->token))->find();
		$this->assign('alipayConfig',$alipayConfig);
		//
		$this->assign('hideTopButton',1);
		$this->display('my_'.$this->tplid);
	}
	public function myOrders(){
		$this->noaccess();
		//是否要支付
		$alipay_config_db=M('Alipay_config');
		$alipayConfig=$alipay_config_db->where(array('token'=>$this->token))->find();
		$this->assign('alipayConfig',$alipayConfig);
		//
		$where=array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'groupon'=>1);
		if (isset($_GET['used'])){
			if (intval($_GET['used'])==1){
				$title='已使用团购';
			}else {
				$title='未使用团购';
			}
			$where['handled']=intval($_GET['used']);
		}elseif (isset($_GET['paid'])){
			$title='待付款订单';
			$where['paid']=intval($_GET['paid']);
		}else{
			$title='全部订单';
		}
		$this->assign('title',$title);
		$product_cart_model=M('product_cart');
		//$this->wecha_id
		$orders=$product_cart_model->where($where)->order('time DESC')->select();
		//

		$productsIDs=array();
		if ($orders){
			foreach ($orders as $k=>$o){
				array_push($productsIDs,$o['productid']);
				$orders[$k]['name'] = M('Product')->where(array('token'=>$this->token,'id'=>$o['productid']))->getField('name');
			}
		}
		
		if ($productsIDs){
		$products=M('Product')->where(array('id'=>array('in',$productsIDs)))->select();
		}
		//
		$productsByID=array();
		if ($products){
			foreach ($products as $p){
				$productsByID[$p['id']]=$p;
			}
		}
		if ($orders){
			$i=0;
			foreach ($orders as $o){
				$orders[$i]['logourl']=$productsByID[$o['productid']]['logourl'];
				$orders[$i]['productName']=$productsByID[$o['productid']]['name'];
				//$orders[$i]['productPrice']=$productsByID[$o['productid']]['price'];
				if (!$o['paid']&&$alipayConfig&&!$o['handled']){
					$orders[$i]['needPay']=1;
				}else {
					$orders[$i]['needPay']=0;
				}
				$i++;
			}
		}
		//
		$this->assign('orders',$orders);
		$this->assign('unpaidCount',$unpaidCount);
		$this->assign('unusedCount',$unusedCount);
		$this->assign('ordersCount',count($orders));
		$this->assign('metaTitle','我的订单');
		//
		
		//
		$this->assign('hideTopButton',1);
		$this->display('myOrders_'.$this->tplid);
	}
	public function search(){
		$this->display('search_'.$this->tplid);
	}
	public function orderCart(){
		$this->noaccess();
		$userinfo_model=M('Userinfo');
		$thisUser=$userinfo_model->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->find();
		$this->assign('thisUser',$thisUser);
		//是否要支付
		$alipay_config_db=M('Alipay_config');
		$alipayConfig=$alipay_config_db->where(array('token'=>$this->token))->find();
		$this->assign('alipayConfig',$alipayConfig);
		//
		if (IS_POST){
			
			$row=array();
			$orderid=$this->randStr(4).time();
			$row['orderid']=$orderid;
			//
			$row['truename']=$this->_post('truename');
			$row['tel']=$this->_post('tel');
			$row['address']=$this->_post('address');


			$row['token']=$this->token;
			$row['wecha_id']=$this->wecha_id;
			if (!$row['wecha_id']){
				$row['wecha_id']='null';
			}
			$time=time();
			$row['time']=$time;
			//分别加入3类订单
			$product_cart_model=M('product_cart');
			$row['total']=intval($_POST['quantity']);
			$row['price']=$row['total']*floatval($_POST['price']);
			$row['diningtype']=0;
			$row['productid']=intval($_POST['productid']);
			$row['code']=substr($row['wecha_id'],0,6).$time;
			$row['tableid']=0;
			$row['info']=serialize(array(intval($_POST['productid'])=>array('count'=>$row['total'],'price'=>intval($_POST['price']))));
			$row['groupon']=1;
			$row['dining']=0;
			$product_cart_model->add($row);
			//。。。。。。。。。。。。。。。。。。。。。。。。。。。。。。。。。。。

			$product_model=M('product');
			$product_cart_list_model=M('product_cart_list');
			$product_model->where(array('id'=>intval($_POST['productid'])))->setInc('salecount',$_POST['quantity']);
			$productName = $product_model->where(array('id'=>intval($_POST['productid'])))->getField('name');
			//保存个人信息
			if ($_POST['saveinfo']){
				$userRow=array('tel'=>$row['tel'],'truename'=>$row['truename'],'address'=>$row['address']);
				if ($thisUser){
					$userinfo_model->where(array('id'=>$thisUser['id']))->save($userRow);
				}else {
					$userRow['token']=$this->token;
					$userRow['wecha_id']=$this->wecha_id;
					$userRow['wechaname']='';
					$userRow['qq']=0;
					$userRow['sex']=-1;
					$userRow['age']=0;
					$userRow['birthday']='';
					$userRow['info']='';
					//
					$userRow['total_score']=0;
					$userRow['sign_score']=0;
					$userRow['expend_score']=0;
					$userRow['continuous']=0;
					$userRow['add_expend']=0;
					$userRow['add_expend_time']=0;
					$userRow['live_time']=0;
					$userinfo_model->add($userRow);
				}
			}
			
			$orderName = '团购-'.$productName;
			if($this->_post('paytype') == 1){
				$this->redirect(U('CardPay/pay',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'price'=>$row['price'],'from'=>'Groupon','orderName'=>$orderName,'single_orderid'=>$orderid)));
				Sms::sendSms($this->token,'您在微信上有新的团购订单');
				exit();
			}
			
			if ($alipayConfig['open']){
				//$this->printOrder($orderid);
				$this->success('提交成功，转向支付页面',U('Alipay/pay',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'success'=>1,'price'=>$row['price'],'from'=>'Groupon','orderName'=>$orderName,'orderid'=>$orderid)));
			}else {
				Sms::sendSms($this->token,'您在微信上有新的团购订单');
				//$this->printOrder($orderid);
				$this->redirect(U('Groupon/my',array('token'=>$_GET['token'],'wecha_id'=>$_GET['wecha_id'],'success'=>1)));
			}
				
			

		}else {
			$where=array('token'=>$this->token);
			if (isset($_GET['id'])){
				$id=intval($_GET['id']);
				$where['id']=$id;
			}

			if($this->wxuser['winxintype'] ==3 && $this->wxuser['oauth'] == 1){
				$addr = new WechatAddr($this->wxuser['appid'],$this->wxuser['appsecret']);
				$this->assign('addrSign', $addr->addrSign());
			}
			
			$product=$this->product_model->where($where)->find();
			$this->assign('product',$product);
			$this->display('orderCart_'.$this->tplid);
		}
	}
	public function printOrder($orderid){
		$thisOrder=M('product_cart')->where(array('orderid'=>$orderid))->find();
		$msg='';
		$msg=$msg.
		chr(10).'姓名：'.$thisOrder['truename'].
		chr(10).'电话：'.$thisOrder['tel'].
		chr(10).'地址：'.$thisOrder['address'].
		chr(10).'下单时间：'.date('Y-m-d H:i:s',$thisOrder['time']).
		chr(10).'配送时间:'.$thisOrder['buytime'].
		chr(10).'*******************************'.
		chr(10).$product_list.
		chr(10).'*******************************'.
		chr(10).'品种数量：'.$thisOrder['total'].
		chr(10).'合计：'.$thisOrder['price'].'元'.
		chr(10).'※※※※※※※※※※※※※※'.
		chr(10).'谢谢惠顾，欢迎下次光临'.
		chr(10).'订单编号：'.$thisOrder['orderid'];
		$op=new orderPrint();
		$op->printit($this->token,0,'Store',$msg);
	}
	public function deleteOrder(){
		$this->noaccess();
		$product_model=M('product');
		$product_cart_model=M('product_cart');
		$product_cart_list_model=M('product_cart_list');
		$thisOrder=$product_cart_model->where(array('id'=>intval($_GET['id'])))->find();
		//检查权限
		$id=$thisOrder['id'];
		if ($thisOrder['wecha_id']!=$this->wecha_id||$thisOrder['handled']==1){
			exit();
		}
		//
		//删除订单和订单列表
		$product_cart_model->where(array('id'=>$id))->delete();
		$product_cart_list_model->where(array('cartid'=>$id))->delete();
		//商品销量做相应的减少
		$product_model->where(array('id'=>$k))->setDec('salecount',$thisOrder['total']);
		$this->redirect($_SERVER['HTTP_REFERER']);
	}
	public function orderDetail(){
		//是否要支付
		$alipay_config_db=M('Alipay_config');
		$alipayConfig=$alipay_config_db->where(array('token'=>$this->token))->find();
		$this->assign('alipayConfig',$alipayConfig);
		//
		$product_cart_model=M('product_cart');
		$thisOrder=$product_cart_model->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'id'=>intval($_GET['id'])))->find();

		//
		$product_model=M('product');
		$thisProduct=$product_model->where(array('id'=>$thisOrder['productid']))->find();
		$this->assign('thisProduct',$thisProduct);
		//
		if (!$thisOrder['paid']&&$alipayConfig&&!$thisOrder['handled']){
			$thisOrder['needPay']=1;
		}else {
			$thisOrder['needPay']=0;
		}
		$this->assign('thisOrder',$thisOrder);
		$this->assign('hideTopButton',1);
		//
		$this->display('orderDetail_'.$this->tplid);
	}
	public function company($display=1){
		//店铺信息
		$company_model=M('Company');
		$where=array('token'=>$this->token);
		if (isset($_GET['companyid'])){
			$where['id']=intval($_GET['companyid']);
		}
		
		$thisCompany=$company_model->where($where)->find();
		$this->assign('thisCompany',$thisCompany);
		//分店信息
		$branchStores=$company_model->where(array('token'=>$this->token,'isbranch'=>1))->order('taxis ASC')->select();
		$branchStoreCount=count($branchStores);
		$this->assign('branchStoreCount',$branchStoreCount);
		$this->assign('branchStores',$branchStores);
		$this->assign('metaTitle','公司信息');
		if($display){
			$this->display('company_'.$this->tplid);
		}
	}
	public function companyMap(){
		$this->apikey=C('baidu_map_api');
		$this->assign('apikey',$this->apikey);
		$this->company(0);
		$this->assign('hideTopButton',1);
		$this->display('companyMap_'.$this->tplid);
	}
	public function handle(){
		$this->noaccess();
		$product_cart_model=M('product_cart');
		if (IS_POST){
			$staff_db=M('Company_staff');
			$thisStaff=$staff_db->where(array('username'=>$this->_post('username'),'token'=>$this->_get('token')))->find();
			if (!$thisStaff){
				echo'{"success":-4,"msg":"用户名和密码不匹配"}';
				exit();
			}else {
				if (md5($this->_post('password'))!=$thisStaff['password']){
					echo'{"success":-4,"msg":"用户名和密码不匹配"}';
					exit();
				}else {
					$now=time();
					$arr['handleduid']=$thisStaff['id'];
					$arr['handledtime']=$time;
					$arr['handled']=1;
					$arr['sent']=1;
					//
					$product_cart_model->where(array('id'=>intval($_POST['id'])))->save($arr);
					echo'{"success":1,"msg":"处理成功"}';
					exit();
				}
			}
			//
		}else{
			//是否要支付
			$alipay_config_db=M('Alipay_config');
			$alipayConfig=$alipay_config_db->where(array('token'=>$this->token))->find();
			$this->assign('alipayConfig',$alipayConfig);
			//
			
			$thisOrder=$product_cart_model->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'id'=>intval($_GET['id'])))->find();

			//
			$product_model=M('product');
			$thisProduct=$product_model->where(array('id'=>$thisOrder['productid']))->find();
			$this->assign('thisProduct',$thisProduct);
			//
			if (!$thisOrder['paid']&&$alipayConfig&&!$thisOrder['handled']){
				$thisOrder['needPay']=1;
			}else {
				$thisOrder['needPay']=0;
			}
			$this->assign('thisOrder',$thisOrder);
			$this->assign('hideTopButton',1);
			$this->display('handle_'.$this->tplid);
		}
	}
	function randStr($randLength){
		$randLength=intval($randLength);
		$chars='abcdefghjkmnpqrstuvwxyz';
		$len=strlen($chars);
		$randStr='';
		for ($i=0;$i<$randLength;$i++){
			$randStr.=$chars[rand(0,$len-1)];
		}
		return $randStr;
	}
	public function payReturn(){
		$this->noaccess();
		$product_cart_model=M('product_cart');
		$out_trade_no=$_GET['orderid'];
		$order=$product_cart_model->where(array('orderid'=>$out_trade_no))->find();
		if (!$this->wecha_id){
			$this->wecha_id=$order['wecha_id'];
		}
		$sepOrder=0;
		if (!$order){
			$order=$product_cart_model->where(array('id'=>$out_trade_no))->find();
			$sepOrder=1;
		}
		if($order){
			if($order['paid']!=1){exit('该订单还未支付');}
			/************************************************/
			Sms::sendSms($this->token,'您的微信里有团购订单已经付款');
			/************************************************/
			if($this->tplid == 0){
				$this->redirect('/index.php?g=Wap&m=Groupon&a=myOrders&token='.$order['token'].'&wecha_id='.$order['wecha_id']);
			}else{
				
				$this->redirect('/index.php?g=Wap&m=Product&a=my&token='.$order['token'].'&wecha_id='.$order['wecha_id']);
			}
			
		}else{
			exit('订单不存在：'.$out_trade_no);
		}
	}
	
	
	public function noaccess(){
		if (!$this->wecha_id){
			$this->error('您无权参与，请关注微信号“'.$this->wxuser['wxname'].'”，然后回复“团购”便可进行',U('Groupon/grouponIndex',array('token'=>$this->token)));
		}
	}
}
	
?>
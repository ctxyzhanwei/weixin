<?php
class DiningAction extends WapAction{
	public $token;
	public $wecha_id;
	public $product_model;
	public $product_cat_model;
	public $isDining;
	public function __construct(){
		
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if(!strpos($agent,"MicroMessenger")) {
		//	echo '此功能只能在微信浏览器中使用';
		//exit;
		}
		
		$this->token= $this->_get('token');
		$this->assign('token',$this->token);
		$this->wecha_id	= $this->_get('wecha_id');
		$this->isDining=1;
		if (!$this->wecha_id){
			//$this->wecha_id='';
		}
		$this->assign('wecha_id',$this->wecha_id);
		$this->product_model=M('Product');
		$this->product_cat_model=M('Product_cat');
		$this->product_cart_model=M('Product_cart');
		$this->product_cart_list_m = M('product_cart_list');
		$this->assign('staticFilePath',str_replace('./','/',THEME_PATH.'Mall'));
		
		
	}
	
	function index(){
		$reply_info_m = M('reply_info');
		$wxuser_m = M('wxuser');
		$where=array('token'=>$this->token,'infotype'=>'Dining');
		$reply_info = $reply_info_m->where($where)->find();
		if($reply_info)
		{
			$reply_info['info'] = str_replace('
','<br/>',$reply_info['info']);
			$this->assign('reply_info',$reply_info);
		}
		$where=array('token'=>$this->token);
		$wxuser = $wxuser_m->where($where)->find();
		
		if($wxuser){
			$printer_m = M('printer');
			$where=array('Uid'=>$wxuser['uid']);
			$printer=$printer_m->where($where)->find();
			if($printer){
				$printer['Describe'] = str_replace('
','<br/>',$printer['Describe']);
				$this->assign('printer',$printer);
			}
		}
		$this->display();
	}
	
	
	function Ajax(){
		$cid = $_GET['cid'];
		$where=array('catid'=>$cid,'token'=>$this->token);
		$product_m = $this->product_model->where($where)->select();
		$carts=$this->_getCart();
		if($product_m){
		
	 	foreach( $product_m as $item){
			$cssstyle = 'foodattention';
			if($carts[$item['id']]['count'] >0)
			{
				$cssstyle= 'foodattention-active';
			}else{
				$carts[$item['id']]['count'] =0;
			}
			
			echo('<div class="fooditem"><div onclick="foodtitleClick('.$item['id'].')" class="foodtitle" id="foodtitle_'.$item['id'].'"><table width="100%"><tbody><tr><td height="44px"><table width="100%"><tbody><tr><td class="foodname">'.$item['name'].'</td></tr></tbody></table></td><td class="foodprice">￥'.$item['price'].'/</td><td class="'.$cssstyle.'" id="foodattention_'.$item['id'].'"></td></tr></tbody></table></div><div style="display: none" class="fooddetail" id="fooddetail_'.$item['id'].'"><table><tbody><tr><td><a data-mini="true" data-inline="true" data-role="button" onclick="showfoodinfo('.$item['id'].')" href="#" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="c" class="ui-btn ui-btn-up-c ui-shadow ui-btn-corner-all ui-mini ui-btn-inline"><span class="ui-btn-inner"><span class="ui-btn-text">详情</span></span></a></td><td class="food-op"><span class="reduce-btn"><div data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="c" data-inline="true" data-disabled="false" class="ui-btn ui-btn-up-c ui-shadow ui-btn-corner-all ui-btn-inline" aria-disabled="false"><span class="ui-btn-inner"><span class="ui-btn-text">-</span></span><button onclick="order_dec_onclick('.$item['id'].', \'/index.php?g=Wap&m=Mall&a=pages&type=dec&token='.$this->token.'&wecha_id='.$this->wecha_id.'&id='.$item['id'].'&price='.$item['price'].'&name='.$item['name'].'\')" data-inline="true" class="ui-btn-hidden" data-disabled="false">-</button></div></span><div class="foodnum" id="order_foodnum_'.$item['id'].'">'.$carts[$item['id']]['count'].'</div><span class="add-btn"><div data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="c" data-inline="true" data-disabled="false" class="ui-btn ui-btn-up-c ui-shadow ui-btn-corner-all ui-btn-inline" aria-disabled="false"><span class="ui-btn-inner"><span class="ui-btn-text">+</span></span><button onclick="order_plus_onclick('.$item['id'].', \'/index.php?g=Wap&m=Mall&a=pages&type=plus&token='.$this->token.'&wecha_id='.$this->wecha_id.'&id='.$item['id'].'&price='.$item['price'].'&name='.$item['name'].'\')" data-inline="true" class="ui-btn-hidden" data-disabled="false">+</button></div></span></td></tr></tbody></table></div></div>');
				}
			}
	}
	
	//购物列表页面初始化
	function lists(){
		$catWhere=array('parentid'=>0,'token'=>$this->token);
		
		if (isset($_GET['parentid'])){
			$parentid=intval($_GET['parentid']);
			
			
			$catWhere['parentid']=$parentid;
			
			$thisCat=$this->product_cat_model->where(array('id'=>$parentid))->find();
			$this->assign('thisCat',$thisCat);
			$this->assign('parentid',$parentid);
		}
		
		if ($this->isDining){
			$catWhere['dining']=1;
		}else {
			$catWhere['dining']=0;
		}
		
		$cats = $this->product_cat_model->where($catWhere)->order('id asc')->select();
		
		$where=array('token'=>$this->token,'infotype'=>'Dining');
		$reply_info_m = M('reply_info');
		$reply_info = $reply_info_m->where($where)->find();
		if($reply_info)
		{
			$this->assign('title',$reply_info['title']);
		}
		
		$this->assign('cats',$cats);
		$this->assign('cid',intval($_GET['cid']));
		$this->assign('metaTitle','商品分类');
		$this->display();
	}
	
		//购物车页面初始化
	function order(){
		//是否外卖预定等
		$diningConfig =M('Reply_info')->where(array('infotype'=>'Dining','token'=>$this->token))->find();
		$this->assign('diningConfig',$diningConfig);
		//可以预定多少天内的
		$diningConfigDetail=unserialize($diningConfig['config']);
		if (!$diningConfigDetail||!$diningConfigDetail['yudingdays']){
			$days=7;
		}else {
			$days=$diningConfigDetail['yudingdays'];
		}
		$time=time();
		$secondsOfDay=24*60*60;
		$dateTimes=array();
		for ($i=0;$i<$days;$i++){
			array_push($dateTimes,$time+$i*$secondsOfDay);
		}
		$this->assign('dateTimes',$dateTimes);
		//
		$orderHour=date('H',$time);
		$this->assign('orderHour',$orderHour);
		$hours=array();
		for ($i=0;$i<24;$i++){
			array_push($hours,$i);
		}
		$this->assign('hours',$hours);
		//
		$product_diningtable_model=M('Product_diningtable');
		$tables=$product_diningtable_model->where(array('token'=>$_GET['token']))->order('taxis ASC')->select();
		$this->assign('tables',$tables);
	
		$carts=$this->_getCart();
		
		foreach ($carts as $k=>$c){
			if($carts[$k]['count']==0){
				unset($carts[$k]);
			}
		}
		$_SESSION['session_cart_mall']=serialize($carts);
		
		$where=array('token'=>$this->token,'infotype'=>'Dining');
		$reply_info_m = M('reply_info');
		$reply_info = $reply_info_m->where($where)->find();
		if($reply_info)
		{
			$this->assign('title',$reply_info['title']);
		}
		$this->assign('cats',$carts);
		
		$userinfo_m = M('userinfo');
		$where=array('token'=>$this->token,'wecha_id'=>$this->wecha_id);
		$userinfo = $userinfo_m->where($where)->find();
		
		if($userinfo){
			$this->assign('userinfo',$userinfo);
		}
		
		$this->display();
	}
	
	//页面初始化总数，价格
	function carInfo(){
		$carts=$this->_getCart();
		$totalPrice ;
		$totalCount ;
		
		foreach ($carts as $k=>$c){
			$totalCount +=$carts[$k]['count'];
			$totalPrice +=$carts[$k]['price']*$carts[$k]['count'];
		}
		echo('<div id="order_totalnum_layout">
<p id="order_totalnum_text">
已选
<span id="order_totalnum">'.$totalCount.'</span>
</p>
</div>
<p id="order_totalprice_text">
￥
<span id="order_totalprice">'.$totalPrice.'</span>
</p>');
	}
	
	function carTotalPrice(){
		$carts=$this->_getCart();
		$totalPrice=0 ;
		
		foreach ($carts as $k=>$c){
			$totalCount +=$carts[$k]['count'];
			$totalPrice +=$carts[$k]['price']*$carts[$k]['count'];
		}
		echo($totalPrice);
	}
	
	//购物车修改
	function pages(){
		$id = $_GET['id'];
		$carts=$this->_getCart();
		if (key_exists($id,$carts)){
			if($_GET['type']=='plus'){
				$carts[$id]['count']++;
			}
			else if($_GET['type']=='dec'){
				if($carts[$id]['count']>0){
					$carts[$id]['count']--;
				}else{
					$carts[$id]['count']=0;
				}
			}
			else{
				
			}
		}else {
			$carts[$id]=array('id'=>$id,'count'=>1,'name'=>$_GET['name'],'price'=>floatval($_GET['price']));
		}
		
		$_SESSION['session_cart_mall']=serialize($carts);
		
		
		$totalPrice ;
		$totalCount ;
		
			foreach ($carts as $k=>$c){
				$totalCount +=$carts[$k]['count'];
				$totalPrice +=$carts[$k]['price']*$carts[$k]['count'];
			}
		
		echo('{"status":"ok","errorcode":"0","message":"","data":{"food_id":"'.$id.'","food_num":"'.$carts[$id]['count'].'","totalNum":"'.$totalCount.'","totalPrice":"'.$totalPrice.'"}}');
	}
	
	//订单保存
	function submitorders(){
		
		//用户信息更新-开始
		$userinfo_m = M('userinfo');
		$where=array('token'=>$_GET['token'],'wecha_id'=>$_GET['wecha_id']);
		
		$info = $userinfo_m->where($where)->find();
		if($info){
			$info['truename'] = $_GET['truename'];
			$info['tel'] = $_GET['tel'];
			$info['address'] = $_GET['address'];
			$userinfo_m->save($info);
		}else{
			$userinfo_m->add($_GET);
		}
		//用户信息更行-结束
		
		//添加购物列表数据 product_cart
	
		
		$carts=$this->_getCart();
		foreach ($carts as $k=>$c){
			$totalCount +=$carts[$k]['count'];
			$totalPrice +=$carts[$k]['price']*$carts[$k]['count'];
		}
		
		$_GET['total'] = $totalCount;
		$_GET['price'] = $totalPrice;
		$_GET['info']= serialize($carts);
		$_GET['time']= time(date('Y-m-d H:i:s'));
		$_GET['year']= date('Y',$_GET['buytime']);
		$_GET['month']= date('m',$_GET['buytime']);
		$_GET['day']= date('d',$_GET['buytime']);
		$_GET['dining']=1;
		$_GET['groupon']=0;
		$_GET['orderid']=$_GET['wecha_id'].time(date('Y-m-d H:i:s'));
	
		$product_catId = $this->product_cart_model->add($_GET);
	
		foreach ($carts as $k=>$c){
			$data['cartid']= $product_catId;
			$data['productid']= $carts[$k]['id'];
			$data['price']= $carts[$k]['price'];
			$data['total']= $carts[$k]['count'];
			$data['wecha_id']= $_GET['wecha_id'];
			$data['token']= $_GET['token'];
			$data['time']= time(date('Y-m-d H:i:s'));
			dump($data);
			$this->product_cart_list_m->add($data);
		}
		$this->printTxt(); // 设置打印
		$_SESSION['session_cart_mall'] = '';
		$this->redirect(U('Mall/myOrders',array('token'=>$_GET['token'],'wecha_id'=>$_GET['wecha_id'])));
	}
	
	//订单详细
	public function myOrderInfo(){
		$thisOrder=$this->product_cart_model->where(array('id'=>intval($_GET['id'])))->find();
		//检查权限
		if ($thisOrder['wecha_id']!=$this->wecha_id){
			exit();
		}
		//
		
		$this->assign('thisOrder',$thisOrder);
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
			$list=$this->product_model->where(array('id'=>array('in',$ids)))->select();
		}
		if ($list){
			$i=0;
			foreach ($list as $p){
				$list[$i]['count']=$carts[$p['id']]['count'];
				$i++;
			}
		}
		$this->assign('products',$list);
		//
		$this->assign('totalFee',$totalFee);
		
		$this->assign('metaTitle','修改订单');
		//
		//是否要支付
		$alipay_config_db=M('Alipay_config');
		$alipayConfig=$alipay_config_db->where(array('token'=>$this->token))->find();
		$this->assign('alipayConfig',$alipayConfig);
		//
		$this->display();
	
		
	}
	
	
	
	//我的订单
	public function myOrders(){
		$product_cart_model=M('product_cart');
		//$this->wecha_id
		$where = array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'groupon'=>0,'dining'=>1);
		$orders=$product_cart_model->where($where)->order('time DESC')->select();
		if ($orders){
			foreach ($orders as $o){
				$products=unserialize($o['info']);
				//$firstProductID=$products
			}
		}
		
		
		$this->assign('orders',$orders);
		$this->assign('ordersCount',count($orders));
		$this->assign('metaTitle','我的订单');
		//
		//是否要支付
		$alipay_config_db=M('Alipay_config');
		$alipayConfig=$alipay_config_db->where(array('token'=>$this->token))->find();
		$this->assign('alipayConfig',$alipayConfig);
		//
		$this->display();
	}
	
	//打印方法
	public function printTxt(){
	
		$where['token']=$this->token;
		
		//打印终端配置
		$wxuserm = M('wxuser');
		$wxusercount = $wxuserm ->where($where)->count();
		if($wxusercount==1){
			$wxusermodel = $wxuserm ->where($where)->find();
		}else{
			return ;
		}
		
		
		
		$where['token']=$this->token;
		//$where['diningtype']=array('gt',0);
		 
		$where['printed']=0;
		$this->product_cart_model=M('product_cart');
		$count      = $this->product_cart_model->where($where)->count();
		$orders=$this->product_cart_model->where($where)->order('time Desc')->limit(0,1)->select();
		
		$printerm = M('printer');
		
		$now=time();
		if ($orders){
			$thisOrder=$orders[0];
			$str= '';
			switch($thisOrder['groupon'].$thisOrder['dining']){
				case '01':
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
				$str="订单类型：".$orderType."\r\n订单编号：".$thisOrder['id']."\r\n姓名：".$thisOrder['truename']."\r\n电话：".$thisOrder['tel']."\r\n地址：".$thisOrder['address']."\r\n桌台：".$thisOrder['tableName']."\r\n预订时间：".$thisOrder['buytime']."\r\n下单时间：".date('Y-m-d H:i:s',$thisOrder['time'])."\r\n打印时间：".date('Y-m-d H:i:s',$now)."\r\n--------------------------------\r\n";
				
				$where='Uid='.$wxusermodel['uid'].' and Type=1';
				break;
			case '00': 
				//购物信息
				$str="订单类型：商城购物\r\n订单编号：".$thisOrder['id']."\r\n姓名：".$thisOrder['truename']."\r\n电话：".$thisOrder['tel']."\r\n地址：".$thisOrder['address']."\r\n下单时间：".date('Y-m-d H:i:s',$thisOrder['time'])."\r\n打印时间：".date('Y-m-d H:i:s',$now)."\r\n--------------------------------\r\n";
				$where='Uid='.$wxusermodel['uid'].' and Type=0';
				break;
			case '11':
				//订餐信息
				
				$str="订单类型：团购\r\n订单编号：".$thisOrder['id']."\r\n姓名：".$thisOrder['truename']."\r\n电话：".$thisOrder['tel']."\r\n地址：".$thisOrder['address']."\r\n下单时间：".date('Y-m-d H:i:s',$thisOrder['time'])."\r\n打印时间：".date('Y-m-d H:i:s',$now)."\r\n--------------------------------\r\n";
				$where='Uid='.$wxusermodel['uid'].' and Type=2';
				break;
			}
			$printercount = $printerm->where($where)->count();
			
			if($printercount==1){
				$printermodel = $printerm->where($where)->find();
				if($printermodel['Status']==-1||$printermodel['Examine']==0){
					return;
				}
			}else{
				return;
			}
			
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
					$str.=$p['name']." X ".$products[$i]['count']."  单价：".$p['price']."元\r\n";
					$i++;
				}
			}
			$str.="\r\n--------------------------------\r\n合计：".$thisOrder['price']."元";
			//店铺信息
			$member_card_info_model=M('Member_card_info');
			$thisCompany=$member_card_info_model->where(array('token'=>$this->token))->find();
			if($thisOrder['note']!=''){
				$str=$str."     "."\r\n--------------------------------\r\n".$thisOrder['note'];
			}
			$str=$str."     "."\r\n--------------------------------\r\n".$printermodel['Describe'];
			$str=$str."     "."\r\n     谢谢惠顾，欢迎下次光临\r\n--------------------------------\r\n     酷客微信营销提供技术支持\r\n     http://www.96760.net\r\n\r\n\r\n\r\n\r\n
			";
			$str="<1B40><1D2111><1B6101>".$printermodel['Title']."<0D0A><1B6100><1D2100><0D0A>".$str;  //初始化打印机加粗居中
			
			
			//$str=iconv('utf-8','gbk',$str);
			//设置打印服务器开始
			$server="http://218.97.194.59:8088/Router/Rest/";  //打印API接口地址
			$appkey= "";  //商户编码
            $appsecret = "";  // 商户密钥
            $type = "addPrintContext"  ;//   打印类型
			$printerid = $printermodel['PrinterId'];  //打印机编号
	        $isrun = "1";   //1为直接打印，非1等待打印
			$printcontext = $str ;    //打印内容
			$printcount= $printermodel['PrinterCount'];
            $contentencode=urlencode("$printcontext");
			//$contentencode=$printcontext;
            $url = "$server/?appkey=$appkey&appsecret=$appsecret&type=$type&printerid=$printerid&$isrun=$isrun&printcount=$printcount&printcontext=$contentencode";
            $content = file_get_contents($url);
			//print_r ('反馈结果'.$content);   服务器返回结果，成功则返回此订单打印序列号，用于判断修改打印状态及处理状态。
			          
			//设置打印服务器结束
			//设置为打印过了
			$this->product_cart_model->where(array('id'=>$thisOrder['id']))->save(array('printed'=>1,'handled'=>1,'pcid'=>$content));
			//echo "CMD=01	FLAG=0	MESSAGE=成功	DATETIME=".date('YmdHis',$now)."	ORDERCOUNT=".$count."	ORDERID=".$thisOrder['id']."	PRINT=".$str;
		//}else {
			//echo "CMD=01	FLAG=1	MESSAGE=no order now	DATETIME=".date('YmdHis',time())."\r\n	";  //打印队列。
		
		}
	}
	
	//读取Session中的购物车
	function _getCart(){
		if (!isset($_SESSION['session_cart_mall'])||!strlen($_SESSION['session_cart_mall'])){
			$carts=array();
		}else {
			$carts=unserialize($_SESSION['session_cart_mall']);
		}
		return $carts;
	}
	//展示商品详细
	function showDetail(){
	 	$id = $_GET['id'];
	 	$info = $this->product_model->where('id='.$id)->find();
		
		echo('
		<h3 id="foodname">'.$info['name'].'</h3>
		<img class="foodimage" src="'.$info['logourl'].'">
		<p><span class="attr-title">价格：</span>'.$info['price'].'</p>
		<p><span class="attr-title">描述：</span>'.$this->replaceHtmlAndJs($info['intro']).'</p>');
	}
	
	
	
	
	//工具类
	//替换HTML标签；
	function replaceHtmlAndJs($document)
	{
		 $document = trim($document);
		 if (strlen($document) <= 0) {
			 return $document;
		 }
    	 $search = array ("'<script[^>]*?>.*?</script>'si",  // 去掉 javascript
                   "'<[\/\!]*?[^<>]*?>'si",          // 去掉 HTML 标记
             //   "'([\r\n])[\s]+'",                // 去掉空白字符
             "'&(quot|#34);'i",                // 替换 HTML 实体
          "'&(amp|#38);'i",
          "'&(lt|#60);'i",
          "'&(gt|#62);'i",
          "'&(nbsp|#160);'i"
          );                    // 作为 PHP 代码运行
     	$replace = array ("",
           "",
          // "\1",
           "\"",
           "&",
           "<",
           ">",
           " "
          );
   		return @preg_replace ($search, $replace, $document);
	}
}
	
?>
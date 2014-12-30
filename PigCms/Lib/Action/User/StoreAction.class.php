<?php
class StoreAction extends UserAction{
	public $token;
	public $product_model;
	public $product_cat_model;
	
	public $_cid = 0;
	
	public function _initialize() 
	{
		parent::_initialize();
		$this->canUseFunction('shop');
		
		$this->_cid = session('companyid');
		if (empty($this->token)) {
			$this->error('不合法的操作', U('Index/index'));
		}
		if (empty($this->_cid))  {
			$company = M('Company')->where(array('token' => $this->token, 'isbranch' => 0))->find();
			if ($company) {
				$this->_cid = $company['id'];
				session('companyid', $this->_cid);
				session('companyk', md5($this->_cid . session('uname')));
				D("Product_cat")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $this->_cid));
				D("Attribute")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $this->_cid));
				D("Product")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $this->_cid));
				D("Product_cart")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $this->_cid));
				D("Product_cart_list")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $this->_cid));
				D("Product_comment")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $this->_cid));
				D("Product_setting")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $this->_cid));
			} else {
				$this->error('您还没有添加您的商家信息',U('Company/index',array('token' => $this->token)));
			}
		} else {
			$k = session('companyk');
			$company = M('Company')->where(array('token' => $this->token, 'id' => $this->_cid))->find();
			if (empty($company)) {
				$this->error('非法操作', U('Store/index',array('token' => $this->token)));
			} else {
				$username = $company['isbranch'] ? $company['username'] : session('uname');
				if (md5($this->_cid . $username) != $k) {
					$this->error('非法操作', U('Store/index',array('token' => $this->token)));
				}
			}
		}
		
		//分支店铺
		$ischild = session('companyLogin');
		$this->assign('ischild', $ischild);
		$this->assign('cid', $this->_cid);
		
		$this->assign('isDining', 0);
		
		//商品分组权限
		$isgroup = 0;
		if (C('zhongshuai')) {
			$company = M('Company')->where("`token`='{$this->token}' AND `isbranch`=0")->find();
			$setting = M("Product_setting")->where(array('token' => $this->token, 'cid' => $company['id']))->find();
			$isgroup = $setting['isgroup'];
		}
		$this->assign('isgroup', $isgroup);
		if ($ischild && $isgroup) {
			if (!in_array(ACTION_NAME, array('orders', 'orderInfo', 'deleteOrder', 'setting', 'comment', 'commentdel', 'flash', 'flashadd', 'flashdel'))) {
				$this->redirect(U('Store/orders',array('token' => $this->token)));
			}
		}
	}
	
	/**
	 * 分类列表
	 */
	public function index() 
	{
		$parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
		$data = M('Product_cat');
		$where = array('token' => session('token'), 'cid' => $this->_cid, 'parentid' => $parentid);
        if (IS_POST) {
            $key = $this->_post('searchkey');
            if(empty($key)){
                $this->error("关键词不能为空");
            }

            $map['token'] = $this->get('token'); 
            $map['name|des'] = array('like',"%$key%"); 
            $list = $data->where($map)->order("sort ASC, id DESC")->select(); 
            $count      = $data->where($map)->count();       
            $Page       = new Page($count,20);
        	$show       = $Page->show();
        } else {
        	$count      = $data->where($where)->count();
        	$Page       = new Page($count,20);
        	$show       = $Page->show();
        	$list = $data->where($where)->order("sort ASC, id DESC")->limit($Page->firstRow.','.$Page->listRows)->select();
        }
		$this->assign('page',$show);		
		$this->assign('list',$list);
		if ($parentid){
			$parentCat = $data->where(array('id'=>$parentid))->find();
		}
		$this->assign('parentCat',$parentCat);
		$this->assign('parentid',$parentid);
		$this->display();		
	}
	
	/**
	 * 创建分类
	 */
	public function catAdd()
	{
		$parentid = isset($_REQUEST['parentid']) ? intval($_REQUEST['parentid']) : 0;
		if ($parentid) {
			$checkdata = M('Product_cat')->where(array('id' => $parentid, 'cid' => $this->_cid))->find();
			$this->assign('parentname', $checkdata['name']);
		}
		if (IS_POST) {
			if($_POST['pc_show']){
				$database_pc_product_category = D('Pc_product_category');
				$data_pc_product_category['cat_name'] = $_POST['name'];
				$data_pc_product_category['token'] = session('token');
				$_POST['pc_cat_id'] = $database_pc_product_category->data($data_pc_product_category)->add();
			}
			
			$_POST['isfinal'] = 0;
			$_POST['time'] = time();
			$_POST['token'] = session('token');
			if (D('Product_cat')->add($_POST)) {
				D('Product_cat')->where(array('id' => $_POST['parentid']))->save(array('isfinal' => 2));
				$this->success('修改成功', U('Store/index', array('token' => session('token'), 'cid' => $this->_cid, 'parentid' => $parentid)));
			}
		} else {
			$parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
			if ($parentid) {
				$checkdata = M('Product_cat')->where(array('id' => $parentid, 'cid' => $this->_cid))->find();
				$this->assign('parentname', $checkdata['name']);
			}
			$this->assign('parentid', $parentid);
			
			
			$queryname=M('token_open')->where(array('token'=>$this->token))->getField('queryname');
			if(strpos(strtolower($queryname),strtolower('website')) !== false){
				$this->assign('has_website',true);
			}
			
			$this->display('catSet');
		}
	}
	
	/**
	 * 分类修改
	 */
	public function catSet()
	{
        $id = $this->_get('id'); 
		$checkdata = M('Product_cat')->where(array('id' => $id, 'cid' => $this->_cid))->find();
		if(empty($checkdata)){
            $this->error("没有相应记录.您现在可以添加.", U('Store/catAdd', array('token' => session('token'), 'cid' => $this->_cid)));
        }
		if (IS_POST) {
			if($_POST['pc_show']){
				$_POST['pc_cat_id'] = 0;
			}
            $data = D('Product_cat');
			if ($data->create()) {
				if ($data->where($where)->save($_POST)) {
					$this->success('修改成功', U('Store/index', array('token' => session('token'), 'cid' => $this->_cid, 'parentid' => $this->_post('parentid'))));
				} else {
					$this->error('操作失败');
				}
			} else {
				$this->error($data->getError());
			}
		} else {
			if ($checkdata['parentid']) {
				$father = M('Product_cat')->where(array('id' => $checkdata['parentid'], 'cid' => $this->_cid))->find();
				$this->assign('parentname', $father['name']);
			}
			$this->assign('parentid', $checkdata['parentid']);
			$this->assign('set', $checkdata);
			$this->display();	
		
		}
	}
	
	/**
	 * 删除分类
	 */
	public function catDel() 
	{
		if ($this->_get('token') != session('token')) {$this->error('非法操作');}
        $id = $this->_get('id');
        if (IS_GET) {                              
            $where = array('id' => $id,'token' => session('token'),'cid' => $this->_cid);
            $data = M('Product_cat');
            $check = $data->where($where)->find();
            if ($check == false) $this->error('非法操作');
            
            $product_model = M('Product');
            $count = $product_model->where(array('catid' => $id))->count();
            if ($count){
            	$this->error('本分类下有商品，请删除商品后再删除分类', U('Store/index', array('token' => session('token'), 'cid' => $this->_cid)));
            	exit();
            }
            
            $catcount = $data->where(array('parentid' => $id))->count();
            if ($catcount){
            	$this->error('本分类下有子分类，请先删除子分类后再删除该分类', U('Store/index', array('token' => session('token'), 'cid' => $this->_cid)));
            	exit();
            }
            
            $back = $data->where($where)->delete();
            if ($back == true) {
				$this->success('操作成功', U('Store/index', array('token' => session('token'),'parentid' => $check['parentid'], 'cid' => $this->_cid)));
            } else {
				$this->error('服务器繁忙,请稍后再试', U('Store/index', array('token' => session('token'), 'cid' => $this->_cid)));
            }
        }        
	}
	
	/**
	 * 分类属性列表
	 */
	public function norms() 
	{
		$type = isset($_GET['type']) ? intval($_GET['type']) : 0;
		$catid = intval($_GET['catid']);
		if($checkdata = M('Product_cat')->where(array('id' => $catid, 'token' => session('token'), 'cid' => $this->_cid))->find()){
			$this->assign('catData', $checkdata);
        } else {
        	$this->error("没有选择相应的分类.", U('Store/index', array('token' => session('token'), 'cid' => $this->_cid)));
        }
        
		$data = M('Norms');
		$where = array('catid' => $catid, 'type' => $type);
		$count      = $data->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list = $data->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page', $show);		
		$this->assign('list', $list);
		$this->assign('catid', $catid);
		$this->assign('type', $type);
		$this->display();		
	}
	
	/**
	 * 分类规格的操作
	 */
	public function normsAdd() 
	{
		$type = intval($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
		if($data = M('Product_cat')->where(array('id' => $this->_get('catid'), 'token' => session('token'), 'cid' => $this->_cid))->find()){
			$this->assign('catData', $data);
        } else {
        	$this->error("没有选择相应的分类.", U('Store/index', array('token' => session('token'), 'cid' => $this->_cid)));
        }
		if (IS_POST) { 
            $data = D('Norms');
            $id = intval($this->_post('id'));
            if ($id) {
	            $where = array('id' => $id, 'type' => $type, 'catid' => $this->_get('catid'));
				$check = $data->where($where)->find();
				if ($check == false) $this->error('非法操作');
            }
			if ($data->create()) {
				if ($id) {
					if ($data->where($where)->save($_POST)) {
						$this->success('修改成功', U('Store/norms',array('token' => session('token'), 'catid' => $this->_post('catid'), 'type' => $type)));
					} else {
						$this->error('操作失败');
					}
				} else {
					if ($data->add($_POST)) {
						$this->success('添加成功', U('Store/norms',array('token' => session('token'), 'catid' => $this->_post('catid'), 'type' => $type)));
					} else {
						$this->error('操作失败');
					}
				}
			} else {
				$this->error($data->getError());
			}
		} else { 
			$data = M('Norms')->where(array('id' => $this->_get('id'), 'type' => $type, 'catid' => $this->_get('catid')))->find();
			//print_r($data);die;
			$this->assign('catid', $this->_get('catid'));
			$this->assign('type', $type);
			$this->assign('token', session('token'));
			$this->assign('set', $data);
			$this->display();	
		}
	}
	
	/**
	 *属性的删除 
	 */
	public function normsDel() 
	{
		if ($this->_get('token') != session('token')) {$this->error('非法操作');}
        $id = intval($this->_get('id'));
        $catid = intval($this->_get('catid'));
        $type = intval($this->_get('type'));
        if (IS_GET) {                              
            $where = array('id' => $id, 'type' => $type, 'catid' => $catid);
            $data = M('Norms');
            $check = $data->where($where)->find();
            if ($check == false) $this->error('非法操作');
            if ($back = $data->where($where)->delete()) {
            	$this->success('操作成功', U('Store/norms', array('type' => $type, 'catid' => $check['catid'])));
            } else {
				$this->error('服务器繁忙,请稍后再试', U('Store/norms', array('type' => $type, 'catid' => $check['catid'])));
            }
        }        
	}
	
	/**
	 * 分类属性列表
	 */
	public function attributes()
	{
		$catid = intval($_GET['catid']);
		if ($checkdata = M('Product_cat')->where(array('id' => $catid, 'token' => session('token'), 'cid' => $this->_cid))->find()) {
			$this->assign('catData', $checkdata);
        } else {
        	$this->error("没有选择相应的分类.", U('Store/index'));
        }
		$data = M('Attribute');
		$where = array('catid' => $catid, 'token' => session('token'), 'cid' => $this->_cid);
		$count      = $data->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list = $data->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page', $show);		
		$this->assign('list', $list);
		$this->assign('catid', $catid);
		$this->display();		
	}
	
	/**
	 * 分类属性的操作
	 */
	public function attributeAdd()
	{
		if ($checkdata = M('Product_cat')->where(array('id' => $this->_get('catid'), 'token' => session('token'), 'cid' => $this->_cid))->find()) {
			$this->assign('catData', $checkdata);
        } else {
        	$this->error("没有选择相应的分类.", U('Store/index'));
        }
		if (IS_POST) { 
            $data = D('Attribute');
            $id = intval($this->_post('id'));
            $catid = intval($this->_post('catid'));
            if ($id) {
	            $where = array('id' => $id, 'token' => session('token'), 'catid' => $catid, 'cid' => $this->_cid);
				$check = $data->where($where)->find();
				if ($check == false) $this->error('非法操作');
            }
			if ($data->create()) {
				if ($id) {
					if ($data->where($where)->save($_POST)) {
						$this->success('修改成功', U('Store/attributes',array('token' => session('token'), 'catid' => $this->_post('catid'))));
					} else {
						$this->error('操作失败');
					}
				} else {
					if ($data->add($_POST)) {
						$this->success('添加成功', U('Store/attributes',array('token' => session('token'), 'catid' => $this->_post('catid'))));
					} else {
						$this->error('操作失败');
					}
				}
			} else {
				$this->error($data->getError());
			}
		} else { 
			$data = M('Attribute')->where(array('id' => $this->_get('id'), 'token' => session('token'), 'cid' => $this->_cid, 'catid' => $this->_get('catid')))->find();
			$this->assign('catid', $this->_get('catid'));
			$this->assign('token', session('token'));
			$this->assign('set', $data);
			$this->display();	
		}
	}
	
	/**
	 *属性的删除 
	 */
	public function attributeDel()
	{
		if($this->_get('token') != session('token')){$this->error('非法操作');}
        $id = intval($this->_get('id'));
        $catid = intval($this->_get('catid'));
        if(IS_GET){                              
            $where = array('id' => $id, 'token' => session('token'), 'catid' => $catid, 'cid' => $this->_cid);
            $data = M('Attribute');
            $check = $data->where($where)->find();
            if($check == false) $this->error('非法操作');
            if ($back = $data->where($where)->delete()) {
            	$this->success('操作成功',U('Store/attributes', array('token' => session('token'), 'catid' => $catid)));
            } else {
				$this->error('服务器繁忙,请稍后再试',U('Store/attributes', array('token' => session('token'), 'catid' => $catid)));
            }
        }        
	}
	
	/**
	 * 商品列表
	 */
	public function product() 
	{		
		$catid = intval($_GET['catid']);
		$product_model = M('Product');
		$product_cat_model = M('Product_cat');
		$where = array('token' => session('token'), 'groupon' => 0, 'dining' => 0, 'cid' => $this->_cid);
		if ($catid){
			$where['catid'] = $catid;
		}
        if(IS_POST){
            $key = $this->_post('searchkey');
            if(empty($key)){
                $this->error("关键词不能为空");
            }

            $map['token'] = $this->get('token'); 
            $map['name|intro|keyword'] = array('like',"%$key%"); 
            $list = $product_model->where($map)->select(); 
            $count      = $product_model->where($map)->count();       
            $Page       = new Page($count,20);
        	$show       = $Page->show();
        } else {
        	$count      = $product_model->where($where)->count();
        	$Page       = new Page($count,20);
        	$show       = $Page->show();
        	$list = $product_model->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        }
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->assign('isProductPage',1);
		$this->assign('catid', $catid);
		$this->display();		
	}
	
	/**
	 * 商品列表
	 */
	public function productgroup() 
	{		
		$gid = isset($_GET['gid']) ? intval($_GET['gid']) : 0;
		$product_model = M('Product');
		$where = array('token' => session('token'), 'groupon' => 0, 'dining' => 0);
		if ($gid){
			$where['gid'] = $gid;
		}
		$count      = $product_model->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list = $product_model->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$group = M("Product_group")->where(array('token' => $this->token))->select();
		$glist = array();
		foreach ($group as $g) {
			$glist[$g['id']] = $g;
		}
		
		$cat = M("Product_cat")->where(array('token' => $this->token))->select();
		$catlist = array();
		foreach ($cat as $c) {
			$catlist[$c['id']] = $c;
		}
		
		foreach ($list as &$row) {
			$row['gname'] = isset($glist[$row['gid']]) ? $glist[$row['gid']]['name'] : '';
			$row['cname'] = isset($catlist[$row['catid']]) ? $catlist[$row['catid']]['name'] : '';
		}
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->assign('isProductPage',1);
		$this->assign('catid', $catid);
		$this->display();		
	}
	
	
	public function changegroup()
	{
		$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
		if (IS_POST) {
			D('Product')->where(array('id' => $id, 'token' => $this->token))->save(array('gid' => $_POST['gid']));
			$this->success("修改成功", U('Store/productgroup', array('token' => session('token'))));
		} else {
			if ($id && ($product = M('Product')->where(array('token' => session('token'), 'id' => $id))->find())) {
				$this->assign("set", $product);
	        
				$groups =  M('Product_group')->where(array('token' => session('token')))->select();
				$this->assign('groups', $groups);
				$this->display();
			} else {
				$this->error("参数错误!");
			}
		}
	}
	
	/**
	 * 添加商品
	 */
	public function addNew() 
	{
		$catid = intval($_GET['catid']);
		$id = intval($_GET['id']);
		if($productCatData = M('Product_cat')->where(array('id' => $catid, 'token' => session('token')))->find()){
			$this->assign('catData', $productCatData);
        } else {
        	$this->error("没有选择相应的分类.", U('Store/index'));
        }
        //产品的规格
        $normsData = M("Norms")->where(array('catid' => $catid))->select();
        $colorData = $formatData = array();
        foreach ($normsData as $row) {
        	if ($row['type']) {
        		$colorData[] = $row;
        	} else {
        		$formatData[] = $row;
        	}
        	$normsList[$row['id']] = $row['value'];
        }
        $attributeData = array();
        if ($id && ($product = M('Product')->where(array('catid' => $catid, 'token' => session('token'), 'id' => $id))->find())) {
        	$attributeData = M("Product_attribute")->where(array('pid' => $id))->select();
        	$productDetailData = M("Product_detail")->where(array('pid' => $id))->select();
        	$productimage = M("Product_image")->where(array('pid' => $id))->select();
        	$colorList = $formatList = $pData = array();
        	foreach ($productDetailData as $p) {
        		$p['formatName'] = $normsList[$p['format']];
        		$p['colorName'] = $normsList[$p['color']];
        		$formatList[] = $p['format'];
        		$colorList[] = $p['color'];
        		$pData[] = $p;
        	}
        	$this->assign('set', $product);
        	$this->assign('formatList', $formatList);
        	$this->assign('colorList', $colorList);
        	$this->assign('imageList', $productimage);
        	//print_r($productimage);die;
        }// else {
	        //分类产品的属性
//	        $data = M("Attribute")->where(array('catid' => $catid))->select();
//	        $temp = array();
//	        foreach ($data as $row) {
//	        	$row['aid'] = $row['id'];
//	        	$row['id'] = 0;
//	        	$temp[$row['id']] = $row;
//	        }
        //}
        $array = array();
        if ($attributeData) {
	        foreach ($attributeData as $row) {
	        	$array[$row['aid']] = $row;
	        }
        }
		$data = M("Attribute")->where(array('catid' => $catid))->select();
        $attributeData = array();
        foreach ($data as $row) {
        	if (isset($array[$row['id']])) {
        		$attributeData[] = $array[$row['id']];
        		unset($array[$row['id']]);
        	} else {
	        	$row['aid'] = $row['id'];
	        	$row['id'] = 0;
	        	$attributeData[] = $row;
        	}
        }
        if ($array) {
        	$ids = array();
        	foreach ($array as $v) {
        		$ids[] = $v['id'];
        	}
        	M('Product_attribute')->where(array('id' => array('in', $ids)))->delete();
        }
        
		$groups =  M('Product_group')->where(array('token' => session('token')))->select();
		$this->assign('groups', $groups);
        
		$this->assign('color', $this->color);
		$this->assign('attributeData', $attributeData);
		$this->assign('normsData', $normsData);
		$this->assign('colorData', $colorData);
		$this->assign('formatData', $formatData);
		$this->assign('productCatData', $productCatData);
		$this->assign('productDetailData', $pData);
		$this->assign('catid', $catid);
		$this->display('set_new');
	}
	
	/**
	 * 增加商品
	 */
	public function productSave() {
		$token = isset($_POST['token']) ? htmlspecialchars($_POST['token']) : '';
		$catid = isset($_POST['catid']) ? intval($_POST['catid']) : 0;
		$num = isset($_POST['num']) ? intval($_POST['num']) : 0;
		$gid = isset($_POST['gid']) ? intval($_POST['gid']) : 0;
		$status = isset($_POST['status']) ? intval($_POST['status']) : 0;
		$fakemembercount = isset($_POST['fakemembercount']) ? intval($_POST['fakemembercount']) : 0;
		$pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;
		$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
		$keyword = isset($_POST['keyword']) ? htmlspecialchars($_POST['keyword']) : '';
		$pic = isset($_POST['pic']) ? htmlspecialchars($_POST['pic']) : '';
		$price = isset($_POST['price']) ? htmlspecialchars($_POST['price']) : '';
		$vprice = isset($_POST['vprice']) ? htmlspecialchars($_POST['vprice']) : '';
		$oprice = isset($_POST['oprice']) ? htmlspecialchars($_POST['oprice']) : '';
		$mailprice = isset($_POST['mailprice']) ? htmlspecialchars($_POST['mailprice']) : '';
		$intro = isset($_POST['intro']) ? $_POST['intro'] : '';
		$attribute = isset($_POST['attribute']) ? htmlspecialchars_decode($_POST['attribute']) : '';
		$norms = isset($_POST['norms']) ? htmlspecialchars_decode($_POST['norms']) : '';
		$images = isset($_POST['images']) ? htmlspecialchars_decode($_POST['images']) : '';
		$sort = isset($_POST['sort']) ? intval($_POST['sort']) : 100;
		if ($token != session('token')) {
			exit(json_encode(array('error_code' => true, 'msg' => '不合法的数据')));
		}
		if (empty($name)) {
			exit(json_encode(array('error_code' => true, 'msg' => '商品不能为空')));
		}
		if (empty($price)) {
			exit(json_encode(array('error_code' => true, 'msg' => '价格不能为空')));
		}
		if (empty($vprice)) {
			exit(json_encode(array('error_code' => true, 'msg' => '会员价不能为空')));
		}
		if (empty($oprice)) {
			exit(json_encode(array('error_code' => true, 'msg' => '原始价格不能为空')));
		}
		if (empty($keyword)) {
			exit(json_encode(array('error_code' => true, 'msg' => '关键词不能为空')));
		}
		if (empty($catid)) {
			exit(json_encode(array('error_code' => true, 'msg' => '商品分类不能为空')));
		}
		if ($objCat = M("Product_cat")->where(array('token' => $this->token, 'cid' => $this->_cid, 'id' => $catid))->find()) {
			if ($objCat['isfinal'] == 2) {
				exit(json_encode(array('error_code' => true, 'msg' => '该分类下有子分类，不可直接添加商品')));
			} else {
				D("Product_cat")->where(array('token' => $this->token, 'cid' => $this->_cid, 'id' => $catid))->save(array('isfinal' => 1));
			}
		} else {
			exit(json_encode(array('error_code' => true, 'msg' => '商品分类不存在')));
		}
		$data = array('token' => $token, 'gid' => $gid, 'status' => $status, 'cid' => $this->_cid, 'num' => $num, 'salecount' => $salecount, 'sort' => $sort, 'catid' => $catid, 'name' => $name, 'price' => $price, 'mailprice' => $mailprice, 'vprice' => $vprice, 'oprice' => $oprice, 'intro' => $intro, 'logourl' => $pic, 'keyword' => $keyword, 'time' => time());
		$data['discount'] = number_format($price / $oprice, 2, '.', '') * 10;
		$product = M('Product');
		if ($pid && $obj = $product->where(array('id' => $pid, 'token' => $token, 'cid' => $this->_cid))->find()) {
			$product->where(array('id' => $pid, 'token' => $token))->save($data);
		} else {
			$pid = $product->add($data);
		}
		if (empty($pid)) {
			exit(json_encode(array('error_code' => false, 'msg' => '商品添加出错了')));
		}
		
		if($_POST['pc_cat_id'] && $_POST['pc_show']){
			$database_pc_product_category = D('Pc_news_category');
			$condition_pc_product_category['cat_id'] = $_POST['pc_cat_id'];
			$condition_pc_product_category['token'] = session('token');
			$now_category = $database_pc_product_category->field(true)->where($condition_pc_product_category)->find();
			if(empty($now_category)){
				//$this->error('检测到与该分类的电脑网站产品分类不存在！请您编辑该分类解绑电脑网站产品分类后再重试。');
				return false;
			}
			$database_pc_product = D('Pc_product');
			$data_pc_product['cat_id'] = $_POST['pc_cat_id'];
			$data_pc_product['price'] = $_POST['price'];
			$data_pc_product['pic'] = $_POST['pic'];
			$data_pc_product['token'] = session('token');
			$data_pc_product['title'] = $this->_post('name');
			$data_pc_product['content'] = $this->_post('intro','stripslashes,htmlspecialchars_decode');
			$data_pc_product['time'] = $_SERVER['REQUEST_TIME'];
			
			$database_pc_product->data($data_pc_product)->add();
		}
		
		if ($keys = M('Keyword')->where(array('pid' => $pid, 'token' => $token, 'module' => 'Product'))->find()) {
			M('Keyword')->where(array('pid' => $pid, 'token' => $token, 'id' => $keys['id']))->save(array('keyword' => $keyword));
		} else {
			M('Keyword')->add(array('token' => $token, 'pid' => $pid, 'keyword' => $keyword, 'module' => 'Product'));
		}
		if (!empty($attribute)) {
			$product_attribute = M('Product_attribute');
			$attribute = json_decode($attribute, true);
			foreach ($attribute as $row) {
				$data_a = array('pid' => $pid, 'aid' => $row['aid'], 'name' => $row['name'], 'value' => $row['value']);
				if ($row['id']) {
					$product_attribute->where(array('id' => $row['id'], 'pid' => $pid))->save($data_a);
				} else {
					$product_attribute->add($data_a);
				}
			}
		}
		
		if (!empty($norms)) {
			$product_detail = M('Product_detail');
			$norms = json_decode($norms, true);
			$detailList = $product_detail->field('id')->where(array('pid' => $pid))->select();
			$oldDetailId = array();
			foreach ($detailList as $val) {
				$oldDetailId[$val['id']] = $val['id'];
			}
			foreach ($norms as $row) {
				$data_d = array('pid' => $pid, 'format' => $row['format'], 'color' => $row['color'], 'num' => $row['num'], 'price' => $row['price'], 'vprice' => $row['vprice']);
				if ($row['id']) {
					unset($oldDetailId[$row['id']]);
					$product_detail->where(array('id' => $row['id'], 'pid' => $pid))->save($data_d);
				} else {
					$product_detail->add($data_d);
				}
			}
			//删除上次剩余的库存
			foreach ($oldDetailId as $id) {
				$product_detail->where(array('id' => $id, 'pid' => $pid))->delete();
			}
		}
		if (!empty($images)) {
			$product_image = M('Product_image');
			$images = json_decode($images, true);
			$iamgelist = $product_image->field('id')->where(array('pid' => $pid))->select();
			$oldImageId = array();
			foreach ($iamgelist as $val) {
				$oldImageId[$val['id']] = $val['id'];
			}
			foreach ($images as $row) {
				if (empty($row['image'])) continue;
				$data_d = array('pid' => $pid, 'image' => $row['image']);
				if ($row['id']) {
					unset($oldImageId[$row['id']]);
					$product_image->where(array('id' => $row['id'], 'pid' => $pid))->save($data_d);
				} else {
					$product_image->add($data_d);
				}
			}
			//删除上次剩余的库存
			foreach ($oldImageId as $id) {
				$product_image->where(array('id' => $id, 'pid' => $pid))->delete();
			}
		}
		exit(json_encode(array('error_code' => false, 'msg' => '商品操作成功')));
	}
	
	/**
	 * 删除商品
	 */
	public function del()
	{
		$product_model = M('Product');
		if($this->_get('token')!=session('token')){$this->error('非法操作');}
        $id = $this->_get('id');
        if (IS_GET) {                              
            $where = array('id'=>$id,'token'=>session('token'), 'cid' => $this->_cid);
            $check = $product_model->where($where)->find();
            if ($check == false) $this->error('非法操作');
            $back = $product_model->where($where)->delete();
            if ($back == true) {
            	$keyword_model = M('Keyword');
            	$keyword_model->where(array('token' => session('token'), 'pid' => $id, 'module' => 'Product'))->delete();
            	$count = $product_model->where(array('catid' => $check['catid']))->count();
            	if (empty($count)) {
            		D("Product_cat")->where(array('id' => $check['catid'], 'token' => session('token')))->save(array('isfinal' => 0));
            	}
                $this->success('操作成功', U('Store/product', array('token' => session('token'), 'dining' => $this->isDining)));
            } else {
				$this->error('服务器繁忙,请稍后再试', U('Store/product', array('token'=>session('token'))));
            }
        }        
	}
	
	public function orders()
	{
		$cid = $this->_cid;
		if (C('zhongshuai')) {
			$company = M('Company')->where("`token`='{$this->token}' AND `isbranch`=0")->find();
			$cid = $company['id'];
		}
		$product_cart_model = M('product_cart');
		$where = array('token' => $this->_session('token'), 'groupon' => 0, 'dining' => 0, 'cid' => $cid);
		if (IS_POST) {
			if ($_POST['token'] != $this->_session('token')) {
				exit();
			}
			$key = $this->_post('searchkey');
			if ($key) {
				$where['truename|tel|orderid'] = array('like', "%$key%");
			} else {
				for ($i=0;$i<40;$i++){
					if (isset($_POST['id_'.$i])){
						$thiCartInfo=$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->find();
						if ($thiCartInfo['handled']){
							$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->save(array('handled'=>0));
						} else {
							$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->save(array('handled'=>1));
						}
					}
				}
				$this->success('操作成功',U('Store/orders',array('token' => session('token'))));
				die;
			}
		}
		if (isset($_GET['handled'])) {
			$where['handled'] = intval($_GET['handled']);
		}
		$count      = $product_cart_model->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$orders		= $product_cart_model->where($where)->order('time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$where['handled'] = 0;
		$unHandledCount = $product_cart_model->where($where)->count();
		$this->assign('unhandledCount', $unHandledCount);
		$this->assign('orders', $orders);
		$this->assign('page', $show);
		$this->display();
	}
	
	public function orderInfo()
	{
		$this->product_model = M('Product');
		$this->product_cat_model = M('Product_cat');
		$product_cart_model = M('product_cart');
		$thisOrder = $product_cart_model->where(array('id'=>intval($_GET['id']), 'token' => $this->token))->find();
		//检查权限
		if (strtolower($thisOrder['token'])!=strtolower($this->_session('token'))){
			exit();
		}
		if (IS_POST){
			if (intval($_POST['sent'])){
				$_POST['handled']=1;
			}
			$product_cart_model->where(array('id'=>$thisOrder['id']))->save(array('sent'=>intval($_POST['sent']),'paid'=>intval($_POST['paid']),'logistics'=>$_POST['logistics'],'logisticsid'=>$_POST['logisticsid'],'handled'=>1));
			//TODO 发货的短信提醒
			
			$company = D('Company')->where(array('token' => $thisOrder['token'], 'id' => $thisOrder['cid']))->find();
			if ($_POST['sent']) {
				$userInfo = D('Userinfo')->where(array('token' => $thisOrder['token'], 'wecha_id' => $thisOrder['wecha_id']))->find();
				Sms::sendSms($this->token, "您在{$company['name']}商城购买的商品，商家已经给您发货了，请您注意查收", $userInfo['tel']);
			}
			
			/*******************支付订单打印*****************************/
			if (intval($_POST['paid'])&&intval($thisOrder['price'])){
				$carts = unserialize($thisOrder['info']);
				$tdata = $this->getCat($carts);
				$list = array();
				foreach ($tdata[0] as $va) {
					$t = array();
					if (!empty($va['detail'])) {
						foreach ($va['detail'] as $v) {
							$t = array('num' => $v['count'], 'colorName' => $v['colorName'], 'formatName' => $v['formatName'], 'price' => $v['price'], 'name' => $va['name']);
							$list[] = $t;
						}
					} else {
						$t = array('num' => $va['count'], 'price' => $va['price'], 'name' => $va['name']);
						$list[] = $t;
					}
				}
				$op = new orderPrint();
				$msg = array('companyname' => $company['name'], 'companytel' => $company['tel'], 'truename' => $thisOrder['truename'], 'tel' => $thisOrder['tel'], 'address' => $thisOrder['address'], 'buytime' => $thisOrder['time'], 'orderid' => $thisOrder['orderid'], 'sendtime' => '', 'price' => $thisOrder['price'], 'total' => $thisOrder['total'], 'list' => $list);
				$msg = ArrayToStr::array_to_str($msg, 1);
				$op->printit($this->token, $this->_cid, 'Store', $msg, 1);
			}
			/*******************支付订单打印*****************************/
			
			$this->success('修改成功',U('Store/orderInfo',array('token'=>session('token'),'id'=>$thisOrder['id'])));
		}else {
			//订餐信息
			$product_diningtable_model = M('product_diningtable');
			if ($thisOrder['tableid']) {
				$thisTable=$product_diningtable_model->where(array('id'=>$thisOrder['tableid']))->find();
				$thisOrder['tableName']=$thisTable['name'];
			}
			$this->assign('thisOrder',$thisOrder);
			$carts = unserialize($thisOrder['info']);
			$totalFee=0;
			$totalCount=0;
			
			$data = $this->getCat($carts);
			if (isset($data[1])) {
				foreach ($data[1] as $pid => $row) {
					$totalCount += $row['total'];
					$totalFee += $row['totalPrice'];
					$listNum[$pid] = $row['total'];
				}
			}
			$list = $data[0];
			$this->assign('products', $list);
			$this->assign('totalFee',$totalFee);
			$this->assign('totalCount',$totalCount);
			$this->assign('mailprice',$data[2]);
			$this->display();
		}
	}
	
	/**
	 * 计算一次购物的总的价格与数量
	 * @param array $carts
	 */
	public function getCat($carts)
	{
		if (empty($carts)) {
			return array();
		}
		//邮费
		$mailPrice = 0;
		//商品的IDS
		$pids = array_keys($carts);
		
		//商品分类IDS
		$productList = $cartIds = array();
		if (empty($pids)) {
			return array(array(), array(), array());
		}
		
		$productdata = $this->product_model->where(array('id'=> array('in', $pids)))->select();
		foreach ($productdata as $p) {
			if (!in_array($p['catid'], $cartIds)) {
				$cartIds[] = $p['catid'];
			}
			$mailPrice = max($mailPrice, $p['mailprice']);
			$productList[$p['id']] = $p;
		}
	
		//商品规格参数值
		$catlist = $norms = array();
		if ($cartIds) {
			$normsdata = M('norms')->where(array('catid' => array('in', $cartIds)))->select();
			foreach ($normsdata as $r) {
				$norms[$r['id']] = $r['value'];
			}
			//商品分类
			$catdata = M('Product_cat')-> where(array('id' => array('in', $cartIds)))->select();
			foreach ($catdata as $cat) {
				$catlist[$cat['id']] = $cat;
			}
		}
		$dids = array();
		foreach ($carts as $pid => $rowset) {
			if (is_array($rowset)) {
				$dids = array_merge($dids, array_keys($rowset));
			}
		}
		
		//商品的详细
		$totalprice = 0;
		$data = array();
		if ($dids) {
			$dids = array_unique($dids);
			$detail = M('Product_detail')->where(array('id'=> array('in', $dids)))->select();
			foreach ($detail as $row) {
				$row['colorName'] = isset($norms[$row['color']]) ? $norms[$row['color']] : '';
				$row['formatName'] = isset($norms[$row['format']]) ? $norms[$row['format']] : '';
				$row['count'] = isset($carts[$row['pid']][$row['id']]['count']) ? $carts[$row['pid']][$row['id']]['count'] : 0;
				$row['price'] = isset($carts[$row['pid']][$row['id']]['price']) ? $carts[$row['pid']][$row['id']]['price'] : $row['price'];
				$productList[$row['pid']]['detail'][] = $row;
				$data[$row['pid']]['total'] = isset($data[$row['pid']]['total']) ? intval($data[$row['pid']]['total'] + $row['count']) : $row['count'];
				$data[$row['pid']]['totalPrice'] = isset($data[$row['pid']]['totalPrice']) ? intval($data[$row['pid']]['totalPrice'] + $row['count'] * $row['price']) : $row['count'] * $row['price'];//array('total' => $totalCount, 'totalPrice' => $totalFee);
				$totalprice += $data[$row['pid']]['totalPrice'];
			}
		}
		//商品的详细列表
		$list = array();
		foreach ($productList as $pid => $row) {
			if (!isset($data[$pid]['total'])) {
				$count = $price = 0;
				if (isset($carts[$pid]) && is_array($carts[$pid])) {
					$a = explode("|", $carts[$pid]['count']);
					$count = isset($a[0]) ? $a[0] : 0;
					$price = isset($a[1]) ? $a[1] : 0;
				} else {
					$a = explode("|", $carts[$pid]);
					$count = isset($a[0]) ? $a[0] : 0;
					$price = isset($a[1]) ? $a[1] : 0;
				}
				$data[$pid] = array();
				$row['price'] = $price ? $price : $row['price'];
				$row['count'] = $data[$pid]['total'] = $count;
				$data[$pid]['totalPrice'] = $data[$pid]['total'] * $row['price'];
				$totalprice += $data[$pid]['totalPrice'];
			}
			$row['formatTitle'] =  isset($catlist[$row['catid']]['norms']) ? $catlist[$row['catid']]['norms'] : '';
			$row['colorTitle'] =  isset($catlist[$row['catid']]['color']) ? $catlist[$row['catid']]['color'] : '';
			$list[] = $row;
		}
		if ($obj = M('Product_setting')->where(array('token' => $this->token, 'cid' => $this->_cid))->find()) {
			if ($totalprice >= $obj['price']) $mailPrice = 0;
		}
		return array($list, $data, $mailPrice);
	}
	
	public function deleteOrder()
	{
		$product_model = M('product');
		$product_cart_model = M('product_cart');
		$product_cart_list_model = M('product_cart_list');
		$thisOrder = $product_cart_model->where(array('id' => intval($_GET['id']), 'cid' => $this->_cid))->find();
		//检查权限
		$id = $thisOrder['id'];
		if ($thisOrder['token'] != $this->_session('token')){
			exit();
		}
		//
		//删除订单和订单列表
		$product_cart_model->where(array('id' => $id, 'cid' => $this->_cid))->delete();
		$product_cart_list_model->where(array('cartid' => $id, 'cid' => $this->_cid))->delete();
		
		//商品销量做相应的减少
		if (empty($thisOrder['paid'])) {
			$carts = unserialize($thisOrder['info']);
			//还原库存
			foreach ($carts as $pid => $rowset) {
				$total = 0;
				if (is_array($rowset)) {
					foreach ($rowset as $did => $row) {
						M('product_detail')->where(array('id' => $did, 'pid' => $pid))->setInc('num', $row['count']);
						$total += $row['count'];
					}
				} else {
					if (strstr($rowset, '|')) {
						$a = explode("|", $rowset);
						$total = $a[0];
					} else {
						$total = $rowset;
					}
				}
				$product_model->where(array('id' => $pid))->setInc('num', $total);
				//$product_model->where(array('id' => $pid))->setDec('salecount', $total);
			}
//			foreach ($carts as $k => $c){
//				if (is_array($c)){
//					$productid=$k;
//					$price=$c['price'];
//					$count=$c['count'];
//					$product_model->where(array('id'=>$k))->setDec('salecount',$c['count']);
//				}
//			}
		}
		$this->success('操作成功',U('Store/orders', array('token' => session('token'))));
		//$this->success('操作成功',$_SERVER['HTTP_REFERER']);
	}
	
	
	/**
	 * 商城设置
	 */
	public function setting()
	{
		$setting = M('Product_setting');
		$obj = $setting->where(array('token' => session('token'), 'cid' => $this->_cid))->find();
		if (IS_POST) {
			if ($obj) {
				unset($_POST['id']);
				$t = $setting->where(array('token' => session('token'), 'cid' => $this->_cid, 'id' => $obj['id']))->save($_POST);
				if ($t) {
					$this->success('修改成功',U('Store/index',array('token' => session('token'))));
				} else {
					$this->error('操作失败');
				}
			} else {
				$pid = $setting->add($_POST);
				if ($pid) {
					$this->success('增加成功',U('Store/index',array('token' => session('token'))));
				} else {
					$this->error('操作失败');
				}
			}
		} else {
			$showGroup = C('zhongshuai') ? 1 : 0;
			
			include('./PigCms/Lib/ORG/index.Tpl.php');
			include('./PigCms/Lib/ORG/cont.Tpl.php');
			
			$this->assign('showgroup', $showGroup);
			$this->assign('tpl', $tpl);
			$this->assign('contTpl', $contTpl);
			$this->assign('setting', $obj);
			$this->display();	
		}
	}
	
	public function comment()
	{
		$catid = intval($_GET['catid']);
		$pid = intval($_GET['pid']);
		$product_model = M('Product_comment');
		
		$cid = $this->_cid;
		if (C('zhongshuai')) {
			$company = M('Company')->where("`token`='{$this->token}' AND `isbranch`=0")->find();
			$cid = $company['id'];
		}
		
		$where = array('token' => $this->token, 'cid' => $cid, 'pid' => $pid, 'isdelete' => 0);
		$count      = $product_model->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list = $product_model->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->assign('pid',$pid);
		$this->assign('catid', $catid);
		$this->display();	
		
	}
	
	/**
	 * 删除商品
	 */
	public function commentdel()
	{
		$catid = intval($_GET['catid']);
		$pid = intval($_GET['pid']);
		if($this->_get('token')!= session('token')){$this->error('非法操作');}
        $id = $this->_get('id');
        if (IS_GET) {
        	$cid = $this->_cid;
			if (C('zhongshuai')) {
				$company = M('Company')->where("`token`='{$this->token}' AND `isbranch`=0")->find();
				$cid = $company['id'];
			}
        	M('Product_comment')->where(array('id' => $id,'token' => session('token'),'cid' => $cid))->save(array('isdelete' => 1));
			$this->success('操作成功', U('Store/comment',array('token'=>session('token'),'catid' => $catid,'pid' => $pid)));
        }        
	}
	
	public function group()
	{
		$data = M('Product_group');
		$where = array('token' => session('token'));
        $count      = $data->where($where)->count();
		$Page       = new Page($count, 20);
		$show       = $Page->show();
		$list = $data->where($where)->order("id DESC")->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->display();		
	}
	
	public function groupAdd()
	{
		if (IS_POST) { 
            $data = D('Product_group');
            $id = intval($this->_post('id'));
            if ($id) {
	            $where = array('id' => $id, 'token' => $this->token);
				$check = $data->where($where)->find();
				if ($check == false) $this->error('非法操作');
            }
			if ($data->create()) {
				if ($id) {
					if ($data->save()) {
						$this->success('修改成功', U('Store/group',array('token' => session('token'))));
					} else {
						$this->error('操作失败');
					}
				} else {
					if ($data->add()) {
						$this->success('添加成功', U('Store/group',array('token' => session('token'))));
					} else {
						$this->error('操作失败');
					}
				}
			} else {
				$this->error($data->getError());
			}
		} else {
	        $id = $this->_get('id'); 
			$group = M('Product_group')->where(array('id' => $id, 'token' => $this->token))->find();
			$this->assign("set", $group);
			$this->display();
		}
	}
	
	/**
	 * 删除分组
	 */
	public function groupDel()
	{
		if($this->_get('token')!= session('token')){$this->error('非法操作');}
        $id = $this->_get('id');
        if (IS_GET) {
        	M('Product_group')->where(array('id' => $id,'token' => session('token')))->delete();
			$this->success('操作成功', U('Store/group',array('token'=>session('token'))));
        }        
	}
	
	/**
	 * 组别分配到店
	 */
	public function groupSet()
	{
		if (IS_POST) {
			$gid = intval($_REQUEST['gid']);
			$relation = M("Product_relation")->where(array('token' => $this->token, 'gid' => $gid))->select();
			$cids = array();
			foreach ($relation as $r) {
				$cids[] = $r['cid'];
			}
			$companys = $_REQUEST['company'];
			foreach ($companys as $cid) {
				if (!in_array($cid, $cids)) {
					D("Product_relation")->add(array('cid' => $cid, 'gid' => $gid, 'token' => $this->token));
				} else {
					$cids = array_diff($cids, array($cid));
				}
			}
			if ($cids) {
				D("Product_relation")->where(array('cid' => array('in', $cids), 'token' => $this->token))->delete();
			}
			$this->success("分配成功", U('Store/group',array('token'=>session('token'))));
		} else {
	        $gid = $this->_get('gid'); 
			$group = M('Product_group')->where(array('id' => $gid, 'token' => $this->token))->find();
			if (empty($group)) {
				$this->error("参数错误", U('Store/group', array('token' => session('token'))));
			}
			$this->assign("group", $group);
			
			$company = M('Company')->where("`token`='{$this->token}' AND ((`isbranch`=1 AND `display`=1) OR `isbranch`=0)")->select();
			$this->assign("company", $company);
			
			$relation = M("Product_relation")->where(array('token' => $this->token, 'gid' => $gid))->select();
			$cids = array();
			foreach ($relation as $r) {
				$cids[] = $r['cid'];
			}
			$this->assign("relation", $cids);
			
			$this->display();
		}
	}
	
	public function flash()
	{
		$flash = M("Store_flash")->where(array('token' => $this->token, 'cid' => $this->_cid))->select();
		$this->assign('flash', $flash);
		$this->display();
	}
	
	public function flashadd()
	{
		$type = isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
		$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
		
		if (IS_POST) { 
            $data = D('Store_flash');
            $id = intval($this->_post('id'));
            if ($id) {
	            $where = array('id' => $id, 'token' => $this->token, 'cid' => $this->_cid);
				$check = $data->where($where)->find();
				if ($check == false) $this->error('非法操作');
            }
			if ($data->create()) {
				if ($id) {
					if ($data->save()) {
						$this->success('修改成功', U('Store/flash',array('token' => session('token'))));
					} else {
						$this->error('操作失败');
					}
				} else {
					if ($data->add()) {
						$this->success('添加成功', U('Store/flash',array('token' => session('token'))));
					} else {
						$this->error('操作失败');
					}
				}
			} else {
				$this->error($data->getError());
			}
		} else {
			$flash = M("Store_flash")->where(array('token' => $this->token, 'cid' => $this->_cid, 'id' => $id))->find();
			$type = isset($flash['type']) ? $flash['type'] : $type;
			$this->assign('flash', $flash);
			$this->assign('type', $type);
			$this->display();
		}
	}
	
	public function flashdel()
	{
		$where = array();
		$where['id']=$this->_get('id','intval');
		$where['token']=$this->token;
		$where['cid']=$this->_cid;
		if(D("Store_flash")->where($where)->delete()){
			$this->success('操作成功',U('Store/flash',array('token' => session('token'))));
		}else{
			$this->error('操作失败',U('Store/flash',array('token' => session('token'))));
		}
	}
public function twitter()
	{
		$data = M('Twitter_count');
		$where = array('token' => session('token'), 'cid' => $this->_cid);
        if (IS_POST) {
            $key = $this->_post('searchkey');
            if(empty($key)){
                $this->error("关键词不能为空");
            }

            $where['twid'] = array('like',"%$key%"); 
            $list = $data->where($where)->order("id DESC")->select(); 
            $count      = $data->where($where)->count();       
            $Page       = new Page($count, 20);
        	$show       = $Page->show();
        	$this->assign('key', $key);
        } else {
        	$count      = $data->where($where)->count();
        	$Page       = new Page($count, 20);
        	$show       = $Page->show();
        	$list = $data->where($where)->order("id DESC")->limit($Page->firstRow . ',' . $Page->listRows)->select();
        }
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->display();
	}
	
	public function twitterset()
	{
		$twitter = M('Twitter_set')->where(array('token' => $this->token, 'cid' => $this->_cid))->find();
		if (IS_POST) {
			$_POST['token'] = $this->token;
			$_POST['cid'] = $this->_cid;
			unset($_POST['id']);
			if ($twitter) {
				$t = D('Twitter_set')->where(array('token' => $this->token, 'cid' => $this->_cid, 'id' => $twitter['id']))->save($_POST);
				if ($t) {
					$this->success('修改成功');
				} else {
					$this->error('操作失败');
				}
			} else {
				$tid = D('Twitter_set')->add($_POST);
				if ($tid) {
					$this->success('增加成功');
				} else {
					$this->error('操作失败');
				}
			}
		} else {
			$this->assign('set', $twitter);
			$this->display();
		}
	}
	
	public function detail()
	{
		$data = M('Twitter_log');
		$twid = isset($_GET['twid']) ? htmlspecialchars($_GET['twid']) : '';
		if (empty($twid)) exit();
		$where = array('token' => $this->token, 'cid' => $this->_cid, 'twid' => $twid);
		$count      = $data->where($where)->count();
		$Page       = new Page($count, 20);
		$show       = $Page->show();
		$list = $data->where($where)->order("id DESC")->limit($Page->firstRow . ',' . $Page->listRows)->select();
		
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->display();
	}
	
	public function remove()
	{
		$data = M('Twitter_remove');
		$twid = isset($_GET['twid']) ? htmlspecialchars($_GET['twid']) : '';
		if (empty($twid)) exit();
		$where = array('token' => $this->token, 'cid' => $this->_cid, 'twid' => $twid);
		$count      = $data->where($where)->count();
		$Page       = new Page($count, 20);
		$show       = $Page->show();
		$list = $data->where($where)->order("id DESC")->limit($Page->firstRow . ',' . $Page->listRows)->select();
		
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->display();
		
	}
	
	public function removesearch()
	{
		$data = M('Twitter_remove');
		$where = array('token' => $this->token, 'cid' => $this->_cid, 'status' => 0);
		$count      = $data->where($where)->count();
		$Page       = new Page($count, 20);
		$show       = $Page->show();
		$list = $data->where($where)->order("id DESC")->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->display();
	}
	
	/**
	 * 删除分组
	 */
	public function changestatus()
	{
		if($this->_get('token')!= session('token')){$this->error('非法操作');}
        $id = $this->_get('id');
        $twid = $this->_get('twid');
        if (IS_GET) {
        	if ($remove = M('Twitter_remove')->where(array('id' => $id,'token' => session('token'),'cid' => $this->_cid,'twid' => $twid))->find()) {
	        	D('Twitter_remove')->where(array('id' => $id,'token' => session('token'),'cid' => $this->_cid,'twid' => $twid))->save(array('status' => 1));
	        	D('Twitter_count')->where(array('token' => session('token'),'cid' => $this->_cid,'twid' => $twid))->setInc('remove', $remove['price']);
				$this->success('操作成功', U('Store/removesearch',array('token' => session('token'), 'cid' => $this->_cid)));
        	}
        }        
	}}
?>
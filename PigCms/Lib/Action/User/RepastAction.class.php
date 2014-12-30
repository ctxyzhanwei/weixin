<?php
class RepastAction extends UserAction{
    public $_cid = 0;
        private $_Companys_repair = array();
        private $_my_cid_arr = array();
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('dx');
		$this->_cid = isset($_GET['cid']) ? intval($_GET['cid']) : session('companyid');
		if (empty($this->token)) {
			$this->error('不合法的操作', U('Index/index'));
		}
		if (empty($this->_cid))  {
			$company = M('Company')->where(array('token' => $this->token, 'isbranch' => 0))->find();
			if ($company) {
				$this->_cid = $company['id'];
				//主店的k存session
				session('companyk', md5($this->_cid . session('uname')));
			} else {
				$this->error('您还没有添加您的商家信息',U('Company/index',array('token' => $this->token)));
			}
		} else {
			$k = session('companyk');
			$company = M('Company')->where(array('token' => $this->token, 'id' => $this->_cid))->find();
			if (empty($company)) {
				$this->error('非法操作', U('Repast/index',array('token' => $this->token)));
			} else {
//				$username = $company['isbranch'] ? $company['username'] : session('uname');
//				if (md5($this->_cid . $username) != $k) {
//					$this->error('非法操作', U('Repast/index',array('token' => $this->token)));
				}
			}
			$company = M('Company')->where(array('id' => $this->_cid))->find();
			if($company['isbranch'] == 1){
				$row =M('Company')->where(array('token'=>$this->token,'id'=>$_GET['cid']))->find();
				$Companys_arr[$row['id']] = $row['name'];
				$Companys_repair[$row['id']] = $row;
				$this->_my_cid_arr[] = $row['id'];
				$Companys[]=$row;
			} else {
				$Companys =M('Company')->where(array('token'=>$this->token))->select();
				$Companys_arr = array();
				$Companys_repair = array();
				foreach ($Companys as $row) {
				$Companys_arr[$row['id']] = $row['name'];
				$Companys_repair[$row['id']] = $row;
				$this->_my_cid_arr[] = $row['id'];
                     }
                }
                //print_r($company);exit;
                $this->_Companys_repair = $Companys_repair;//删除已用记录用
                $this->assign('Companys',$Companys);
                $this->assign('Companys_arr',$Companys_arr);//列表用显示名称
		$this->assign('ischild', session('companyLogin'));
		$this->assign('cid', $this->_cid);
	}
	
	/**
	 * 餐台列表
	 */
	public function index() {
		$data = M('Dining_table');
	        //$where = array('cid' => $this->_cid);
                $where       = "cid in (0," .join(',', $this->_my_cid_arr). ")";
		$count      = $data->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list = $data->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page', $show);	
		$this->assign('list', $list);
		$this->display();
	}
	
	/**
	 * 对餐台的操作
	 * @see UserAction::add()
	 */
	public function add() {
		$dataBase = D('Dining_table');
		if (IS_POST) {
			$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
			if ($id) {//edit
				if ($dataBase->create() !== false) {
					$action = $dataBase->save();
					if ($action != false) {
						$this->success('修改成功',U('Repast/index',array('token' => $this->token, 'cid' => $this->_cid)));
					} else {
						$this->error('操作失败');
					}
				} else {
					$this->error($dataBase->getError());
				}
			} else {//add
				if ($dataBase->create() !== false) {
					$action = $dataBase->add();
					if ($action != false ) {
						$this->success('添加成功',U('Repast/index',array('token' => $this->token, 'cid' => $this->_cid)));
					} else {
						$this->error('操作失败');
					}
				} else {
					$this->error($dataBase->getError());
				}
			}
		} else {
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			$findData = $dataBase->where(array('id' => $id, 'cid' => $this->_cid))->find();
			$this->assign('tableData', $findData);
                        $slt_company = array();
                        $data = M('Dish_company');
                        $where = "cid in (0," .join(',', $this->_my_cid_arr). ")";
                        $list = $data->where($where)->select();       
                        foreach ($list as $row) {
                            if ($row['cid']==0) continue;;
                            $slt_company[$row['cid']] = $this->_Companys_repair[$row['cid']];
                        }
                        $this->assign('Companys', $slt_company);
                        
			$this->display();
		}
	}
	
	/**
	 * 删除餐台
	 */
	public function del() {
		$diningTable = M('Dining_table');
        if (IS_GET) {
        	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;      
            $where = array('id' => $id,'cid' => $this->_cid);
            $check = $diningTable->where($where)->find();
            if($check == false) $this->error('非法操作');
            $back = $diningTable->where($wehre)->delete();
            if ($back == true) {
                $this->success('操作成功',U('Repast/index',array('token' => $this->token,'cid' => $this->_cid)));
            } else {
                $this->error('服务器繁忙,请稍后再试',U('Repast/index',array('token' => $this->token,'cid' => $this->_cid)));
            }
          }        
	}
	
	/**
	 * 分类管理
	 */
	public function sort() {
		$data = M('Dish_sort');
		//$where = array('cid' => $this->_cid);
                $where = "cid in (0," .join(',', $this->_my_cid_arr). ")";
		$count      = $data->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
                $list  = $data->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('page', $show);	
		$this->assign('list', $list);
		$this->display();		
	}
	
	/**
	 * 对餐台的操作
	 */
	public function sortadd() {
		$dataBase = D('Dish_sort');
		if (IS_POST) {
			$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
			if ($id) {//edit
				if ($dataBase->create() !== false) {
					$action = $dataBase->save();
					if ($action != false) {
						$this->success('修改成功',U('Repast/sort',array('token' => $this->token, 'cid' => $this->_cid)));
					} else {
						$this->error('操作失败');
					}
				} else {
					$this->error($dataBase->getError());
				}
			} else {//add
				if ($dataBase->create() !== false) {
					$action = $dataBase->add();
					if ($action != false ) {
						$this->success('添加成功',U('Repast/sort',array('token' => $this->token, 'cid' => $this->_cid)));
					} else {
						$this->error('操作失败');
					}
				} else {
					$this->error($dataBase->getError());
				}
			}
		} else {
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			$findData = $dataBase->where(array('id' => $id, 'cid' => $this->_cid))->find();
			$this->assign('tableData', $findData);
			$this->display();
		}
	}
	
	/**
	 * 删除分类
	 */
	public function sortdel() {
		$dishSort = M('Dish_sort');
        if(IS_GET){
        	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;     
            $where = array('id' => $id,'cid' => $this->_cid);
            $check = $dishSort->where($where)->find();
            if($check == false) $this->error('非法操作');
            $back = $dishSort->where($wehre)->delete();
            if ($back == true) {
                $this->success('操作成功',U('Repast/sort',array('token' => $this->token,'cid' => $this->_cid)));
            }else{
                 $this->error('服务器繁忙,请稍后再试',U('Repast/sort',array('token' => $this->token,'cid' => $this->_cid)));
            }
           }
	}
	
	/**
	 * 餐桌预定情况
	 */
	public function detail() {
		$list = M('Dining_table')->where(array('cid' => $this->_cid))->select();
		$dinings = array();
		foreach($list as $l) {
			$dinings[$l['id']] = $l;
		}
		$reservetime = isset($_GET['time']) ? strtotime($_GET['time']) : '';
		if ($reservetime) {
			$where = array('reservetime' => array(array('EGT', $reservetime), array('LT', $reservetime + 86400), 'AND'));
		} else {
			$where = array('reservetime' => array(array('EGT', strtotime(date("Y-m-d"))), array('LT', strtotime(date("Y-m-d")) + 86400), 'AND'));
		}
		$where['cid'] = $this->_cid;
		
		$list = array();
		$tables = M('Dish_table')->where($where)->select();
		//echo M('Dish_table')->getLastSql();die;
		if ($tables) {
			foreach ($tables as $t) {
				$t['name'] = isset($dinings[$t['tableid']]['name']) ? $dinings[$t['tableid']]['name'] : '';
				$list[] = $t;
			}
		}
		
		
		
		$dates = array();
		$dates[] = array('k' => date("Y-m-d"), 'v' => date("m月d日"));
		for ($i = 1; $i <= 90; $i ++) {
			$dates[] = array('k' => date("Y-m-d", strtotime("+{$i} days")), 'v' => date("m月d日", strtotime("+{$i} days")));
		}
		$this->assign('dates', $dates);
		$this->assign('list', $list);
		$this->display();
		
	}
	public function company_index() {
		$data = M('Dish_company');
		//$where = array('cid' => $this->_cid);
                $where = "cid in (0," .join(',', $this->_my_cid_arr). ")";
		$count      = $data->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list = $data->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page', $show);	
		$this->assign('list', $list);
		$this->display();            
	}
	public function company_del() {
		$diningTable = M('Dish_company');
                if (IS_GET) {
                        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;      
                    $where = array('id' => $id,'cid' => $this->_cid);
                    $check = $diningTable->where($where)->find();
                    if($check == false) $this->error('非法操作');
                    $back = $diningTable->where($wehre)->delete();
                    if ($back == true) {
                        $this->success('操作成功',U('Repast/company_index',array('token' => $this->token,'cid' => $this->_cid)));
                    } else {
                        $this->error('服务器繁忙,请稍后再试',U('Repast/company_index',array('token' => $this->token,'cid' => $this->_cid)));
                  }
             }        
	}	

	public function company() {
                $findData = array();
		$dataBase = D('Dish_company');
                
		if (IS_POST) {     //print_r($_POST);exit;   
                        //$findData = $dataBase->where(array('cid' => $this->_cid))->find();                   
			if ($_POST['id'] > 0) {//edit && $findData
				if ($dataBase->create() !== false) {
					$action = $dataBase->save();
					if ($action != false) {
						$this->success('修改成功',U('Repast/company_index',array('token' => $this->token, 'cid' => $this->_cid)));
					} else {
						$this->error('操作失败');
					}
				} else {
					$this->error($dataBase->getError());
				}
			} else {//add
				if ($dataBase->create() !== false) {
					$action = $dataBase->add();
					if ($action != false ) {
						$this->success('添加成功',U('Repast/company_index',array('token' => $this->token, 'cid' => $this->_cid)));
					} else {
						$this->error('操作失败');
					}
				} else {
					$this->error($dataBase->getError());
				}
			}
		} else {
                        if ($id = $_GET['id']) {
                            $findData = $dataBase->where(array('cid' => $this->_cid, 'id'=>$id))->find();
                        }
			$this->assign('company', $findData);
                        $data = M('Dish_company');
                        // $where = array('cid' => $this->_cid);;
                        $where = "cid in (0," .join(',', $this->_my_cid_arr). ")";
                        $list = $data->where($where)->select();       
                        foreach ($list as $row) {
                            if ($row['id'] == $id)continue;//跳过自己
                            unset($this->_Companys_repair[$row['catid']]);
                        }
                        $this->assign('Companys',$this->_Companys_repair);
			$this->display();
		}
	}
	
	/**
	 * 菜的列表
	 */
	public function dish() {
		$data = M('Dish');
		//$where = array('cid' => $this->_cid);
                $where = "cid in (0," .join(',', $this->_my_cid_arr). ")";
		$count      = $data->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$dish = $data->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$list = $sortList = array();
		$sort = M('Dish_sort')->where(array('cid' => $this->_cid))->select();
		foreach ($sort as $row) {
			$sortList[$row['id']] = $row;
		}
		foreach ($dish as $r) {
			$r['sortName'] = isset($sortList[$r['sid']]['name']) ? $sortList[$r['sid']]['name'] : '';
			$list[] = $r;
		}
		$this->assign('page', $show);	
		$this->assign('list', $list);
		$this->display();		
	}
	
	/**
	 * 对菜品的操作
	 */
	public function dishadd() {
		$dataBase = D('Dish');
		$dish_sort = M('Dish_sort');
		if (IS_POST) {
			$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
			$_POST['ishot'] = isset($_POST['ishot']) ? intval($_POST['ishot']) : 0;
			$_POST['isopen'] = isset($_POST['isopen']) ? intval($_POST['isopen']) : 0;
			if ($id) {//edit
				if ($dataBase->create() !== false) {
					$temp = M('Dish')->where(array('cid' => $this->_cid, 'id' => $id))->find();
					$action = $dataBase->save();
					if ($action != false) {
						if ($temp['sid'] != $_POST['sid']) {
							$dish_sort->where(array('id' => $_POST['sid'], 'cid' => $this->_cid))->setInc('num', 1);
							$dish_sort->where(array('id' => $temp['sid'], 'cid' => $this->_cid))->setDec('num', 1);
						}
						$this->success('修改成功',U('Repast/dish',array('token' => $this->token, 'cid' => $this->_cid)));
					} else {
						$this->error('操作失败');
					}
				} else {
					$this->error($dataBase->getError());
				}
			} else {//add
				if ($dataBase->create() !== false) {
					$action = $dataBase->add();
					if ($action != false ) {
						$dish_sort->where(array('id' => $_POST['sid'], 'cid' => $this->_cid))->setInc('num', 1);
						$this->success('添加成功',U('Repast/dish',array('token' => $this->token, 'cid' => $this->_cid)));
					} else {
						$this->error('操作失败');
					}
				} else {
					$this->error($dataBase->getError());
				}
			}
		} else {
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
                        $where = "cid in (0," .join(',', $this->_my_cid_arr). ")";//array('cid' => $this->_cid)
			$dishSort = M('Dish_sort')->where($where)->select();
			if (empty($dishSort)) {
				$this->redirect(U('Repast/sortadd',array('token' => $this->token, 'cid' => $this->_cid)));
			}
			$findData = $dataBase->where(array('id' => $id, 'cid' => $this->_cid))->find();
			$this->assign('tableData', $findData);
			$this->assign('dishSort', $dishSort);
                        $slt_company = array();                        
                        $data = M('Dish_company');
                        $where = "cid in (0," .join(',', $this->_my_cid_arr). ")";
                        $list = $data->where($where)->select();       
                        foreach ($list as $row) {
                            if ($row['cid']==0) continue;;
                            $slt_company[$row['cid']] = $this->_Companys_repair[$row['cid']];
                        }                        
                        $this->assign('Companys', $slt_company);
			$this->display();
		}
	}
	
	/**
	 * 删除菜
	 */
	public function dishdel(){
		$dish = M('Dish');
        if(IS_GET){
        	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;     
            $where = array('id' => $id,'cid' => $this->_cid);
            $check = $dish->where($where)->find();
            if($check == false) $this->error('非法操作');
            $back = $dish->where($wehre)->delete();
            if ($back == true) {
            	M('Dish_sort')->where(array('id' => $check['sid'], 'cid' => $this->_cid))->setDec('num', 1);
                $this->success('操作成功',U('Repast/dish',array('token' => $this->token,'cid' => $this->_cid)));
            }else{
                 $this->error('服务器繁忙,请稍后再试',U('Repast/dish',array('token' => $this->token,'cid' => $this->_cid)));
            }
          }        
	}
	
	/**
	 * 订单列表
	 */
	public function orders() {
		$status = isset($_GET['status']) ? intval($_GET['status']) : 0;
		$dish_order = M('Dish_order');
        
				$where = array('token' => $this->_session('token'));
				$company = M('Company')->where(array('id' => $this->_cid))->find();
               if($company['isbranch'] == 1){
					$where['cid']  = $this->_cid;
                }
		if (IS_POST) {
			$key = $this->_post('searchkey');
			if(empty($key)){
				$this->error("关键词不能为空");
			}
			$where['name|address'] = array('like',"%$key%");
			$orders = $dish_order->where($where)->select();
			$count      = $dish_order->where($where)->limit($Page->firstRow.','.$Page->listRows)->count();
			$Page       = new Page($count,20);
			$show       = $Page->show();
		} else {
			switch ($status) {
				case 4 :
					$where['isuse'] = 1;
					$where['paid'] = 1;
					break;
				case 3 :
					$where['isuse'] = 0;
					$where['paid'] = 1;
					break;
				case 2:
					$where['isuse'] = 1;
					$where['paid'] = 0;
					break;
				case 1 :
					$where['isuse'] = 0;
					$where['paid'] = 0;
			}
			$count      = $dish_order->where($where)->count();
			$Page       = new Page($count, 20);
			$show       = $Page->show();
			$orders=$dish_order->where($where)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		}
		
		$diningTable = M('Dining_table')->where(array('cid' => $this->_cid))->select();
		$list = array();
		foreach ($diningTable as $row) {
			$list[$row['id']] = $row;
		}
		$this->assign('diningTable', $list);
		$this->assign('orders', $orders);
		$this->assign('status', $status);

		$this->assign('page',$show);
		$this->display();
	}
	
	/**
	 * 订单详情
	 */
	public function orderInfo() {
		$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
		$dishOrder = M('Dish_order');
		if ($thisOrder = $dishOrder->where(array('id' => $id, 'token' => $this->token))->find()) {
			if (IS_POST) {
				$isuse = isset($_POST['isuse']) ? intval($_POST['isuse']) : 0;
				$paid = isset($_POST['paid']) ? intval($_POST['paid']) : 0;
				$dishOrder->where(array('id' => $thisOrder['id']))->save(array('isuse' => $isuse, 'paid' => $paid));
				if ($thisOrder['tableid'] && $isuse) {
					D('Dish_table')->where(array('orderid' => $thisOrder['id']))->save(array('isuse' => 1));
				}
				$company = M('Company')->where(array('token' => $this->token, 'id' => $this->_cid))->find();
				if ($paid) {
					$temp = unserialize($thisOrder['info']);
					$takeAwayPrice = $temp['takeAwayPrice'];
					
					$op = new orderPrint();
					
					$msg = array('companyname' => $company['name'], 'des' => $thisOrder['des'], 'companytel' => $company['tel'], 'truename' => $thisOrder['name'], 'tel' => $thisOrder['tel'], 'takeAwayPrice' => $takeAwayPrice, 'address' => $thisOrder['address'], 'buytime' => $thisOrder['time'], 'orderid' => $thisOrder['orderid'], 'sendtime' => $thisOrder['reservetime'], 'price' => $thisOrder['price'], 'total' => $thisOrder['nums'], 'list' => $temp['list']);
					$msg['typename'] =  $thisOrder['takeaway'] == 1 ? '外卖' : ($thisOrder['takeaway'] == 2 ? '现在点餐' : '预约点餐');
					if ($thisOrder['tableid']) {
						$t_table = M("Dining_table")->where(array('id' => $thisOrder['tableid']))->find();
						$msg['tablename'] = isset($t_table['name']) ? $t_table['name'] : '';
					}
					
					$msg = ArrayToStr::array_to_str($msg, 1);
					$op->printit($this->token, $this->_cid, 'Repast', $msg, 1);
					
				}
				Sms::sendSms($this->token, "{$company['name']}欢迎您，本店对您的订单号为：{$thisOrder['orderid']}的订单状态进行了修改，如有任何疑意，请您及时联系本店！", $thisOrder['tel']);
				$this->success('修改成功',U('Repast/orderInfo',array('token'=>session('token'),'id'=>$thisOrder['id'])));
			} else {
				$dishList = unserialize($thisOrder['info']);
				$this->assign('thisOrder', $thisOrder);
				$this->assign('dishList', $dishList);
				$this->display();
			}
		}
	}
	
	/**
	 * 删除订单
	 */
	public function deleteOrder() {
		$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
		$dishOrder = M('Dish_order');
		if ($thisOrder = $dishOrder->where(array('id' => $id, 'token' => $this->token))->find()) {
			$dishOrder->where(array('id' => $id))->delete();
			if ($thisOrder['tableid']) {
				D('Dish_table')->where(array('orderid' => $thisOrder['id']))->delete();
			}
			$this->success('操作成功', U('Repast/orders', array('token' => session('token'), 'cid' => $this->_cid)));
		}
	}
}
?>
<?php
class ShareAction extends UserAction{
	public $tokenWhere;
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('share');
		$this->tokenWhere=array('token'=>$this->token);
	}
	public function set(){
		$record=M('Share_set')->where($this->tokenWhere)->find();
		if (IS_POST){
			$row=array();
			$row['score']=intval($_POST['score']);
			$row['daylimit']=intval($_POST['daylimit']);
			if ($record){
				M('Share_set')->where($this->tokenWhere)->save($row);
			}else {
				$row['token']=$this->token;
				M('Share_set')->add($row);
			}
			$this->success('设置成功');
		}else {
			if (!$record){
				
			}
			$this->assign('record',$record);
			$this->assign('tab','set');
			$this->display();
		}
	}
	public function records(){
		$db=D('Share');
		$where['token']=$this->token;
		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$info=$db->where($where)->order('id DESC')->limit($page->firstRow.','.$page->listRows)->select();
		$wecha_ids=array();
		if ($info){
			foreach ($info as $item){
				if (!in_array($item['wecha_id'],$wecha_ids)){
					array_push($wecha_ids,$item['wecha_id']);
				}
			}
			$users=M('Userinfo')->where(array('wecha_id'=>array('in',$wecha_ids)))->select();
			if ($users){
				foreach ($users as $useritem){
					$users[$useritem['wecha_id']]=$useritem;
				}
			}
			$i=0;
			foreach ($info as $item){
				$info[$i]['user']=$users[$item['wecha_id']];
				$info[$i]['moduleName']=funcDict::moduleName($item['module']);
				$i++;
			}
		}
		
		$this->assign('page',$page->show());
		$this->assign('info',$info);
		$this->assign('tab','records');
		$this->display();
	}
	public function index(){
		$days=7;
		$this->assign('days',$days);
		$where=array('token'=>$this->token);
		$where['time']=array('gt',time()-$days*24*3600);
		$where['module']=array('neq','');
		$db=M('Share');
		$items=$db->where($where)->select();

		$datas=array();
		if ($items){
			foreach ($items as $item){
				if (trim($item['module'])){
					if (!key_exists($item['module'],$datas)){
						$datas[$item['module']]=array('module'=>$item['module'],'count'=>1,'moduleName'=>funcDict::moduleName($item['module']));
					}else {
						$datas[$item['module']]['count']++;
					}
				}
			}
		}	
		$xml='<chart borderThickness="0" caption="'.$days.'日内分享统计" baseFontColor="666666" baseFont="宋体" baseFontSize="14" bgColor="FFFFFF" bgAlpha="0" showBorder="0" bgAngle="360" pieYScale="90"  pieSliceDepth="5" smartLineColor="666666">';
		if ($datas){
			foreach ($datas as $item){
				$xml.='<set label="'.$item['moduleName'].'" value="'.$item['count'].'"/>';
			}
		}
		$xml.='</chart>';
		$this->assign('items',$items);
		$this->assign('xml',$xml);
		//
		$this->assign('list',$datas);
		$this->assign('listinfo',1);
		$this->assign('tab','stastic');
		$this->display();
	}
	public function tongji(){
        $db = D('Share');
        $where['token'] = $this -> token;
        $count = $db -> where($where) -> count();
        $page = new Page($count, 1000);
        $info = $db -> where($where) -> order('id DESC') -> limit($page -> firstRow . ',' . $page -> listRows) -> select();
        $wecha_ids = array();
        if ($info){
            foreach ($info as $item){
                if (!in_array($item['wecha_id'], $wecha_ids)){
                    array_push($wecha_ids, $item['wecha_id']);
                }
            }
            $users = M('Userinfo') -> where(array('wecha_id' => array('in', $wecha_ids))) -> select();
            if ($users){
                foreach ($users as $useritem){
                    $users[$useritem['wecha_id']] = $useritem;
                }
            }
            $i = 0;
            foreach ($info as $item){
                $info[$i]['user'] = $users[$item['wecha_id']];
                $info[$i]['moduleName'] = funcDict :: moduleName($item['module']);
                $i++;
            }
        }
		
        $r = 0;
        foreach ($info as $item){
            $iinfo[$r]=$info[$r]['wecha_id'];
            $r++;
        }
			
	$ac=array_count_values($iinfo);
        $this -> assign('page', $page -> show());
        $this -> assign('info', $info);
	$this -> assign('ac', $ac);
        $this -> assign('tab', 'tongji');
        $this -> display();
    }
}
?>

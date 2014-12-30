<?php
class MapAction extends Action{
	public $token;
	public $apikey;
	public $isamap;
	public $key;
	public $amap;
	public function _initialize() {
		$this->token=$this->_get('token');
		$this->assign('token',$this->token);
		$this->apikey=C('baidu_map_api');
		$this->assign('apikey',$this->apikey);
		//
		if (C('baidu_map')){
			$this->isamap=0;
		}else {
			$this->isamap=1;
			$this->amap=new amap();
		}
	}
	//公司静态地图
	public function staticCompanyMap(){
		$amap=$this->amap;
		//main company
		$company_model=M('Company');
		$where=array('token'=>$this->token);
		$thisCompany=$company_model->where($where)->order('isbranch ASC')->find();
		//branches
		$where['isbranch']=1;
		$companies=$company_model->where($where)->order('taxis ASC')->select();
		//
		$return=array();
		//http://restapi.amap.com/v3/staticmap?location=116.48482,39.94858&zoom=10&size=440*280&labels=%E6%9C%9D%E9%98%B3%E5%85%AC%E5%9B%AD,2,0,16,0xFFFFFF,0x008000:116.48482,39.94858&key=ee95e52bf08006f63fd29bcfbcf21df0
		
		//$imgUrl='http://api.map.baidu.com/staticimage?center='.$thisCompany['longitude'].','.$thisCompany['latitude'].'&width=640&height=320&zoom=11&markers='.$thisCompany['longitude'].','.$thisCompany['latitude'].'&markerStyles=l,1&t=gdf.png';
		if (!$this->isamap){
			$imgUrl='http://api.map.baidu.com/staticimage?center='.$thisCompany['longitude'].','.$thisCompany['latitude'];
			$link=C('site_url').'/index.php?g=Wap&m=Company&a=map&token='.$this->token;
		}else {
			$imgUrl=$amap->staticMap($thisCompany['longitude'],$thisCompany['latitude'],$thisCompany['name']);
			$link=$amap->getPointMapLink($thisCompany['longitude'],$thisCompany['latitude'],$thisCompany['name']);
		}
		
		$titleStr=$thisCompany['name'].'地图';
		if ($companies){
			$titleStr='1.'.$titleStr;
		}
		$return[]=array($titleStr,"电话：".$thisCompany['tel']."\r\n地址：".$thisCompany['address']."\r\n回复“开车去”“步行去”或“坐公交”获取详细线路\r\n点击查看详细",$imgUrl,$link);
		
		if ($companies){
			$i=2;
			$sep='';
			foreach ($companies as $thisCompany){
				if (!$this->isamap){
					$imgUrl='http://api.map.baidu.com/staticimage?center='.$thisCompany['longitude'].','.$thisCompany['latitude'].'&width=80&height=80&zoom=11&markers='.$thisCompany['longitude'].','.$thisCompany['latitude'].'&markerStyles=l,'.$i;
					$link=C('site_url').'/index.php?g=Wap&m=Company&a=map&companyid='.$thisCompany['id'].'&token='.$this->token;
				}else {
					$imgUrl=$amap->staticMap($thisCompany['longitude'],$thisCompany['latitude'],$thisCompany['name'],200,200);
					$link=$amap->getPointMapLink($thisCompany['longitude'],$thisCompany['latitude'],$thisCompany['name']);
				}
			    
			    //$imgUrl=$thisCompany['logourl'];
				$return[]=array($i.'.'.$thisCompany['name'].'地图',"电话：".$thisCompany['tel']."\r\n地址：".$thisCompany['address']."\r\n点击查看详细",$imgUrl,$link);
				$i++;
			}
			//使用操作
			$imgUrl=$thisCompany['logourl'];
			$return[]=array('回复“最近的”查看哪一个离你最近，或者回复“开车去+编号”“步行去+编号”或“坐公交+编号”获取详细线路',"电话：".$thisCompany['tel']."\r\n地址：".$thisCompany['address']."\r\n点击查看详细",$imgUrl,$link);
		}
		
		//return array($return,'news');
		return array(serialize($return),'text');
	}
	public function walk($x,$y,$companyid=1){
		//
		$company_model=M('Company');
		$where=array('token'=>$this->token);
		$companies=$company_model->where($where)->order('isbranch ASC,taxis ASC')->select();
		$i=intval($companyid)-1;
		$thisCompany=$companies[$i];
		//
		if (!$this->isamap){
			$rt=json_decode(file_get_contents('http://api.map.baidu.com/direction/v1?region=&mode=walking&origin='.$x.','.$y.'&destination='.$thisCompany['latitude'].','.$thisCompany['longitude'].'&output=json&ak='.$this->apikey),1);

			if (is_array($rt)){
				$return=array();
				//
				//$imgUrl='http://api.map.baidu.com/staticimage?center='.$thisCompany['longitude'].','.$thisCompany['latitude'].'&width=640&height=320&zoom=13&markers='.$thisCompany['longitude'].','.$thisCompany['latitude'];
				$imgUrl='http://api.map.baidu.com/staticimage?center='.$thisCompany['longitude'].','.$thisCompany['latitude'];
				//长度
				$distance=$rt['result']['routes'][0]['distance'];
				if ($distance>1000){
					$distanceStr=(round($distance/1000,2)).'公里';
				}else {
					$distanceStr=$distance.'米';
				}
				//耗时
				$duration=$rt['result']['routes'][0]['duration']/60;
				if ($duration>60){
					$durationStr=intval($duration/100).'小时';
					if ($duration%60>0){
						$durationStr.=($duration%60).'分钟';
					}
				}else {
					$durationStr=intval($duration).'分钟';
				}
				//路书
				$stepStr="";
				$steps=$rt['result']['routes'][0]['steps'];
				if ($steps){
					$i=1;
					foreach ($steps as $s){
						$stepStr.="\r\n".$i.".".str_replace(array('<b>','</b>'),'',$s['instructions']);
						$i++;
					}
				}
				$return[]=array('步行到'.$thisCompany['name'].'行程有'.$distanceStr.',大概需要'.$durationStr,"具体方案：".$stepStr,$imgUrl,C('site_url').'/index.php?g=Wap&m=Company&a=walk&longitude='.$y.'&latitude='.$x.'&companyid='.$thisCompany['id'].'&token='.$this->token);
				return array($return,'news');
			}else {
				return array('没有相应的路书','text');
			}
		}else {
			$imgUrl=$this->amap->staticMap($thisCompany['longitude'],$thisCompany['latitude'],$thisCompany['name']);
			$rt=$this->amap->walking($x.','.$y,$thisCompany['longitude'].','.$thisCompany['latitude']);
			if ($rt['route']){
				//路书
				$stepStr="";
				$steps=$rt['route']['paths'][0];
				$distanceStr=$this->_getDistance($steps['distance']);
				$durationStr=$this->_getTime($steps['duration']);
				if ($steps['steps']){
					$i=1;
					foreach ($steps['steps'] as $s){
						$stepStr.="\r\n".$i.".".str_replace(array('<b>','</b>'),'',$s['instruction']);
						$i++;
					}
				}
				$link=$this->amap->navi($x.','.$y,$thisCompany['longitude'].','.$thisCompany['latitude'],$thisCompany['name'],'walk');
				$return[]=array('步行到'.$thisCompany['name'].'行程有'.$distanceStr.',大概需要'.$durationStr,"具体方案：".$stepStr,$imgUrl,$link);
				return array($return,'news');
			}else {
				return array($rt['info'],'text');
			}
		}
	}
	public function drive($x,$y,$companyindex=1){
		//
		$company_model=M('Company');
		$where=array('token'=>$this->token);
		$companies=$company_model->where($where)->order('isbranch ASC,taxis ASC')->select();
		$i=intval($companyindex)-1;
		$thisCompany=$companies[$i];
		if (!$this->isamap){
			//
			$rt=json_decode(file_get_contents('http://api.map.baidu.com/direction/v1?mode=driving&origin='.$x.','.$y.'&destination='.$thisCompany['latitude'].','.$thisCompany['longitude'].'&origin_region=&destination_region=&output=json&ak='.$this->apikey),1);
			if (is_array($rt)){
				$return=array();
				//
				//$imgUrl='http://api.map.baidu.com/staticimage?center='.$thisCompany['longitude'].','.$thisCompany['latitude'].'&width=640&height=320&zoom=13&markers='.$thisCompany['longitude'].','.$thisCompany['latitude'];
				$imgUrl='http://api.map.baidu.com/staticimage?center='.$thisCompany['longitude'].','.$thisCompany['latitude'];
				//长度
				$distance=$rt['result']['routes'][0]['distance'];
				if ($distance>1000){
					$distanceStr=(round($distance/1000,2)).'公里';
				}else {
					$distanceStr=$distance.'米';
				}
				//耗时
				$duration=$rt['result']['routes'][0]['duration']/60;
				if ($duration>60){
					$durationStr=intval($duration/100).'小时';
					if ($duration%60>0){
						$durationStr.=($duration%60).'分钟';
					}
				}else {
					$durationStr=intval($duration).'分钟';
				}
				//路书
				$stepStr="";
				$steps=$rt['result']['routes'][0]['steps'];
				if ($steps){
					$i=1;
					foreach ($steps as $s){
						$stepStr.="\r\n".$i.".".strip_tags($s['instructions']);
						$i++;
					}
				}

				$return[]=array('开车到'.$thisCompany['name'].'行程有'.$distanceStr.',大概需要'.$durationStr,"具体方案：".$stepStr,$imgUrl,C('site_url').'/index.php?g=Wap&m=Company&a=drive&longitude='.$y.'&latitude='.$x.'&companyid='.$thisCompany['id'].'&token='.$this->token);
				return array($return,'news');
			}else {
				return array('没有相应的路书','text');
			}
		}else {
			$imgUrl=$this->amap->staticMap($thisCompany['longitude'],$thisCompany['latitude'],$thisCompany['name']);
			$rt=$this->amap->driving($x.','.$y,$thisCompany['longitude'].','.$thisCompany['latitude']);
			if ($rt['route']){
				//路书
				$stepStr="";
				$steps=$rt['route']['paths'][0];
				$distanceStr=$this->_getDistance($steps['distance']);
				$durationStr=$this->_getTime($steps['duration']);
				if ($steps['steps']){
					$i=1;
					foreach ($steps['steps'] as $s){
						$stepStr.="\r\n".$i.".".str_replace(array('<b>','</b>'),'',$s['instruction']);
						$i++;
					}
				}
				$link=$this->amap->navi($x.','.$y,$thisCompany['longitude'].','.$thisCompany['latitude'],$thisCompany['name'],'car');
				$return[]=array('开车到'.$thisCompany['name'].'行程有'.$distanceStr.',大概需要'.$durationStr,"具体方案：".$stepStr,$imgUrl,$link);
				return array($return,'news');
			}else {
				return array($rt['info'],'text');
			}
		}
	}
	public function bus($x='',$y='',$companyindex=1){
		//
		$company_model=M('Company');
		$where=array('token'=>$this->token);
		$companies=$company_model->where($where)->order('isbranch ASC,taxis ASC')->select();
		$i=intval($companyindex)-1;
		$thisCompany=$companies[$i];
		if (!$this->isamap){
			//查出起点所在城市
			$ocityArr=json_decode(file_get_contents('http://api.map.baidu.com/geocoder/v2/?ak='.$this->apikey.'&location='.$x.','.$y.'&output=json&pois=0'),1);
			$ocityName=$ocityArr['result']['addressComponent']['city'];
			//查出终点所在城市
			$dcityArr=json_decode(file_get_contents('http://api.map.baidu.com/geocoder/v2/?ak='.$this->apikey.'&location='.$thisCompany['latitude'].','.$thisCompany['longitude'].'&output=json&pois=0'),1);
			$dcityName=$dcityArr['result']['addressComponent']['city'];
			if ($dcityName!=$ocityName){
				return array('起点和终点不在同一城市，不支持公共交通查询','text');
			}
			//
			$url='http://api.map.baidu.com/direction/v1?region='.$ocityName.'&mode=transit&type=2&origin='.$x.','.$y.'&destination='.$thisCompany['latitude'].','.$thisCompany['longitude'].'&output=json&ak='.$this->apikey;
			$rt=json_decode(file_get_contents($url),1);

			if (is_array($rt)){
				$return=array();
				//
				//$imgUrl='http://api.map.baidu.com/staticimage?center='.$thisCompany['longitude'].','.$thisCompany['latitude'].'&width=640&height=320&zoom=13&markers='.$thisCompany['longitude'].','.$thisCompany['latitude'];
				$imgUrl='http://api.map.baidu.com/staticimage?center='.$thisCompany['longitude'].','.$thisCompany['latitude'];
				//路书
				$schemeStr="";
				$schemes=$rt['result']['routes'][0]['scheme'];

				if ($schemes){
					$i=1;
					foreach ($schemes as $s){
						$distance=$this->_getDistance($s['distance']);
						$duration=$this->_getTime($s['duration']);
						$stepStr='';
						if ($s['steps']){
							$sep="";
							foreach ($s['steps'] as $step){
								$stepStr.=$sep.strip_tags($step[0]['stepInstruction']);
								$sep="\r\n";
							}
						}
						$schemeStr.="\r\n".$distance."/".$duration.":\r\n".$stepStr;
						$i++;
					}
				}
				$return[]=array('坐公交到'.$thisCompany['name'].'行程有'.$distance.',大概需要'.$duration,"推荐线路：\r\n".$schemeStr,$imgUrl,C('site_url').'/index.php?g=Wap&m=Company&a=bus&longitude='.$y.'&latitude='.$x.'&companyid='.$thisCompany['id'].'&token='.$this->token);
				return array($return,'news');
			}else {
				return array('没有相应的路书','text');
			}
		}else {
			//
			$imgUrl=$this->amap->staticMap($thisCompany['longitude'],$thisCompany['latitude'],$thisCompany['name']);
			$rt=$this->amap->bus($x.','.$y,$thisCompany['longitude'].','.$thisCompany['latitude']);
			if ($rt['transits']){
				//路书
				$stepStr="";
				$steps=$rt['route']['transits'][0];
				$distanceStr=$this->_getDistance($steps['walking_distance']);
				$durationStr=$this->_getTime($steps['duration']);
				$rt=$steps['segments'][0]['bus']['buslines'];
				if ($rt){
					$i=1;
					foreach ($rt as $s){
						$stepStr.="\r\n".$i.".".str_replace(array('<b>','</b>'),'',$s['name']);
						$i++;
					}
				}
				$link=$this->amap->navi($x.','.$y,$thisCompany['longitude'].','.$thisCompany['latitude'],$thisCompany['name'],'bus');
				$return[]=array('公交到'.$thisCompany['name'].'行程有'.$distanceStr.',大概需要'.$durationStr,"具体方案：".$stepStr,$imgUrl,$link);
				return array($return,'news');
			}else {
				return array($rt['info'],'text');
			}
		}
	}
	public function nearest($lat,$lon){
		$company_model=M('Company');
		$where=array('token'=>$this->token);
		$allcompanies=$company_model->where($where)->order('isbranch ASC,taxis ASC')->select();
		$companies=$company_model->where($where)->order('ACOS(SIN(('.$lat.' * 3.1415) / 180 ) *SIN((latitude * 3.1415) / 180 ) +COS(('.$lat.' * 3.1415) / 180 ) * COS((latitude * 3.1415) / 180 ) *COS(('.$lon.' * 3.1415) / 180 - (longitude * 3.1415) / 180 ) ) * 6380 ASC')->limit(1)->select();
		$nearestCompany=$companies[0];
		$distance=ACOS(SIN(($lat * 3.1415) / 180 ) *SIN(($infot['latitude'] * 3.1415) / 180 ) +COS(($lat * 3.1415) / 180 ) * COS(($infot['latitude'] * 3.1415) / 180 ) *COS(($lon * 3.1415) / 180 - ($infot['longitude'] * 3.1415) / 180 ) ) * 6380;
		$distance=$distance*1000;
		$distanceStr=$this->_getDistance($ldistance);
		$i=1;
		if ($allcompanies){
			foreach ($allcompanies as $ac){
				$i++;
				if ($ac['id']==$nearestCompany['id']){
					$index=$i;
					break;
				}
			}
			if (!$this->isamap){
				$imgUrl='http://api.map.baidu.com/staticimage?center='.$nearestCompany['longitude'].','.$nearestCompany['latitude'];
				$return[]=array('最近的是'.$nearestCompany['name'].'，大约'.$distanceStr,"回复“步行去".$index."”“坐公交".$index."”或“开车去".$index."”获取详细路线图",$imgUrl,C('site_url').'/index.php?g=Wap&m=Company&a=map&companyid='.$nearestCompany['id'].'&token='.$this->token);
			}else {
				$amap=$this->amap;
				$imgUrl=$amap->staticMap($nearestCompany['longitude'],$nearestCompany['latitude'],$nearestCompany['name'],360,200);
				$link=$amap->getPointMapLink($nearestCompany['longitude'],$nearestCompany['latitude'],$nearestCompany['name']);
				$return[]=array('最近的是'.$nearestCompany['name'].'，大约'.$distanceStr,"点击查看导航信息",$imgUrl,$link);
			}
			return array($return,'news');
		}else {
			return array('还没配置公司信息呢','text');
		}
		/*
		if (!$this->isamap){
			//
			$company_model=M('Company');
			$where=array('token'=>$this->token);
			$companies=$company_model->where($where)->order('isbranch ASC,taxis ASC')->select();
			$ldistance=0;
			$nearestCompany=array();
			$i=1;
			$index=0;
			$j=0;
			if ($companies){
				foreach ($companies as $c){
					$furl='http://api.map.baidu.com/direction/v1?region=&mode=driving&origin='.$x.','.$y.'&destination='.$c['latitude'].','.$c['longitude'].'&output=json&ak='.$this->apikey;
					//file_put_contents('s.html',$furl."\r\n".file_get_contents('s.html'));

					$json=file_get_contents($furl);
					$rt=json_decode($json,true);


					if (is_array($rt)){
						//长度
						$distance=$rt['result']['routes'][0]['distance'];
						if ($ldistance==0){
							$nearestCompany=$c;
							$ldistance=$distance;
							$index=1;
						}else {
							if ($distance<$ldistance){
								$nearestCompany=$c;
								$ldistance=$distance;
								$index=$j+1;
							}
						}

					}else {

					}
					$j++;
				}
				//
				$distanceStr=$this->_getDistance($ldistance);
				//$imgUrl='http://api.map.baidu.com/staticimage?center='.$nearestCompany['longitude'].','.$nearestCompany['latitude'].'&width=640&height=320&zoom=13&markers='.$nearestCompany['longitude'].','.$nearestCompany['latitude'];
				$imgUrl='http://api.map.baidu.com/staticimage?center='.$nearestCompany['longitude'].','.$nearestCompany['latitude'];
				$return[]=array('最近的是'.$nearestCompany['name'].'，大约'.$distanceStr,"回复“步行去".$index."”“坐公交".$index."”或“开车去".$index."”获取详细路线图",$imgUrl,C('site_url').'/index.php?g=Wap&m=Company&a=map&companyid='.$nearestCompany['id'].'&token='.$this->token);
				return array($return,'news');
			}else {
				return array('还没配置公司信息呢，您稍等','text');
			}
		}else {
		}
		*/
	}
	public function _getDistance($distance){
		if ($distance>1000){
			$distanceStr=(round($distance/1000,2)).'公里';
		}else {
			$distanceStr=$distance.'米';
		}
		return $distanceStr;
	}
	public function _getTime($duration){
		$duration=$duration/60;
		if ($duration>60){
			$durationStr=intval($duration/100).'小时';
			if ($duration%60>0){
				$durationStr.=($duration%60).'分钟';
			}
		}else {
			$durationStr=intval($duration).'分钟';
		}
		return $durationStr;
	}
}


?>
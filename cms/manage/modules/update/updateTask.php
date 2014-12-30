<?php
bpBase::loadAppClass('manage','manage',0);
class updateTask extends manage {
	function __construct() {
		$this->update_log_db = bpBase::loadModel('update_log_model');
		parent::__construct();
		$this->exitWithoutAccess();
	}
	public function autophoto201307023_d(){
		$taskName='autophoto201307023_d';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$autoclassification_db=bpBase::loadModel('autoclassification_model');
	
		$autos=$autoclassification_db->select('logo!=\'\' AND logo IS NOT NULL','*','','id ASC');
		$count=count($autos);
		$i=intval($_GET['i']);
		
		//
		if ($i<$count){
			$step=10;
			for ($j=0;$j<$step;$j++){
				$num=$i+$j;
				//
				$logoPathDir=ABS_PATH.'autoPhotos'.DIRECTORY_SEPARATOR.'logo'.DIRECTORY_SEPARATOR.$autos[$num]['id'].DIRECTORY_SEPARATOR;
				if (file_exists($logoPathDir.'logo_l.jpg')){
					$logoPath=$logoPathDir.'logo_l.jpg';
				}else{
					$logoPath=$logoPathDir.'logo_m.jpg';
				}
				bpBase::loadSysClass('image');
				if (!file_exists($logoPathDir.'logo_b.jpg')){
					image::zfResize($logoPath,$logoPathDir.'logo_b.jpg',120, 90,1,2,0,0,0);
				}
			}
			$i=$i+$step;
			showMessage($thisTask['des'].':'.$i.'/'.$count,'?m=update&c=updateTask&a='.$taskName.'&i='.$i,0);
		}else{
			$this->_finishTask($taskName);
		}
	}
	/**
	 * 在autophoto表里给每张图片设置g1autoid,g2autoid,g3autoid
	*/
	public function autophoto201307023_c(){
		$taskName='autophoto201307023_c';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$autoclassification_db=bpBase::loadModel('autoclassification_model');
		$autophoto_db=bpBase::loadModel('autophoto_model');
		$where='g1autoid=0';
		$photos=$autophoto_db->select($where,'id,autoid','','id ASC');
		$count=$autophoto_db->count($where);
		$i=intval($_GET['i']);
		
		//
		$autos=array();
		if ($i<$count){
			$step=20;
			for ($j=0;$j<$step;$j++){
				$num=$i+$j;
				//
				if (!$autos[$photos[$num]['autoid']]){
					$thisAuto=$autoclassification_db->getCfByID($photos[$num]['autoid']);
					$autos[$photos[$num]['autoid']]=$thisAuto;
				}else {
					$thisAuto=$autos[$photos[$num]['autoid']];
				}
				$autophoto_db->update(array('g1autoid'=>$thisAuto->g1id,'g2autoid'=>$thisAuto->g2id,'g3autoid'=>$thisAuto->g3id),array('id'=>$photos[$num]['id']));
			}
			$i=$i+$step;
			showMessage($thisTask['des'].':'.$i.'/'.$count,'?m=update&c=updateTask&a='.$taskName.'&i='.$i,0);
		}else{
			$this->_finishTask($taskName);
		}
	}
	public function autophoto20130812(){
		$taskName='autophoto20130812';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		
		//
		$autoclassification_db=bpBase::loadModel('autoclassification_model');
		$autoclassification_db->query('UPDATE auto_autoclassification SET logo=\'logo_s.jpg\' WHERE logo!=\'\' AND logo IS NOT NULL');
		delCache('brandlibrary');
		delCache('pricelibrary');
		delCache('autotypelibrary');
		//
		$this->_finishTask($taskName,$thisTask);
	}
	public function mobileIndex20130809(){
		$taskName='mobileIndex20130809';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$fileTemplatePath=ABS_PATH.'templates'.DIRECTORY_SEPARATOR.AUTO_SKIN.DIRECTORY_SEPARATOR.'index.html';
		copy($fileTemplatePath,$fileTemplatePath.'_bak');
		$code=file_get_contents($fileTemplatePath);
		if (!strExists($code,'r={versions:function(){var u=navigator.userAgent,app=navigato')){
			$jsStr='{literal}<script>var browser={versions:function(){var u=navigator.userAgent,app=navigator.appVersion;return{trident:u.indexOf(\'Trident\')>-1,presto:u.indexOf(\'Presto\')>-1,webKit:u.indexOf(\'AppleWebKit\')>-1,gecko:u.indexOf(\'Gecko\')>-1&&u.indexOf(\'KHTML\')==-1,mobile:!!u.match(/AppleWebKit.*Mobile.*/)||!!u.match(/AppleWebKit/),ios:!!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),android:u.indexOf(\'Android\')>-1||u.indexOf(\'Linux\')>-1,iPhone:u.indexOf(\'iPhone\')>-1||u.indexOf(\'Mac\')>-1,iPad:u.indexOf(\'iPad\')>-1,webApp:u.indexOf(\'Safari\')==-1,QQbrw:u.indexOf(\'MQQBrowser\')>-1,ucLowEnd:u.indexOf(\'UCWEB7.\')>-1,ucSpecial:u.indexOf(\'rv:1.2.3.4\')>-1,ucweb:function(){try{return parseFloat(u.match(/ucweb\d+\.\d+/gi).toString().match(/\d+\.\d+/).toString())>=8.2}catch(e){if(u.indexOf(\'UC\')>-1){return true;}else{return false;}}}(),Symbian:u.indexOf(\'Symbian\')>-1,ucSB:u.indexOf(\'Firefox/1.\')>-1};}()};var _gaq=_gaq||[];(function(win,browser,undefined){var rf=document.referrer;if(rf===""||rf.toLocaleLowerCase().indexOf(".{/literal}{$domainRoot}{literal}")===-1){if(screen==undefined||screen.width<810){if(browser.versions.iPad==true){return;}if(browser.versions.webKit==true||browser.versions.mobile==true||browser.versions.ios==true||browser.versions.iPhone==true||browser.versions.ucweb==true||browser.versions.ucSpecial==true){win.location.href="{/literal}{$mainUrlRoot}/index.php?m=site&c=home&a=indexSelect{literal}";return;}if(browser.versions.Symbian){win.location.href="{/literal}{$mainUrlRoot}/index.php?m=site&c=home&a=indexSelect{literal}";}}}})(window,browser);</script>{/literal}';
			$code=str_replace(array('<body id="body">','<body>'),array('<body id="body">'.$jsStr,'<body>'.$jsStr),$code);
			file_put_contents($fileTemplatePath,$code);
		}
		//top.html
		$tfileTemplatePath=ABS_PATH.'templates'.DIRECTORY_SEPARATOR.AUTO_SKIN.DIRECTORY_SEPARATOR.'top.html';
		copy($tfileTemplatePath,$tfileTemplatePath.'_bak');
		$tcode=file_get_contents($tfileTemplatePath);
		if (strExists($tcode,'<li style="display:none"><a href="#" class="icon01">移动客户端</a></li>')||strExists($tcode,'<div class="fr"><a onclick="this.style.behavior=')){
			$tcode=str_replace(array('<li style="display:none"><a href="#" class="icon01">移动客户端</a></li>','<li style="display:none">|</li>'),array('<li><a href="/index.php?m=site&c=home&a=mobileIndexOnPC" target="_blank" class="icon01">手机版</a></li>','<li>|</li>'),$tcode);
			$tcode=str_replace(array('<div class="fr"><a onclick="this.style.behavior='),array('<div class="fr"><a href="/index.php?m=site&c=home&a=mobileIndexOnPC" target="_blank">手机版</a>&#160;<a onclick="this.style.behavior='),$tcode);
			file_put_contents($tfileTemplatePath,$tcode);
		}
		//
		$this->_finishTask($taskName,$thisTask);
	}
	public function cron20130808(){
		$taskName='cron20130808';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		
		//
		$template_db=bpBase::loadModel('template_model');
		$template1=$template_db->get_one(array('name'=>'移动版首页'));
		$template2=$template_db->get_one(array('name'=>'移动版资讯页'));
		//加入计划任务
		$cron1=array('file'=>'createSinglePage','parmvalue'=>$template1['id'],'cronon'=>1,'cronswitch'=>0,'name'=>'移动首页生成','type'=>'','time'=>120,'addtime'=>1375945951,'flag'=>1375945913,'info'=>'','nextruntime'=>1375953151);
		$cron2=array('file'=>'createSinglePage','parmvalue'=>$template2['id'],'cronon'=>1,'cronswitch'=>0,'name'=>'移动资讯页生成','type'=>'','time'=>120,'addtime'=>1375945961,'flag'=>1375945944,'info'=>'','nextruntime'=>1375953161);
		$cron_db=bpBase::loadModel('cron_model');
		$cron_db->insert($cron1);
		$cron_db->insert($cron2);
		//
		$this->_finishTask($taskName,$thisTask);
	}
	public function mobile(){
		$taskName='mobile';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		$channel_db=bpBase::loadModel('channel_model');
		//
		//推荐品牌栏目
		$channel_db->query("INSERT INTO `moopha_channel` (`name`, `channeltype`, `cindex`, `link`, `externallink`, `des`, `thumb`, `metatitle`, `metakeyword`, `metades`, `thumbwidth`, `thumbheight`, `thumb2width`, `thumb2height`, `thumb3width`, `thumb3height`, `thumb4width`, `thumb4height`, `parentid`, `channeltemplate`, `contenttemplate`, `autotype`, `ex`, `iscity`, `site`, `taxis`, `lastcreate`, `pagesize`, `specialid`, `time`) VALUES
('品牌推荐', 3, 'recommendbrand', '', 0, '', '', '', '', '', 100, 100, 0, 0, 0, 0, 0, 0, 33, 4, 5, '', 0, 0, 1, 0, 1400000000, 30, 0, 1372471611);
");
		//焦点图
		$channel_db->update(array('thumb2width'=>368,'thumb2height'=>284),array('cindex'=>'focus'));
		//
		$this->_finishTask($taskName,$thisTask);
	}
	public function minstoreprice20130802(){
		$taskName='minstoreprice20130802';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$autoclassification_db=bpBase::loadModel('autoclassification_model');
		$autoprice_db=bpBase::loadModel('autoprice_model');
		$autos=$autoclassification_db->select('grade=4 AND status<3','*','','id ASC');
		$count=count($autos);
		$i=intval($_GET['i']);
		
		//
		if ($i<$count){
			$step=5;
			for ($j=0;$j<$step;$j++){
				$num=$i+$j;
				//
				$autoprice_db->updateMinMaxStorePrice($autos[$num]);
				//
			}
			$i=$i+$step;
			showMessage($thisTask['des'].':'.$i.'/'.$count,'?m=update&c=updateTask&a='.$taskName.'&i='.$i,0);
		}else{
			$this->_finishTask($taskName);
		}
	}
	public function autophoto201307023_b(){
		$taskName='autophoto201307023_b';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$autoclassification_db=bpBase::loadModel('autoclassification_model');
	
		$autos=$autoclassification_db->select('logo!=\'\' AND logo IS NOT NULL','*','','id ASC');
		$count=count($autos);
		$i=intval($_GET['i']);
		
		//
		if ($i<$count){
			$step=5;
			for ($j=0;$j<$step;$j++){
				$num=$i+$j;
				//
				$logoPathDir=ABS_PATH.'autoPhotos'.DIRECTORY_SEPARATOR.'logo'.DIRECTORY_SEPARATOR.$autos[$num]['id'].DIRECTORY_SEPARATOR;
				
				bpBase::loadSysClass('image');
				@$imgInfo=getimagesize($logoPathDir.'logo_s.jpg');
				if ($imgInfo[0]>100){
					@copy($logoPathDir.'logo_s.jpg',$logoPathDir.'logo_s.jpg.bak');
					image::zfResize($logoPathDir.'logo_s.jpg',$logoPathDir.'logo_s.jpg',80, 60,1,2,0,0,0);
					echo $autos[$num]['id'].'<br>';
				}
				
					
			}
			$i=$i+$step;
			showMessage($thisTask['des'].':'.$i.'/'.$count,'?m=update&c=updateTask&a='.$taskName.'&i='.$i,0);
		}else{
			$this->_finishTask($taskName);
		}
	}
	public function autophoto201307023_a(){
		$taskName='autophoto201307023_a';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$autoclassification_db=bpBase::loadModel('autoclassification_model');
	
		$autos=$autoclassification_db->select('logo!=\'\' AND logo IS NOT NULL','*','','id ASC');
		$count=count($autos);
		$i=intval($_GET['i']);
		
		//
		if ($i<$count){
			$step=5;
			for ($j=0;$j<$step;$j++){
				$num=$i+$j;
				//
				$logoPathDir=ABS_PATH.'autoPhotos'.DIRECTORY_SEPARATOR.'logo'.DIRECTORY_SEPARATOR.$autos[$num]['id'].DIRECTORY_SEPARATOR;
				
				/*
				@rename($logoPathDir.str_replace('_s','_l',$autos[$num]['logo']),$logoPathDir.'logo_l.jpg');
				@rename($logoPathDir.str_replace('_s','_m',$autos[$num]['logo']),$logoPathDir.'logo_m.jpg');
				@rename($logoPathDir.str_replace('_s','_t',$autos[$num]['logo']),$logoPathDir.'logo_t.jpg');
				@rename($logoPathDir.str_replace('_s','_s',$autos[$num]['logo']),$logoPathDir.'logo_s.jpg');
				@rename($logoPathDir.str_replace('_s','_b',$autos[$num]['logo']),$logoPathDir.'logo_s.jpg');
				*/
				@copy($logoPathDir.str_replace('_s','_l',$autos[$num]['logo']),$logoPathDir.'logo_l.jpg');
				@copy($logoPathDir.str_replace('_s','_m',$autos[$num]['logo']),$logoPathDir.'logo_m.jpg');
				@copy($logoPathDir.str_replace('_s','_t',$autos[$num]['logo']),$logoPathDir.'logo_t.jpg');
				@copy($logoPathDir.str_replace('_s','_s',$autos[$num]['logo']),$logoPathDir.'logo_s.jpg');
				@copy($logoPathDir.str_replace('_s','_b',$autos[$num]['logo']),$logoPathDir.'logo_s.jpg');
				/*
				$filesInDataDir=scandir($logoPathDir);
				if ($filesInDataDir){
					foreach ($filesInDataDir as $f){
						if (strpos($f,'.jpg')){
							$fileParts=explode('_',$f);
							$newName=$fileParts[0].'_'.$fileParts[2];
							rename($logoPathDir.$f,$logoPathDir.$newName);
						}
					}
				}
				*/
				//
			}
			$i=$i+$step;
			showMessage($thisTask['des'].':'.$i.'/'.$count,'?m=update&c=updateTask&a='.$taskName.'&i='.$i,0);
		}else{
			$this->_finishTask($taskName);
		}
	}
	public function autoconfig20130708(){
		$taskName='autoconfig20130708';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$auto_db=bpBase::loadModel('auto_model');
		$autoconfig_db=bpBase::loadModel('autoconfig_model');
		//$autos=$auto_db->select('','zhuchezhidongleixing,ketiaoxuangua,kongqixuangua,quanjingtianchuang,fangxiangpanhuandang,zuoyitongfeng,autoid','','autoid ASC');
		$count=count($autos);
		$i=intval($_GET['i']);
		
		//
		if ($i<$count){
			$step=5;
			for ($j=0;$j<$step;$j++){
				$num=$i+$j;
				$arr=$autoconfig_db->get_one(array('autoid'=>$autos[$num]['autoid']));
				if ($arr){
					//更新车型表
					$parms=array('zhuchezhidongleixing','ketiaoxuangua','kongqixuangua','quanjingtianchuang','fangxiangpanhuandang','zuoyitongfeng');
					$autoRow=array();
					foreach ($parms as $p){
						if ($p=='zhuchezhidongleixing'){
							if ($arr[$p]=='电子驻车'){
								$rowValue=1;
							}else {
								$rowValue=0;
							}
						}else {
							switch ($arr[$p]){
								default:
									$rowValue=$arr[$p];
									break;
								case '●':
								case '1':
									$rowValue=1;
									break;
								case '-':
								case '':
									$rowValue='0';
									break;
								case '○':
									$rowValue='0';
									break;
							}
						}
						$autoRow[$p]=$rowValue;
					}
					$auto_db->update($autoRow,array('autoid'=>$autos[$num]['autoid']));
					//
				}
			}
			$i=$i+$step;
			showMessage($thisTask['des'].':'.$i.'/'.$count,'?m=update&c=updateTask&a='.$taskName.'&i='.$i,0);
		}else{
			$this->_finishTask($taskName);
		}
	}
	public function pinggushuru20130503(){
		$taskName='pinggushuru20130503';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$info=array (
		'autoid_close' => '0',
		'qz_autoid' => '',
		'biansuxiang_close' => '0',
		'qz_biansuxiang' => '',
		'pailiang_close' => '0',
		'qz_pailiang' => '',
		'peizhi_close' => '0',
		'qz_peizhi' => '',
		'price_close' => '1',
		'qz_price' => '',
		'mileage_close' => '0',
		'qz_mileage' => '',
		'qz_lianxiren' => '',
		'qz_shouji' => '',
		'qz_chezhufuyan' => '',
		'geoid_close' => '1',
		'qz_geoid' => '',
		'dengjizheng_close' => '1',
		'qz_dengjizheng' => '',
		'xingshizheng_close' => '1',
		'qz_xingshizheng' => '',
		'qq_close' => '1',
		'qz_qq' => '',
		'zhaopian_close' => '0',
		'color_close' => '0',
		'shangpairiqi_close' => '0',
		'nianshen_close' => '0',
		'baoxian_close' => '0',
		);
		$arr=var_export($info,1);
		$str="<?php\r\n"."return ".$arr.";"."\r\n?>";
		file_put_contents(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'pingguInputConfig.config.php',$str);
		//
		$this->_finishTask($taskName,$thisTask);
	}
	public function ucarshuru20130502(){
		$taskName='ucarshuru20130502';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$info=array (
		'qz_autoid' => '8',
		'qz_biansuxiang' => '4',
		'qz_pailiang' => '4',
		'qz_peizhi' => '4',
		'qz_price' => '4',
		'qz_mileage' => '4',
		'qz_lianxiren' => '4',
		'qz_shouji' => '5',
		'qz_chezhufuyan' => '5',
		'qz_zhaopian' => '10',
		'geoid_close' => '0',
		'qz_geoid' => '5',
		'color_close' => '0',
		'qz_color' => '5',
		'shangpairiqi_close' => '0',
		'qz_shangpairiqi' => '6',
		'nianshen_close' => '0',
		'qz_nianshen' => '6',
		'baoxian_close' => '0',
		'qz_baoxian' => '6',
		'dengjizheng_close' => '0',
		'qz_dengjizheng' => '7',
		'xingshizheng_close' => '0',
		'qz_xingshizheng' => '7',
		'qq_close' => '0',
		'qz_qq' => '6',
		);
		$arr=var_export($info,1);
		$str="<?php\r\n"."return ".$arr.";"."\r\n?>";
		file_put_contents(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'ucarInputConfig.config.php',$str);
		//
		$this->_finishTask($taskName,$thisTask);
	}
	//重新采集车型配置（暂时不操作）
	public function rec(){
		//1、删除form表内的内容，新插入
		$sql='TRUNCATE auto_autoconfigform';
		//2.新插入
		$sql="INSERT INTO `auto_autoconfigform` (`id`, `name`, `displayname`, `inputtype`, `defaultvalue`, `datatype`, `datalength`, `displaywidth`, `displayheight`, `cat`, `autohome`, `starthtml`, `endhtml`) VALUES
(43, 'fadongji', '发动机', 'Text', '', 'VARCHAR', 50, 120, 20, 0, 4, '', ''),
(44, 'biansuxiang', '变速箱', 'Text', '', 'VARCHAR', 50, 120, 20, 0, 5, '', ''),
(45, 'chang', '长度(mm)', 'Text', '-', 'VARCHAR', 5, 120, 20, 1, 15, '', ''),
(46, 'kuan', '宽度(mm)', 'Text', '', 'INT', 5, 120, 20, 1, 16, '', ''),
(47, 'gao', '高度(mm)', 'Text', '', 'INT', 5, 120, 20, 1, 17, '', ''),
(48, 'menshu', '车门数(个)', 'Text', '4', 'INT', 4, 120, 20, 1, 24, '', ''),
(49, 'zuoweishu', '座位数(个)', 'Text', '5', 'INT', 4, 120, 20, 1, 25, '', ''),
(52, 'zuigaochesu', '最高车速(km/h)', 'Text', '-', 'VARCHAR', 4, 120, 20, 0, 8, '', ''),
(53, 'guanfangjiasu', '官方0-100加速(s)', 'Text', '-', 'VARCHAR', 4, 120, 20, 0, 9, '', ''),
(56, 'shicejiasu', '实测0-100加速(s)', 'Text', '-', 'VARCHAR', 4, 120, 20, 0, 10, '', ''),
(57, 'shicezhidong', '实测100-0制动(m)', 'Text', '-', 'VARCHAR', 4, 120, 20, 0, 11, '', ''),
(58, 'shiceyouhao', '实测油耗(L)', 'Text', '-', 'VARCHAR', 4, 120, 20, 0, 12, '', ''),
(59, 'zhengchezhibao', '整车质保', 'Text', '', 'VARCHAR', 100, 320, 20, 0, 14, '', ''),
(60, 'zhouju', '轴距(mm)', 'Text', '', 'INT', 4, 120, 20, 1, 18, '', ''),
(61, 'qianlunju', '前轮距(mm)', 'Text', '', 'INT', 4, 120, 20, 1, 19, '', ''),
(62, 'houlunju', '后轮距(mm)', 'Text', '', 'INT', 4, 120, 20, 1, 20, '', ''),
(63, 'zuixiaolidijianxi', '最小离地间隙(mm)', 'Text', '', 'INT', 4, 120, 20, 1, 21, '', ''),
(64, 'chezhong', '整备质量(Kg)', 'Text', '', 'INT', 4, 120, 20, 1, 22, '', ''),
(65, 'youxiangrongji', '油箱容积(L)', 'Text', '', 'INT', 4, 120, 20, 1, 26, '', ''),
(66, 'xinglixiangrongji', '行李厢容积(L)', 'Text', '', 'VARCHAR', 60, 120, 20, 1, 27, '', ''),
(67, 'qigangrongji', '汽缸容积（cc）', 'Text', '', 'INT', 4, 120, 20, 2, -1, '', ''),
(68, 'pailiang', '排量(L)', 'Text', '', 'FLOAT', 5, 120, 20, 2, 29, '', ''),
(69, 'gongzuofangshi', '进气形式', 'Text', '', 'VARCHAR', 50, 120, 20, 2, 30, '', ''),
(70, 'qigangpailiefangshi', '气缸排列形式', 'Text', '', 'VARCHAR', 50, 120, 20, 2, 31, '', ''),
(71, 'qigangshu', '气缸数(个)', 'Text', '', 'INT', 4, 120, 20, 2, 32, '', ''),
(72, 'meigangqimenshu', '每缸气门数(个)', 'Text', '', 'INT', 4, 120, 20, 2, 33, '', ''),
(73, 'yasuobi', '压缩比', 'Text', '', 'FLOAT', 6, 120, 20, 2, 34, '', ''),
(74, 'qimenjiegou', '配气机构', 'Text', '', 'VARCHAR', 50, 120, 20, 2, 35, '', ''),
(75, 'gangjing', '缸径', 'Text', '', 'FLOAT', 6, 120, 20, 2, 36, '', ''),
(76, 'chongcheng', '冲程', 'Text', '', 'FLOAT', 6, 120, 20, 2, 37, '', ''),
(77, 'zuidamali', '最大马力(Ps)', 'Text', '', 'INT', 4, 120, 20, 2, 38, '', ''),
(78, 'zuidagonglv', '最大功率(kW)', 'Text', '', 'INT', 4, 120, 20, 2, 39, '', ''),
(79, 'zuidagonglvzhuansu', '最大功率转速(rpm)', 'Text', '', 'VARCHAR', 50, 120, 20, 2, 40, '', ''),
(80, 'zuidaniuju', '最大扭矩(N·m)', 'Text', '', 'INT', 4, 120, 20, 2, 41, '', ''),
(81, 'zuidaniujuzhuansu', '最大扭矩转速(rpm)', 'Text', '', 'VARCHAR', 50, 120, 20, 2, 42, '', ''),
(82, 'fadongjiteyoujishu', '发动机特有技术', 'Text', '', 'VARCHAR', 100, 320, 20, 2, 43, '', ''),
(83, 'ranyou', '燃料形式', 'Text', '', 'VARCHAR', 50, 120, 20, 2, 44, '', ''),
(84, 'ranyoubiaohao', '燃油标号', 'Text', '', 'VARCHAR', 50, 120, 20, 2, 45, '', ''),
(85, 'gongyoufangshi', '供油方式', 'Text', '', 'VARCHAR', 50, 120, 20, 2, 46, '', ''),
(86, 'ganggaicailiao', '缸盖材料', 'Text', '', 'VARCHAR', 50, 120, 20, 2, 47, '', ''),
(87, 'gangticailiao', '缸体材料', 'Text', '', 'VARCHAR', 50, 120, 20, 2, 48, '', ''),
(88, 'huanbaobiaozhun', '环保标准', 'Text', '', 'VARCHAR', 50, 120, 20, 2, 49, '', ''),
(89, 'dangweigeshu', '挡位个数', 'Text', '', 'VARCHAR', 50, 120, 20, 3, 51, '', ''),
(90, 'biansuxiangleixing', '变速箱类型', 'Text', '', 'VARCHAR', 50, 120, 20, 3, 52, '', ''),
(91, 'qudongfangshi', '驱动方式', 'Text', '', 'VARCHAR', 500, 120, 20, 4, 53, '', ''),
(92, 'qianxuangualeixing', '前悬挂类型', 'Text', '', 'VARCHAR', 500, 120, 20, 4, 54, '', ''),
(93, 'houxuangualeixing', '后悬挂类型', 'Text', '', 'VARCHAR', 500, 120, 20, 4, 55, '', ''),
(94, 'zhulileixing', '助力类型', 'Text', '', 'VARCHAR', 500, 120, 20, 4, 56, '', ''),
(95, 'dipanjiegou', '车体结构', 'Text', '', 'VARCHAR', 500, 120, 20, 4, 57, '', ''),
(96, 'qianzhidongqileixing', '前制动器类型', 'Text', '', 'VARCHAR', 500, 120, 20, 5, 58, '', ''),
(97, 'houzhidongleixing', '后制动器类型', 'Text', '', 'VARCHAR', 500, 120, 20, 5, 59, '', ''),
(98, 'zhuchezhidongleixing', '驻车制动类型', 'Text', '', 'VARCHAR', 500, 120, 20, 5, 60, '', ''),
(99, 'qianluntaiguige', '前轮胎规格', 'Text', '', 'VARCHAR', 100, 120, 20, 5, 61, '', ''),
(100, 'houluntaiguige', '后轮胎规格', 'Text', '', 'VARCHAR', 100, 120, 20, 5, 62, '', ''),
(101, 'beitai', '备胎规格', 'Text', '', 'VARCHAR', 100, 120, 20, 5, 63, '', ''),
(102, 'jiashizuoanquanqinang', '驾驶座安全气囊', 'Radio', '', 'TINYINT', 1, 120, 20, 6, 64, '', ''),
(103, 'fujiashianquanqinang', '副驾驶安全气囊', 'Radio', '', 'TINYINT', 1, 120, 20, 6, 65, '', ''),
(104, 'qianpaiceqinang', '前排侧气囊', 'Radio', '', 'TINYINT', 1, 120, 20, 6, 66, '', ''),
(105, 'houpaiceqinang', '后排侧气囊', 'Radio', '', 'TINYINT', 1, 120, 20, 6, 67, '', ''),
(106, 'qianpaitoubuqinang', '前排头部气囊(气帘)', 'Radio', '', 'TINYINT', 1, 120, 20, 6, 68, '', ''),
(107, 'houpaitoubuqinang', '后排头部气囊(气帘)', 'Radio', '', 'TINYINT', 1, 120, 20, 6, 69, '', ''),
(108, 'xibuqinang', '膝部气囊', 'Radio', '', 'TINYINT', 1, 120, 20, 6, 70, '', ''),
(109, 'anquandaiweijitishi', '安全带未系提示', 'Radio', '', 'TINYINT', 1, 120, 20, 6, 73, '', ''),
(110, 'fadongjidianzifangdao', '发动机电子防盗', 'Radio', '', 'TINYINT', 1, 120, 20, 6, 76, '', ''),
(111, 'cheneizhongkongsuo', '车内中控锁', 'Radio', '', 'TINYINT', 1, 120, 20, 6, 77, '', ''),
(112, 'yaokongyaoshi', '遥控钥匙', 'Radio', '', 'TINYINT', 1, 120, 20, 6, 78, '', ''),
(113, 'wuyaoshiqidongxitong', '无钥匙启动系统', 'Radio', '', 'TINYINT', 1, 120, 20, 6, 79, '', ''),
(114, 'absfangbaosi', 'ABS防抱死', 'Radio', '', 'TINYINT', 1, 120, 20, 7, 80, '', ''),
(115, 'zhidonglifenpei', '制动力分配(EBD/CBC等)', 'Radio', '', 'TINYINT', 1, 120, 20, 7, 81, '', ''),
(116, 'shachefuzhu', '刹车辅助(EBA/BAS/BA等)', 'Radio', '', 'TINYINT', 1, 120, 20, 7, 82, '', ''),
(117, 'qianyinlikongzhi', '牵引力控制(ASR/TCS/TRC等)', 'Radio', '', 'TINYINT', 1, 120, 20, 7, 83, '', ''),
(118, 'cheshenwendingkongzhi', '车身稳定控制(ESP/DSC/VSC等)', 'Radio', '', 'TINYINT', 1, 120, 20, 7, 84, '', ''),
(119, 'zidongzhucezhidongxitong', '自动驻车/上坡辅助', 'Radio', '', 'TINYINT', 1, 120, 20, 7, 85, '', ''),
(120, 'doupohuanjiang', '陡坡缓降', 'Radio', '', 'TINYINT', 1, 120, 20, 7, 86, '', ''),
(121, 'ketiaoxuangua', '可变悬挂', 'Radio', '', 'TINYINT', 1, 120, 20, 7, 87, '', ''),
(122, 'kongqixuangua', '空气悬挂', 'Radio', '', 'TINYINT', 1, 120, 20, 7, 88, '', ''),
(123, 'taiyajiancezhuangzhi', '胎压监测装置', 'Radio', '', 'TINYINT', 1, 120, 20, 6, 71, '', ''),
(124, 'lingtaiyajixuxingshi', '零胎压继续行驶', 'Radio', '', 'TINYINT', 1, 120, 20, 6, 72, '', ''),
(125, 'zhudongzhuanxiangxitong', '整体主动转向系统', 'Radio', '', 'TINYINT', 1, 120, 20, 15, 179, '', ''),
(126, 'diandongtianchuang', '电动天窗', 'Radio', '', 'TINYINT', 1, 120, 20, 8, 90, '', ''),
(127, 'quanjingtianchuang', '全景天窗', 'Radio', '', 'TINYINT', 1, 120, 20, 8, 91, '', ''),
(130, 'yundongtaojian', '运动外观套件', 'Radio', '', 'TINYINT', 1, 120, 20, 8, 92, '', ''),
(131, 'lvhejinlungu', '铝合金轮毂', 'Radio', '', 'TINYINT', 1, 120, 20, 8, 93, '', ''),
(132, 'zhenpifangxiangpan', '真皮方向盘', 'Radio', '', 'TINYINT', 1, 120, 20, 9, 95, '', ''),
(133, 'fangxiangpanshangxiatiaojie', '方向盘上下调节', 'Radio', '', 'TINYINT', 1, 120, 20, 9, 96, '', ''),
(134, 'fangxiangpanqianhoutiaojie', '方向盘前后调节', 'Radio', '', 'TINYINT', 1, 120, 20, 9, 97, '', ''),
(135, 'duogongnengfangxiangpan', '多功能方向盘', 'Radio', '', 'TINYINT', 1, 120, 20, 9, 99, '', ''),
(136, 'fangxiangpanhuandang', '方向盘换挡', 'Radio', '', 'TINYINT', 1, 120, 20, 9, 100, '', ''),
(137, 'dingsuxunhang', '定速巡航', 'Radio', '', 'TINYINT', 1, 120, 20, 9, 101, '', ''),
(138, 'bochefuzhu', '泊车辅助', 'Radio', '', 'TINYINT', 1, 120, 20, 9, 102, '', ''),
(139, 'daocheshipinyingxiang', '倒车视频影像', 'Radio', '', 'TINYINT', 1, 120, 20, 9, 103, '', ''),
(140, 'xingchediannaoxianshiping', '行车电脑显示屏', 'Radio', '', 'TINYINT', 1, 120, 20, 9, 104, '', ''),
(141, 'hudtaitoushuzixianshi', 'HUD抬头数字显示', 'Radio', '', 'TINYINT', 1, 120, 20, 9, 105, '', ''),
(143, 'zhenpizuoyi', '真皮/仿皮座椅', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 106, '', ''),
(144, 'yundongzuoyi', '运动座椅', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 107, '', ''),
(145, 'zuoyigaoditiaojie', '座椅高低调节', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 108, '', ''),
(146, 'yaobuzhichengtiaojie', '腰部支撑调节', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 109, '', ''),
(147, 'qianpaizuoyidiandongtiaojie', '前排座椅电动调节', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 111, '', ''),
(148, 'houpaizuoyishoudongtiaojie', '第二排座椅移动', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 113, '', ''),
(149, 'houpaizuoyidiandongtiaojie', '后排座椅电动调节', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 114, '', ''),
(150, 'diandongzuoyijiyi', '电动座椅记忆', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 115, '', ''),
(151, 'qianpaizuoyijiare', '前排座椅加热', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 116, '', ''),
(152, 'houpaizuoyijiare', '后排座椅加热', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 117, '', ''),
(153, 'zuoyitongfeng', '座椅通风', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 118, '', ''),
(154, 'zuoyianmo', '座椅按摩', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 119, '', ''),
(155, 'houpaizuoyizhengtifangdao', '后排座椅整体放倒', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 120, '', ''),
(156, 'houpaizuoyibilifangdao', '后排座椅比例放倒', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 121, '', ''),
(157, 'disanpaizuoyi', '第三排座椅', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 122, '', ''),
(158, 'qianzuozhongyangfushou', '前座中央扶手', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 123, '', ''),
(159, 'houzuozhongyangfushou', '后座中央扶手', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 124, '', ''),
(161, 'houpaibeijia', '后排杯架', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 125, '', ''),
(162, 'diandonghoubeixiang', '电动后备箱', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 126, '', ''),
(163, 'gpsdaohangxitong', 'GPS导航系统', 'Radio', '', 'TINYINT', 1, 120, 20, 11, 127, '', ''),
(164, 'zhongkongtaicaisedaping', '中控台彩色大屏', 'Radio', '', 'TINYINT', 1, 120, 20, 11, 129, '', ''),
(165, 'renjijiaohuxitong', '人机交互系统', 'Radio', '', 'TINYINT', 1, 120, 20, 11, 130, '', ''),
(166, 'neizhiyingpan', '内置硬盘', 'Radio', '', 'TINYINT', 1, 120, 20, 11, 131, '', ''),
(167, 'lanyaxitong', '蓝牙/车载电话', 'Radio', '', 'TINYINT', 1, 120, 20, 11, 132, '', ''),
(168, 'chezaidianshi', '车载电视', 'Radio', '', 'TINYINT', 1, 120, 20, 11, 133, '', ''),
(170, 'houpaiyejingping', '后排液晶屏', 'Radio', '', 'TINYINT', 1, 120, 20, 11, 134, '', ''),
(171, 'waijieyinyuanjiekou', '外接音源接口(AUX/USB/iPod等)', 'Radio', '', 'TINYINT', 1, 120, 20, 11, 135, '', ''),
(172, 'cdzhichi', 'CD支持MP3/WMA', 'Radio', '', 'TINYINT', 1, 120, 20, 11, 136, '', ''),
(173, 'dandiecd', '单碟CD', 'Radio', '', 'TINYINT', 1, 120, 20, 11, -1, '', ''),
(174, 'duodiecdxitong', '多碟CD系统', 'Radio', '', 'TINYINT', 1, 120, 20, 11, -1, '', ''),
(175, 'dandiedvd', '单碟DVD', 'Radio', '', 'TINYINT', 1, 120, 20, 11, -1, '', ''),
(176, 'duodiedvdxitong', '多碟DVD系统', 'Radio', '', 'TINYINT', 1, 120, 20, 11, -1, '', ''),
(177, 'ersanlaba', '2-3喇叭扬声器系统', 'Radio', '', 'TINYINT', 1, 120, 20, 11, -1, '', ''),
(178, 'siwulaba', '4-5喇叭扬声器系统', 'Radio', '', 'TINYINT', 1, 120, 20, 11, -1, '', ''),
(179, 'liuqilaba', '6-7喇叭扬声器系统', 'Radio', '', 'TINYINT', 1, 120, 20, 11, -1, '', ''),
(180, 'balaba', '≥8喇叭扬声器系统', 'Radio', '', 'TINYINT', 1, 120, 20, 11, -1, '', ''),
(181, 'shanqidadeng', '氙气大灯', 'Radio', '', 'TINYINT', 1, 120, 20, 12, 146, '', ''),
(182, 'rijianxingchedeng', '日间行车灯', 'Radio', '', 'TINYINT', 1, 120, 20, 12, 148, '', ''),
(183, 'zidongtoudeng', '自动头灯', 'Radio', '', 'TINYINT', 1, 120, 20, 12, 149, '', ''),
(184, 'zhuanxiangtoudeng', '转向头灯(辅助灯)', 'Radio', '', 'TINYINT', 1, 120, 20, 12, 150, '', ''),
(185, 'qianwudeng', '前雾灯', 'Radio', '', 'TINYINT', 1, 120, 20, 12, 151, '', ''),
(187, 'dadenggaoduketiao', '大灯高度可调', 'Radio', '', 'TINYINT', 1, 120, 20, 12, 152, '', ''),
(188, 'dadengqingxizhuangzhi', '大灯清洗装置', 'Radio', '', 'TINYINT', 1, 120, 20, 12, 153, '', ''),
(189, 'qiandiandongchechuang', '前电动车窗', 'Radio', '', 'TINYINT', 1, 120, 20, 13, 155, '', ''),
(190, 'houdiandongchechuang', '后电动车窗', 'Radio', '', 'TINYINT', 1, 120, 20, 13, 156, '', ''),
(191, 'chechuangfangjiashou', '车窗防夹手功能', 'Radio', '', 'TINYINT', 1, 120, 20, 13, 157, '', ''),
(192, 'houshijingdiandongtiaojie', '后视镜电动调节', 'Radio', '', 'TINYINT', 1, 120, 20, 13, 159, '', ''),
(193, 'houshijingjiare', '后视镜加热', 'Radio', '', 'TINYINT', 1, 120, 20, 13, 160, '', ''),
(194, 'houshijingfangxuanmu', '后视镜自动防眩目', 'Radio', '', 'TINYINT', 1, 120, 20, 13, 161, '', ''),
(195, 'houshijingzhedie', '后视镜电动折叠', 'Radio', '', 'TINYINT', 1, 120, 20, 13, 162, '', ''),
(196, 'houfengdangzheyanglian', '后风挡遮阳帘', 'Radio', '', 'TINYINT', 1, 120, 20, 13, 164, '', ''),
(197, 'houpaicezheyanglian', '后排侧遮阳帘', 'Radio', '', 'TINYINT', 1, 120, 20, 13, 165, '', ''),
(198, 'zheyangbanhuazhuangjing', '遮阳板化妆镜', 'Radio', '', 'TINYINT', 1, 120, 20, 13, 166, '', ''),
(199, 'gangyingyushua', '感应雨刷', 'Radio', '', 'TINYINT', 1, 120, 20, 13, 168, '', ''),
(200, 'shoudongkongtiao', '手动空调', 'Radio', '', 'TINYINT', 1, 120, 20, 14, -1, '', ''),
(201, 'zidongkongtiao', '自动空调', 'Radio', '', 'TINYINT', 1, 120, 20, 14, -1, '', ''),
(202, 'houzuochufengkou', '后座出风口', 'Radio', '', 'TINYINT', 1, 120, 20, 14, 172, '', ''),
(203, 'wendufengqukongzhi', '温度分区控制', 'Radio', '', 'TINYINT', 1, 120, 20, 14, 173, '', ''),
(204, 'kongqitiaojie', '空气调节/花粉过滤', 'Radio', '', 'TINYINT', 1, 120, 20, 14, 174, '', ''),
(205, 'chezaibingxiang', '车载冰箱', 'Radio', '', 'TINYINT', 1, 120, 20, 14, 175, '', ''),
(206, 'zidongpocheruwei', '自动泊车入位', 'Radio', '', 'TINYINT', 1, 120, 20, 15, 176, '', ''),
(207, 'bingxianfuzhu', '并线辅助', 'Radio', '', 'TINYINT', 1, 120, 20, 15, 177, '', ''),
(208, 'yeshixitong', '夜视系统', 'Radio', '', 'TINYINT', 1, 120, 20, 15, 180, '', ''),
(209, 'zishiyingxunhang', '自适应巡航', 'Radio', '', 'TINYINT', 1, 120, 20, 15, 182, '', ''),
(210, 'quanjingshexiangtou', '全景摄像头', 'Radio', '', 'TINYINT', 1, 120, 20, 15, 183, '', ''),
(211, 'chetijiegou', '车身结构', 'Text', '', 'VARCHAR', 60, 120, 20, 0, 7, '', ''),
(212, 'chexingmingcheng', '车型名称', 'Text', '', 'VARCHAR', 60, 120, 20, 0, 0, '', ''),
(213, 'cszdj', '厂商指导价(元)', 'Text', '-', 'VARCHAR', 8, 120, 20, 0, 1, '', ''),
(214, 'pinpai', '厂商', 'Text', '', 'VARCHAR', 50, 120, 20, 0, 2, '', ''),
(215, 'jibie', '级别', 'Text', '', 'VARCHAR', 20, 120, 20, 0, 3, '', ''),
(216, 'ckg', '长×宽×高(mm)', 'Text', '', 'VARCHAR', 60, 120, 20, 0, 6, '', ''),
(217, 'cheshenjiegou', '车身结构_', 'Text', '', 'VARCHAR', 20, 120, 20, 1, -1, '', ''),
(218, 'jianjie', '简称', 'Text', '', 'VARCHAR', 60, 120, 20, 3, 50, '', ''),
(219, 'ddxhm', '电动吸合门', 'Radio', '', 'TINYINT', 1, 120, 20, 8, 94, '', ''),
(220, 'fxpddtj', '方向盘电动调节', 'Radio', '', 'TINYINT', 1, 120, 20, 9, 98, '', ''),
(221, 'dwhdfw', '定位互动服务', 'Radio', '', 'TINYINT', 1, 120, 20, 11, 128, '', ''),
(222, 'xnddcd', '虚拟多碟CD', 'Radio', '', 'TINYINT', 1, 120, 20, 11, -1, '', ''),
(223, 'cnfwd', '车内氛围灯', 'Radio', '', 'TINYINT', 1, 120, 20, 12, 154, '', ''),
(224, 'fzwx', '防紫外线/隔热玻璃', 'Radio', '', 'TINYINT', 1, 120, 20, 13, 158, '', ''),
(225, 'hys', '后雨刷', 'Radio', '', 'TINYINT', 1, 120, 20, 13, 167, '', ''),
(226, 'hpdlkt', '后排独立空调', 'Radio', '', 'TINYINT', 1, 120, 20, 14, 171, '', ''),
(227, 'zdsc', '主动刹车/主动安全系统', 'Radio', '', 'TINYINT', 1, 120, 20, 15, 178, '', ''),
(228, 'ztzdzx', '整体主动转向系统_', 'Radio', '', 'TINYINT', 1, 120, 20, 0, -1, '', ''),
(229, 'zkyjfpxs', '中控液晶屏分屏显示', 'Radio', '', 'TINYINT', 1, 120, 20, 15, 181, '', ''),
(230, 'jbzctj', '肩部支撑调节', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 110, '', ''),
(231, 'gxbyh', '工信部综合油耗(L)', 'Text', '', 'VARCHAR', 10, 120, 20, 0, 13, '', ''),
(232, 'fdjxh', '发动机型号', 'Text', '', 'VARCHAR', 40, 120, 20, 2, 28, '', ''),
(233, 'erpaikaobei', '第二排靠背角度调节', 'Radio', '', 'TINYINT', 1, 120, 20, 10, 112, '', ''),
(234, 'hsjjy', '后视镜记忆', 'Text', '', 'VARCHAR', 1, 120, 20, 13, 163, '', ''),
(235, 'kbzxb', '可变转向比', 'Text', '-', 'VARCHAR', 1, 120, 20, 7, 89, '', ''),
(236, 'leddeng', 'LED大灯', 'Text', '', 'VARCHAR', 1, 120, 20, 12, 147, '', ''),
(237, 'isofixjiekou', 'ISO FIX儿童座椅接口', 'Text', '-', 'VARCHAR', 1, 120, 20, 6, 74, '', ''),
(238, 'latchjiekou', 'LATCH座椅接口(兼容ISO FIX)', 'Text', '', 'VARCHAR', 1, 120, 20, 6, -1, '', ''),
(239, 'hpmkqfs', '后排车门开启方式', 'Text', '', 'VARCHAR', 20, 120, 20, 1, -1, '', ''),
(240, 'hxcc', '货箱尺寸(mm)', 'Text', '', 'VARCHAR', 25, 120, 20, 1, -1, '', ''),
(241, 'zdzhzl', '最大载重质量(kg)', 'Text', '', 'VARCHAR', 10, 120, 20, 1, -1, '', ''),
(242, 'wuyaoshijinruxitong', '无钥匙进入系统', 'Text', '-', 'VARCHAR', 4, 120, 20, 6, 79, '', ''),
(243, 'qianqiaoxianhuachasuqi', '前桥限滑差速器/差速锁', 'Text', '-', 'VARCHAR', 4, 120, 20, 7, 87, '', ''),
(244, 'zycsqszgn', '中央差速器锁止功能', 'Text', '-', 'VARCHAR', 4, 120, 20, 7, 87, '', ''),
(245, 'hqxhcsq', '后桥限滑差速器/差速锁', 'Text', '-', 'VARCHAR', 4, 120, 20, 7, 87, '', ''),
(246, 'fxpjr', '方向盘加热', 'Text', '-', 'VARCHAR', 4, 120, 20, 9, 100, '', ''),
(247, 'dmtxt', '多媒体系统', 'Text', '', 'VARCHAR', 40, 120, 20, 11, 136, '', ''),
(248, 'ysqsl', '扬声器数量', 'Text', '-', 'VARCHAR', 40, 120, 20, 11, 136, '', ''),
(249, 'houpaiceyinsiboli', '后排侧隐私玻璃', 'Text', '-', 'VARCHAR', 4, 120, 20, 13, 156, '', ''),
(250, 'ktkzfs', '空调控制方式', 'Text', '', 'VARCHAR', 20, 120, 20, 14, 170, '', ''),
(251, 'fdjqtjs', '发动机启停技术', 'Text', '-', 'VARCHAR', 4, 120, 20, 15, 179, '', ''),
(252, 'cdplyjxt', '车道偏离预警系统', 'Text', '-', 'VARCHAR', 4, 120, 20, 15, 179, '', '')";
		//3.auto_autoconfig表里新增列
		$sql="ALTER TABLE `auto_autoconfig` ADD `wuyaoshijinruxitong` varchar(4) NOT NULL DEFAULT '-' AFTER `zdzhzl`,
  ADD `qianqiaoxianhuachasuqi` varchar(4) DEFAULT '-' AFTER `zdzhzl`,
  ADD `zycsqszgn` varchar(4) DEFAULT '-' AFTER `zdzhzl`,
  ADD `hqxhcsq` varchar(4) DEFAULT '-' AFTER `zdzhzl`,
  ADD `fxpjr` varchar(4) DEFAULT '-' AFTER `zdzhzl`,
  ADD `dmtxt` varchar(40) DEFAULT '' AFTER `zdzhzl`,
  ADD `ysqsl` varchar(40) DEFAULT '-' AFTER `zdzhzl`,
  ADD `houpaiceyinsiboli` varchar(4) DEFAULT '-' AFTER `zdzhzl`,
  ADD `ktkzfs` varchar(20) DEFAULT '' AFTER `zdzhzl`,
  ADD `fdjqtjs` varchar(4) DEFAULT '-' AFTER `zdzhzl`,
  ADD `cdplyjxt` varchar(4) DEFAULT '-' AFTER `zdzhzl`";
	}
	//删除内容为ex的关联auto_content
	public function deleteExRelateArticle20130416(){
		$taskName='deleteExRelateArticle20130416';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$content_db=bpBase::loadModel('content_model');
		$article_db=bpBase::loadModel('article_model');
		$articles=$article_db->select(array('ex'=>1),'id');
		$articleids=array();
		if ($articles){
			foreach ($articles as $a){
				array_push($articleids,$a['id']);
			}
		}
		$content_db->delete(to_sqls($articleids,'','contentid'));
		//
		$this->_finishTask($taskName,$thisTask);
	}
	public function specialConfig20130414(){
		$taskName='specialConfig20130414';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$info=array (
		'folder' => 'zhuanti',
		'urlFormate' => 'http://{domainName}/{folder}/{catIndex}/{specialIndex}',
		);
		$arr=var_export($info,1);
		$str="<?php\r\n"."return ".$arr.";"."\r\n?>";
		file_put_contents(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'special.config.php',$str);
		//
		$this->_finishTask($taskName,$thisTask);
	}
	//处理suv子类型信息
	public function suvSubTypes20130407(){
		$taskName='suvSubTypes20130407';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		include('suvSubTypes.php');
		$auto_db=bpBase::loadModel('autoclassification_model');
		$auto_db2=bpBase::loadModel('auto_model');
		$count=count($typeAutos);
		$i=intval($_GET['i']);
		$suvSubTypeIDs=array(161,162,163,164,165);
		if ($i<$count){
			foreach ($typeAutos[$suvSubTypeIDs[$i]] as $auto){
				$thisSerie=$auto_db->get_one(array('autohome_id'=>intval($auto['autohome_id']),'grade'=>3));
				if($thisSerie['id']){
					$auto_db->update(array('subtype'=>$suvSubTypeIDs[$i],'type'=>9),'parentid='.$thisSerie['id'].' OR id='.$thisSerie['id']);
					$auto_db2->update(array('subtype'=>$suvSubTypeIDs[$i],'type'=>9),'g3id='.$thisSerie['id']);
				}
			}
			$i++;
			showMessage($thisTask['des'].':'.$i.'/'.$count,'?m=update&c=updateTask&a='.$taskName.'&i='.$i,0);
		}else{
			$this->_finishTask($taskName);
		}
	}
	//转换团购成员信息
	public function convertTuangouMember20130321(){
		$taskName='convertTuangouMember20130321';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//start
		$groupbuying_db = bpBase::loadModel('groupbuying_model');
		$auto_db=bpBase::loadModel('autoclassification_model');
		$gbm_db=bpBase::loadModel('groupbuying_member_model');
		$members=$gbm_db->select('','*','','time ASC');
		$count=count($members);
		$i=intval($_GET['i']);
		if ($i<$count){
			$info=$members[$i];
			if ($info['chexing']){
				$thisAuto=$auto_db->getCfByID($info['chexing']);
			}else {
				$thisTuangou=$groupbuying_db->get(intval($info['groupbuying_id']));
				$thisAuto=$auto_db->getCfByID($thisTuangou['autoid']);
			}
			if ($thisAuto){
				if ($thisAuto->grade==3){
					$info['brandid']=$thisAuto->g1id;
					$info['companyid']=$thisAuto->g2id;
					$info['serieid']=$thisAuto->id;
					$info['autoname']='';
					$info['seriename']=$thisAuto->name;
				}elseif ($thisAuto->grade==4){
					$parentAuto=$auto_db->getCfByID($thisAuto->parentid);
					$info['brandid']=$thisAuto->g1id;
					$info['companyid']=$thisAuto->g2id;
					$info['serieid']=$thisAuto->parentid;
					$info['autoname']=$thisAuto->name;
					$info['seriename']=$parentAuto->name;
				}
			}
			$gbm_db->update($info,array('id'=>$info['id']));
			$tuangou_count_db=bpBase::loadModel('tuangou_count_model');
			$tuangou_count_db->statisticGroupBuyingCount($thisAuto,$info['time']);
			$i++;
			showMessage($thisTask['des'].':'.$i.'/'.$count,'?m=update&c=updateTask&a='.$taskName.'&i='.$i,0);
		}else{
			$this->_finishTask($taskName);
		}
	}
	public function convertWatermarkConfig20130227(){
		$taskName='convertWatermarkConfig20130227';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$info=array();
		if (file_exists(ABS_PATH.'constant'.DIRECTORY_SEPARATOR.'watermark.config.php')){
			$info['waterMarkText']=WATERMARK_TEXT;
			$info['waterMarkType']=WATERMARK_TYPE;
			$info['useWaterMark']=USE_WATERMARK;
		}else {
			$info['waterMarkText']='';
			$info['waterMarkType']='text';
			$info['useWaterMark']=0;
		}
		$info['leftTop']=1;
		$arr=var_export($info,1);
		$str="<?php\r\n"."return ".$arr.";"."\r\n?>";
		file_put_contents(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'watermark.config.php',$str);
		//
		$this->_finishTask($taskName,$thisTask);
	}
	/**
	 * 车型加补贴信息
	 *
	 */
	public function butieinfo_20130225(){
		$taskName='butieinfo_20130225';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$idstr=file_get_contents(ABS_PATH.MANAGE_DIR.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'update'.DIRECTORY_SEPARATOR.'butie.txt');
		$ids=explode(',',$idstr);
		$auto_db=bpBase::loadModel('autoclassification_model');
		$count=count($ids);
		$i=intval($_GET['i']);
		if ($i<$count){
			if ($ids[$i]>0){
				$thisAuto=$auto_db->get_one(array('autohome_id'=>$ids[$i],'grade'=>4));
				$auto_db->updateRow($thisAuto['id'],array('butie'=>1));
				delCache('c_childCfOf'.$thisAuto['parentid'].'includesale1');
				delCache('c_childCfOf'.$thisAuto['parentid'].'includesale0');
				delCache('autoCf'.$thisAuto['parentid']);
				delCache('autoCf'.$thisAuto['id']);
				//静态页面
				if (loadConfig('site','tohtml')){
					$htmlpage_needrefresh_db=bpBase::loadModel('htmlpage_needrefresh_model');
					if (!function_exists('cache_autoSelectOfStore')){
						include ABS_PATH.MANAGE_DIR.DIRECTORY_SEPARATOR.'caches.php';
					}
					$seriePage=html_g3autoIndex(array('autoid'=>$thisAuto['parentid']));
					$brandPricePage=html_autoPriceList(array('id'=>$thisAuto['g1id']));
					$companyPricePage=html_autoPriceList(array('id'=>$thisAuto['g2id']));
					$seriePricePage=html_autoPriceList(array('id'=>$thisAuto['parentid']));

					$htmlpage_needrefresh_db->insert(array('time'=>SYS_TIME,'url'=>'','pagetype'=>$seriePage['pagetype'],'parmid'=>$seriePage['id']));
					$htmlpage_needrefresh_db->insert(array('time'=>SYS_TIME,'url'=>'','pagetype'=>$brandPricePage['pagetype'],'parmid'=>$brandPricePage['id']));
					$htmlpage_needrefresh_db->insert(array('time'=>SYS_TIME,'url'=>'','pagetype'=>$companyPricePage['pagetype'],'parmid'=>$companyPricePage['id']));
					$htmlpage_needrefresh_db->insert(array('time'=>SYS_TIME,'url'=>'','pagetype'=>$seriePricePage['pagetype'],'parmid'=>$seriePricePage['id']));
				}
				//end
			}
			$i++;
			showMessage($thisTask['des'].':'.$i.'/'.$count,'?m=update&c=updateTask&a='.$taskName.'&i='.$i,0);
		}else{
			$this->_finishTask($taskName);
		}
	}
	/**
	 * 刷新每个车系的最低最高价格、车身结构、排量、变速箱信息
	 *
	 */
	public function refreshSerie20130114(){
		$taskName='refreshSerie20130114';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$g3auto_db=bpBase::loadModel('g3auto_model');
		$series = $g3auto_db->select('','*','','autoid DESC');
		$count=$g3auto_db->count();
		$i=intval($_GET['i']);
		if ($i<$count){
			$auto_db=bpBase::loadModel('autoclassification_model');
			$autoconfig_db=bpBase::loadModel('autoconfig_model');
			//start
			$thisSerie=$auto_db->get_one(array('id'=>$series[$i]['autoid']));
			$autoconfig_db->updateG3autoParms($thisSerie['id']);
			$auto_db->calMinAndMaxPrice($thisSerie['g1id'],$thisSerie['g2id'],$thisSerie['id']);
			//cache
			delCache('brandlibrary');
			delCache('pricelibrary');
			delCache('autotypelibrary');
			//静态页面
			if (loadConfig('site','tohtml')){
				$htmlpage_needrefresh_db=bpBase::loadModel('htmlpage_needrefresh_model');
				if (!function_exists('cache_autoSelectOfStore')){
					include ABS_PATH.MANAGE_DIR.DIRECTORY_SEPARATOR.'caches.php';
				}
				$brandPage=html_brandIndex(array('autoid'=>$thisSerie['g1id']));
				$companyPage=html_brandIndex(array('autoid'=>$thisSerie['g2id']));
				$seriePage=html_g3autoIndex(array('autoid'=>$thisSerie['id']));
				$brandPricePage=html_autoPriceList(array('id'=>$thisSerie['g1id']));
				$companyPricePage=html_autoPriceList(array('id'=>$thisSerie['g2id']));
				$seriePricePage=html_autoPriceList(array('id'=>$thisSerie['id']));

				$htmlpage_needrefresh_db->insert(array('time'=>SYS_TIME,'url'=>'','pagetype'=>$brandPage['pagetype'],'parmid'=>$brandPage['id']));
				$htmlpage_needrefresh_db->insert(array('time'=>SYS_TIME,'url'=>'','pagetype'=>$companyPage['pagetype'],'parmid'=>$companyPage['id']));
				$htmlpage_needrefresh_db->insert(array('time'=>SYS_TIME,'url'=>'','pagetype'=>$seriePage['pagetype'],'parmid'=>$seriePage['id']));
				$htmlpage_needrefresh_db->insert(array('time'=>SYS_TIME,'url'=>'','pagetype'=>$brandPricePage['pagetype'],'parmid'=>$brandPricePage['id']));
				$htmlpage_needrefresh_db->insert(array('time'=>SYS_TIME,'url'=>'','pagetype'=>$companyPricePage['pagetype'],'parmid'=>$companyPricePage['id']));
				$htmlpage_needrefresh_db->insert(array('time'=>SYS_TIME,'url'=>'','pagetype'=>$seriePricePage['pagetype'],'parmid'=>$seriePricePage['id']));
			}
			//end
			$i++;
			showMessage($thisTask['des'].':'.$i.'/'.$count,'?m=update&c=updateTask&a='.$taskName.'&i='.$i,0);
		}else{
			$this->_finishTask($taskName);
		}
	}
	public function handleOrder20121228(){
		$taskName='handleOrder20121228';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$store_order_db=bpBase::loadModel('store_order_model');
		$orders = $store_order_db->select('','*','','id DESC');
		$count=$store_order_db->count();
		$i=intval($_GET['i']);
		if ($i<$count){
			//start
			$thisRow=$orders[$i];
			$auto_db = bpBase::loadModel('autoclassification_model');
			$thisAuto=$auto_db->getCfByID($thisRow['autoid']);
			$parentAuto=$auto_db->getCfByID($thisAuto->parentid);
			$row=array();
			$row['brandid']=$thisAuto->g1id;
			$row['companyid']=$thisAuto->g2id;
			$row['serieid']=$thisAuto->parentid;
			$row['autoname']=$thisAuto->name;
			$row['seriename']=$parentAuto->name;
			if (!$thisRow['geoid']){
				if ($thisRow['storeid']){
					$store_db=bpBase::loadModel('store_model');
					$thisStore=$store_db->getStoreByStoreID($thisRow['storeid']);
					$row['geoid']=$thisStore->cityid;
				}
			}
			$store_order_db->update($row,array('id'=>$thisRow['id']));
			//end
			$i++;
			showMessage($thisTask['des'].':'.$i.'/'.$count,'?m=update&c=updateTask&a='.$taskName.'&i='.$i,0);
		}else{
			$this->_finishTask($taskName);
		}
	}
	public function handleTuangou20121204(){
		$taskName='handleTuangou20121204';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$groupbuying_db = bpBase::loadModel('groupbuying_model');
		$tuangous = $groupbuying_db->select('','*','0,10000','id DESC');
		$count=count($tuangous);
		$i=intval($_GET['i']);
		if ($i<$count){
			//start
			$thisTuangou=$tuangous[$i];
			$auto_db = bpBase::loadModel('autoclassification_model');
			$autoObj=bpBase::loadAppClass('autoObj','auto');
			$thisAuto=$auto_db->getCfByID($thisTuangou['autoid']);
			$thisAuto->mlogo=$autoObj->getLogo($thisAuto->id,$thisAuto->logo,$type='m',$thisAuto->grade);
			//g3autoid,autoids,coverlogo,contentlogo,brandid,serieid,autointro
			$row=array();
			if ($thisAuto->grade==1){
				$row['brandid']=$thisAuto->id;
				$row['autointro']=remove_html_tag($thisAuto->intro);
			}else {
				$row['brandid']=$thisAuto->g1id;
				$row['serieid']=$thisAuto->id;
				$row['g3autoid']=$thisAuto->id;
				$row['autointro']=$thisAuto->advantage;
			}
			$row['coverlogo']=MAIN_URL_ROOT.$thisAuto->mlogo;
			$row['contentlogo']=MAIN_URL_ROOT.$thisAuto->mlogo;
			//
			if ($thisAuto->grade==1){
				$childAutos=$auto_db->select('`grade`=3 AND `status`<3 AND `g1id`='.$thisTuangou['autoid']);
			}else {
				$childAutos=$auto_db->select('`status`<3 AND `parentid`='.$thisTuangou['autoid']);
			}
			if ($childAutos){
				$row['autoids']='';
				$comma='';
				foreach ($childAutos as $ca){
					$row['autoids'].=$comma.$ca['id'];
				}
			}
			$groupbuying_db->update($row,array('id'=>$thisTuangou['id']));
			//end
			$i++;
			showMessage($thisTask['des'].':'.$i.'/'.$count,'?m=update&c=updateTask&a='.$taskName.'&i='.$i,0);
		}else{
			$this->_finishTask($taskName);
		}
	}
	public function autoSelect20121204(){
		$taskName='autoSelect20121204';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$autoObj=bpBase::loadAppClass('autoObj','auto');
		$autoObj->js_autos();
		$autoObj->js_autos(1);
		//
		$this->_finishTask($taskName);
	}
	public function tuangouConfig20121203(){
		$taskName='tuangouConfig20121203';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$configArr=array (
		'newVersion' => '1',
		'severalPlace' => '0',
		'serviceTel' => ' ',
		'QQ' => '',
		'coverLogoWidth' => '240',
		'coverLogoHeight' => '180',
		'contentLogoWidth' => '433',
		'contentLogoHeight' => '343',
		'severalPlaceSinglePage' => '0',
		'geoSelectDomainName' => '',
		'sendSuccessMsg' => '0',
		'successMsgFormate' => '',
		'urlFormate' => '/tuangou',
		'feature' => '专业讲解导购，让您更了解爱车专业讲解导购，让您更了解爱车|人多力量大，价格优惠多|到现场即有礼品赠送|为您提供最优质服务',
		'needKnow' => '<p>
	1. 人多力量大，赶紧报名吧!<br />
	<br />
	2. 每期活动报名截至时间为截止日期当天晚上12点<br />
	<br />
	3.本网将全心全意为车友服务，期待大家的加入!!!</p>
',
'follow' => '<p>
	1、报名：加入看车团</p>
<p>
	填写好车型，完整填写报名信息</p>
<p>
	2、竞价：4S店相互竞价</p>
<p>
	各4S店根据团购人数和团购车型相互竞争，我们选择最低价作为团购价格</p>
<p>
	3、确认：网友确认参加</p>
<p>
	团购前一天确认报名人员是否参加，并通知集合地点和事件</p>
<p>
	4、团购提车</p>
<p>
	网友根据合同时间提车，团购完成</p>
',
'tuangouInputQQ' => '1',
'tuangouInputSex' => '1',
'tuangouInputDrive' => '1',
'tuangouInputYouhui' => '1',
'orderNickName' => '快速订购',
'orderFeature' => '专业讲解导购，让您更了解爱车专业讲解导购，让您更了解爱车|人多力量大，价格优惠多|到现场即有礼品赠送|为您提供最优质服务',
'orderIntro' => '<p>
	1. 快速订购是一种快速汽车团购模式，让您既享受优惠政策，又可以快速提车。<br />
	2. 通过快速订购购车，不仅车价优惠，还可享受保险、车贷、上牌等一条龙优惠，每个环节都为您省钱！<br />
	3. 通过快速订购将帮您免费购车维权，让您买车更放心！</p>
',
'xuecheOpen' => '0',
'xuecheContact' => '',
'xuecheIntro' => '<p>
	学车</p>
',
);
		$arr=var_export($configArr,1);
		$str="<?php\r\n"."return ".$arr.";"."\r\n?>";
		file_put_contents(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'autoTuangou.config.php',$str);
		//
		$this->_finishTask($taskName);
	}
	public function recoveryarticle20121113(){
		$taskName='recoveryarticle20121113';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$content_db=bpBase::loadModel('content_model');
		if (getCache('c_function_contenttimeis0')){
			$contents=unserialize(getCache('c_function_contenttimeis0'));
		}else {
			$contents=$content_db->select(array('time'=>0),'contentid');
			setCache('c_function_contenttimeis0',serialize($contents));
		}
		$count=count($contents);
		$i=intval($_GET['i']);
		if ($i<$count){
			//start
			$article_db=bpBase::loadModel('article_model');
			$thisArticle=$article_db->get_one(array('id'=>$contents[$i]['contentid']));
			$content_db->update(array('time'=>$thisArticle['time']),array('contentid'=>$contents[$i]['contentid']));
			//end
			$i++;
			showMessage($thisTask['des'].':'.$i.'/'.$count,'?m=update&c=updateTask&a='.$taskName.'&i='.$i,0);
		}else{
			//删除缓存
			delCache('c_function_contenttimeis0');
			$this->_finishTask($taskName);
		}
	}
	public function tohtmlConfigConvert(){
		$taskName='tohtmlConfigConvert';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$siteConfig=loadConfig('site');
		include(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'system.php');
		$siteConfig['tohtml']=defined('TO_HTML')?TO_HTML:0;
		$arr=var_export($siteConfig,1);
		$str="<?php\r\n"."return ".$arr.";"."\r\n?>";
		file_put_contents(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'site.config.php',$str);
		//
		$this->_finishTask($taskName,$thisTask);
	}
	public function convertIndexConfig(){
		$taskName='convertIndexConfig';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		$this->_executedCheck($taskName,$thisTask);
		//
		$siteConfig=loadConfig('site');
		$systemConfig=loadConfig('system');
		$configs=array(
		'indexMetaTitle'=>$systemConfig['indexMetaTitle'],
		'indexMetaKeyword'=>$systemConfig['indexMetaKeyword'],
		'indexMetaDes'=>$systemConfig['indexMetaDes'],
		'indexHotCarMethod'=>$siteConfig['indexHotCarMethod'],
		'displayHotCar'=>1,
		'rightRecommendAuto'=>1,
		'ucar'=>1,
		'carRental'=>1,
		);
		$arr=var_export($configs,1);
		$str="<?php\r\n"."return ".$arr.";"."\r\n?>";
		file_put_contents(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'index.config.php',$str);
		//
		$this->_finishTask($taskName,$thisTask);
	}
	public function _executedCheck($taskName,$thisTask=''){
		if ($thisTask==''){
			$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		}
		if ($thisTask['executed']){
			showMessage('程序已经执行过了','?m=update&c=update&a=task');
			exit();
		}
	}
	public function _finishTask($taskName,$thisTask=''){
		if ($thisTask==''){
			$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		}
		$this->update_log_db->update(array('executed'=>1),array('id'=>$thisTask['id']));
		showMessage($thisTask['des'].'执行完成','?m=update&c=update&a=task');
	}
	/**
	 * 经销商报价表中添加了汽车名称和经销商指导价字段，读取汽车表中数据填入该表
	 *
	 */
	public function autoPriceTable(){
		$taskName='autoPriceTable';
		//$this->executedCheck($taskName);
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		//
		$autoprice_db = bpBase::loadModel('autoprice_model');
		$autoprices=$autoprice_db->get_all('DISTINCT(autoid)','','','autoid ASC');
		$count=count($autoprices);
		$i=intval($_GET['i']);
		if ($i<$count){
			//start
			$autoclassification_db = bpBase::loadModel('autoclassification_model');
			$thisAuto=$autoclassification_db->getCfByID($autoprices[$i]['autoid']);
			$parentAuto=$autoclassification_db->getCfByID($thisAuto->parentid);
			$autoObj=bpBase::loadAppClass('autoObj','auto',1);
			$logo=$autoObj->getLogo($parentAuto->id,$parentAuto->logo);
			$autoprice_db->update(array('serieid'=>$parentAuto->id,'seriename'=>$parentAuto->name,'autoname'=>$thisAuto->name,'companyprice'=>$thisAuto->companyprice,'logo'=>$logo),array('autoid'=>$thisAuto->id));
			//end
			$i++;
			showMessage($thisTask['des'].':'.$i.'/'.$count,'?m=update&c=updateTask&a='.$taskName.'&i='.$i,0);
		}else{
			//删除缓存
			@rename(ABS_PATH.'cache',ABS_PATH.'backup'.DIRECTORY_SEPARATOR.'cache_'.time());
			$this->_finishTask($taskName);
		}
	}
	public function autoPriceTables(){
		$taskName='autoPriceTables';
		//$this->executedCheck($taskName);
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		//
		$autoprice_db = bpBase::loadModel('autoprice_model');
		$autoprices=$autoprice_db->get_all('DISTINCT(autoid)','','','autoid ASC');
		$count=count($autoprices);
		$i=intval($_GET['i']);
		if ($i<$count){
			//start
			$autoclassification_db = bpBase::loadModel('autoclassification_model');
			$thisAuto=$autoclassification_db->getCfByID($autoprices[$i]['autoid']);
			$parentAuto=$autoclassification_db->getCfByID($thisAuto->parentid);
			$autoObj=bpBase::loadAppClass('autoObj','auto',1);
			$logo=$autoObj->getLogo($parentAuto->id,$parentAuto->logo);
			$autoprice_db->update(array('serieid'=>$parentAuto->id,'seriename'=>$parentAuto->name,'autoname'=>$thisAuto->name,'companyprice'=>$thisAuto->companyprice,'logo'=>$logo),array('autoid'=>$thisAuto->id));
			//end
			$i++;
			showMessage($thisTask['des'].':'.$i.'/'.$count,'?m=update&c=updateTask&a='.$taskName.'&i='.$i,0);
		}else{
			//删除缓存
			@rename(ABS_PATH.'cache',ABS_PATH.'backup'.DIRECTORY_SEPARATOR.'cache_'.time());
			$this->_finishTask($taskName);
		}
	}
	public function convertConfig(){
		$taskName='convertConfig';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		if ($thisTask['executed']){
			showMessage('程序已经执行过了','?m=update&c=update&a=task');
			exit();
		}
		//
		include ABS_PATH.'constant'.DIRECTORY_SEPARATOR.'config.inc.php';
		$configs=array(
		'iisWebServer'=>IIS_WEB_SERVER,
		'bbsUrlRoot'=>BBS_URL_ROOT,
		'signupUrl'=>SIGNUP_URL,
		'siteName'=>SITE_NAME,
		'syncWithUc'=>SYNC_WITH_UC,
		'syncWithPhpwind'=>SYNC_WITH_PHPWIND,
		'geoGradeCount'=>GEO_GRADE_COUNT,
		'cityChannelGradeCount'=>CITY_CHANNEL_GRADE_COUNT,
		'emailAddress'=>EMAIL_ADDRESS,
		'emailPassword'=>EMAIL_PASSWORD,
		'emailSsl'=>EMAIL_SSL,
		'emailPort'=>EMAIL_PORT,
		'emailServer'=>EMAIL_SERVER,
		'indexMetaTitle'=>INDEX_META_TITLE,
		'indexMetaKeyword'=>INDEX_META_KEYWORD,
		'indexMetaDes'=>INDEX_META_DES,
		'mapbarApi'=>MAPBAR_API,
		'statisticCode'=>base64_encode(stripslashes(STATISTIC_CODE)),
		'contentCommentCheck'=>CONTENT_COMMENT_CHECK,
		'storeMessageCheck'=>STORE_MESSAGE_CHECK,
		'qaCheck'=>QA_CHECK,
		'contentCheck'=>CONTENT_CHECK,
		'gzip'=>GZIP
		);
		$arr=var_export($configs,1);
		$str="<?php\r\n"."return ".$arr.";"."\r\n?>";
		file_put_contents(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'system.config.php',$str);
		//
		$this->update_log_db->update(array('executed'=>1),array('id'=>$thisTask['id']));
		showMessage('执行完成','?m=update&c=update&a=task');
	}
	public function convertStoreConfig(){
		$taskName='convertStoreConfig';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		if ($thisTask['executed']){
			showMessage('该程序已经执行过了','?m=update&c=update&a=task');
			exit();
		}
		//
		include ABS_PATH.'constant'.DIRECTORY_SEPARATOR.'store.php';
		$configs=array(
		'showViewCount'=>SHOW_VIEWCOUNT,
		'serviceTel'=>$serviceTel,
		'storeThumbWidth'=>STORE_THUMB_WIDTH,
		'storeThumbHeight'=>STORE_THUMB_HEIGHT,
		'storeLogoWidth'=>STORE_LOGO_WIDTH,
		'storeLogoHeight'=>STORE_LOGO_HEIGHT,
		'consultantAsUser'=>CONSULTANT_AS_USER,
		'storeUrlType'=>STORE_URL_TYPE,
		'createHtmlpage'=>CREATE_HTMLPAGE,
		'storeUseDomainname'=>STORE_USE_DOMAINNAME,
		'forbiddenStoreIndex'=>FORBIDDEN_STORE_INDEX,
		'scorePerDay'=>SCORE_PER_DAY,
		'storeUserIndependent'=>STORE_USER_INDEPENDENT,
		'storeApplyProtocol'=>$storeApplyProtocol
		);
		$arr=var_export($configs,1);
		$str="<?php\r\n"."return ".$arr.";"."\r\n?>";
		file_put_contents(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'store.config.php',$str);
		//
		$this->update_log_db->update(array('executed'=>1),array('id'=>$thisTask['id']));
		showMessage('执行完成','?m=update&c=update&a=task');
	}
	public function handleIndex(){
		$taskName='handleIndex';
		$thisTask=$this->update_log_db->get_one(array('file'=>$taskName));
		if ($thisTask['executed']){
			showMessage('改程序已经执行过了','?m=update&c=update&a=task');
			exit();
		}
		$str='ALTER TABLE auto_usedcar_store DROP INDEX id;ALTER TABLE auto_answer DROP INDEX id_2;ALTER TABLE auto_answer DROP INDEX id;ALTER TABLE auto_autocfgroup DROP INDEX id;ALTER TABLE auto_autoclassification DROP INDEX parentid_5;ALTER TABLE auto_autoclassification DROP INDEX parentid_4;ALTER TABLE auto_autoclassification DROP INDEX parentid_3;ALTER TABLE auto_autoclassification DROP INDEX id;ALTER TABLE auto_autocomment_category DROP INDEX id;ALTER TABLE auto_autoconfig DROP INDEX autoid_2;ALTER TABLE auto_autoconfig DROP INDEX id;ALTER TABLE auto_autoconfigform DROP INDEX id;ALTER TABLE auto_autoconfigformitem DROP INDEX id;ALTER TABLE auto_automaint DROP INDEX autoid_2;ALTER TABLE auto_automaint_record DROP INDEX autoid_2;ALTER TABLE auto_automaint_record DROP INDEX id;ALTER TABLE auto_autophoto DROP INDEX id_2;ALTER TABLE auto_autophoto DROP INDEX id;ALTER TABLE `auto_autophoto` ADD INDEX ( `catid` );ALTER TABLE auto_autophotocategory DROP INDEX id;ALTER TABLE auto_autoprice DROP INDEX autoid_2;ALTER TABLE auto_autoprice DROP INDEX storeid_2;ALTER TABLE auto_autoprice DROP INDEX id;ALTER TABLE `auto_autoprice` ADD INDEX ( `storeid` , `autoid` );ALTER TABLE `auto_autoprice` ADD INDEX ( `price` );ALTER TABLE `auto_autoprice` ADD INDEX ( `display` );ALTER TABLE `auto_autoprice` ADD INDEX ( `recommend` );ALTER TABLE `auto_autoprice` ADD INDEX ( `taxis` );ALTER TABLE auto_autotype DROP INDEX id;ALTER TABLE auto_bank_rate DROP INDEX id;ALTER TABLE auto_carpool DROP INDEX id;ALTER TABLE `auto_carpool` ADD INDEX ( `time` );ALTER TABLE `auto_carpool_passenger` ADD INDEX ( `time` );ALTER TABLE `auto_carpool_passenger` ADD INDEX ( `uid` );ALTER TABLE `auto_carpool_passenger` ADD INDEX ( `infotype` );ALTER TABLE auto_complaint DROP INDEX id_2;ALTER TABLE auto_classification DROP INDEX id;ALTER TABLE auto_consultant DROP INDEX id_2;ALTER TABLE auto_consultant DROP INDEX id;ALTER TABLE auto_content DROP INDEX autoid_3;ALTER TABLE `auto_content` ADD INDEX ( `time` );ALTER TABLE auto_fee DROP INDEX id;ALTER TABLE auto_g3auto_comment DROP INDEX id;ALTER TABLE `auto_g3auto_comment` ADD INDEX ( `time` );ALTER TABLE auto_gb_autoproduct DROP INDEX id;ALTER TABLE auto_gb_autoproduct_member DROP INDEX id;ALTER TABLE auto_gb_config DROP INDEX id;ALTER TABLE auto_gb_insurance DROP INDEX id;ALTER TABLE auto_gb_insurance_member DROP INDEX id;ALTER TABLE auto_gb_learndrive DROP INDEX id;ALTER TABLE auto_gb_learndrive_member DROP INDEX id;ALTER TABLE auto_geo DROP INDEX id_2;ALTER TABLE auto_geo DROP INDEX id;ALTER TABLE `auto_geo` ADD INDEX ( `parentid` );ALTER TABLE `auto_geo` ADD INDEX ( `geoindex` );ALTER TABLE auto_groupbuying DROP INDEX id_2;ALTER TABLE auto_groupbuying DROP INDEX id;ALTER TABLE auto_groupbuying_member DROP INDEX id;ALTER TABLE auto_htmlpage_needrefresh DROP INDEX id_2;ALTER TABLE auto_oil_price DROP INDEX id;ALTER TABLE `auto_photo_count` ADD INDEX ( `autoid` );ALTER TABLE `auto_photo_count` ADD INDEX ( `autoid` , `catid` );ALTER TABLE auto_question DROP INDEX id_2;ALTER TABLE auto_question DROP INDEX id;ALTER TABLE `auto_question` ADD INDEX ( `solved` );ALTER TABLE `auto_question` ADD INDEX ( `time` );ALTER TABLE auto_question_category DROP INDEX id;ALTER TABLE auto_school_price DROP INDEX id;ALTER TABLE `auto_school_price` ADD INDEX ( `schoolid` );ALTER TABLE auto_store DROP INDEX geo_id_2;ALTER TABLE auto_store DROP INDEX id;ALTER TABLE `auto_store` ADD INDEX ( `storeindex` );ALTER TABLE `auto_store` ADD INDEX ( `url` );ALTER TABLE `auto_store` ADD INDEX ( `branch` );ALTER TABLE `auto_store` ADD INDEX ( `display` );ALTER TABLE `auto_store` ADD INDEX ( `taxis` );ALTER TABLE `auto_store` ADD INDEX ( `time` );ALTER TABLE `auto_store` ADD INDEX ( `verified` );ALTER TABLE `auto_store` ADD INDEX ( `handled` );ALTER TABLE `auto_store` ADD INDEX ( `class` );ALTER TABLE `auto_store` ADD INDEX ( `score` );ALTER TABLE `auto_store` ADD INDEX ( `groupid` );ALTER TABLE auto_store_channel DROP INDEX id;ALTER TABLE `auto_store_channel` ADD INDEX ( `storeid` );ALTER TABLE `auto_store_channel` ADD INDEX ( `storeid` , `storetype` );ALTER TABLE auto_store_content DROP INDEX id;ALTER TABLE `auto_store_content` ADD INDEX ( `storeid` );ALTER TABLE `auto_store_content` ADD INDEX ( `storeid` , `storetype` );ALTER TABLE `auto_store_content` ADD INDEX ( `time` );ALTER TABLE `auto_store_content` ADD INDEX ( `uid` );ALTER TABLE auto_store_content DROP INDEX time_2;ALTER TABLE auto_store_grade DROP INDEX storetype_2;ALTER TABLE auto_store_messageboard DROP INDEX id;ALTER TABLE `auto_store_messageboard` ADD INDEX ( `replied` );ALTER TABLE `auto_store_messageboard` ADD INDEX ( `storetype` );ALTER TABLE `auto_store_score` ADD INDEX ( `storeid` );ALTER TABLE `auto_store_score` ADD INDEX ( `time` );ALTER TABLE auto_store_user DROP INDEX uid;ALTER TABLE `auto_store_usetime` ADD INDEX ( `storetype` );ALTER TABLE `auto_update_log` ADD INDEX ( `time` );ALTER TABLE auto_usedcar DROP INDEX id;ALTER TABLE `auto_usedcar` ADD INDEX ( `uid` );ALTER TABLE `auto_usedcar` ADD INDEX ( `autoid` );ALTER TABLE `auto_usedcar` ADD INDEX ( `parentautoid` );ALTER TABLE `auto_usedcar` ADD INDEX ( `geoid` );ALTER TABLE `auto_usedcar` ADD INDEX ( `price` );ALTER TABLE `auto_usedcar` ADD INDEX ( `color` );ALTER TABLE `auto_usedcar` ADD INDEX ( `displacement` );ALTER TABLE `auto_usedcar` ADD INDEX ( `gearbox` );ALTER TABLE `auto_usedcar` ADD INDEX ( `bodystruct` );ALTER TABLE `auto_usedcar` ADD INDEX ( `time` );ALTER TABLE `auto_carrental` ADD INDEX ( `uid` );ALTER TABLE `auto_carrental` ADD INDEX ( `company` );ALTER TABLE `auto_carrental` ADD INDEX ( `price` );ALTER TABLE `auto_carrental` ADD INDEX ( `autoid` );ALTER TABLE `auto_carrental` ADD INDEX ( `parentautoid` );ALTER TABLE `auto_carrental` ADD INDEX ( `autoname` );ALTER TABLE `auto_carrental` ADD INDEX ( `bodystruct` );ALTER TABLE `auto_carrental` ADD INDEX ( `color` );ALTER TABLE `auto_carrental` ADD INDEX ( `checked` );ALTER TABLE `auto_carrental` ADD INDEX ( `inputfinish` );ALTER TABLE `auto_carrental` ADD INDEX ( `inputfinish` );ALTER TABLE `auto_carrental` ADD INDEX ( `recommend` );ALTER TABLE `auto_carrental` ADD INDEX ( `name` );ALTER TABLE `auto_carrental` ADD INDEX ( `time` );ALTER TABLE `auto_carrental` ADD INDEX ( `occupation` );ALTER TABLE auto_carrental_need DROP INDEX id;ALTER TABLE `auto_carrental_need` ADD INDEX ( `uid` );ALTER TABLE `auto_carrental_need` ADD INDEX ( `time` );ALTER TABLE `auto_carrental_need` ADD INDEX ( `checked` );ALTER TABLE `auto_carrental_need` ADD INDEX ( `recommend` );ALTER TABLE `auto_carrental_need` ADD INDEX ( `title` );ALTER TABLE `auto_carrental_need` ADD INDEX ( `geoid` );ALTER TABLE `auto_carrental_need` ADD INDEX ( `autoid` );ALTER TABLE `auto_carrental_need` ADD INDEX ( `price` );ALTER TABLE `auto_carrental_need` ADD INDEX ( `color` );ALTER TABLE `auto_carrental_need` ADD INDEX ( `bodystruct` ) ;ALTER TABLE `auto_usedcar` ADD INDEX ( `checked` );ALTER TABLE `auto_usedcar` ADD INDEX ( `company` );ALTER TABLE `auto_usedcar` ADD INDEX ( `viewcount` );ALTER TABLE `auto_usedcar` ADD INDEX ( `inputfinish` );ALTER TABLE `auto_usedcar` ADD INDEX ( `completerate` );ALTER TABLE `auto_usedcar` ADD INDEX ( `recommend` );ALTER TABLE `auto_usedcar` ADD INDEX ( `handled` );ALTER TABLE `auto_usedcar` ADD INDEX ( `validtime` );ALTER TABLE `auto_usedcar_store` ADD INDEX ( `geo_id` );ALTER TABLE `auto_usedcar_store` ADD INDEX ( `cityid` );ALTER TABLE `auto_usedcar_store` ADD INDEX ( `display` );ALTER TABLE `auto_usedcar_store` ADD INDEX ( `taxis` );ALTER TABLE `auto_usedcar_store` ADD INDEX ( `time` );ALTER TABLE `auto_usedcar_store` ADD INDEX ( `verified` );ALTER TABLE `auto_usedcar_store` ADD INDEX ( `handled` );ALTER TABLE `auto_usedcar_store` ADD INDEX ( `name` , `shortname` );ALTER TABLE auto_usedcar_storeconsultant DROP INDEX id;ALTER TABLE `auto_usedcar_storeconsultant` ADD INDEX ( `storeid` );ALTER TABLE `auto_usedcar_storeconsultant` ADD INDEX ( `time` );ALTER TABLE auto_user DROP INDEX uid;ALTER TABLE auto_viewlog DROP INDEX autoid_2;ALTER TABLE `auto_viewlog` ADD INDEX ( `year` , `week` );ALTER TABLE `auto_viewlog` ADD INDEX ( `grade` );ALTER TABLE `auto_viewlog` ADD INDEX ( `viewcount` );ALTER TABLE `auto_viewlogcount` ADD INDEX ( `grade` );ALTER TABLE `auto_viewlogcount` ADD INDEX ( `nowcount` );ALTER TABLE `auto_viewlogcount` ADD INDEX ( `previouscount` );ALTER TABLE `auto_weizhang` ADD INDEX ( `time` );ALTER TABLE `auto_weizhang` ADD INDEX ( `area` );ALTER TABLE auto_carrental DROP INDEX id;ALTER TABLE auto_carrental_need DROP INDEX id;ALTER TABLE moopha_ad DROP INDEX id;ALTER TABLE `moopha_ad` ADD INDEX ( `time` );ALTER TABLE `moopha_adset` ADD INDEX ( `sindex` );ALTER TABLE `moopha_adset` ADD INDEX ( `site` );ALTER TABLE `moopha_adset` ADD INDEX ( `time` );ALTER TABLE moopha_article DROP INDEX ex_2;ALTER TABLE moopha_channel DROP INDEX id;ALTER TABLE `moopha_channel` ADD INDEX ( `parentid` );ALTER TABLE `moopha_channel` ADD INDEX ( `site` );ALTER TABLE `moopha_channel` ADD INDEX ( `taxis` );ALTER TABLE `moopha_channel` ADD INDEX ( `time` );ALTER TABLE `moopha_channel_contentattribute` ADD INDEX ( `channelid` );ALTER TABLE `moopha_channel_contentattribute` ADD INDEX ( `taxis` );ALTER TABLE moopha_contentgroup DROP INDEX id;ALTER TABLE `moopha_contentgroup` ADD INDEX ( `site` );ALTER TABLE moopha_contentgroup_content DROP INDEX taxis_2;ALTER TABLE `moopha_contentgroup_content` ADD INDEX ( `contentid` );ALTER TABLE `moopha_content_attribute` ADD INDEX ( `taxis` );ALTER TABLE `moopha_content_comment` ADD INDEX ( `contentid` );ALTER TABLE `moopha_content_comment` ADD INDEX ( `time` );ALTER TABLE `moopha_content_comment` ADD INDEX ( `checked` );ALTER TABLE `moopha_content_viewlog` ADD INDEX ( `viewcount` );ALTER TABLE `moopha_content_viewlog` ADD INDEX ( `year` , `week` );ALTER TABLE `moopha_content_viewlog` ADD INDEX ( `contentid` );ALTER TABLE `moopha_picture` ADD INDEX ( `taxis` );ALTER TABLE `moopha_picture` ADD INDEX ( `time` );ALTER TABLE moopha_site DROP INDEX id;ALTER TABLE `moopha_site` ADD INDEX ( `siteindex` );ALTER TABLE moopha_spider_content DROP INDEX id;ALTER TABLE `moopha_spider_content` ADD INDEX ( `ruleid` );ALTER TABLE `moopha_spider_content` ADD INDEX ( `time` );ALTER TABLE `moopha_spider_rule` ADD INDEX ( `time` );ALTER TABLE `moopha_spider_rulefield` ADD INDEX ( `ruleid` );ALTER TABLE moopha_template DROP INDEX id;ALTER TABLE `moopha_template` ADD INDEX ( `isdefault` );ALTER TABLE `moopha_template` ADD INDEX ( `site` );ALTER TABLE `moopha_template` ADD INDEX ( `time` );ALTER TABLE `moopha_user_role` ADD INDEX ( `uid` );ALTER TABLE `moopha_user_role` ADD INDEX ( `role_id` );ALTER TABLE auto_carrental DROP INDEX inputfinish_2';
		$sqls=explode(';',$str);
		$count=count($sqls);
		$i=intval($_GET['i']);
		if ($i<$count){
			@$this->update_log_db->query($sqls[$i]);
			$i++;
			showMessage($thisTask['des'].':'.$i.'/'.$count,'?m=update&c=updateTask&a='.$taskName.'&i='.$i,0);
		}else{
			$this->update_log_db->update(array('executed'=>1),array('id'=>$thisTask['id']));
			showMessage('执行完成','?m=update&c=updateTask&a='.$taskName.'&i='.$i);
		}		
	}
}
?>
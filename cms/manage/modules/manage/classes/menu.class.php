<?php
bpBase::loadAppClass('manage','manage',0);
class menu extends manage {
	public $menu;
	public $ahauto;
	public function __construct() {
		parent::__construct();
		$this->menus=$this->menus();
	}
	public function menus(){
		$top=array();
		$homeLink='?m=manage&c=background&a=home';
		$top['home']=array('text'=>'网站内容管理','link'=>$homeLink);
		$top['config']=array('text'=>'配置','link'=>'?m=config&c=config&a=site');
		return $top;
	}
	
	public function submenu_config(){
		$home_menus['content']=array('text'=>'配置','icon'=>'image/config.png','link'=>'?m=config&c=config&a=site','folder'=>0,'submenu'=>array());
		$home_menus['content']['submenu'][]=array('text'=>'网站配置','icon'=>'image/tools.gif','link'=>'?m=config&c=config&a=site','target'=>'','folder'=>1,'submenu'=>array());
		$home_menus['content']['submenu'][]=array('text'=>'选择模板','icon'=>'image/page_world.png','link'=>'?m=template&c=m_template&a=selectTemplate','target'=>'','folder'=>1,'submenu'=>array());
		
		//$home_menus[]=array('text'=>'系统配置','icon'=>'image/world.png','link'=>'?m=config&c=config&a=system','target'=>'','folder'=>1,'submenu'=>array());

		//$home_menus[]=array('text'=>'水印设置','icon'=>'image/tools.gif','link'=>'?m=config&c=config&a=watermark','target'=>'','folder'=>1,'submenu'=>array());
		//$home_menus[]=array('text'=>'文章参数','icon'=>'image/tools.gif','link'=>'?m=config&c=config&a=cmsContent','target'=>'','folder'=>1,'submenu'=>array());
		
		/////$home_menus['']=array('text'=>'数据库备份','icon'=>'image/database_save.gif','link'=>'?m=manage&c=database&a=export');
		
		//$home_menus[]=array('text'=>'模版备份','icon'=>'/'.CMS_DIR.'/image/database_save.gif','link'=>'/'.CMS_DIR.'/templateBackup.php','target'=>'','folder'=>1,'submenu'=>array());
		/////$home_menus['seo']=array('text'=>'搜索引擎优化','icon'=>'image/color_swatch.gif','folder'=>1,'submenu'=>array());
		//$home_menus['seo']['submenu'][]=array('text'=>'刷新robots.txt','link'=>'?m=seo&c=seo&a=createRobots');
		/////$home_menus['seo']['submenu'][]=array('text'=>'关键词管理','link'=>'?m=seo&c=seo&a=keywords');
		//$home_menus['seo']['submenu'][]=array('text'=>'sitemap设置','link'=>'?m=seo&c=seo&a=sitemapConfig');
		//$home_menus['seo']['submenu'][]=array('text'=>'sitemap管理','link'=>'?m=seo&c=seo&a=sitemaps');
		//$home_menus[]=array('text'=>'计划任务','icon'=>'image/clock.png','link'=>'?m=cron&c=cron&a=init','target'=>'','folder'=>1,'submenu'=>array());
		return $home_menus;
	}
	public function submenu_home(){
		if (!$this->site){//如果站点不存在，请先设置站点
			$home_menus['content']=array('text'=>'请配置站点信息','icon'=>'image/content.gif','folder'=>0,'link'=>'?m=config&c=config&a=site');
			$home_menus['content']['submenu'][]=array('text'=>'点击设置','link'=>'?m=config&c=config&a=site');
			return $home_menus;
		}
		if (!$this->site['template']){//如果没设置模板，请先设置模板
			$home_menus['content']=array('text'=>'请选择模板','icon'=>'image/content.gif','folder'=>0,'link'=>'?m=template&c=m_template&a=selectTemplate');
			return $home_menus;
		}
		$home_menus['content']=array('text'=>'信息管理','icon'=>'image/content.png','folder'=>0,'link'=>'?m=channel&c=m_channel&a=rightFrame&siteid='.$this->siteid,'submenu'=>array());

		$home_menus['content']['submenu'][]=array('text'=>'内容管理','link'=>'?m=channel&c=m_channel&a=rightFrame&siteid='.$this->siteid);
		$home_menus['content']['submenu'][]=array('text'=>'内容搜索','link'=>'?m=article&c=m_article&a=search&siteid='.$this->siteid);
		$home_menus['content']['submenu'][]=array('text'=>'栏目管理','link'=>'?m=channel&c=m_channel&a=rightFrame&siteid='.$this->siteid.'&type=channel');

        /*
		$home_menus['special']=array('text'=>'专题管理','link'=>'?m=special&c=m_special&a=specials','icon'=>'image/page_world.png','folder'=>1);
		$home_menus['special']['submenu'][]=array('text'=>'内容管理','link'=>'?m=special&c=m_special&a=content_selectSpecial');
		$home_menus['special']['submenu'][]=array('text'=>'专题列表','link'=>'?m=special&c=m_special&a=specials');
		$home_menus['special']['submenu'][]=array('text'=>'专题类别','link'=>'?m=special&c=m_special&a=cats');
		$home_menus['special']['submenu'][]=array('text'=>'专题配置','link'=>'?m=special&c=m_special&a=config');
		$home_menus['special']['submenu'][]=array('text'=>'专题模型','link'=>'?m=special&c=m_special&a=models');
		*/
		$home_menus['display']=array('text'=>'显示管理','icon'=>'image/display.png','folder'=>0,'link'=>'?m=template&c=m_template&a=templates&siteid='.$this->siteid);
		$home_menus['display']['submenu'][]=array('text'=>'模板管理','link'=>'?m=template&c=m_template&a=templates&siteid='.$this->siteid);
		//$home_menus['display']['submenu'][]=array('text'=>'模板匹配','link'=>'/'.CMS_DIR.'/templateMatch.php?site='.$this->siteid);
		//$home_menus['display']['submenu'][]=array('text'=>'删除模板缓存','link'=>'/'.CMS_DIR.'/template_deleteCache.php');
		
		$home_menus['create']=array('text'=>'生成管理','icon'=>'image/create.png','folder'=>0,'link'=>'?m=template&c=createHtml&a=createChannelPageSelect&siteid='.$this->siteid.'&type=channel');
		//$home_menus['create']['submenu'][]=array('text'=>'生成首页','link'=>'?m=template&c=createHtml&a=createIndexPage&siteid='.$siteid);
		$home_menus['create']['submenu'][]=array('text'=>'生成栏目页','link'=>'?m=template&c=createHtml&a=createChannelPageSelect&siteid='.$this->siteid.'&type=channel');
		$home_menus['create']['submenu'][]=array('text'=>'生成内容页','link'=>'?m=template&c=createHtml&a=createChannelPageSelect&siteid='.$this->siteid.'&type=content');
		//$home_menus['create']['submenu'][]=array('text'=>'生成单页','link'=>'?m=template&c=createHtml&a=createSinglePageSelect&siteid='.$siteid.'&type=channel');
		return $home_menus;
	}
}
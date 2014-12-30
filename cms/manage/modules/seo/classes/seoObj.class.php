<?php
class seoObj {
	public $sitemapTypes;
	function __construct() {
		$this->sitemapTypes=$this->types();
	}
	function types(){
		return array(
		array('type'=>'news','name'=>'新闻')
		);
	}
	function createSitemap($type,$showMessage=1){
		$sitemapConfig=loadConfig('sitemap');
		$articleCount=$sitemapConfig['articleCount']?$sitemapConfig['articleCount']:500;
		$ucarCount=$sitemapConfig['ucarCount']?$sitemapConfig['ucarCount']:500;
		$datas=array();
		switch ($type){
			default:
			case 'news':
				$article_db=bpBase::loadModel('article_model');
				$articles=$article_db->select(array('ex'=>0),'link,time,title,keywords','0,'.$articleCount,'time DESC');
				if ($articles){
					foreach ($articles as $a){
						if (!strExists($a['link'],'http://')){
							$a['link']=MAIN_URL_ROOT.$a['link'];
						}
						if ($a['keywords']==','){
							$a['keywords']='';
						}
						
						array_push($datas,array('url'=>$a['link'],'time'=>$a['time'],'keywords'=>$a['keywords']));
					}
				}
				break;
		}
		$this->_createSitemap($type,$datas,$showMessage);
	}
	function _createSitemap($type,$datas,$showMessage=1){
		$str='<?xml version="1.0" encoding="GBK"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.sitemaps.org/schemas/sitemap/0.9">';
		if ($datas){
			foreach ($datas as $d){
				if (!$d['time']){
					$d['time']=SYS_TIME;
				}
				$d['url']=str_replace('&','&amp;',$d['url']);
				$str.='<url><loc>'.$d['url'].'</loc><news:news><news:publication_date>'.date('Y-m-d',$d['time']).'T'.date('G:i:s',$d['time']).'Z</news:publication_date><news:keywords>'.$d['keywords'].'</news:keywords></news:news></url>';
			}
		}
		$str.='</urlset>';
		//
		if (!file_exists(ABS_PATH.'sitemap')||is_dir(ABS_PATH.'sitemap')){
			@mkdir(ABS_PATH.'sitemap',0777);
		}
		//
		file_put_contents(ABS_PATH.'sitemap'.DIRECTORY_SEPARATOR.$type.'_sitemap.xml',$str);
		if ($showMessage){
			showMessage('生成完成','?m=seo&c=seo&a=sitemaps');
		}
	}
}
?>
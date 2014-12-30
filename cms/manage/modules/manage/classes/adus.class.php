<?php
bpBase::loadAppClass('manage','manage',0);
class adus extends manage {
	public $styles;//css files
	public $jss;//js files
	public $title;//page title
	public $opers;//opers
	public $pageStr;
	public $loadFormCheck=1;
	public $loadArtDialog=1;
	public $loadAutoSelect=0;
	public $loadCalendar=0;
	public $formAction='';
	public $inputs;//输入项
	public $headerHtml;
	public $tip;
	public function __construct() {
		
	}
	public function inputPageStr(){
		//css files
		if (is_array($this->styles)){
			foreach ($this->styles as $f){
				$this->pageStr.='<link href="style/'.$f.'" type="text/css" rel="stylesheet">';
			}
		}
		$this->pageStr.=$this->headerHtml;
		//js files
		if (is_array($this->jss)){
			foreach ($this->jss as $f){
				$this->pageStr.='<script type="text/javascript" src="js/'.$f.'"></script>';
			}
		}
		//form check widget
		if ($this->loadFormCheck){
			$this->pageStr.='<script type="text/javascript" src="js/formCheck/lang/cn.js"></script><script type="text/javascript" src="js/formCheck/formcheck.js"></script><link rel="stylesheet" href="js/formCheck/theme/grey/formcheck.css" type="text/css" media="screen" /><script type="text/javascript">window.addEvent(\'domready\', function(){new FormCheck(\'form\');});</script>';
		}
		//art dialog
		if ($this->loadArtDialog){
			$this->pageStr.='<script src="js/artDialog4.1.6/artDialog.js?skin=default"></script><script src="/js/artDialog4.1.6/plugins/iframeTools.js"></script>';
		}
		//auto select
		if ($this->loadAutoSelect){
			$this->pageStr.='<script type="text/javascript" src="'.MAIN_URL_ROOT.'/upload/a1.html"></script><script type="text/javascript" src="'.JS_URL_ROOT.'/autoSelect.js"></script>';
		}
		
		//calendar
		if ($this->loadCalendar){
			$this->pageStr.='<link href="style/calendar.css" type="text/css" rel="stylesheet"><script src="/js/calendar.js"></script>';
		}
		//page title
		$this->pageStr.='<div class="columntitle">'.$this->title.'</div>';
		//tip
		if ($this->tip){
			$this->pageStr.='<div class="ftip">'.$this->tip.'</div>';
		}
		//opers,单个样式 array('text'=>'','href'=>'','class'=>'','onclick'=>'','target'=>'')
		if (is_array($this->opers)){
			$this->pageStr.='<div class="oper">';
			foreach ($this->opers as $o){
				$onclickStr=isset($o['onclick'])?' onclick="'.$o['onclick'].'"':'';
				$targetStr=isset($o['target'])?' target="'.$o['target'].'"':'';
				$this->pageStr.='<a href="'.$o['href'].'" class="'.$o['class'].'">'.$o['text'].'</a>';
			}
			$this->pageStr.='</div>';
		}
		//form
		$this->pageStr.='<form method="post" action="'.$this->formAction.'" id="form"><table class="addTable">';
		/*****input start*****/
		if ($this->inputs){
			foreach ($this->inputs as $a){
				$trDisplayStyle=$a['type']=='hidden'?'display:none;':'';
				$this->pageStr.='<tr style="'.$trDisplayStyle.'"><th><nobr>'.$a['name'].'</nobr></th><td>';
				$validateStr=isset($a['validate'])?' validate['.$a['validate'].']':'';
				switch ($a['type']){
					case 'html':
						$this->pageStr.=$a['html'];
						break;
					case 'text':
						if (!isset($a['style'])){
							$a['style']='width:80%;';
						}
						$this->pageStr.='<input type="text" class="colorblur'.$validateStr.'" name="'.$a['field'].'" id="'.$a['field'].'" style="'.$a['style'].'" value="'.$a['value'].'" />';
						break;
					case 'select':
						$onChangeStr=isset($a['onchange'])?' onchange="'.$a['onchange'].'"':'';
						$selects='<select class="'.$validateStr.'" name="'.$a['field'].'" id="'.$a['field'].'" style="'.$a['style'].'"'.$onChangeStr.'>';
						if ($a['data']){
							foreach ($a['data'] as $ar){
								if (!$ar['selected']){
									$selects.='<option value="'.$ar['itemValue'].'"> '.$ar['itemText'].'</option>';
								}else {
									$selects.='<option value="'.$ar['itemValue'].'" selected> '.$ar['itemText'].'</option>';
								}
							}
						}
						$this->pageStr.=$selects.'</select>';
						break;
					case 'hidden':
						$this->pageStr.='<input type="hidden" name="'.$a['field'].'" id="'.$a['field'].'" value="'.$a['value'].'" />';
						break;
					case 'textarea':
						if (!isset($a['style'])){
							$a['style']='width:80%;';
						}
						$this->pageStr.='<textarea class="colorblur" name="'.$a['field'].'" id="'.$a['field'].'" style="'.$a['style'].'">'.$a['value'].'</textarea>';
						break;
					case 'ckeditor':
						$this->pageStr.='<script type="text/javascript" src="'.MAIN_URL_ROOT.'/editor/ckeditor/ckeditor.js"></script><script type="text/javascript" src="'.MAIN_URL_ROOT.'/editor/ckfinder/ckfinder.js"></script><textarea name="'.$a['field'].'">'.$a['value'].'&nbsp;</textarea><script type="text/javascript">var editor = CKEDITOR.replace("'.$a['field'].'");CKFinder.setupCKEditor(editor,"'.MAIN_URL_ROOT.'/editor/ckfinder/") ;</script>';
						break;
					case 'thumb':
						$this->pageStr.='<input type="text" class="colorblur'.$validateStr.'" name="'.$a['field'].'" id="'.$a['field'].'" style="'.$a['style'].'" value="'.$a['value'].'" /> <a href="###" onclick="picUpload(\''.$a['field'].'\','.$a['width'].','.$a['height'].',\'\',\''.MANAGE_DIR.'\')">上传</a> <a href="###" onclick="viewImg(\''.$a['field'].'\',\'预览\')">预览</a>';
						break;	
						
						
					case 'radio':
						$radios='';
						if ($a['data']){
							foreach ($a['data'] as $ar){
								if (!$ar['selected']){
									$radios.='<input type="radio" name="'.$a['field'].'[]" value="'.$ar['itemValue'].'" /> '.$ar['itemText'].'&nbsp; ';
								}else {
									$radios.='<input type="radio" name="'.$a['field'].'[]" value="'.$ar['itemValue'].'" checked /> '.$ar['itemText'].'&nbsp; ';
								}
							}
						}
						$html.=$radios;
						break;
					
					case 'file':
						$html.='<input type="text" class="colorblur" name="'.$a['field'].'" id="'.$a['field'].'" style="width:60%" value="'.$a['value'].'"></input>&nbsp;<a href="javascript:void(0)" onclick="thumbUpload(\''.$a['field'].'\','.$a['width'].','.$a['height'].')">上传</a>';
						break;
					
				}
				$this->pageStr.=' <span class="tdtip">'.$a['complement'].'</span>';
				$this->pageStr.='</td></tr>';
			}
		}
		/*****input end*****/
		$this->pageStr.='<tr><td class="addName"></td><td><input type="submit" name="doSubmit" value="提交" class="button"/></td></tr>';
		$this->pageStr.='</table></form>';
		return $this->pageStr;
	}
	public function outputPage(){
		include($this->showManageTpl('header','manage'));
		echo $this->inputPageStr();
		include($this->showManageTpl('footer','manage'));
	}
}
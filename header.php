<?php if(constant("AlenCMS!") !== true)die;include_once(SYS_ROOT.INC.'html.php');?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="x-ua-compatible" content="ie=8" />
<?php if($pagetil=='index'){ ?>
<?php ;$cattop['id']=1;; ?>
<title><?php ;echo $webname; ?></title>
<?php ;}else{ ?>
<title><?php ;echo $pagetil; ?></title>
<?php ;} ?>

<meta name="description" content="<?php ;echo $webinfo; ?>" />
<meta name="keywords" content="<?php ;echo WEBKEY; ?>" />
<meta name="author" content="Alen" />

<link rel="stylesheet" type="text/css" href="<?php ;echo WEBURL; ?><?php ;echo FE_DIR; ?>alenprt/initialize.css" />
<link rel="stylesheet" type="text/css" href="<?php ;echo WEBURL; ?><?php ;echo FE_DIR; ?>alenprt/icon/icon.css" />
<link rel="stylesheet" type="text/css" href="<?php ;echo WEBURL; ?><?php ;echo FE_DIR; ?>alenprt/main.css" />
<link rel="stylesheet" type="text/css" href="<?php ;echo WEBURL; ?><?php ;echo FE_DIR; ?>alenprt/object.css" />
<link rel="stylesheet" href="<?php ;echo $tpurl; ?>Alen/Css/Index.css" type="text/css" />

<script src="<?php ;echo $tpurl; ?>Alen/Calls.php?u=<?php ;echo urlencode($tpurl); ?>" language="javascript"></script>
</head>

<body>


	<div class="toptools">
		<div class="fr_width fr_center">
			<?php ;$tmp=Alen::setart(496); ?>
			<?php $_i=0;if(is_array($tmp))foreach($tmp AS $v){$_i++; ?>
			<a class="fr_left fr_mr10" href="<?php ;echo Html::url($v['staticurl']); ?>"><?php ;echo $v['name']; ?></a>
			<?php ;} ?>
			<a class="fr_right fr_ml10">协会网站</a>
			<a class="fr_right fr_ml10">商学院</a>
			<a class="fr_right fr_ml10" onclick="SetHome(this,'<?php ;echo WEBURL; ?>')">设为首页</a>
			<a class="fr_right fr_ml10" onclick="AddFavorite('<?php ;echo WEBNAME; ?>','<?php ;echo WEBURL; ?>')">加入收藏</a>
			<a class="fr_right fr_ml10">博客</a>
			<a class="fr_right">会员登录</a>
			<div class="fr_clear"></div>
		</div>
	</div>
	
<div class="TopBg">

	
	<div class="Top fr_width fr_center">
		<a class="fr_left toplogo fr_mt10" title="<?php ;echo WEBNAME; ?>" href="<?php ;echo WEBURL; ?>"><img src="<?php ;echo $tpurl; ?>Alen/Img/index_03.jpg" /></a>
		
		<div class="topss fr_left fr_pz10 fr_mz10 fr_ms10">
			<input type="text" placeholder="请输入关键词" class="fr_left" />
			<button class="fr_left" type="submit"></button>
			<p class="fr_left fr_ml20">
				<?php ;$tmp=$dbit->get_one(TB."cms",'id=1246',"tags");$tmp=explode(',',$tmp['tags']);; ?>
				<?php $_i=0;if(is_array($tmp))foreach($tmp AS $v){$_i++; ?>
				<a class="fr_left"><?php ;echo $v; ?></a>
				<?php ;} ?>
				<div class="fr_clear"></div>
			</p>
			<div class="fr_clear"></div>
			<div class="toplink">
				<?php ;$tmp=Alen::setart(493); ?>
				<?php $_i=0;if(is_array($tmp))foreach($tmp AS $v){$_i++; ?>
				<a class="fr_left fr_mr10" href="<?php ;echo Html::url($v['staticurl']); ?>"><?php ;echo $v['name']; ?></a>
				<?php ;} ?>
				<div class="fr_clear"></div>
			</div>
		</div>
		
		<div class="fr_right toptime">
			<p>echo date('Y-m-j');<br />echo date('w');</p>
			<?php ;$tmp=Alen::setart(495); ?>
			<?php $_i=0;if(is_array($tmp))foreach($tmp AS $v){$_i++; ?><a href="<?php ;echo Html::url($v['staticurl']); ?>"><?php ;echo $v['name']; ?></a><?php ;} ?>
		</div>
		<div class="fr_clear"></div>
    </div>
</div>

<div class="TopNavBg">
	<div class="TopNav fr_width fr_center">
		<ul id="mainNav" class="ui_nav1">
			<?php ;$tmp=Alen::SetColList(471); ?>
			<?php ;$_iii=1;; ?>
			<?php $_i=0;if(is_array($tmp['zCls']))foreach($tmp['zCls'] AS $v){$_i++; ?>
			<li class="mainlevel fr_left">
				<a target="<?php ;echo $v['target']; ?>" href="<?php ;echo Html::url($v['staticurl']); ?>"><?php ;echo Html::txt($v['name'],8,0,''); ?></a>
				<?php if($v['zCls']){ ?>
				<ul>
				<?php ;$_ii=1;; ?>
				<?php $_i=0;if(is_array($v['zCls']))foreach($v['zCls'] AS $vv){$_i++; ?>
				<li><a target="<?php ;echo $vv['target']; ?>" href="<?php ;echo Html::url($vv['staticurl']); ?>"><?php ;echo Html::txt($vv['name'],8,0,''); ?></a></li>
				<?php ;if($_ii==2)break;$_ii++;; ?>
				<?php ;} ?>
				</ul>
				<?php ;} ?>
			</li>
			<?php ;if($_iii==10)break;$_iii++;; ?>
			<?php ;} ?>
		</ul>
	</div>
	<div class="TopNavBg_x">
		<div class="fr_width fr_center">
			<span>人民最喜爱的品牌：</span>
			<?php ;$tmp=Alen::setart(494); ?>
			<?php $_i=0;if(is_array($tmp))foreach($tmp AS $v){$_i++; ?><a target="_blank" href="<?php ;echo Html::url($v['staticurl']); ?>"><?php ;echo $v['name']; ?></a><?php ;} ?>
		</div>
	</div>
</div>

<div class="MainBg">

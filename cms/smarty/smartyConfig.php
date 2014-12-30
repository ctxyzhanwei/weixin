<?php
//smarty
$smarty=new Smarty();
$smarty->template_dir=ABS_PATH.'smarty'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR;
$smarty->compile_dir=ABS_PATH.'smarty'.DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR;
$smarty->config_dir=ABS_PATH.'smarty'.DIRECTORY_SEPARATOR.'configs'.DIRECTORY_SEPARATOR;
$smarty->cache_dir=ABS_PATH.'smarty'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR;
$smarty->use_sub_dirs=false;
?>
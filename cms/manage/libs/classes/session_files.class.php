<?php 
class session_files {
    function __construct() {
		$path = ABS_PATH.'sessions';
		if (!file_exists($path)&&!is_dir($path)){
			mkdir($path,0777);
		}
		ini_set('session.save_handler', 'files');
		session_save_path($path);
		session_start();
    }
}
?>
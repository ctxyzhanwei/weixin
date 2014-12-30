<?php
class configWidget {
	function __construct() {
	}
	public function getConfigValue(){
		echo loadConfig($_GET['file'],$_GET['key']);
	}
}
?>
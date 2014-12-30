<?php
class stag_config{
	function __construct(){
		
	}
	function getValue($parm){
		$parms=explode('.',$parm);
		return loadConfig($parms[0],$parms[1]);
	}
}
?>
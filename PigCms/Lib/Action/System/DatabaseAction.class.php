<?php
class DatabaseAction extends BackAction{
	public function index(){
	$dbName=C('DB_NAME');
	$base_dir = "./PigData/database/";
    $this->mk_dir($base_dir);
    $fso   = opendir($base_dir);
    $files=array();
	$i=0;
    while($filename=readdir($fso)){
	    if($filename != "." && $filename != "..")
         $files[$i++]=date('Y-m-d H:i:s',substr($filename,0,10)); 
    }
    closedir($fso);
	$this->assign("files",$files);
	$this->assign("re",$dbName);
	$this->display();
 }
 public function mk_dir($filepath,$mode = 0777){
		$dir = dirname($filepath);
		if(!file_exists($dir)){
			mkdir($dir,0777,true);
			if(!file_exists($filepath)){
				fopen($filepath,"w+");
			}
		}
	}
 public function back(){
    $DataDir = "./PigData/database/";
        import("@.ORG.MySQLReback");
        $config = array(
            'host' => C('DB_HOST'),
            'port' => C('DB_PORT'),
            'userName' => C('DB_USER'),
            'userPassword' => C('DB_PWD'),
            'dbprefix' => C('DB_PREFIX'),
            'charset' => 'utf8',
            'path' => $DataDir,
            'isCompress' => 0, //是否开启gzip压缩
            'isDownload' => 0  
        );
    $mr = new MySQLReback($config);
    $mr->setDBName(C('DB_NAME'));
	$dir="./PigData/database/".time().".sql";
    if($mr->backup() == true){
	   $this->success("备份成功");
	}
 }

 public function recovery(){
    set_time_limit(300);
    $DataDir = "./PigData/database/";
        import("@.ORG.MySQLReback");
        $config = array(
            'host' => C('DB_HOST'),
            'port' => C('DB_PORT'),
            'userName' => C('DB_USER'),
            'userPassword' => C('DB_PWD'),
            'dbprefix' => C('DB_PREFIX'),
            'charset' => 'utf8',
            'path' => $DataDir,
            'isCompress' => 1, //是否开启gzip压缩
            'isDownload' => 0  
        );
    $mr = new MySQLReback($config);
    $mr->setDBName(C('DB_NAME'));
    $filename=strtotime($_GET['time']).".sql";     
    $mr->recover($filename);  
    $this->success("成功恢复数据库！");
 }
  public function delete(){
    $filename="./PigData/database/".strtotime($_GET['time']).".sql";
	//dump($filename);
    unlink($filename);
     $this->success("成功删除备份数据库！");
 }
 protected function getTable(){
  $dbName=C('DB_NAME');
  $result=M()->query('show tables from '.$dbName);
  foreach ($result as $v){
      $tbArray[]=$v['Tables_in_'.C('DB_NAME')];
  }
  return $tbArray;
 }
 protected function bakStruct($array){
  foreach ($array as $v){
   $tbName=$v;
   $result=M()->query('show columns from '.$tbName);
   $sql.="--\r\n";
   $sql.="-- 数据表结构: `$tbName`\r\n";
   $sql.="--\r\n\r\n";
   $sql.="DROP TABLE IF EXISTS `$tbName`;\r\n";
   $sql.="create table `$tbName` (\r\n";
   $rsCount=count($result);
   foreach ($result as $k=>$v){
           $field  =       $v['Field'];
           $type   =       $v['Type'];
           $default=       $v['Default'];
           $extra  =       $v['Extra'];
           $null   =       $v['Null'];
     if(!($default=='')){
      $default="default '".$default."'";
     }      
           if($null=='NO'){
               $null='not null';
           }else{
               $null="null";
           }          
           if($v['Key']=='PRI'){
                   $key    =       'primary key';
           }else{
                   $key    =       '';
           }
     if($k<($rsCount-1)){
      $sql.="`$field` $type $null $default $key $extra ,\r\n";
     }else{
      //最后一条不需要","号
      $sql.="`$field` $type $null $default $key $extra \r\n";
     }
   }
   $sql.=")engine=innodb charset=utf8;\r\n\r\n";
  }
  return str_replace(',)',')',$sql);
 }
 protected function bakRecord($array){
		foreach ($array as $v){
			$tbName=$v;
			$rs=M()->query('select * from '.$tbName);
			if(count($rs)<=0){
				continue;
			}
           $sql.="--\r\n";
           $sql.="-- 数据表中的数据: `$tbName`\r\n";
           $sql.="--\r\n\r\n";
			$sql.="INSERT INTO `$tbName` VALUES";
			foreach ($rs as $k=>$v){
				$sql.=" (";
				foreach ($v as $key=>$value){
					if($value==''){
						$value='null';
					}
					$type=gettype($value);
					if($type=='string'){
						$value="'".addslashes($value)."'";
					}
					$sql.="$value," ;
				}
				$sql.="),";
			}
			$sql=substr($sql,0,-1);
			$sql.=";\r\n";
		}
		return str_replace(',)',')',$sql);
	}
 public function click()
 {
	$url=explode("&",$_GET['zhi']);
	$do=$url[0];
	$table=$url[1];
	switch($do)
	{
		case optimize://优化
			$rs =M()->Query("OPTIMIZE TABLE `$table` ");
			if($rs)
			{
				echo "执行优化表： $table  OK！";
			}
			else
			{
				echo "执行优化表： $table  失败，原因是：".M()->GetError();
			}
			break;
		case repair://修复
			$rs = M()->Query("REPAIR TABLE `$table` ");
			if($rs)
			{
				echo "修复表： $table  OK！";
			}
			else
			{
				echo "修复表： $table  失败，原因是：".M()->GetError();
			}
			break;
		default://结构
			$dsql=M()->Query("SHOW CREATE TABLE ".$table);
			foreach($dsql as $k=>$v)
			{
				foreach($v as $k1=>$v1)
				{
					$rs=$v1;
				}
			}
			echo trim($rs);
	}	
 }
}
?>
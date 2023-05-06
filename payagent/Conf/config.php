<?php
if (!defined('THINK_PATH')) exit();

$config = require '../config.php'; 
$index_config = array(
 
);
//将两个数组合并成一个数组
return array_merge($config,$index_config); 

/*
return array(
	'DB_TYPE'=>'mysql',
	'DB_HOST'=>'192.168.1.107',
	'DB_NAME'=>'dome521fms',
	'DB_USER'=>'dome521fms',
	'DB_PWD'=>'tieweishivps',
	'DB_PORT'=>'3306',
	'DB_PREFIX'=>'ss_',
	'SHOW_ERROR_MSG' => true,
);
*/
?>
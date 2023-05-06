<?php 
//加载公共配置文件
$config = require './config.php'; 
$index_config = array(
       'DEFAULT_THEME' => 'Newtpl',
);
//将两个数组合并成一个数组
return array_merge($config,$index_config); 


 ?>
<?php
	error_reporting(0);
	//定义已授权站点
	$site_list = array(
		'www.yunbaokj.com',
	);
	
	$source_url =  $_REQUEST['domain']; //获取来源地址
	if($source_url == NULL) $source_url = "NULL";
	in_array($source_url, $site_list) or die("error");

	

?>
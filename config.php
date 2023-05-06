<?php

	return array (
	  'DB_TYPE' => 'mysql',
	  'DB_HOST' => ' ',
	  'DB_NAME' => ' ',
	  'DB_USER' => ' ',
	  'DB_PWD' => ' ',
	  'DB_PORT' => ' ',
	  'DB_PREFIX' => 'ss_',
	  'SHOW_ERROR_MSG' => true,
	  'HTML_CACHE_ON' => '0',   
	  'HTML_CACHE_RULES' => 
	  array (
		'*' => 
		array (
		  0 => '\{Array.REQUEST_URI|md5}',
		  1 => 300,
		),
	  ),
	  //'SHOW_PAGE_TRACE' =>true,

	  'HTML_CACHE_TIME' => '605',
	  'HTML_READ_TYPE' => '0',
	  'HTML_FILE_SUFFIX' => '.html',
	  'TMPL_ACTION_ERROR' => 'Public:error',
	  'TMPL_ACTION_SUCCESS' => 'Public:success',
	  'REDIS_HOST'=>' ',
	  'REDIS_AUTH'=>' '


	);
?>
<?php
$config = require '../config.php'; 

	define('UC_CONNECT', 'mysql');
	define('UC_DBHOST', $config['DB_HOST']);
	define('UC_DBUSER',  $config['DB_USER']);
	define('UC_DBPW', $config['DB_PWD']);
	define('UC_DBNAME',  $config['DB_NAME']);
	define('UC_DBCHARSET', 'utf8');
	define('UC_DBTABLEPRE', 'uc_');
	define('UC_COOKIEPATH', '/');
	define('UC_COOKIEDOMAIN', '');
	define('UC_DBCONNECT', 0);
	define('UC_CHARSET', 'utf-8');
	define('UC_FOUNDERPW', '7c2aca277543482fb4574fda19fba175');
	define('UC_FOUNDERSALT', '519051');
	define('UC_KEY', '32112fdsafdsafdsa12312');
	define('UC_SITEID', '7B4Lfb0Q5cdd5j6Dcd714T1c3Ea8czbB7R7J0DaCfxfTbW7b8HfzaR472E7A7V4v');
	define('UC_MYKEY', '7Y4BfY095edZ5A6scD7Q4y1k3TaXcCbd7p7x0yatfWfPba7X8JftaX4K2h7k7n4C');
	define('UC_DEBUG', false);
	define('UC_PPP', 20);


?>
<?php defined('ROOT_PATH') or exit('No direct script access allowed');

	$active_group = 'default';
	$query_builder = TRUE;
 
	$db['default'] = array(
		'dsn' => '',
		'hostname' => 'localhost',
		'username' => 'root',
		'password' => '',
		'database' => 'admin_komilfo',
		'dbdriver' => 'mysqli',
		'dbprefix' => 'ko_',
		'pconnect' => TRUE,
		'db_debug' => TRUE,
		'cache_on' => FALSE,
		'cachedir' => '',
		'char_set' => 'utf8',
		'dbcollat' => 'utf8_general_ci',
		'swap_pre' => '',
		'autoinit' => TRUE,
		'encrypt' => FALSE,
		'compress' => FALSE,
		'stricton' => FALSE,
		'failover' => array()
	);

	/* End of file database.php */
	/* Location: ./application/config/database.php */
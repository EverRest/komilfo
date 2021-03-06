<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.2.4 or newer
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Open Software License version 3.0
 *
 * This source file is subject to the Open Software License (OSL 3.0) that is
 * bundled with this package in the files license.txt / license.rst.  It is
 * also available through the world wide web at this URL:
 * http://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world wide web, please send an email to
 * licensing@ellislab.com so we can send you a copy immediately.
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2013, EllisLab, Inc. (http://ellislab.com/)
 * @license		http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/config.html
 */

	/**
	 * @property CI_DB_query_builder $db              This is the platform-independent base Active Record implementation class.
	 * @property CI_DB_forge $dbforge                 Database Utility Class
	 * @property CI_Benchmark $benchmark              This class enables you to mark points and calculate the time difference between them.<br />  Memory consumption can also be displayed.
	 * @property CI_Calendar $calendar                This class enables the creation of calendars
	 * @property CI_Cart $cart                        Shopping Cart Class
	 * @property CI_Config $config                    This class contains functions that enable config files to be managed
	 * @property CI_Controller $controller            This class object is the super class that every library in.<br />CodeIgniter will be assigned to.
	 * @property CI_Email $email                      Permits email to be sent using Mail, Sendmail, or SMTP.
	 * @property CI_Encrypt $encrypt                  Provides two-way keyed encoding using XOR Hashing and Mcrypt
	 * @property CI_Exceptions $exceptions            Exceptions Class
	 * @property CI_Form_validation $form_validation  Form Validation Class
	 * @property CI_Ftp $ftp                          FTP Class
	 * @property CI_Hooks $hooks                      //dead
	 * @property CI_Image_lib $image_lib              Image Manipulation class
	 * @property CI_Input $input                      Pre-processes global input data for security
	 * @property CI_Lang $lang                        Language Class
	 * @property CI_Loader $load                      Loads views and files
	 * @property CI_Log $log                          Logging Class
	 * @property CI_Model $model                      CodeIgniter Model Class
	 * @property CI_Output $output                    Responsible for sending final output to browser
	 * @property CI_Pagination $pagination            Pagination Class
	 * @property CI_Parser $parser                    Parses pseudo-variables contained in the specified template view,<br />replacing them with the data in the second param
	 * @property CI_Profiler $profiler                This class enables you to display benchmark, query, and other data<br />in order to help with debugging and optimization.
	 * @property CI_Router $router                    Parses URIs and determines routing
	 * @property CI_Session $session                  Session Class
	 * @property CI_Table $table                      HTML table generation<br />Lets you create tables manually or from database result objects, or arrays.
	 * @property CI_Trackback $trackback              Trackback Sending/Receiving Class
	 * @property CI_Typography $typography            Typography Class
	 * @property CI_Unit_test $unit_test              Simple testing class
	 * @property CI_Upload $upload                    File Uploading Class
	 * @property MY_URI $uri                          Parses URIs and determines routing
	 * @property CI_User_agent $user_agent            Identifies the platform, browser, robot, or mobile devise of the browsing agent
	 * @property CI_Xmlrpc $xmlrpc                    XML-RPC request handler class
	 * @property CI_Xmlrpcs $xmlrpcs                  XML-RPC server class
	 * @property CI_Zip $zip                          Zip Compression Class
	 * @property CI_Javascript $javascript            Javascript Class
	 * @property CI_Jquery $jquery                    Jquery Class
	 * @property CI_Utf8 $utf8                        Provides support for UTF-8 environments
	 * @property CI_Security $security                Security Class, xss, csrf, etc...
	 * @property CI_Driver_Library $driver            CodeIgniter Driver Library Class
	 * @property CI_Cache $cache                      CodeIgniter Caching Class
	 *
	 * @property Template_lib $template_lib
	 * @property Seo_lib $seo_lib
	 *
	 * @property Init_model $init_model
	 */
class CI_Model {

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		log_message('debug', 'Model Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * __get magic
	 *
	 * Allows models to access CI's loaded classes using the same
	 * syntax as controllers.
	 *
	 * @param	string	$key
	 */
	public function __get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
	}

}

/* End of file Model.php */
/* Location: ./system/core/Model.php */
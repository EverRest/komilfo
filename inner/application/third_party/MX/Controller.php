<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

	/** load the CI class for Modular Extensions **/
	require dirname(__FILE__) . '/Base.php';

	/**
	 * Modular Extensions - HMVC
	 *
	 * Adapted from the CodeIgniter Core Classes
	 * @link    http://codeigniter.com
	 *
	 * Description:
	 * This library replaces the CodeIgniter Controller class
	 * and adds features allowing use of modules and the HMVC design pattern.
	 *
	 * Install this file as application/third_party/MX/Controller.php
	 *
	 * @copyright    Copyright (c) 2011 Wiredesignz
	 * @version    5.4
	 *
	 * Permission is hereby granted, free of charge, to any person obtaining a copy
	 * of this software and associated documentation files (the "Software"), to deal
	 * in the Software without restriction, including without limitation the rights
	 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	 * copies of the Software, and to permit persons to whom the Software is
	 * furnished to do so, subject to the following conditions:
	 *
	 * The above copyright notice and this permission notice shall be included in
	 * all copies or substantial portions of the Software.
	 *
	 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	 * THE SOFTWARE.
	 **/

	/**
	 * @property CI_DB_query_builder $db
	 * @property CI_DB_forge $dbforge
	 * @property CI_Benchmark $benchmark
	 * @property CI_Calendar $calendar
	 * @property CI_Config $config
	 * @property CI_Controller $controller
	 * @property CI_Encrypt $encrypt
	 * @property CI_Exceptions $exceptions
	 * @property CI_Form_validation $form_validation
	 * @property CI_Ftp $ftp
	 * @property CI_Hooks $hooks
	 * @property CI_Input $input
	 * @property CI_Lang $lang
	 * @property CI_Loader $load
	 * @property CI_Log $log
	 * @property CI_Model $model
	 * @property CI_Output $output
	 * @property CI_Pagination $pagination
	 * @property CI_Parser $parser
	 * @property CI_Profiler $profiler
	 * @property CI_Router $router
	 * @property CI_Session $session
	 * @property CI_Table $table
	 * @property CI_Trackback $trackback
	 * @property CI_Typography $typography
	 * @property CI_Unit_test $unit_test
	 * @property CI_Upload $upload
	 * @property MY_URI $uri
	 * @property CI_User_agent $user_agent
	 * @property CI_Xmlrpc $xmlrpc
	 * @property CI_Xmlrpcs $xmlrpcs
	 * @property CI_Zip $zip
	 * @property CI_Javascript $javascript
	 * @property CI_Jquery $jquery
	 * @property CI_Utf8 $utf8
	 * @property CI_Security $security
	 * @property CI_Driver_Library $driver
	 * @property CI_Cache $cache

	 * @property Template_lib $template_lib
	 * @property Seo_lib $seo_lib
	 * @property Captcha_lib $captcha_lib
	 * @property Image_moo $image_moo
	 * @property Email_lib $email_lib
	 * @property Image_lib $image_lib
	 *
	 * @property Init_model $init_model
	 * @property Admin_menu_model $admin_menu_model
	 * @property Menu_model $menu_model
	 * @property Admin_components_model $admin_components_model
	 * @property Components_model $components_model
	 * @property Profile_model $profile_model
	 */

	class MX_Controller {

		public $autoload = array();

		public function __construct()
		{
			$class = str_replace(CI::$APP->config->item('controller_suffix'), '', get_class($this));
			log_message('debug', $class . " MX_Controller Initialized");
			Modules::$registry[strtolower($class)] = $this;

			/* copy a loader instance and initialize */
			$this->load = clone load_class('Loader');
			$this->load->initialize($this);

			/* autoload module items */
			$this->load->_autoloader($this->autoload);
		}

		public function __get($class)
		{
			return CI::$APP->$class;
		}
	}
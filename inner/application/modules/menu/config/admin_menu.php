<?php defined('ROOT_PATH') OR exit('No direct script access allowed');
	return array(
		'root' => array(
			array(
				'code' => 'components',
				'name' => 'Сторінка',
				'url' => '',
			),
			array(
				'code' => 'menu',
				'name' => 'Меню',
				'url' => 'admin/menu/index?menu_index=1&',
			),
			array(
				'code' => 'seo',
				'name' => 'SEO',
				'url' => 'admin/seo/tags?',
			),
			array(
				'code' => 'config',
				'name' => 'Налаштування',
				'url' => 'admin/config/common?',
			),
			/*
			array(
				'code' => '',
				'name' => '',
				'url' => '',
			),
			*/
		),
		'components' => array(
			array(
				'name' => 'Стаття',
				'module' => 'article',
				'index' => 'index',
				'config' => '',
				'class' => 'fm com_article component_add',
				'on_main' => true,
				// 'check' => 'on_page',
			),
			
			// array(
			// 	'name' => 'Новини',
			// 	'module' => 'news',
			// 	'index' => 'index',
			// 	'config' => '',
			// 	'class' => 'fm com_news component_add',
			// 	'on_main' => true,
			// 	// 'check' => 'on_page',
			// ),
			array(
				'name' => 'Google map',
				'module' => 'google_map',
				'index' => 'index',
				'config' => '',
				'class' => 'fm google component_add',
				'on_main' => true,
				// 'check' => 'on_page',
			),
			// array(
			// 	'name' => 'Магазин',
			// 	'module' => 'where',
			// 	'index' => 'index',
			// 	'config' => '',
			// 	'class' => 'fm shop_window component_add',
			// 	// 'on_main' => true,
			// 	'check' => 'on_page',
			// ),
			array(
				'name' => 'Магазини',
				'module' => 'where',
				'index' => 'index',
				'config' => '',
				'class' => 'fm shop_window component_add',
				'on_main' => true,
				// 'check' => 'on_page',
			),
			array(
				'name' => 'Форма зворотнього зв`язку',
				'module' => 'feedback',
				'index' => 'index',
				'config' => '',
				'class' => 'fm feedback component_add',
				'on_main' => true,
				// 'check' => 'on_page',
			),
			array(
				'name' => 'Каталог',
				'module' => 'catalog',
				'index' => 'index',
				'config' => '',
				'class' => 'fm com_catalog component_add',
				'on_main' => true,
				// 'check' => 'on_page',
			),
		),
		'menu' => array(
			array(
				'name' => 'Головне меню',
				'url' => 'admin/menu/index?menu_index=1&',
				'module' => 'menu',
				'index' => 1,
				'config' => '',
				'class' => 'fm main_menu',
			),
			array(
				'name' => 'Країни',
				'url' => 'admin/menu/index?menu_index=2&',
				'module' => 'menu',
				'index' => 2,
				'config' => '',
				'class' => 'fm main_menu',
			),
		),
		// 'sliders' => array(
		// 	array(
		// 		'name' => 'Мета-теги',
		// 		'url' => 'admin/seo/tags?',
		// 		'module' => 'seo',
		// 		'index' => 'tags',
		// 		'config' => '',
		// 		'class' => 'fm metetegs',
		// 	),
			
		// ),
		'seo' => array(
			array(
				'name' => 'Мета-теги',
				'url' => 'admin/seo/tags?',
				'module' => 'seo',
				'index' => 'tags',
				'config' => '',
				'class' => 'fm metetegs',
			),
			array(
				'name' => 'Xml карта сайту',
				'url' => 'admin/seo/xml?',
				'module' => 'seo',
				'index' => 'xml',
				'config' => '',
				'class' => 'fm xml',
			),
		),
		'config' => array(
			array(
				'name' => 'Загальні',
				'url' => 'admin/config/common?',
				'module' => 'config',
				'index' => 'common',
				'config' => '',
				'class' => 'fm common_set',
			),
			array(
				'name' => 'Мови',
				'url' => 'admin/config/languages?',
				'module' => 'config',
				'index' => 'languages',
				'config' => '',
				'class' => 'fm languages_set',
			),
			array(
				'name' => 'Адміністратори',
				'url' => 'admin/administrators/index?',
				'module' => 'config',
				'index' => 'administrators',
				'config' => '',
				'class' => 'fm access_set',
			),
			array(
				'name' => 'Заглушка',
				'url' => 'admin/config/gag?',
				'module' => 'config',
				'index' => 'gag',
				'config' => '',
				'class' => 'fm gag',
			),
			array(
				'name' => 'Водяний знак',
				'url' => 'admin/config/watermark?',
				'module' => 'config',
				'index' => 'watermark',
				'config' => '',
				'class' => 'fm watermark_set',
			),
			array(
				'name' => 'Статична інформація',
				'url' => 'admin/config/static_information?',
				'module' => 'config',
				'index' => 'static_information',
				'config' => '',
				'class' => 'fm static_information',
			),
		),
		/*
		array(
			'name' => '',
			'url' => '',
			'module' => '',
			'index' => '',
			'config' => '',
			'class' => '',
		),
		*/
	);
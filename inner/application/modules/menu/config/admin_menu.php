<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	return array(
		'root' => array(
			array(
				'code' => 'components',
				'name' => 'Сторінка',
				'url' => '',
			),
			array(
				'code' => 'seo',
				'name' => 'SEO',
				'url' => 'admin/seo/tags?',
			),
			array(
				'code' => 'config',
				'name' => 'Налаштування',
				'url' => '#',
			),
			array(
				'code' => 'menu',
				'name' => 'Меню',
				'url' => 'admin/menu/index?menu_index=1&',
			),
		),
		'components' => array(

			array(
				'name' => 'Стаття',
				'module' => 'article',
				'index' => 'index',
				'config' => '',
				'class' => 'fm com_article component_add',
				'on_main' => TRUE,
			),
            array(
                'name' => 'Контакти',
                'module' => 'google_map',
                'index' => 'index',
                'config' => '',
                'class' => 'fm google component_add',
                'on_main' => TRUE,
            ),
			 array(
			 	'name' => 'Послуги',
			 	'module' => 'benefit_services',
			 	'index' => 'index',
			 	'config' => '',
			 	'class' => 'fm com_catalog component_add',
			 	'on_main' => TRUE,
			 	'check' => 'on_page'
			 ),
			array(
				'name' => 'Про нас',
				'module' => 'benefits',
				'index' => 'index',
				'config' => '',
				'class' => 'fm com_news component_add',
				'on_main' => TRUE,
				'check' => 'on_page'
			),
            array(
                'name' => 'Swiper',
                'module' => 'swiper',
                'index' => 'index',
                'config' => '',
                'class' => 'fm com_reviews hidden component_add',
                'on_main' => TRUE,
                'check' => 'on_page'
            ),
			array(
				'name' => 'Переваги',
				'module' => 'guarantee',
				'index' => 'index',
				'config' => '',
				'class' => 'fm com_benefits component_add',
				'on_main' => TRUE,
				'check' => 'on_page'
			),
            array(
                'name' => 'Наші роботи',
                'module' => 'slider',
                'index' => 'index',
                'config' => '',
                'class' => 'fm slider component_add',
                'on_main' => TRUE,
                'check' => 'on_page'
            ),
			array(
				'name' => 'Зворотній зв\'язок',
				'module' => 'questions',
				'index' => 'index',
				'config' => '',
				'class' => 'fm feedback component_add',
				'on_main' => TRUE,
				'check' => 'on_page'
			),
		),
		'menu' => array(
			array(
				'name' => 'Меню сайту',
				'url' => 'admin/menu/index?menu_index=1&',
				'module' => 'menu',
				'index' => 1,
				'config' => '',
				'class' => 'fm main_menu',
			),
		),
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
				'name' => 'Seo-link',
				'url' => 'admin/seo/seo_link?',
				'module' => 'seo',
				'index' => 'seo_link',
				'config' => '',
				'class' => 'fm seo_link',
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
                'name' => 'Меню',
                'url' => 'admin/menu/index?',
                'module' => 'menu',
                'index' => 'index',
                'config' => '',
                'class' => 'fm menu',
            ),
			array(
				'name' => 'Номери телефонів',
				'url' => 'admin/config/header?',
				'module' => 'config',
				'index' => 'header',
				'config' => '',
				'class' => 'fm header_set',
			),
			array(
				'name' => 'Соціальні мережі',
				'url' => 'admin/config/main_footer?',
				'module' => 'config',
				'index' => 'footer',
				'config' => '',
				'class' => 'fm footer_set',
			),
			array(
				'name' => 'Назва сайту',
				'url' => 'admin/seo/site_name?',
				'module' => 'seo',
				'index' => 'site_name',
				'config' => '',
				'class' => 'fm site_name',
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
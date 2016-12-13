<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Search_model extends CI_Model
	{
		private $count = array();
		private $query = '';

		public function set_query($query = '')
		{
			if (LANG == 'ua') $def = 'Пошук...';
			if (LANG == 'ru') $def = 'Поиск...';
			if (LANG == 'en') $def = 'Search...';

			$this->query = $query;

			return (!empty($this->query) AND ($this->query != $def) AND mb_strlen($this->query) > 0) ? TRUE : FALSE;
		}

		public function count_results()
		{
			$count = array();

			// /* Articles */
			// $this->db->join('components', 'components.component_id = component_article.component_id');
			// $this->db->where('components.hidden', 0);

			// $this->db->join('menu', 'menu.id = component_article.menu_id');
			// $this->db->where('menu.hidden', 0);

			// $this->db->group_start();
			// $this->db->like('`'. $this->db->dbprefix . 'component_article`.`title_' . LANG . '`', $this->db->escape_like_str($this->query), 'both', FALSE);
			// $this->db->or_like('`' . $this->db->dbprefix . 'component_article`.`text_' . LANG . '`', $this->db->escape_like_str($this->query), 'both', FALSE);
			// $this->db->group_end();
			// //$this->db->group_by('component_article.component_id');

			// $count['articles'] = $this->db->count_all_results('component_article');

			/* Catalog */
			$this->db->join('menu', 'menu.id = catalog.menu_id');
			$this->db->where('menu.hidden', 0);

			$this->db->join('components', 'components.component_id = catalog.component_id');
			$this->db->where('components.hidden', 0);

			$this->db->where('catalog.hidden', 0);
			$this->db->group_start();
			$this->db->like('`'. $this->db->dbprefix . 'catalog`.`title_' . LANG . '`', $this->db->escape_like_str($this->query), 'both', FALSE);
			//$this->db->or_like('`'. $this->db->dbprefix . 'catalog`.`sub_title_' . LANG . '`', $this->db->escape_like_str($this->query), 'both', FALSE);
			//$this->db->or_like('`'. $this->db->dbprefix . 'catalog`.`sign_' . LANG . '`', $this->db->escape_like_str($this->query), 'both', FALSE);
			$this->db->or_like('`'. $this->db->dbprefix . 'catalog`.`text_' . LANG . '`', $this->db->escape_like_str($this->query), 'both', FALSE);
			//$this->db->or_like('`'. $this->db->dbprefix . 'catalog`.`additional_' . LANG . '`', $this->db->escape_like_str($this->query), 'both', FALSE);
			$this->db->group_end();

			$count['catalog'] = $this->db->count_all_results('catalog');

			// $this->db->join('components', 'components.component_id = news.component_id');
			// $this->db->join('menu', 'menu.id = news.menu_id');
			// $this->db->where('components.hidden', 0);
			// $this->db->where('menu.hidden', 0);
			// $this->db->group_start();
			// $this->db->like('`'. $this->db->dbprefix . 'news`.`title_' . LANG . '`', $this->db->escape_like_str($this->query), 'both', FALSE);
			// $this->db->or_like('`' . $this->db->dbprefix . 'news`.`text_' . LANG . '`', $this->db->escape_like_str($this->query), 'both', FALSE);
			// $this->db->group_end();
			// //$this->db->group_by('news.news_id');
			// $count['news'] = $this->db->count_all_results('news');

			$count['all'] = array_sum($count);

			return $count;
		}

		/* Catalog */

		public function catalog_preview()
		{
			$prefix = $this->db->dbprefix;

			$this->db->select('catalog.catalog_id, catalog.menu_id, menu.name_'.LANG.' as menu_name, catalog.title_' . LANG . ' as title, catalog.text_'.LANG.' as text, catalog.url_' . LANG . ' as url');
			$this->db->select('(select `' . $prefix . 'catalog_images`.`photo` from `' . $prefix . 'catalog_images` where `' . $prefix . 'catalog_images`.`catalog_id` = `' . $prefix . 'catalog`.`catalog_id` order by `' . $prefix . 'catalog_images`.`position` limit 1) as image', FALSE);

			$this->db->join('menu', 'menu.id = catalog.menu_id');
			$this->db->where('menu.hidden', 0);

			$this->db->join('components', 'catalog.component_id = components.component_id');
			$this->db->where('components.hidden', 0);

			$this->db->where('catalog.hidden', 0);
			$this->db->group_start();
			$this->db->like('`'. $this->db->dbprefix . 'catalog`.`title_' . LANG . '`', $this->db->escape_like_str($this->query), 'both', FALSE);
			//$this->db->or_like('`'. $this->db->dbprefix . 'catalog`.`sub_title_' . LANG . '`', $this->db->escape_like_str($this->query), 'both', FALSE);
			//$this->db->or_like('`'. $this->db->dbprefix . 'catalog`.`sign_' . LANG . '`', $this->db->escape_like_str($this->query), 'both', FALSE);
			$this->db->or_like('`'. $this->db->dbprefix . 'catalog`.`text_' . LANG . '`', $this->db->escape_like_str($this->query), 'both', FALSE);
			//$this->db->or_like('`'. $this->db->dbprefix . 'catalog`.`additional_' . LANG . '`', $this->db->escape_like_str($this->query), 'both', FALSE);
			$this->db->group_end();

			$results = $this->db->get('catalog')->result_array();

			foreach ($results as $key => $result) {
				$results[$result['menu_name']][$key] = $results[$key];
				unset($results[$result['menu_name']][$key]['menu_name'], $results[$key]);
			}
			
			return  $results;
		}

		// /* Articles */

		// public function articles_preview()
		// {
		// 	$this->db->select('component_article.title_' . LANG . ' as title, component_article.text_' . LANG . ' as text, menu.main, menu.url_' . LANG . ' as url');
		// 	$this->db->join('components', 'components.component_id = component_article.component_id');
		// 	$this->db->join('menu', 'menu.id = component_article.menu_id');
		// 	$this->db->where('components.hidden', 0);
		// 	$this->db->where('menu.hidden', 0);
		// 	$this->db->group_start();
		// 	$this->db->like('`'. $this->db->dbprefix . 'component_article`.`title_' . LANG . '`', $this->db->escape_like_str($this->query), 'both', FALSE);
		// 	$this->db->or_like('`' . $this->db->dbprefix . 'component_article`.`text_' . LANG . '`', $this->db->escape_like_str($this->query), 'both', FALSE);
		// 	$this->db->group_end();
		// 	$this->db->group_by('component_article.component_id');

		// 	$r = $this->db->get('component_article');

		// 	return ($r->num_rows() > 0) ? $r->result_array() : NULL;
		// }

		// /* News */

		// public function news_preview()
		// {
		// 	$this->db->select('news.title_' . LANG . ' as title, news.url_' . LANG . ' as url, news.anons_' . LANG . ' as anons, menu.main, menu.url_' . LANG . ' as menu_url');
		// 	$this->db->join('components', 'components.component_id = news.component_id');
		// 	$this->db->join('menu', 'menu.id = news.menu_id');
		// 	$this->db->where('components.hidden', 0);
		// 	$this->db->where('menu.hidden', 0);
		// 	$this->db->group_start();
		// 	$this->db->like('`'. $this->db->dbprefix . 'news`.`title_' . LANG . '`', $this->db->escape_like_str($this->query), 'both', FALSE);
		// 	$this->db->or_like('`' . $this->db->dbprefix . 'news`.`text_' . LANG . '`', $this->db->escape_like_str($this->query), 'both', FALSE);
		// 	$this->db->group_end();
		// 	$this->db->group_by('news.news_id');

		// 	$r = $this->db->get('news');

		// 	return ($r->num_rows() > 0) ? $r->result_array() : NULL;
		// }
	}

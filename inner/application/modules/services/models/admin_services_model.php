<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_services_model extends CI_Model
	{
		/**
		 * Загальна кількість всіх новин в компоненті
		 *
		 * @param int $component_id
		 * @return string
		 */
		public function count_services($component_id)
		{
			return $this->db->where('component_id', $component_id)->count_all_results('services');
		}

		/**
		 * Отримання всіх новин компоненту
		 *
		 * @param int $component_id
		 * @param int $page
		 * @return null
		 */
		public function get_services($component_id, $page)
		{
			$prefix = $this->db->dbprefix;

			$this->db->select('services.services_id, services.component_id, services.hidden, services.date, services.title as title, services.anons_' . LANG . ' as anons, services.price, services.text, menu.main, menu.url_path_' . LANG . ' as menu_url');
			$this->db->join('menu', 'services.menu_id = menu.id', 'inner');
			$this->db->where('services.component_id', $component_id);
			$this->db->limit(30, ($page > 0 ? $page - 1 : $page) * 30);
			$this->db->order_by('services.position', 'asc')->order_by('services.date', 'desc');

			return $this->db->get('services')->result_array();
		}

		/**
		 * Додавання новини
		 *
		 * @param array $set
		 * @return mixed
		 */
		public function insert($set)
		{
			$this->db->insert('services', $set);

			$services_id = $this->db->insert_id();

			$set = array(
				'item_id' => $services_id,
				'component_id' => $set['component_id'],
				'menu_id' => $set['menu_id'],
				'module' => 'services',
				'method' => 'details'
			);
			$this->db->insert('site_links', $set);

			$set = array(
				'item_id' => $services_id,
				'component_id' => $set['component_id'],
				'menu_id' => $set['menu_id'],
				'module' => 'services'
			);
			$this->db->insert('seo_tags', $set);

			return $services_id;
		}

		/**
		 * Отримання новини
		 *
		 * @param $services_id
		 */
		public function get($services_id)
		{
			return $this->db->get_where('services', array('services_id' => $services_id))->row_array();
		}

		/**
		 * Оновлення новини
		 *
		 * @param array $set
		 * @param array $where
		 */
		public function update($set, $where, $position=0)
		{
            $this->db->update('services', $set, $where);

			if($position==0){
			$languages = array_keys($this->config->item('languages'));

			$this->db->select('menu.main');

			foreach ($languages as $language)
			{
				$this->db->select('menu.url_path_' . $language);
			}

			$this->db->join('menu', 'menu.id = services.menu_id');
			$this->db->where('services.services_id', $where['services_id']);

			$menu = $this->db->get('services')->row_array();

			foreach ($languages as $val)
			{
				$url = ($menu['main'] == 0) ? $menu['url_path_' . $val] . '/' . $set['url_' . $val] : $set['url_' . $val];

				$this->db->set('hash_' . $val, md5($url));
				$this->db->where('item_id', $where['services_id']);
				$this->db->where('module', 'services');
				$this->db->update('site_links');
			}
			}
		}

		/**
		 * Отримання останньої позиції в компоненті
		 *
		 * @param int $component_id
		 * @return int
		 */
		public function get_position($component_id)
		{
			$this->db->set('`position`', '`position` + 1', FALSE);
			$this->db->where('component_id', $component_id);
			$this->db->update('services');

			return 0;
		}

		### Зображення ###

		public function get_image($image_id)
		{
			return $this->db->where('image_id', $image_id)->get('services_images')->row_array();
		}

		public function get_services_images($services_id)
		{
			return $this->db->where('services_id', $services_id)->order_by('position')->get('services_images')->result_array();
		}

		public function get_image_position($services_id)
		{
			$this->db->select_max('position', 'max');
			$this->db->where('services_id', $services_id);
			$r = $this->db->get('services_images');
			return $r->row()->max + 1;
		}

		public function insert_image($set)
		{
			$this->db->insert('services_images', $set);
			return $this->db->insert_id();
		}

		public function update_image($image_id, $set)
		{
			$this->db->update('services_images', $set, array('image_id' => $image_id));
		}

		/**
		 * Видалення зображення
		 *
		 * @param int $image_id
		 */
		public function delete_image($image_id)
		{
			$im = $this->db->select('services_id, component_id, file_name')->where('image_id', $image_id)->get('services_images')->row_array();

			if (count($im) > 0)
			{
				$dir = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/upload/services/' . $this->init_model->dir_by_id($im['services_id']) . '/' . $im['services_id'] . '/';
				$file_name = $im['file_name'];

				if (file_exists($dir . $file_name)) unlink($dir . $file_name);
				if (file_exists($dir . 's_' . $file_name)) unlink($dir . 's_' . $file_name);
				if (file_exists($dir . 't_' . $file_name)) unlink($dir . 't_' . $file_name);


				$this->db->delete('services_images', array('image_id' => $image_id));
			}
		}

		/**
		 * Видалення новини
		 *
		 * @param int $services_id
		 */
		public function delete($services_id)
		{
			$result = $this->db->select('component_id')->get_where('services', array('services_id' => $services_id))->row_array();

			if (count($result) > 0)
			{
				$dir = ROOT_PATH . 'upload/services/' . $this->init_model->dir_by_id($services_id) . '/' . $services_id . '/';
				delete_files($dir, TRUE, FALSE, 1);

				$this->db->delete('services', array('services_id' => $services_id));
				$this->db->delete('site_links', array('item_id' => $services_id, 'module' => 'services'));
				$this->db->delete('seo_tags', array('item_id' => $services_id, 'module' => 'services'));
			}
		}

		/**
		 * Видалення компоненту
		 *
		 * @param int $component_id
		 */
		public function delete_component($component_id)
		{
			$services = $this->db->select('services_id')->where('component_id', $component_id)->get('services')->result_array();

			foreach ($services as $v)
			{
				$this->db->delete('site_links', array('item_id' => $v['services_id'], 'module' => 'services'));
				$this->db->delete('seo_tags', array('item_id' => $v['services_id'], 'module' => 'services'));
			}

			$dir = ROOT_PATH . 'upload/services/' . $component_id . '/';
			delete_files($dir, TRUE, FALSE, 1);

			$this->db->delete('services', array('component_id' => $component_id));
			$this->db->delete('services_images', array('component_id' => $component_id));
			$this->db->delete('components', array('component_id' => $component_id));
		}

		/**
		 * Видалення компоненту останніх новин
		 *
		 * @param int $component_id
		 */
		public function delete_last_component($component_id)
		{
			$this->db->delete('components', array('component_id' => $component_id));
		}
	}


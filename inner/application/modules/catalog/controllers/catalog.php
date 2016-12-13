<?php defined('ROOT_PATH') or exit('No direct script access allowed');
	/**
	 * Компонент "Каталог аптек"
	 *
	 * @property Admin_catalog_model $admin_catalog_model
	 * @property Catalog_model $catalog_model
	 */
	class Catalog extends MX_Controller
	{
		/**
		 * Вивід компоненту каталогу аптек
		 *
		 * @param int $menu_id
		 * @param int $component_id
		 * @param int $hidden
		 * @param array $config
		 * @return string
		 */
		public function index($menu_id, $component_id, $hidden, array $config)
		{
			// echo "<pre>";
			// print_r( $component_id);
			// echo "</pre>";exit();
			$component_content = '';
			if ( $this->init_model->is_admin() and !$this->init_model->is_panel_hidden() and $this->init_model->check_access('catalog_index', $menu_id) ) {
				
				$this->load->model('admin_catalog_model');
				
				$is_parent = $this->admin_catalog_model->is_parent($menu_id);
				if ($is_parent)
				{
					$template_vars = array(
						'menu_id' => $menu_id,
						'component_id' => $component_id,
						'hidden' => $hidden,
					);
					$template_vars['categories'] = $this->admin_catalog_model->get_categories($menu_id);
					return $this->load->view('admin/categories_tpl', $template_vars, TRUE);
				}else{
					$this->init_model->set_menu_id($menu_id, true);
					$this->load->config('catalog');
					$this->template_lib
						->set_title('Управління каталогом аптек')
						->set_admin_menu_active('components')
						->set_admin_menu_active('catalog', 'sub_level');


					$component_content = $this->load->view(
						'catalog/admin/catalog_tpl',
						array(
							'menu_id' => $menu_id,
							'component_id' => $component_id,
							'hidden' => $hidden,
							'last' => (int)$this->session->userdata('last_catalog'),
							'thumb' => (array)$this->config->item('catalog_thumb_sizes'),
							'catalog' => $this->admin_catalog_model->get_catalog_list(array("component_id" => $component_id)),
						),
						true
					);
				}

				// $this->load->model('admin_catalog_model');
				// $component_content = $this->load->view(
				// 	'catalog/admin/catalog_component_tpl',
				// 	array(
				// 		'menu_id' => $menu_id,
				// 		'component_id' => $component_id,
				// 		'hidden' => $hidden,
				// 	),
				// 	true
				// );
			} else {
				$this->load->model('catalog_model');
				$is_parent = $this->catalog_model->is_parent($menu_id);
				if ($is_parent)
				{
					$template_vars['categories'] = $this->catalog_model->get_categories($menu_id);
					return $this->load->view('category_tpl', $template_vars, TRUE);
				}else{
					$items = $this->catalog_model->get_total();
					if ($items > 0) {
						$component_content = $this->load->view(
							'catalog/catalog_tpl',
							array(
								'component_id' => $component_id,
								'catalog' => $this->catalog_model->get_items($component_id),
							),
							true
						);
					}
				}
			}
			return $component_content;
		}
		/**
		 * Отримання списку маркерів
		 *
		 * @return string
		 */
		public function get_markers()
		{
			$response = array(
				'success' => false,
			);
			if ($this->input->is_ajax_request() and $this->security->csrf_verify()) {
				$this->load->model('catalog_model');
				$response['markers'] = $this->catalog_model->get_items();
				$response['success'] = true;
			}
			return json_encode($response);
		}
		/**
		 * Отримання аптеки
		 *
		 * @return string
		 */
		public function get_catalog()
		{
			$response = array(
				'success' => false,
			);
			$catalog_id = (int)$this->input->post('catalog_id');
			if ($this->input->is_ajax_request() and $this->security->csrf_verify() and $catalog_id > 0) {
				$this->load->model('catalog_model');
				$catalog = $this->catalog_model->get_item($catalog_id);
				if ($catalog !== null) {
					$this->load->helper('directory');
					$response['item'] = $this->load->view(
						'catalog/map_info_tpl',
						array(
							'catalog_id' => $catalog_id,
							'catalog' => $catalog,
						),
						true
					);
					$response['success'] = true;
				}
			}
			return json_encode($response);
		}
		/**
		 * Вивід компоненту "Ми відкрились"
		 *
		 * @param int $menu_id
		 * @param int $component_id
		 * @param int $hidden
		 * @param array $config
		 * @return string
		 */
		public function open($menu_id, $component_id, $hidden, array $config)
		{
			$component_content = '';
			if (
				$this->init_model->is_admin()
				and !$this->init_model->is_panel_hidden()
				and $this->init_model->check_access('catalog_open', $menu_id)
			) {
				$component_content = $this->load->view(
					'catalog/admin/open_component_tpl',
					array(
						'menu_id' => $menu_id,
						'component_id' => $component_id,
						'hidden' => $hidden,
					),
					true
				);
			} else {
				$this->load->model('catalog_model');
				$items = $this->catalog_model->get_open();
				if ($items > 0) {
					$component_content = $this->load->view(
						'catalog/open_tpl',
						array(
							'component_id' => $component_id,
							'anchor' => array_key_exists('anchor_' . LANG, $config) ? $config['anchor_' . LANG] : '',
							'items' => $items,
							'config' => $config,
						),
						true
					);
				}
			}
			return $component_content;
		}
	 	public function details($catalog_id = NULL)
	 	{
	 		
			if (!is_numeric($catalog_id)) show_404();
			$this->load->model('catalog_model');
			$catalog = $this->catalog_model->get_item($catalog_id);
			$this->load->helper('translit');
	 		// echo "<pre>";
	 		// print_r($catalog_id);
	 		// echo "</pre>";exit();
			$this->template_lib->set_h1();
			// $images = $this->catalog_model->get_images($catalog_id);
			if (count($catalog) == 0) show_404();
			$this->init_model->set_menu_id($catalog['menu_id']);
			$this->template_lib->set_title($catalog['title_'.LANG]);
			// $this->template_lib->set_bread_crumbs('', $catalog['title_'.LANG]);
			
			$this->load->library('seo_lib');
			$description = $this->seo_lib->generate_description($catalog['title_'.LANG]);
			$this->template_lib->set_description($description);
			$keywords = $this->seo_lib->generate_keywords($catalog['title_'.LANG]);
			$this->template_lib->set_keywords($keywords);
			$language_links = $this->catalog_model->get_language_links($catalog_id, $this->config->item('database_languages'));
			$this->template_lib->set_template_var('language_links', $language_links);

			$tpl_data = array(
				'catalog_id' => $catalog_id,
				'catalog' => $catalog,
				'shop_button' => ($this->init_model->get_component_link('where', null, true) != '#'? true : false),
				'shop_link' => $this->uri->full_url($this->init_model->get_component_link('where', null, true).'/'.translit(str_replace(' ', '-', strtolower($catalog['title_'.LANG]))).'/'.$catalog_id.'/'),
				'menu' => $this->catalog_model->get_menu(),
				'markers' => $this->catalog_model->get_all_markers(),
				'h1' => $this->template_lib->get_h1(),
				'menu_id'=> $catalog['menu_id']
			);
			$content = $this->load->view('details_tpl', $tpl_data, TRUE);
			$this->template_lib->set_content($content);
	 	}
	}
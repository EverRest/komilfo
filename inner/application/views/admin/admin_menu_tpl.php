<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');
	/** @var int $menu_id */
	/** @var array $admin_menu */
	/** @var array $admin_menu_rules */
	/** @var string $page_content */
	$this->template_lib->set_css('admin.css');
	$this->template_lib->set_js('admin/plugins.js');
	$top_level = isset($active['top_level']) ? $active['top_level'] : '';
	$sub_level = isset($active['sub_level']) ? $active['sub_level'] : '';
	if ((int)$menu_id === 0) {
		$menu_id = (int)$this->db->select('id')->where('main', 1)->get('menu')->row('id');
	}
	$is_main = $this->init_model->is_main();
	$page_uri = $this->init_model->get_link($menu_id, '{URL}');
	$root_admin = $this->session->userdata('admin_root');
	foreach ($admin_menu as $k => $v) {
		foreach ($v as $_k => $_v) {
			if ($k === 'root')
			{
				if (!in_array($_v['code'], $admin_menu_rules, true) and (int)$root_admin === 0) {
					if (array_key_exists($_v['code'], $admin_menu))
					{
						unset($admin_menu[$_v['code']]);
					}
					unset($admin_menu[$k][$_k]);
				}
			} else {
				if (!in_array($_v['module'] . '_' . $_v['index'], $admin_menu_rules, true) and (int)$root_admin === 0) {
					unset($admin_menu[$k][$_k]);
				}
			}
		}
	}
	if (isset($admin_menu['root'])) {
		foreach ($admin_menu['root'] as &$v) {
			if ($v['url'] === '') {
				$v['url'] = $page_uri;
			} else {
				if (array_key_exists($v['code'], $admin_menu) and count($admin_menu[$v['code']]) > 0) {
					$admin_menu[$v['code']] = array_values($admin_menu[$v['code']]);
					$v['url'] = $this->uri->full_url($admin_menu[$v['code']][0]['url'] . 'menu_id=' . $menu_id);
				} else {
					$v['url'] = $this->uri->full_url($v['url'] . 'menu_id=' . $menu_id);
				}
			}
		}
	} else {
		$admin_menu = array('root' => '');
	}
?>
<div class="admin_base">
	<div class="fm main_panel">
		<div class="fm top_info_part">
			<div class="fm tip_admin"><?=$this->session->userdata('admin_name')?></div>
			<div class="tip_logo"></div>
			<a href="<?=$this->uri->full_url('admin/login/logout');?>" class="fmr tip_exit">Вийти</a>
		</div>
		<div class="fm middle_buttons_part">
			<div class="mbp_width">
				<?php foreach ($admin_menu['root'] as $k => $item): ?>
					<a href="<?=$item['url'];?>"<?php if ($top_level === $item['code'] OR ($k === 0 and $top_level === 'catalog_config')): ?> class="active"<?php endif; ?>><b></b><?=$item['name'];?><?=((isset($item['counter']) AND $item['counter']) > 0 ? '(' . $item['counter'] . ')' : '');?></a>
				<?php endforeach; ?>
			</div>
		</div>
		<?php if (isset($admin_menu[$top_level]) AND $sub_level !== ''): ?>
			<div class="fm bottom_links_part" id="components_controls">
				<?php foreach ($admin_menu[$top_level] as $item): ?>
					<?php if ((!$is_main and !array_key_exists('only_main', $item)) or ($is_main and (isset($item['on_main'])) or $top_level !== 'components')): ?>
						<a href="<?=!isset($item['url']) ? '#' : $this->uri->full_url($item['url'] . 'menu_id=' . $menu_id);?>" class="<?=$item['class'];?><?=($sub_level === $item['index']) ? ' active' : '';?>" data-module="<?=$item['module'];?>" data-method="<?=$item['index'];?>" data-config="<?=$item['config'];?>"<?php if (array_key_exists('check', $item) and $this->init_model->check_component(($item['check'] === 'on_page' ? $menu_id : ''), $item['module'], $item['index'])): ?> style="display: none"<?php endif; ?>><b></b><?=$item['name'];?><?=((isset($item['counter']) AND $item['counter']) > 0 ? '(' . $item['counter'] . ')' : '');?></a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
	<div id="admin_components_list"><?=$page_content;?></div>
</div>
<script type="text/javascript">
//<![CDATA[
	var delete_alert = <?=(int)$this->config->item('delete_alert') === 1 ? 'true': 'false';?>, menu_id = <?=$menu_id;?>;
	function component_position(callback) {
		var components = [];
		$('.admin_component').each(function (i, val) {
			components[i] = $(val).data('component-id');
		});
		if (components.length > 0) {
			$.post(
				full_url('admin/components/update_position'),
				{
					components: components,
					menu_id: menu_id
				},
				function () {
					if ($.type('callback') === 'function') callback();
					global_helper.loader($('.component_loader'));
				},
				'json'
			);
		}
	}
	$(function () {
		component_controls();
		$('#components_controls').on('click', '.component_add', function (e) {
			e.preventDefault();
			var request = {
					menu_id: menu_id,
					module: $(this).data('module'),
					method: $(this).data('method'),
					config: $(this).data('config')
				},
				reload = false;
			if ($(this).hasClass('com_catalog')) {
				$(this).hide();
				reload = true;
			}
			$.post(
				full_url('admin/components/insert'),
				request,
				function (response) {
					if (response.success) {
						if (reload) {
							window.location.reload();
						} else {
							$('#admin_components_list').prepend(response.component);
							component_controls();
						}
					}
				},
				'json'
			);
		});
	});
	$(function () {
		$('.hide_adm_panel').on('click', function (e) {
			e.preventDefault();
			$.post(
				full_url('admin/login/hide_panel'),
				{
					menu_id: menu_id
				},
				function (response) {
					if (response.success) {
						window.location.reload();
						//window.location.href = response.uri;
					}
				},
				'json'
			)
		});
	});
//]]>
</script>
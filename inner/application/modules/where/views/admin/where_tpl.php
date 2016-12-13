<?php defined('ROOT_PATH') OR exit('No direct script access allowed'); ?>
<div class="admin_component" id="admin_component_<?=$component_id;?>" data-component-id="<?=$component_id;?>" data-menu-id="<?=$menu_id;?>" data-module="where" data-css-class="where" data-visibility-url="<?=$this->uri->full_url('admin/components/toggle_visibility');?>" data-delete-url="<?=$this->uri->full_url('admin/where/delete_component');?>">
	<div class="component_loader"><span></span></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component shop_window">
			<div class="<?=(($hidden == 0) ? 'catalog' : 'hidden');?>"></div>
		</div>
		<div class="fm component_edit_links">
			<div class="fm only_text"><div>Магазини</div></div>
		</div>
		<div class="fmr component_del">
			<a href="#" class="fm delete_component"><b></b></a>
		</div>
		<div class="fmr component_pos">
			<a href="#" class="fm up_component"><b></b></a>
			<a href="#" class="fm down_component"><b></b></a>
		</div>
		<div class="fmr component_edit_links">
			<a href="#" class="fm show_hide"><b></b><?=(($hidden == 0) ? 'Приховати' : 'Показати');?></a>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function () {
		$('#admin_component_<?=$component_id;?>').component();
	});
</script>
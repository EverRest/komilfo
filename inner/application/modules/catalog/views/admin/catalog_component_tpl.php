<?php defined('ROOT_PATH') or exit('No direct script access allowed');
	/** @var int $menu_id */
	/** @var int $component_id */
?>
<div class="fm admin_component" id="admin_component_<?=$component_id;?>" data-component-id="<?=$component_id;?>" data-menu-id="<?=$menu_id;?>" data-module="catalog" data-css-class="catalog" data-visibility-url="<?=$this->uri->full_url('admin/components/toggle_visibility');?>" data-delete-url="<?=$this->uri->full_url('admin/catalog/delete_component');?>">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="catalog"></div>
		</div>
		<div class="fm component_edit_links">
			<div class="fm only_text"><div>Каталог суконь</div></div>
			<a class="fm edit" href="<?=$this->uri->full_url('admin/catalog/index?menu_id=' . $menu_id. '&component_id='. $component_id);?>"><b></b>Редагувати каталог суконь</a>
		</div>
		<div class="fmr component_del">
			<a href="#" class="fm delete_component"><b></b></a>
		</div>
		<div class="fmr component_pos">
			<a href="#" class="fm up_component"><b></b></a>
			<a href="#" class="fm down_component"><b></b></a>
		</div>
	</div>
	<div class="fm admin_massage">
		Вивід каталогу суконь
	</div>
</div>
<script type="text/javascript">
	$(function () {
		$('#admin_component_<?=$component_id;?>').component();
	});
</script>
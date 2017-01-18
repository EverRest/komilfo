<?php defined('ROOT_PATH') OR exit('No direct script access allowed'); ?>
<div class="admin_component" id="admin_component_<?=$component_id;?>" data-component-id="<?=$component_id;?>" data-menu-id="<?=$menu_id;?>" data-module="google_map" data-css-class="google_map" data-visibility-url="<?=$this->uri->full_url('admin/components/toggle_visibility');?>" data-delete-url="<?=$this->uri->full_url('admin/google_map/delete_component');?>">
	<div class="component_loader"><span></span></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="<?=(($hidden == 0) ? 'google_map' : 'hidden');?>"></div>
		</div>
		<div class="fm component_edit_links">
        	<div class="fm only_text"><div>Наші Контакти</div></div>
			<a href="<?=$this->uri->full_url('/admin/google_map/edit?menu_id=' . $menu_id . '&component_id=' . $component_id);?>" class="fm edit"><b></b>Редагувати</a>
			<a href="#" class="fm show_hide"><b></b><?=(($hidden == 0) ? 'Приховати' : 'Показати');?></a>
		</div>
		<div class="fmr component_del">
			<a href="#" class="fm delete_component"><b></b></a>
		</div>
		<div class="fmr component_pos">
			<a href="#" class="fm up_component"><b></b></a>
			<a href="#" class="fm down_component"><b></b></a>
		</div>
	</div>
	<article>
		<?php if (isset($google_map) AND count($google_map) > 0): ?>
			<?php if (!$h1): ?><h1><?='Графік роботи і контакти'?></h1><?php else: ?><h2><?='Графік роботи і контакти';?></h2><?php endif; ?><br>
			<?=stripslashes($google_map['schedule']); ?><br><?=stripslashes($google_map['information']); ?><br><br>
		<?php else: ?>
			<header><h2>Контакти</h2></header>
		<?php endif; ?>
	</article>
</div>
<script type="text/javascript">
	$(function () {
		$('#admin_component_<?=$component_id;?>').component();
	});
</script>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="admin_component" id="admin_component_<?=$component_id;?>" data-component-id="<?=$component_id;?>" data-menu-id="<?=$menu_id;?>" data-module="reviews" data-css-class="reviews" data-visibility-url="<?=$this->uri->full_url('admin/components/toggle_visibility');?>" data-delete-url="<?=$this->uri->full_url('admin/reviews/delete_last_component');?>">
	<div class="component_loader"><span></span></div>
	<div class="fm sub_admin_part_bg">
		<div class="apm_1"></div>
		<div class="apm_2"></div>
		<div class="apm_3"></div>
	</div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="<?=(($hidden == 0) ? 'reviews' : 'hidden');?>"></div>
		</div>
		<div class="fm component_edit_links">
			<div class="fm only_text"><div>Останні відгуки наших клієнтів</div></div>
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
	<div class="fm admin_massage"><?php if ($reviews_url != ''): ?><a href="<?=$reviews_url;?>">Перейти</a> до редагування відгуків.<?php endif; ?></div>
</div>
<script type="text/javascript">
	$(function () {
		$('#admin_component_<?=$component_id;?>').component({
			onDelete: function () {
				$('.com_last_reviews').show();
			}
		});
	});
</script>
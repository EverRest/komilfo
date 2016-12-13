<?php defined('ROOT_PATH') OR exit('No direct script access allowed'); ?>
<div class="admin_component" id="admin_component_<?=$component_id;?>" data-component-id="<?=$component_id;?>" data-menu-id="<?=$menu_id;?>" data-module="article" data-css-class="article" data-visibility-url="<?=$this->uri->full_url('admin/components/toggle_visibility');?>" data-delete-url="<?=$this->uri->full_url('admin/article/delete_component');?>">
	<div class="component_loader"><span></span></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="<?=(($hidden == 0) ? 'article' : 'hidden');?>"></div>
		</div>
		<div class="fm component_edit_links">
        	<div class="fm only_text"><div>Стаття</div></div>
			<a href="<?=$this->uri->full_url('/admin/article/edit?menu_id=' . $menu_id . '&component_id=' . $component_id);?>" class="fm edit"><b></b>Редагувати</a>
			<a href="#" class="fm show_hide <?=($hidden == 0) ? "active": "";?>"><b></b><?=(($hidden == 0) ? 'Приховати' : 'Показати');?></a>
		</div>
		<div class="fmr component_del">
			<a href="#" class="fm delete_component"><b></b></a>
		</div>
		<div class="fmr component_pos">
			<a href="#" class="fm up_component"><b></b></a>
			<a href="#" class="fm down_component"><b></b></a>
		</div>
	</div>
	<div class="fm admin_menu">
		<article>
			<?php if (isset($article) AND count($article) > 0): ?>
	        	<header>
					<?php if (!$h1): ?><h1><?=$article['title'];?></h1><?php else: ?><h2><?=$article['title'];?></h2><?php endif; ?>
	            </header>
	            <div class="fm article_text">
					<?=stripslashes($article['text']); ?>
	            </div>
			<?php else: ?>
				<header><h2>Нова стаття</h2></header>
			<?php endif; ?>
		</article>
	</div>
</div>
<script type="text/javascript">
	$(function () {
		$('#admin_component_<?=$component_id;?>').component();
	});
</script>
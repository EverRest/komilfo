<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');

	$this->template_lib->set_js('admin/jquery.form.js');
?>
<div class="fm admin_base admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="site_name"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save"><b></b>Зберегти</a>
		</div>
		<?php if (count($languages) > 1): ?>
		<div class="fmr component_lang" id="site_name_langs">
			<?php foreach ($languages as $key => $val): ?>
			<a href="#" class="flags <?=$key;?><?=(($key == LANG) ? ' active' : '');?>" data-language="<?=$key;?>"><img src="<?=base_url('img/flags_' . $key . '.png');?>" alt=""></a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
	<form id="site_name_form" action="<?=$this->uri->full_url('admin/seo/save_site_name');?>" method="post">
		<?php foreach ($languages as $key => $val): ?>
		<div id="site_name_tab_<?=$key;?>" class="site_name_tab"<?=(($key != LANG) ? '  style="display:none"' : '');?>>
			<div class="evry_title">
				<label for="site_name_<?=$key;?>" class="block_label">Назва сайту:</label>
				<input type="text" id="site_name_<?=$key;?>" name="site_name[<?=$key;?>]" value="<?=(isset($site_name[$key]) ? form_prep($site_name[$key]) : '');?>">
			</div>
		</div>
		<?php endforeach; ?>
		<div class="fm for_sucsess">
			<div class="fmr save_links">
				<a href="#" class="fm save_adm"><b></b>Зберегти</a>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(function () {
		$('#site_name_langs').on('click', 'a', function (e) {
			e.preventDefault();
			$('#site_name_langs a').removeClass('active');
			$(this).addClass('active');
			$('.site_name_tab').hide();
			$('#site_name_tab_' + $(this).data('language')).show();
		});

		/**
		 * Збереження
		 */
		$('.save_adm, .component_edit_links .save').on('click', function (e) {
			e.preventDefault();

			$('#site_name_form').ajaxSubmit({
				beforeSubmit: function () {
					component_loader_show($('.component_loader'), '');
				},
				success: function (response) {
					if (response.success) {
						component_loader_hide($('.component_loader'), '');
						$('.for_sucsess .sucsess').fadeTo(200, 1).delay(2000).fadeTo(200, 0);
					}
				},
				dataType: 'json'
			});
		});
	});
</script>
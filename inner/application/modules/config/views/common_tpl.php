<?php defined('ROOT_PATH') or exit('No direct script access allowed');
//$this->template_lib->set_js('admin/ckeditor/ckeditor.js');
?>
<div class="fm admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="common"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save save_config"><b></b>Зберегти</a>
		</div>
		<?php if (count($languages) > 1): ?>
			<div class="fmr component_lang">
				<?php foreach ($languages as $key => $val): ?>
					<a href="#" class="flags <?=$key;?><?=(($key == LANG) ? ' active' : '');?>" data-language="<?=$key;?>"><img src="<?=base_url("img/flags_".$key.".png")?>"></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
	<?foreach ($languages as $key => $value) {?>
		<div id="common_lang_<?=$key;?>" class="common_lang"<?php if (LANG != $key) echo ' style="display: none"'; ?>>
			<div class="evry_title">
				<label class="block_label" for="config_site_name">Назва сайту:</label>
				<input type="text" id="config_site_name" name="config[site_name_<?=$key?>]" value="<?=form_prep(stripslashes($config['site_name_' . $key]));?>">
			</div>
		</div>
	<?}?>
	<div class="evry_title">
		<label class="block_label" for="config_site_email">Основний e-mail:</label>
		<input type="text" id="config_site_email" name="config[site_email]" value="<?=form_prep(stripslashes($config['site_email']));?>" class="short">
	</div>
	<!--div class="evry_title">
		<label class="block_label" for="config_address">Адреса(<?=LANG;?>):</label>
		<div class="no_float"><textarea id="config_address" name="config[address_<?=LANG;?>]"><?=stripslashes($config['address_' . LANG]);?></textarea></div>
	</div-->
	<!--div class="evry_title" style="margin-top: 20px">
		<label class="block_label" for="config_print_icon">&nbsp;</label>
		<div class="fm controls">
			<label class="check_label">
				<i>
					<input type="checkbox" id="config_print_icon" name="config[print_icon]" value="1"<?php if ((int)$config['print_icon'] === 1): ?> checked="checked"<?php endif; ?>>
				</i>
				додати іконку друку
			</label>
		</div>
	</div-->
	<div class="evry_title">
		<label class="block_label" for="delete_alert">&nbsp;</label>
		<div class="fm controls">
			<label class="check_label">
				<i>
					<input type="checkbox" id="config_delete_alert" name="config[delete_alert]" value="1"<?php if ((int)$config['delete_alert'] === 1): ?> checked="checked"<?php endif; ?>>
				</i>
				попередження при видаленні
			</label>
		</div>
	</div>
	<div class="fm for_sucsess short">
		<div class="fmr save_links">
			<a href="#" class="fm save_adm save_config"><b></b>Зберегти</a>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function () {
		$('.component_lang').on('click', 'a', function (e) {
			e.preventDefault();
			$('.common_lang').hide();
			$('#common_lang_' + $(this).data('language')).show();
			$(this).addClass('active').siblings().removeClass('active');
		});
		var $component = $('.admin_component');
		$component.on('click', '.save_config', function (e) {
			e.preventDefault();
			var request = {};
			$component.find('input').add($component.find('textarea')).map(function () {
				if ($(this).attr('type') !== undefined && $(this).attr('type').toLowerCase() === 'checkbox') {
					request[$(this).attr('name')] = $(this).prop('checked') ? $(this).val() : null;
				} else if ($(this).attr('type') !== undefined && $(this).attr('type').toLowerCase() === 'radio') {
					request[$(this).attr('name')] = $('[name="' + $(this).attr('name') + '"]:checked').val();
				} else {
					request[$(this).attr('name')] = $(this).val();
				}
			});
			global_helper.loader($component);
			$.post(
				full_url('admin/config/save_common'),
				request,
				function (response) {
					if (response.success) {
						global_helper.loader($component);
					}
				},
				'json'
			);
		});
	});
</script>
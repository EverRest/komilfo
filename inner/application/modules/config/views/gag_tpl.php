<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');
	$this->template_lib->set_js('admin/ckeditor/ckeditor.js');
?>
<div class="fm admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="gag"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save"><b></b>Зберегти</a>
		</div>
		<?php if (count($languages) > 1): ?>
			<div class="fmr component_lang">
				<?php foreach ($languages as $key => $val): ?>
					<a href="#" class="flags <?=$key;?><?=(($key == LANG) ? ' active' : '');?>" data-language="<?=$key;?>"><img src="img/flags_<?=$key;?>.png"></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="evry_title">
		<label class="block_label">&nbsp;</label>
		<div class="no_float">
			На закритий сайт можна потрапити за посиланням: <b><?=$this->uri->full_url();?>?is_open</b>
		</div>
	</div>
	<div class="evry_title">
		<label class="block_label">Закрити сайт:</label>
		<div class="fm controls">
			<label class="check_label"><i><input type="checkbox" name="is_gag" value="1"<?php if ((int)$config['is_gag'] === 1) echo ' checked="checked"'; ?>></i></label>
		</div>
	</div>
	<?php foreach ($languages as $key => $val): ?>
		<div class="lang_tab lang_tab_<?=$key;?>"<?=((LANG != $key) ? ' style="display:none"' : '');?>>
			<div class="evry_title">
				<label for="text_<?=$key;?>" class="block_label">Повідомлення про закритття:</label>
				<div class="no_float"><textarea id="text_<?=$key;?>" class="gag_text" name="<?=$key;?>"><?=stripslashes($config[$key]);?></textarea></div>
			</div>
		</div>
	<?php endforeach; ?>
	<div class="fm for_sucsess short">
		<div class="fmr save_links">
			<a href="#" class="fm save_adm"><b></b>Зберегти</a>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		$('.component_lang').on('click', 'a', function (e) {
			e.preventDefault();
			$(this).addClass('active').siblings().removeClass('active');
			$('.lang_tab').hide();
			$('.lang_tab_' + $(this).data('language')).show();
		});
		$('.gag_text').ckeditor();
		$('.for_sucsess .save_adm, .component_edit_links .save').on('click', function (e) {
			e.preventDefault();
			global_helper.loader($('.component_loader'), '');
			$('.gag_text').ckeditor({action: 'update'});
			var uri = '<?=$this->uri->full_url('admin/config/save_gag');?>',
				request = {
					ua: $('textarea[name="ua"]').val(),
					ru: $('textarea[name="ru"]').val(),
					en: $('textarea[name="en"]').val(),
					is_gag: $('input[name="is_gag"]').prop('checked') ? 1 : 0
				};
			$.post(
				uri,
				request,
				function (response) {
					if (response.success) global_helper.loader($('.component_loader'), '');
				},
				'json'
			);
		});
	});
</script>
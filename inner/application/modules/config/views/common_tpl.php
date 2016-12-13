<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="fm admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="common"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save"><b></b>Зберегти</a>
		</div>
	</div>
	<div class="evry_title">
		<label class="block_label">Основний e-mail:</label>
		<input type="text" name="site_email" value="<?=$config['site_email'];?>" class="short">
	</div>
	<div class="evry_title">
		<label class="block_label">Додати іконку друку:</label>
		<div class="fm select"><input type="checkbox" name="print_icon" value="1"<?php if ($config['print_icon'] == 1) echo ' checked="checked"'; ?> /></div>
	</div>
	<div class="evry_title">
		<label class="block_label">Попередження при видаленні:</label>
		<div class="fm select" style="margin-top: 10px"><input type="checkbox" name="delete_alert" value="1"<?php if ($config['delete_alert'] == 1) echo ' checked="checked"'; ?> /></div>
	</div>
	<div class="fm for_sucsess short">
		<div class="fmr save_links">
			<a href="#" class="fm save_adm"><b></b>Зберегти</a>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {

		$('[name="print_icon"]').add('[name="delete_alert"]').iphoneStyle({
			resizeContainer: false,
			resizeHandle: false,
			onChange: function(elem, value) {
				(value === true) ? $(elem).attr('checked', 'checked') : $(elem).removeAttr('checked');
			}
		});

		/**
		 * Збереження змін
		 */
		$('.for_sucsess .save_adm, .component_edit_links .save').on('click', function (e) {
			e.preventDefault();

			component_loader_show($('.component_loader'), '');

			var uri = '<?=$this->uri->full_url('admin/config/save_common');?>',
				request = {
					site_email: $('input[name="site_email"]').val(),
					print_icon: ($('input[name="print_icon"]').attr('checked') === 'checked') ? 1 : 0,
					delete_alert: ($('input[name="delete_alert"]').attr('checked') === 'checked') ? 1 : 0
				};

			$.post(
				uri,
				request,
				function (response) {
					if (response.success) component_loader_hide($('.component_loader'), '');
				},
				'json'
			);
		});
	});
</script>
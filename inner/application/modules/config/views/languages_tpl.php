<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="fm admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="languages"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save"><b></b>Зберегти</a>
		</div>
	</div>
	<div class="fm common_settings">
		<div class="evry_title">
			<div class="fm language_labels">
				<label class="block_label">Основна мова:</label>
				<label class="block_label">Доступні мови:</label>
			</div>
			<?php
				$config['languages'] = $config['languages'] != '' ? unserialize($config['languages']) : array();

				$i = 0;
				foreach ($languages as $key => $val)
				{
			?>
			<div class="fm one_lang_set<?php if ($i % 2 == 0) echo ' grey'; ?>">
				<div class="fm for_lang_set">
					<div class="fm radio<?php if ($key == $config['def_lang']) echo ' checked'; ?>">
						<input type="radio" value="<?php echo $key; ?>"<?php if ($key == $config['def_lang']) echo ' checked="checked"'; ?> />
					</div>
					<div class="fm check<?php if (in_array($key, $config['languages'])) echo ' checked'; ?>">
						<input type="checkbox" value="<?php echo $key; ?>"<?php if (in_array($key, $config['languages'])) echo ' checked="checked"'; ?> />
					</div>
				</div>
				<div class="fm flag">
					<div class="fm <?php echo $key; ?>"></div><?php echo mb_substr($val, 0, 3); ?>
				</div>
			</div>
			<?php
					$i++;
				}
			?>
		</div>
	</div>
	<div class="fm for_sucsess short">
		<div class="fmr save_links">
			<a href="#" class="fm save_adm"><b></b>Зберегти</a>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {

		/**
		 * Radio
		 */

		$('.for_lang_set .check').each(function () {
			if (!$(this).hasClass('checked')) {
				$(this).prevAll('.radio:eq(0)').addClass('no_active');
			}
		});

		$('.for_lang_set .radio').on('click', function () {

			if ($(this).nextAll('.check:eq(0)').hasClass('checked')) {
				$('.for_lang_set .radio').removeClass('checked');
				$('.for_lang_set .radio').find('input').removeAttr('checked');

				$(this).addClass('checked');
				$(this).find('input').attr('checked', 'checked');
			}
		});

		/**
		 * Checkbox
		 */
		$('.for_lang_set .check').on('click', function () {

			if ($(this).hasClass('checked')) {
				if (!$(this).closest('.for_lang_set').find('.radio').hasClass('checked')) {
					$(this).removeClass('checked');
					$(this).find('input').removeAttr('checked');
					$(this).closest('.one_lang_set').find('.radio').addClass('no_active');
					$(this).closest('.one_lang_set').find('.flag div').addClass('no_active');
				}
			} else {
				$(this).addClass('checked');
				$(this).find('input').attr('checked', 'checked');
				$(this).closest('.one_lang_set').find('.radio').removeClass('no_active');
				$(this).closest('.one_lang_set').find('.flag div').removeClass('no_active');
			}
		});

		$('.for_sucsess .save_adm, .component_edit_links .save').on('click', function (e) {
			e.preventDefault();

			component_loader_show($('.component_loader'), '');

			var languages = [];

			$('.one_lang_set input[type="checkbox"]:checked').each(function (i, val) {
				languages.push($(val).val());
			});

			var uri = '<?php echo $this->uri->full_url('admin/config/save_languages'); ?>',
				request = {
					def_lang : $('.for_lang_set .radio.checked').find('input').val(),
					languages : languages
				};

			$.post(
				uri,
				request,
				function (response) {
					if (response.success) {
						component_loader_hide($('.component_loader'), '');
					}
				},
				'json'
			);

		});
	});
</script>
<?php defined('ROOT_PATH') or exit('No direct script access allowed');
	$config['languages'] = $config['languages'] !== '' ? unserialize(stripslashes($config['languages'])) : array();
?>
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
				$i = 0;
				foreach ($languages as $key => $val)
				{
			?>
			<div class="fm one_lang_set<?php if ($i % 2 === 0): ?> grey<?php endif; ?>">
				<div class="fm for_lang_set">
					<div class="fm radio<?php if ($key === $config['def_lang']): ?> checked<?php endif; ?>">
						<input type="radio" value="<?=$key;?>"<?php if ($key === $config['def_lang']): ?> checked="checked"<?php endif; ?>>
					</div>
					<div class="fm check<?php if (in_array($key, $config['languages'], true)): ?> checked<?php endif; ?>">
						<input type="checkbox" value="<?=$key;?>"<?php if (in_array($key, $config['languages'], true)): ?> checked="checked"<?php endif; ?>>
					</div>
				</div>
				<div class="fm flag">
					<div class="fm <?=$key;?>"></div><?=mb_substr($val, 0, 3);?>
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
		var $component = $('.admin_component');
		/**
		 * Radio
		 */
		$component.find('.for_lang_set').find('.check').map(function () {
			if (!$(this).hasClass('checked')) {
				$(this).prevAll('.radio').eq(0).addClass('no_active');
			}
		});
		$component.find('.for_lang_set').on('click', '.radio', function () {
			if ($(this).nextAll('.check').eq(0).hasClass('checked')) {
				$component.find('.for_lang_set')
					.find('.radio').removeClass('checked')
					.find('input').prop('checked', false);
				$(this).addClass('checked').find('input').prop('checked', true);
			}
		});
		/**
		 * Checkbox
		 */
		$component.find('.for_lang_set').on('click', '.check', function () {
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
			global_helper.loader($component);
			var languages = [];
			$('.one_lang_set input[type="checkbox"]:checked').map(function (i, val) {
				languages.push($(val).val());
			});
			var uri = '<?=$this->uri->full_url('admin/config/save_languages');?>',
				request = {
					config: {
						def_lang : $('.for_lang_set .radio.checked').find('input').val(),
						languages : languages
					}
				};
			$.post(
				uri,
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
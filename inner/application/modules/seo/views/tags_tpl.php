<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	$this->template_lib->set_js('admin/checkboxes.js');
	$this->template_lib->set_js('admin/textarea.js');
	$this->template_lib->set_js('admin/jquery.form.js');
?>
<div class="admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="metetegs"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save"><b></b>Зберегти</a>
		</div>
		<div class="fmr component_lang" id="tags_langs">
			<?php if (count($languages) > 1): ?>
				<?php foreach ($languages as $key => $val): ?>
					<a href="#" class="flags <?=$key;?><?php echo ($key == LANG) ? ' active' : ''; ?>" data-language="<?=$key;?>"><img src="img/flags_<?=$key;?>.png"></a>
				<?php endforeach; ?>
			<?php else: ?>
				<a href="#" class="flags <?=LANG;?> active" data-language="<?=LANG;?>" style="display: none"></a>
			<?php endif; ?>
		</div>
	</div>
	<form id="tags_form" action="<?=$this->uri->full_url('admin/seo/update_tags');?>" method="post">
		<input type="hidden" name="tags_id" value="<?=$tags['tags_id']; ?>">
		<div class="fm editing_metategs">
			<?php foreach ($languages as $key => $val): ?>
			<div id="tags_<?=$key;?>" class="tags_tab"<?=(($key != LANG) ? ' style="display:none"' : '');?>>
				<div class="evry_title">
					<label for="tags_type" class="block_label">Тип розкрутки:</label>
					<div class="no_float">
						<div class="select">
							<input type="checkbox" id="tags_type_<?=$key;?>" name="type[<?=$key;?>]" value="1"<?=(($tags['type_' . $key] == 1) ? ' checked="checked"' : '');?>>
						</div>
					</div>
				</div>
				<div class="evry_title">
					<label for="meta_title_<?=$key;?>" class="block_label">Назва сторінки:</label>
					<input type="text" id="meta_title_<?=$key;?>" name="title[<?=$key;?>]" value="<?=$tags['title_' . $key];?>">
				</div>
				<div class="evry_title">
					<label for="meta_description_<?=$key;?>" class="block_label">Опис сторінки:</label>
					<textarea id="meta_description_<?=$key;?>" name="description[<?=$key;?>]"><?=$tags['description_' . $key];?></textarea>
					<div class="letters_sum" id="letters_sum_<?=$key;?>"><?=mb_strlen($tags['description_' . $key]);?></div>
				</div>
				<div class="evry_title">
					<label for="meta_keywords_<?=$key;?>" class="block_label">Ключові слова:</label>
					<textarea id="meta_keywords_<?=$key;?>" name="keywords[<?=$key;?>]"><?=$tags['keywords_' . $key];?></textarea>
					<a class="generation" id="generation_<?=$key;?>" href="#"></a>
				</div>
			</div>
			<?php endforeach; ?>
			<div class="fm for_sucsess">
				<div class="fmr save_links">
					<a href="#" class="fm save_adm"><b></b>Зберегти</a>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		$('#tags_langs')
			.on('click', 'a', function (e) {
				e.preventDefault();

				$('#tags_langs').find('a').removeClass('active');
				$(this).addClass('active');

				$('.tags_tab').hide();
				$('#tags_' + $(this).data('language')).show();
			})
			.find('a').each(function () {
				var language = $(this).data('language');

				$('#tags_type_' + language).iphoneStyle({
					checkedLabel: 'ручна',
					uncheckedLabel: 'автоматична',
					resizeContainer: false,
					resizeHandle: false,
					onChange: function(elem, value) {
						(value === true) ? $(elem).attr('checked', 'checked') : $(elem).removeAttr('checked');
					}
				});

				var $description = $('#meta_description_' + language),
					$letter = $('#letters_sum_' + language),
					desc_length = $description.val().length;

				$description.textareaCount({}, function(data){
					if (data.input < 300) $letter.removeClass('red').removeClass('yellow').addClass('green');
					if (data.input > 300 && data.input < 360) $letter.removeClass('green').removeClass('red').addClass('yellow');
					if (data.input > 360) $letter.removeClass('green').removeClass('yellow').addClass('red');
					$letter.text(data.input);
				});

				if (desc_length < 300) $letter.removeClass('red').removeClass('yellow').addClass('green');
				if (desc_length > 300 && desc_length < 360) $letter.removeClass('green').removeClass('red').addClass('yellow');
				if (desc_length > 360) $letter.removeClass('green').removeClass('yellow').addClass('red');

				$('#generation_' + language).on('click', function (e) {
					e.preventDefault();

					global_helper.loader($('.admin_component'));

					$.post(
						'<?php echo $this->uri->full_url('admin/seo/generate_keywords'); ?>',
						{
							tags_id : <?php echo $tags['tags_id']; ?>,
							language: language
						},
						function (response) {
							if (response.success) {
								global_helper.loader($('.admin_component'));
								$('#meta_keywords_' + language).val(response.keywords);
							}
						},
						'json'
					);
				});
			});

		$('.save_adm, .component_edit_links .save').on('click', function (e) {
			e.preventDefault();

			$('#tags_form').ajaxSubmit({
				beforeSubmit: function () {
					global_helper.loader($('.admin_component'));
				},
				success: function (response) {
					if (response.success) global_helper.loader($('.admin_component'));
				},
				dataType: 'json'
			});
		});
	});
</script>
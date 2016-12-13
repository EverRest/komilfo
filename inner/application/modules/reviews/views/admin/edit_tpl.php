<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	$this->template_lib->set_css('js/admin/fineuploader/fineuploader-3.5.0.css', TRUE);
	$this->template_lib->set_css('js/admin/jcrop/css/jquery.Jcrop.min.css', TRUE);

	$this->template_lib->set_js('admin/fineuploader/jquery.fineuploader-3.5.0.min.js');
	$this->template_lib->set_js('admin/jcrop/js/jquery.Jcrop.min.js');
	$this->template_lib->set_js('admin/ckeditor/ckeditor.js');
	$this->template_lib->set_js('admin/jquery.form.js');
	$this->template_lib->set_js('admin/checkboxes.js');
	$this->template_lib->set_js('admin/ui/jquery-ui-1.10.3.custom.min.js');

	$dir_code = $this->init_model->dir_by_id($review_id);
?>
<div class="admin_component">
	<div class="component_loader"><span></span></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="reviews"></div>
		</div>
		<div class="fm component_edit_links">
			<div class="fm only_text"><div>Редагування відгуку</div></div>
			<a href="#" class="fm save"><b></b>Зберегти</a>
			<a href="#" class="fm apply"><b></b>Застосувати</a>
			<a href="<?php echo $this->init_model->get_link($menu_id, '{URL}'); ?>" class="fm cancel"><b></b>До списку відгуків</a>
		</div>
		<div class="fmr component_lang">
		<?php if (count($languages) > 1): ?><?php foreach ($languages as $key => $val): ?>
			<a href="#" class="flags <?=$key;?><?=(($key == LANG) ? ' active' : '');?>" data-language="<?=$key;?>"><img src="img/flags_<?=$key;?>.png"></a>
		<?php endforeach; ?><?php endif; ?>
		</div>
	</div>
	<form id="review_form" action="<?=$this->uri->full_url('admin/reviews/save');?>" method="post">
		<input type="hidden" name="review_id" value="<?=$review_id;?>">
		<input type="hidden" name="menu_id" value="<?=$menu_id;?>">
		<?php foreach ($languages as $key => $val): ?>
		<div id="review_tab_<?=$key?>" class="review_tab"<?php if (LANG != $key) echo ' style="display: none"'; ?>>
			<div class="evry_title">
				<label for="review_title_<?=$key;?>" class="block_label">Відгук від:</label>
				<input type="text" id="review_title_<?=$key;?>" name="title[<?=$key;?>]" value="<?=form_prep($review['title_' . $key]);?>">
			</div>
			<div class="evry_title">
				<label for="review_text_<?=$key;?>" class="block_label">Текст відгуку:</label>
				<div class="no_float"><textarea class="review_text" id="review_text_<?=$key;?>" name="text[<?=$key;?>]"><?=$review['text_' . $key];?></textarea></div>
			</div>
		</div>
		<?php endforeach; ?>
		<div class="evry_title padding">
			<div class="no_float bold">Зображення</div>
		</div>
		<div id="review_image_uploader_box" class="evry_title padding<?php if ($review['image'] != ''): ?> adm_hidden<?php endif; ?>" style="width: 300px">
			<div id="review_image_uploader"></div>
		</div>
		<div class="evry_title padding images_list" id="gallery_images_box">
			<ul id="images_list" style="width:100%;">
			<?php if ($review['image'] != ''): ?>
				<li style="float: left; width: 150px; height: 150px;">
					<div class="fm for_photo_cut">
						<div class="fm photo_cut" style="width: 150px; height: 150px">
							<?php $sizes = getimagesize(ROOT_PATH . 'upload/reviews/' . $dir_code . '/' . $review_id . '/s_' . $review['image']); ?>
							<div style="width: 150px; height: 150px"><img src="/upload/reviews/<?=$dir_code;?>/<?=$review_id;?>/<?=$review['image'] . '?t=' . time() . rand(10000, 1000000);?>" alt=""></div>
							<div class="links">
								<a href="#" class="fm fpc_edit" data-src="/upload/reviews/<?=$dir_code;?>/<?=$review_id;?>/s_<?=$review['image'];?>" data-width="<?=$sizes[0];?>" data-height="<?=$sizes[1];?>"><b></b>Редагувати</a>
								<a href="#" class="fm fpc_delete"><b></b>Видалити</a>
							</div>
						</div>
					</div>
				</li>
			<?php endif; ?>
			</ul>
		</div>
		<div class="evry_title padding">
			<div class="no_float bold">Логотип</div>
		</div>
		<div id="review_logo_uploader_box" class="evry_title padding<?php if ($review['logo'] != ''): ?> adm_hidden<?php endif; ?>" style="width: 300px">
			<div id="review_logo_uploader"></div>
		</div>
		<div class="evry_title padding images_list" id="gallery_logos_box">
			<ul id="logos_list" style="width:100%;">
				<?php if ($review['logo'] != ''): ?>
					<li style="float: left; width: 262px; height: 60px;">
						<div class="fm for_photo_cut">
							<div class="fm photo_cut" style="width: 262px; height: 60px">
								<?php $sizes = getimagesize(ROOT_PATH . 'upload/reviews/' . $dir_code . '/' . $review_id . '/s_' . $review['logo']); ?>
								<div style="width: 262px; height: 60px"><img src="/upload/reviews/<?=$dir_code;?>/<?=$review_id;?>/<?=$review['logo'] . '?t=' . time() . rand(10000, 1000000);?>" alt=""></div>
								<div class="links">
									<a href="#" class="fm fpc_edit" data-src="/upload/reviews/<?=$dir_code;?>/<?=$review_id;?>/s_<?=$review['logo'];?>" data-width="<?=$sizes[0];?>" data-height="<?=$sizes[1];?>"><b></b>Редагувати</a>
									<a href="#" class="fm fpc_delete"><b></b>Видалити</a>
								</div>
							</div>
						</div>
					</li>
				<?php endif; ?>
			</ul>
		</div>
		<div class="fm for_sucsess">
			<div class="fmr save_links">
				<a href="#" class="fm save_adm"><b></b>Зберегти</a>
				<a href="#" class="fm apply_adm"><b></b>Застосувати</a>
				<a href="<?=$this->init_model->get_link($menu_id, '{URL}');?>" class="fm cansel_adm"><b></b>До списку відгуків</a>
			</div>
		</div>
	</form>
</div>
<script type="text/template" id="image_template">
	<li style="float:left; width: 150px; max-height: 150px">
		<div class="fm for_photo_cut">
			<div class="fm photo_cut" style="width: 150px; max-height: 150px">
				<div style="width: 150px; max-height: 150px"><img src="/upload/reviews/<?=$dir_code;?>/<?=$review_id;?>/%file_name%" alt=""></div>
				<div class="links">
					<a href="#" class="fm fpc_edit" data-src="/upload/reviews/<?=$dir_code;?>/<?=$review_id;?>/s_%file_name%" data-width="%width%" data-height="%height%"><b></b>Редагувати</a>
					<a href="#" class="fm fpc_delete"><b></b>Видалити</a>
				</div>
			</div>
		</div>
	</li>
</script>
<script type="text/template" id="logo_template">
	<li style="float:left; width: 262px; max-height: 60px">
		<div class="fm for_photo_cut">
			<div class="fm photo_cut" style="width: 262px; max-height: 60px">
				<div style="width: 262px; max-height: 60px"><img src="/upload/reviews/<?=$dir_code;?>/<?=$review_id;?>/%file_name%" alt=""></div>
				<div class="links">
					<a href="#" class="fm fpc_edit" data-src="/upload/reviews/<?=$dir_code;?>/<?=$review_id;?>/s_%file_name%" data-width="%width%" data-height="%height%"><b></b>Редагувати</a>
					<a href="#" class="fm fpc_delete"><b></b>Видалити</a>
				</div>
			</div>
		</div>
	</li>
</script>
<script type="text/javascript">

	$(function () {
		var $review_form = $('#review_form'),
			$loader = $('.component_loader');

		$review_form.find('.review_text').ckeditor({height: 200});

		$('.save, .save_adm, .apply, a.apply_adm').on('click', function (e) {
			e.preventDefault();

			var redirect = ($(this).hasClass('apply_adm') || $(this).hasClass('apply')) ? false : true;

			$('.review_text').ckeditor({action: 'update'});

			$review_form.ajaxSubmit({
				beforeSubmit: function () { component_loader_show($loader, ''); },
				success: function (response) {
					component_loader_hide($loader, '');
					if (redirect) window.location.href = $('.cansel_adm').attr('href');
				}
			});
		});

		$('.sub_admin_bottom').on('click', 'a', function (e) {
			e.preventDefault();

			$('.sub_admin_bottom').find('a.active').removeClass('active');
			$(this).addClass('active');
			$('.review_tab').hide();
			$('#review_tab_' + $(this).data('language')).show();
		});

		/**
		 * Завантаження зображення
		 */
		var $review_image = $('#review_image_uploader');

		$review_image
			.fineUploader({
				request: {
					endpoint: '<?=$this->uri->full_url('admin/reviews/upload_image');?>',
					inputName: 'review_image',
					params: {
						menu_id: <?=$menu_id;?>,
						review_id: <?=$review_id;?>
					}
				},
				multiple: true,
				text: {
					uploadButton: 'Виберіть або перетягніть файл зображення',
					dragZone: '',
					dropProcessing: ''
				},
				validation: {
					allowedExtensions: ['jpeg', 'jpg', 'png', 'gif'],
					sizeLimit: <?=intval(ini_get('upload_max_filesize')) * 1048576;?>
				},
				messages: {
					typeError: "Дозволено завантажувати: {extensions}.",
					sizeError: "Розмір файлу не повинен перевищувати {sizeLimit}.",
					tooManyFilesError: "Дозволено завантажувати файлів: {itemsLimit}."
				}
			})
			.on('complete', function (event, id, fileName, response) {
				$('.qq-upload-success').remove();
				$('.for_sucsess').find('.loader').hide();

				if (response.success) {
					var row = $('#image_template').html();

					row = row.split('%file_name%').join(response.file_name);
					row = row.replace('%width%', response.width);
					row = row.replace('%height%', response.height);

					$('#images_list').html(row);

					$('#review_image_uploader_box').addClass('adm_hidden');
				}
			});

		$('#images_list')
			.on('mouseover', '.holder', function () {
				$(this).addClass('active');
			})
			.on('mouseout', '.holder', function () {
				$(this).removeClass('active');
			})
			.on('click', '.fpc_edit', function (e) {
				e.preventDefault();

				var $link = $(this),
					width = $link.data('width') > 700 ? 700 : $link.data('width'),
					height = width * $link.data('height') / $link.data('width'),
					crop_modal = '<div id="crop_overlay" class="confirm_overlay" style="display: block; opacity: 0.5; height:' + $(document).height() + 'px"></div><div id="crop_modal" class="crop_modal"><div class="fm crop_area"><div class="fm ca_panel"><a id="crop_cancel" href="#" class="fmr ca_cencel"><b></b>Скасувати</a><a id="crop_save" href="#" class="fmr ca_save"><b></b>Зберегти</a><span class="controls"><label class="check_label active"><i><input type="checkbox" name="proportion" checked="checked" value="1"></i>Пропорційно</label></span></div><div id="crop_preview" class="fm crop_review"><div style="overflow: hidden" class="crop_prew_border"><img src="' + $link.data('src') + '" alt=""></div></div><div id="crop_source" class="fm crop_source"><img width="' + width + '" height="' + height + '" src="' + $link.data('src') + '"></div></div></div>';

				$('body').append(crop_modal);
				$('#crop_modal').css('top', $(document).scrollTop() + 50);

				$('#crop_source').find('img').Jcrop({
					keySupport: false,
					aspectRatio: 1,
					setSelect: [0, 0, 108, 108],
					realSizes: [$link.data('width'), $link.data('height')],
					onChange: function (coords) {
						crop_preview($('#crop_preview').find('div'), coords, 108, 108, width, height);
					}
				}, function () {
					var api = this;

					$('[name="proportion"]').off('change').on('change', function () {
						if ($(this).prop('checked')) {
							$(this).closest('label').addClass('active');
							api.setOptions({aspectRatio: 1});
						} else {
							$(this).closest('label').removeClass('active');
							api.setOptions({aspectRatio: 0});
						}
						api.focus();
					});

					$('#crop_cancel').off('click').on('click', function (e) {
						e.preventDefault();
						api.destroy();
						$('#crop_modal').add('#crop_overlay').remove();
					});

					$('#crop_save').off('click').on('click', function (e) {
						e.preventDefault();

						component_loader_show($('.component_loader'));

						$.post(
							'<?=$this->uri->full_url('admin/reviews/crop_image');?>',
							{
								review_id: <?=$review_id;?>,
								width: width,
								coords: api.tellScaled(),
								menu_id: <?=$menu_id;?>
							},
							function (response) {
								if (response.success) {
									api.destroy();

									$link.closest('.for_photo_cut').find('img').attr('src', response.image);
									$('#crop_modal').add('#crop_overlay').remove();

									component_loader_hide($loader, '');
								}
							},
							'json'
						);
					});
				});
			})
			.on('click', '.fpc_delete', function (e) {
				e.preventDefault();

				var $link = $(this);

				confirmation('Видалити зображення?', function () {

					component_loader_show($loader, '');

					$.post(
						'<?=$this->uri->full_url('admin/reviews/delete_image');?>',
						{
							review_id: <?=$review_id;?>,
							menu_id: <?=$menu_id;?>
						},
						function (response) {
							if (response.success) {
								$link.closest('li').slideUp().remove();
								$('#review_image_uploader_box').removeClass('adm_hidden');

								component_loader_hide($loader, '');
							}
						},
						'json'
					);
				});
			});

		/**
		 * Завантаження логотипу
		 */
		var $review_logo = $('#review_logo_uploader');

		$review_logo
			.fineUploader({
				request: {
					endpoint: '<?=$this->uri->full_url('admin/reviews/upload_logo');?>',
					inputName: 'review_logo',
					params: {
						menu_id: <?=$menu_id;?>,
						review_id: <?=$review_id;?>
					}
				},
				multiple: true,
				text: {
					uploadButton: 'Виберіть або перетягніть файл зображення',
					dragZone: '',
					dropProcessing: ''
				},
				validation: {
					allowedExtensions: ['jpeg', 'jpg', 'png', 'gif'],
					sizeLimit: <?=intval(ini_get('upload_max_filesize')) * 1048576;?>
				},
				messages: {
					typeError: "Дозволено завантажувати: {extensions}.",
					sizeError: "Розмір файлу не повинен перевищувати {sizeLimit}.",
					tooManyFilesError: "Дозволено завантажувати файлів: {itemsLimit}."
				}
			})
			.on('complete', function (event, id, fileName, response) {
				$('.qq-upload-success').remove();
				$('.for_sucsess').find('.loader').hide();

				if (response.success) {
					var row = $('#logo_template').html();

					row = row.split('%file_name%').join(response.file_name);
					row = row.replace('%width%', response.width);
					row = row.replace('%height%', response.height);

					$('#logos_list').html(row);

					$('#review_logo_uploader_box').addClass('adm_hidden');
				}
			});

		$('#logos_list')
			.on('click', '.fpc_edit', function (e) {
				e.preventDefault();

				var $link = $(this),
					width = $link.data('width') > 700 ? 700 : $link.data('width'),
					height = width * $link.data('height') / $link.data('width'),
					crop_modal = '<div id="crop_overlay" class="confirm_overlay" style="display: block; opacity: 0.5; height:' + $(document).height() + 'px"></div><div id="crop_modal" class="crop_modal"><div class="fm crop_area"><div class="fm ca_panel"><a id="crop_cancel" href="#" class="fmr ca_cencel"><b></b>Скасувати</a><a id="crop_save" href="#" class="fmr ca_save"><b></b>Зберегти</a><span class="controls"><label class="check_label active"><i><input type="checkbox" name="proportion" checked="checked" value="1"></i>Пропорційно</label></span></div><div id="crop_preview" class="fm crop_review"><div style="overflow: hidden" class="crop_prew_border"><img src="' + $link.data('src') + '" alt=""></div></div><div id="crop_source" class="fm crop_source"><img width="' + width + '" height="' + height + '" src="' + $link.data('src') + '"></div></div></div>';

				$('body').append(crop_modal);
				$('#crop_modal').css('top', $(document).scrollTop() + 50);

				$('#crop_source').find('img').Jcrop({
					keySupport: false,
					aspectRatio: 262/60,
					setSelect: [0, 0, 262, 60],
					realSizes: [$link.data('width'), $link.data('height')],
					onChange: function (coords) {
						crop_preview($('#crop_preview').find('div'), coords, 262, 60, width, height);
					}
				}, function () {
					var api = this;

					$('[name="proportion"]').off('change').on('change', function () {
						if ($(this).prop('checked')) {
							$(this).closest('label').addClass('active');
							api.setOptions({aspectRatio: 262/60});
						} else {
							$(this).closest('label').removeClass('active');
							api.setOptions({aspectRatio: 0});
						}
						api.focus();
					});

					$('#crop_cancel').off('click').on('click', function (e) {
						e.preventDefault();
						api.destroy();
						$('#crop_modal').add('#crop_overlay').remove();
					});

					$('#crop_save').off('click').on('click', function (e) {
						e.preventDefault();

						component_loader_show($('.component_loader'));

						$.post(
							'<?=$this->uri->full_url('admin/reviews/crop_logo');?>',
							{
								review_id: <?=$review_id;?>,
								width: width,
								coords: api.tellScaled(),
								menu_id: <?=$menu_id;?>
							},
							function (response) {
								if (response.success) {
									api.destroy();

									$link.closest('.for_photo_cut').find('img').attr('src', response.image);
									$('#crop_modal').add('#crop_overlay').remove();

									component_loader_hide($loader, '');
								}
							},
							'json'
						);
					});
				});
			})
			.on('click', '.fpc_delete', function (e) {
				e.preventDefault();

				var $link = $(this);

				confirmation('Видалити логотип?', function () {

					component_loader_show($loader, '');

					$.post(
						'<?=$this->uri->full_url('admin/reviews/delete_logo');?>',
						{
							review_id: <?=$review_id;?>,
							menu_id: <?=$menu_id;?>
						},
						function (response) {
							if (response.success) {
								$link.closest('li').slideUp().remove();
								$('#review_logo_uploader_box').removeClass('adm_hidden');

								component_loader_hide($loader, '');
							}
						},
						'json'
					);
				});
			});
	});
</script>
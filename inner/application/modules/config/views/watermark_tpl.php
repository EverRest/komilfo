<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	$this->template_lib->set_css('js/admin/fineuploader/fineuploader-3.5.0.css', TRUE);
	$this->template_lib->set_js('admin/fineuploader/jquery.fineuploader-3.5.0.min.js');
?>
<div class="fm admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="watermark"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save"><b></b>Зберегти</a>
		</div>
	</div>
	<div class="fm common_settings">
		<div class="evry_title">
			<label class="block_label">Зображення водяного знаку:</label>
			<div class="fm" id="watermark_uploader_box"<?php if ($config['watermark'] != '') echo ' style="display: none"'; ?>><div id="watermark_uploader"></div></div>
			<div class="fm for_photo_cut" id="watermark_box" style="<?php if ($config['watermark'] == '') echo ' display: none'; ?>">
				<div class="fm photo_cut" id="big_photo">
					<div class="fm photo_holder" style="width:200px; height:200px;">
						<div style="width:200px; height:200px;">
							<?php if ($config['watermark'] != ''): ?>
								<?php $sizes = getimagesize(ROOT_PATH . 'upload/watermarks/' . $config['watermark']); ?>
								<img src="/upload/watermarks/<?php echo $config['watermark']; ?>" alt="" style="max-width: 200px; max-height: 200px;">
							<?php endif; ?>
						</div>
					</div>
					<div class="links">
						<a href="#" id="delete_image" class="fm fpc_delete"><b></b>Видалити</a>
					</div>
				</div>
			</div>
		</div>
		<div class="evry_title">
			<label class="block_label">Відступ від краю зображення:</label>
			<input type="text" name="watermark_padding" value="<?php echo $config['watermark_padding']; ?>" class="short">
		</div>
		<div class="evry_title">
			<label class="block_label">Прозорість(0-1):</label>
			<input type="text" name="watermark_opacity" value="<?php echo $config['watermark_opacity']; ?>" class="short">
		</div>
		<div class="fm for_sucsess short">
			<div class="sucsess" style="display: none">Збережено</div>
			<div class="loader" style="display: none"></div>
			<div class="fmr save_links">
				<a href="#" class="fm save_adm"><b></b>Зберегти</a>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		$('#watermark_uploader')
			.fineUploader({
				request: {
					endpoint: '<?php echo $this->uri->full_url('admin/config/upload_watermark'); ?>',
					inputName: 'watermark_image'
				},
				multiple: false,
				text: {
					uploadButton: 'Виберіть або перетягніть файл зображення',
					dragZone: '',
					dropProcessing: ''
				},
				validation: {
					allowedExtensions: ['jpeg', 'jpg', 'png', 'gif'],
					sizeLimit: <?php echo intval(ini_get('upload_max_filesize')) * 1048576; ?>
				},
				messages: {
					typeError: "Дозволено завантажувати: {extensions}.",
					sizeError: "Розмір файлу не повинен перевищувати {sizeLimit}.",
					tooManyproductsError: "Дозволено завантажувати файлів: {productLimit}."
				}
			})
			.on('complete', function (event, id, fileName, response) {
				if (response.success) {
					$('.qq-upload-success').remove();

					$('#watermark_uploader_box').hide();
					$('#big_photo').html('<div style="width: 200px; height: 200px;" class="fm photo_holder"><div style="width: 200px; height: 200px;"><img src="/upload/watermarks/' + response.file_name + '" style="max-width: 200px; max-height: 200px;" alt=""></div></div><div class="links"><a href="#" id="delete_image" class="fm fpc_delete"><b></b>Видалити</a></div>');
					$('#watermark_box').show();
				}
			});

		$('#watermark_box')
			.on('click', '#delete_image', function (e) {
				e.preventDefault();
				confirmation('Видалити водяний знак?', function () {
					component_loader_show($('.component_loader'), '');
					$.post(
						'<?php echo $this->uri->full_url('admin/config/delete_watermark'); ?>',
						function (response) {
							if (response.success) {
								component_loader_hide($('.component_loader'), '');
								$('#watermark_box')
									.hide('')
									.find('#big_image').html('');
								$('#watermark_uploader_box').show();
							}
						},
						'json'
					);
				});
			});

		/**
		 * Збереження змін
		 */
		$('.for_sucsess .save_adm, .component_edit_links .save').on('click', function (e) {
			e.preventDefault();

			component_loader_show($('.component_loader'), '');

			var uri = '<?php echo $this->uri->full_url('admin/config/save_watermark'); ?>',
				request = {
					watermark_padding: $('input[name="watermark_padding"]').val(),
					watermark_opacity: $('input[name="watermark_opacity"]').val()
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
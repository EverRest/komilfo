<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * @var int $menu_id
	 * @var int $component_id
	 * @var array $thumbs
	 * @var array $big
	 * @var array $config
	 */

	$this->template_lib
		->set_css('js/admin/fineuploader/fineuploader-3.5.0.css', true)
		->set_js('admin/fineuploader/jquery.fineuploader-3.5.0.min.js')

		->set_css('js/admin/jcrop/css/jquery.Jcrop.min.css', true)
		->set_js('admin/jcrop/js/jquery.Jcrop.min.js')

		->set_js('plugins/mustache.min.js');

	$sizes = array();
	$dir = get_dir_path('upload/open/' . $component_id, false);
?>
<div class="fm admin_component">
	<div class="component_loader"></div>

	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="catalog"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="<?=$this->init_model->get_link($menu_id, '{URL}');?>" class="fm cancel"><b></b>Повернутись назад</a>
		</div>
	</div>

	<div class="evry_title">
		<label class="block_label">Зображення блоку "Скоро відкриття":</label>

		<div class="fm<?php if (array_key_exists('open', $config)): ?> adm_hidden<?php endif; ?>" id="open_uploader_box">
			<div id="open_uploader"></div>
		</div>

		<div id="open_box" class="no_float images_list<?php if (!array_key_exists('open', $config)): ?> adm_hidden<?php endif; ?>">
			<ul>
				<?php if (array_key_exists('open', $config)): ?>
					<?php $sizes = getimagesize($dir . 's_' . $config['open']['file_name']); ?>
					<li>
						<div class="fm pc_photo" style="height: <?=$thumb[1];?>px">
							<div class="vertical">
								<img src="/upload/open/<?=$component_id;?>/t_<?=$config['open']['file_name'] . '?t=' . time() . mt_rand(10000, 1000000);?>" alt="">
								<a href="#" class="fm fpc_delete"><b></b></a>
							</div>
						</div>
						<div class="fm links">
							<a href="#" class="fm fpc_edit" data-src="/upload/open/<?=$component_id;?>/s_<?=$config['open']['file_name'];?>" data-width="<?=$sizes[0];?>" data-height="<?=$sizes[1];?>"><span>Редагувати</span></a>
							<?php if ((string)$this->config->item('watermark') !== ''): ?><a href="#" class="fm fpc_watermark" data-src="/upload/open/<?=$component_id;?>/<?=$config['open']['file_name'];?>" data-width="<?=$sizes[0];?>" data-height="<?=$sizes[1];?>" data-position="<?=(array_key_exists('watermark_position', $config['open']) ? $config['open']['watermark_position'] : 0);?>"><span>Водяний знак</span></a><?php endif; ?>
						</div>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>

	<div class="evry_title">
		<label class="block_label">Зображення блоку "Ми відкрились":</label>

		<div class="fm<?php if (array_key_exists('opened', $config)): ?> adm_hidden<?php endif; ?>" id="opened_uploader_box">
			<div id="opened_uploader"></div>
		</div>

		<div id="opened_box" class="no_float images_list<?php if (!array_key_exists('opened', $config)): ?> adm_hidden<?php endif; ?>">
			<ul>
				<?php if (array_key_exists('opened', $config)): ?>
					<?php $sizes = getimagesize($dir . 's_' . $config['opened']['file_name']); ?>
					<li>
						<div class="fm pc_photo" style="height: <?=$thumb[1];?>px">
							<div class="vertical">
								<img src="/upload/open/<?=$component_id;?>/t_<?=$config['opened']['file_name'] . '?t=' . time() . mt_rand(10000, 1000000);?>" alt="">
								<a href="#" class="fm fpc_delete"><b></b></a>
							</div>
						</div>
						<div class="fm links">
							<a href="#" class="fm fpc_edit" data-src="/upload/open/<?=$component_id;?>/s_<?=$config['opened']['file_name'];?>" data-width="<?=$sizes[0];?>" data-height="<?=$sizes[1];?>"><span>Редагувати</span></a>
							<?php if ((string)$this->config->item('watermark') !== ''): ?><a href="#" class="fm fpc_watermark" data-src="/upload/open/<?=$component_id;?>/<?=$config['opened']['file_name'];?>" data-width="<?=$sizes[0];?>" data-height="<?=$sizes[1];?>" data-position="<?=(array_key_exists('watermark_position', $config['opened']) ? $config['opened']['watermark_position'] : 0);?>"><span>Водяний знак</span></a><?php endif; ?>
						</div>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>

	<div class="fm for_sucsess">
		<div class="fmr save_links">
			<a href="<?=$this->init_model->get_link($menu_id, '{URL}');?>" class="fm cansel_adm"><b></b>Повернутись назад</a>
		</div>
	</div>

	<script id="photo_template" type="text/html">
		<li>
			<div class="fm pc_photo" style="height: <?=$thumb[1];?>px">
				<div class="vertical">
					<img src="/upload/open/<?=$component_id;?>/t_{{ file_name }}" alt="">
					<a href="#" class="fm fpc_delete"><b></b></a>
				</div>
			</div>
			<div class="fm links">
				<a href="#" class="fm fpc_edit" data-src="/upload/open/<?=$component_id;?>/s_{{ file_name }}" data-width="{{ width }}" data-height="{{ height }}"><span>Редагувати</span></a>
				<?php if ((string)$this->config->item('watermark') !== ''): ?><a href="#" class="fm fpc_watermark" data-src="/upload/open/<?=$component_id;?>/{{ file_name }}" data-width="{{ width }}" data-height="{{ height }}" data-position="0"><span>Водяний знак</span></a><?php endif; ?>
			</div>
		</li>
	</script>

	<?php $this->load->view('admin/image_tools_tpl'); ?>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		var $component = $('.admin_component'),
			menu_id = <?=$menu_id;?>,
			component_id = <?=$component_id;?>,

			thumb_w = <?=$thumb[0];?>,
			thumb_h = <?=$thumb[1];?>,
			big_w = <?=$big[0];?>,
			big_h = <?=$big[1];?>,
			block_w = <?=$thumb[0];?>;

		global_helper.photo_grid($component.find('.images_list'), block_w);

		$component.find('#open_uploader')
			.fineUploader({
				request: {
					endpoint: full_url('admin/catalog/upload_open_photo'),
					inputName: 'image',
					params: {
						menu_id: menu_id,
						component_id: component_id,
						type: 'open'
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
					sizeLimit: <?=((int)ini_get('upload_max_filesize') * 1048576);?>
				},
				messages: {
					typeError: "Дозволено завантажувати: {extensions}.",
					sizeError: "Розмір файлу не повинен перевищувати {sizeLimit}.",
					tooManyproductsError: "Дозволено завантажувати файлів: {productLimit}."
				}
			})
			.on('complete', function (event, id, fileName, response) {
				if (response.hasOwnProperty('success') && response.success) {
					$('.qq-upload-success').remove();

					$component.find('#open_uploader_box').addClass('adm_hidden');

					$component.find('#open_box').removeClass('adm_hidden').find('ul').eq(0).html(
						Mustache.render(
							$component.find('#photo_template').html(),
							{
								file_name: response.file_name,
								width: response.width,
								height: response.height
							}
						)
					);

					global_helper.photo_grid($component.find('.images_list'), block_w);
				}
			});

		$component.find('#open_box')
			.on('click', '.fpc_edit', function (e) {
				e.preventDefault();

				var $link = $(this);

				global_helper.photo_crop(
					$link,
					$component.find('#crop_template'),
					thumb_w,
					thumb_h,
					big_w,
					big_h,
					function (api, width) {
						global_helper.loader($component);

						$.post(
							full_url('admin/catalog/crop_open_photo'),
							{
								menu_id: menu_id,
								component_id: component_id,
								type: 'open',
								width: width,
								coords: api.tellScaled()
							},
							function (response) {
								if (response.success) {
									$link.closest('li').find('.pc_photo').find('img').eq(0).attr('src', response.src);
									global_helper.loader($component);
								}
							},
							'json'
						);
					}
				);
			})
			.on('click', '.fpc_watermark', function (e) {
				e.preventDefault();

				var $link = $(this);

				global_helper.photo_watermark(
					$link,
					$component.find('#watermark_template'),
					function (position) {
						global_helper.loader($component);

						$.post(
							full_url('admin/catalog/watermark_open_photo'),
							{
								menu_id: menu_id,
								component_id: component_id,
								position: position,
								type: 'open'
							},
							function (response) {
								if (response.success) {
									$link.data('position', position);
									global_helper.loader($component);
								}
							},
							'json'
						);
					}
				);
			})
			.on('click', '.fpc_delete', function (e) {
				e.preventDefault();

				var $link = $(this);

				global_helper.confirmation(
					'Видалити зображення?',
					function () {
						global_helper.loader($component);

						$.post(
							full_url('admin/catalog/remove_open_photo'),
							{
								menu_id: menu_id,
								component_id: component_id,
								type: 'open'
							},
							function (response) {
								if (response.hasOwnProperty('success') && response.success) {
									$link.closest('li').remove();

									$component.find('#open_uploader_box').removeClass('adm_hidden');
									$component.find('#open_box').addClass('adm_hidden');

									global_helper.loader($component);
								}
							},
							'json'
						);
					}
				);
			});

		$component.find('#opened_uploader')
			.fineUploader({
				request: {
					endpoint: full_url('admin/catalog/upload_open_photo'),
					inputName: 'image',
					params: {
						menu_id: menu_id,
						component_id: component_id,
						type: 'opened'
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
					sizeLimit: <?=((int)ini_get('upload_max_filesize') * 1048576);?>
				},
				messages: {
					typeError: "Дозволено завантажувати: {extensions}.",
					sizeError: "Розмір файлу не повинен перевищувати {sizeLimit}.",
					tooManyproductsError: "Дозволено завантажувати файлів: {productLimit}."
				}
			})
			.on('complete', function (event, id, fileName, response) {
				if (response.hasOwnProperty('success') && response.success) {
					$('.qq-upload-success').remove();

					$component.find('#opened_uploader_box').addClass('adm_hidden');

					$component.find('#opened_box').removeClass('adm_hidden').find('ul').eq(0).html(
						Mustache.render(
							$component.find('#photo_template').html(),
							{
								file_name: response.file_name,
								width: response.width,
								height: response.height
							}
						)
					);

					global_helper.photo_grid($component.find('.images_list'), block_w);
				}
			});

		$component.find('#opened_box')
			.on('click', '.fpc_edit', function (e) {
				e.preventDefault();

				var $link = $(this);

				global_helper.photo_crop(
					$link,
					$component.find('#crop_template'),
					thumb_w,
					thumb_h,
					big_w,
					big_h,
					function (api, width) {
						global_helper.loader($component);

						$.post(
							full_url('admin/catalog/crop_open_photo'),
							{
								menu_id: menu_id,
								component_id: component_id,
								type: 'opened',
								width: width,
								coords: api.tellScaled()
							},
							function (response) {
								if (response.success) {
									$link.closest('li').find('.pc_photo').find('img').eq(0).attr('src', response.src);
									global_helper.loader($component);
								}
							},
							'json'
						);
					}
				);
			})
			.on('click', '.fpc_watermark', function (e) {
				e.preventDefault();

				var $link = $(this);

				global_helper.photo_watermark(
					$link,
					$component.find('#watermark_template'),
					function (position) {
						global_helper.loader($component);

						$.post(
							full_url('admin/catalog/watermark_open_photo'),
							{
								menu_id: menu_id,
								component_id: component_id,
								position: position,
								type: 'opened'
							},
							function (response) {
								if (response.success) {
									$link.data('position', position);
									global_helper.loader($component);
								}
							},
							'json'
						);
					}
				);
			})
			.on('click', '.fpc_delete', function (e) {
				e.preventDefault();

				var $link = $(this);

				global_helper.confirmation(
					'Видалити зображення?',
					function () {
						global_helper.loader($component);

						$.post(
							full_url('admin/catalog/remove_open_photo'),
							{
								menu_id: menu_id,
								component_id: component_id,
								type: 'opened'
							},
							function (response) {
								if (response.hasOwnProperty('success') && response.success) {
									$link.closest('li').remove();

									$component.find('#opened_uploader_box').removeClass('adm_hidden');
									$component.find('#opened_box').addClass('adm_hidden');

									global_helper.loader($component);
								}
							},
							'json'
						);
					}
				);
			});
	});
</script>
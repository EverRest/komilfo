<?php defined('ROOT_PATH') or exit('No direct script access allowed');
	/**
	 * @var int $menu_id
	 * @var int $item_id
	 * @var int $menu_index
	 * @var array $item
	 */
	$this->template_lib
		->set_css('js/admin/fineuploader/fineuploader-3.5.0.css', true)
		->set_css('js/admin/jcrop/css/jquery.Jcrop.min.css', true)
		->set_js('admin/fineuploader/jquery.fineuploader-3.5.0.min.js')
		->set_js('admin/jcrop/js/jquery.Jcrop.min.js')
		->set_js('admin/jquery.form.js')
		->set_js('plugins/mustache.min.js');
	if ((int)$item['menu_index'] === 1) {
		$block_sizes = $thumb;
		// array(100, 100);
		// $thumb = array(42, 34);
		$icon_sizes = array(718, 510);
		$icon_hover_sizes = array(0, 0);
		// if ($item['image'] !== '' and file_exists(ROOT_PATH . 'upload/menu/' . $item['id'] . '/s_' . $item['image'])) {
		// 	// $icon_sizes = getimagesize(ROOT_PATH . 'upload/menu/' . $item['id'] . '/s_' . $item['image']);
		// }
		if ($item['icon'] !== '' and file_exists(ROOT_PATH . 'upload/menu/' . $item['id'] . '/s_' . $item['icon'])) {
			$icon_hover_sizes = getimagesize(ROOT_PATH . 'upload/menu/' . $item['id'] . '/s_' . $item['icon']);
		}
	}
?>
<div class="admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="menu"></div>
		</div>
		<?php if ((int)$item['menu_index'] === 2): ?>
		<div class="fm component_edit_links">
			<a href="#" class="fm save save_menu"><b></b>Зберегти</a>
			<a href="#" class="fm apply save_menu ajax_save"><b></b>Застосувати</a>
			<a href="#" class="fm cancel cancel_menu"><b></b>До списку</a>
		</div>
		<?php endif; ?>
	</div>
	<form id="menu_form" action="<?=$this->uri->full_url('/admin/menu/update_info');?>" method="post">
		<input type="hidden" name="id" value="<?=$item_id;?>">
		<div class="evry_title">
			<label class="block_label">Пукт меню:</label>
			<div class="no_float"><b><?=$item['name_' . LANG];?></b></div>
		</div>
		<?php if ((int)$item['menu_index'] === 2): ?>
		<div class="evry_title">
			<label class="block_label">Email:</label>
			<input type="text" name="email" value="<?=$item['email'];?>">
		</div>
		<?php endif; ?>
		<?php if ((int)$item['menu_index'] === 1): ?>
		<div class="evry_title">
			<label class="block_label">Іконка:</label>
			<div class="fm<?php if ($item['image'] !== ''): ?> adm_hidden<?php endif; ?>" id="image_uploader_box">
				<div id="image_uploader"></div>
			</div>
			<div id="image_box" class="no_float images_list<?php if ($item['image'] === ''): ?> adm_hidden<?php endif; ?>">
				<ul>
				<?php if ($item['image'] !== ''): ?>
					<li style="width:<?=$icon_sizes[1];?>px">
						<div class="fm pc_photo" style="height: <?=$icon_sizes[1];?>px">
							<div class="vertical">
								<img src="/upload/menu/<?=$item['id'];?>/s_<?=$item['image'] . '?t=' . time() . mt_rand(10000, 1000000);?>" alt="">
								<a href="#" class="fm fpc_delete"><b></b></a>
							</div>
						</div>
					</li>
				<?php endif; ?>
				</ul>
			</div>
		</div>
		<?php endif; ?>
		<?php if ((int)$item['menu_index'] === 2): ?>
		<div class="evry_title">
			<label class="block_label">Іконка при наведенні:</label>
			<div class="fm<?php if ($item['icon'] !== ''): ?> adm_hidden<?php endif; ?>" id="icon_uploader_box">
				<div id="icon_uploader"></div>
			</div>
			<div id="icon_box" class="no_float images_list<?php if ($item['icon'] === ''): ?> adm_hidden<?php endif; ?>">
				<ul>
					<?php if ($item['icon'] !== ''): ?>
						<li>
							<div class="fm pc_photo" style="height: <?=$block_sizes[1];?>px">
								<div class="vertical">
									<img src="/upload/menu/<?=$item['id'];?>/<?=$item['icon'] . '?t=' . time() . mt_rand(10000, 1000000);?>" alt="">
									<a href="#" class="fm fpc_delete"><b></b></a>
								</div>
							</div>
						</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
		<?php endif; ?>
		<?php if ((int)$item['menu_index'] === 2): ?>
		<div class="fm for_sucsess">
			<div class="fmr save_links">
				<a href="#" class="fm save_adm save_menu"><b></b>Зберегти</a>
				<a href="#" class="fm apply_adm save_menu ajax_save"><b></b>Застосувати</a>
				<a href="#" class="fm cansel_adm cancel_menu"><b></b>До списку</a>
			</div>
		</div>
		<?php endif; ?>
	</form>
	<?php if ((int)$item['menu_index'] === 1): ?>
	<script id="media_template" type="text/html">
		<li style="width:<?=$icon_sizes[1];?>px">
			<div class="fm pc_photo" style="height: <?=$block_sizes[1];?>px">
				<div class="vertical">
					<img src="/upload/menu/<?=$item['id'];?>/s_{{ file_name }}" alt="">
					<a href="#" class="fm fpc_delete"><b></b></a>
				</div>
			</div>
		</li>
	</script>
	<?php endif; ?>
</div>
<script type="text/javascript">
	$(function () {
		var $component = $('.admin_component'),
			menu_id = <?=$menu_id?>,
			item_id = <?=$item_id;?>,
			menu_index = <?=$menu_index;?>;
		<?php if ((int)$item['menu_index'] === 1): ?>
			var block_w = <?=$block_sizes[0];?>;
			global_helper.photo_grid($component.find('.images_list'), block_w);
			$component.find('#image_uploader')
				.fineUploader({
					request: {
						endpoint: full_url('admin/menu/upload_image'),
						inputName: 'image',
						params: {
							menu_id: item_id
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
						$component.find('#image_uploader_box').addClass('adm_hidden');
						$component.find('#image_box').removeClass('adm_hidden').find('ul').eq(0).html(
							Mustache.render(
								$component.find('#media_template').html(),
								{
									file_name: response.file_name
								}
							)
						);
						$component.find('.component_lang').find('.active').trigger('click');
						global_helper.photo_grid($component.find('.images_list'), block_w);
					}
				});
			$component.find('#image_box')
				.on('click', '.fpc_delete', function (e) {
					e.preventDefault();
					var $link = $(this);
					global_helper.confirmation(
						'Видалити іконку?',
						function () {
							global_helper.loader($component);
							$.post(
								full_url('admin/menu/remove_image'),
								{
									menu_id: item_id,
									menu_index: menu_index
								},
								function (response) {
									if (response.hasOwnProperty('success') && response.success) {
										$link.closest('li').remove();
										$component.find('#image_uploader_box').removeClass('adm_hidden');
										$component.find('#image_box').addClass('adm_hidden');
										global_helper.loader($component);
									}
								},
								'json'
							);
						}
					);
				});
			$component.find('#icon_uploader')
				.fineUploader({
					request: {
						endpoint: full_url('admin/menu/upload_icon'),
						inputName: 'icon',
						params: {
							menu_id: item_id
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
						$component.find('#icon_uploader_box').addClass('adm_hidden');
						$component.find('#icon_box').removeClass('adm_hidden').find('ul').eq(0).html(
							Mustache.render(
								$component.find('#media_template').html(),
								{
									file_name: response.file_name
								}
							)
						);
						$component.find('.component_lang').find('.active').trigger('click');
						global_helper.photo_grid($component.find('.images_list'), block_w);
					}
				});
			$component.find('#icon_box')
				.on('click', '.fpc_delete', function (e) {
					e.preventDefault();
					var $link = $(this);
					global_helper.confirmation(
						'Видалити іконку при наведенні?',
						function () {
							global_helper.loader($component);
							$.post(
								full_url('admin/menu/remove_icon'),
								{
									menu_id: item_id,
									menu_index: menu_index
								},
								function (response) {
									if (response.hasOwnProperty('success') && response.success) {
										$link.closest('li').remove();
										$component.find('#icon_uploader_box').removeClass('adm_hidden');
										$component.find('#icon_box').addClass('adm_hidden');
										global_helper.loader($component);
									}
								},
								'json'
							);
						}
					);
				});
		<?php endif; ?>
		<?php if ((int)$item['menu_index'] === 2): ?>
			$component.find('.component_lang').on('click', 'a', function (e) {
				e.preventDefault();
				$(this).addClass('active').siblings().removeClass('active');
				$('.lang_tab').addClass('hidden');
				$('.lang_tab_' + $(this).data('language')).removeClass('hidden');
			});
			$component
				.on('click', '.save_menu', function (e) {
					e.preventDefault();
					global_helper.loader($component);
					var $link = $(this);
					$('#menu_form').ajaxSubmit({
						success:function (response) {
							if (response.success) {
								if ($link.hasClass('ajax_save')) {
									global_helper.loader($component);
								} else {
									window.location.href = '<?=$this->uri->full_url('admin/menu/index?menu_index=' . $menu_index . '&menu_id=' . $menu_id);?>';
								}
							}
						},
						dataType: 'json'
					});
				})
				.on('click', '.cancel_menu', function (e) {
					e.preventDefault();
					window.location.href = '<?=$this->uri->full_url('admin/menu/index?menu_index=' . $menu_index . '&menu_id=' . $menu_id);?>';
				});
		<?php endif; ?>
	});
</script>
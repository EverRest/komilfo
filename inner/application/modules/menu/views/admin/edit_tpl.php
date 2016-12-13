<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');

	$this->template_lib->set_css('js/admin/fineuploader/fineuploader-3.5.0.css', TRUE);
	$this->template_lib->set_css('js/admin/jcrop/css/jquery.Jcrop.min.css', TRUE);

	$this->template_lib->set_js('admin/fineuploader/jquery.fineuploader-3.5.0.min.js');
	$this->template_lib->set_js('admin/jcrop/js/jquery.Jcrop.min.js');
	$this->template_lib->set_js('admin/jquery.form.js');

	if ($menu_index == 1) $_size = array(180, 180);
	if ($menu_index == 4) $_size = array(250, 58);

	$sizes = array(0, 0);
	if (file_exists(ROOT_PATH . 'upload/menu/' . $menu['id'] . '/s_' . $menu['image'])) $sizes = getimagesize(ROOT_PATH . 'upload/menu/' . $menu['id'] . '/s_' . $menu['image']);
?>
<div class="admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="article"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save"><b></b>Зберегти</a>
			<a href="#" class="fm apply"><b></b>Застосувати</a>
			<a href="#" class="fm cancel"><b></b><?php if ($catalog == 0): ?>До списку меню<?php else: ?>До списку товарів<?php endif; ?></a>
		</div>
		<?php if (count($languages) > 1): ?>
		<!--div class="fmr component_lang">
			<?php foreach ($languages as $key => $val): ?>
				<a href="#" class="flags <?=$key;?><?=(($key == LANG) ? ' active' : '');?>" data-language="<?=$key;?>"><img src="img/flags_<?=$key;?>.png"></a>
			<?php endforeach; ?>
		</div-->
		<?php endif; ?>
	</div>
	<form id="menu_form" action="<?=$this->uri->full_url('/admin/menu/update_info');?>" method="post">
		<input type="hidden" name="id" value="<?=$item_id;?>">
		<div class="evry_title">
			<label class="block_label">Пукт меню:</label>
			<div class="no_float"><b><?=$menu['name_' . LANG];?></b></div>
		</div>
		<div class="evry_title">
			<label for="code" class="block_label">ID розділу прайсу:</label>
			<input type="text" id="code" name="code" value="<?=$menu['code'];?>">
		</div>
		<?php foreach ($languages as $key => $val): ?>
		<!--div class="lang_tab lang_tab_<?=$key;?>"<?=((LANG != $key) ? ' style="display:none"' : '');?>>
			<div class="evry_title">
				<label for="title_<?=$key;?>" class="block_label">Короткий опис:</label>
				<input type="text" id="title_<?=$key;?>" name="title[<?=$key;?>]" value="<?=$menu['title_' . $key];?>">
			</div>
		</div-->
		<?php endforeach; ?>
		<div class="evry_title">
			<label class="block_label">Зображення:</label>
			<div class="fm" id="image_uploader_box"<?php if ($menu['image'] != '') echo ' style="display: none"'; ?>><div id="image_uploader"></div></div>
			<div class="fm for_photo_cut" id="image_box" style="<?php if ($menu['image'] == '') echo ' display: none'; ?>">
				<div class="fm photo_cut" id="big_photo">
					<div class="fm photo_holder" style="width:<?=$_size[0];?>px; height:<?=$_size[1];?>px;">
						<div style="width:<?=$_size[0];?>px; height:<?=$_size[1];?>px;">
							<?php if ($menu['image'] != ''): ?><img src="/upload/menu/<?=$menu['id'];?>/<?=$menu['image'];?>" alt=""><?php endif; ?>
						</div>
					</div>
					<div class="links">
						<a href="#" id="crop_image" class="fm fpc_edit" data-width="<?=$sizes[0];?>" data-height="<?=$sizes[1];?>"><b></b>Редагувати</a>
						<a href="#" id="delete_image" class="fm fpc_delete"><b></b>Видалити</a>
					</div>
				</div>
			</div>
			<div class="fm crop_wrapper" id="crop_box"></div>
		</div>
		<div class="fm for_sucsess">
			<div class="fmr save_links">
				<a href="#" class="fm save_adm"><b></b>Зберегти</a>
				<a href="#" class="fm apply_adm"><b></b>Застосувати</a>
				<a href="#" class="fm cansel_adm"><b></b><?php if ($catalog == 0): ?>До списку меню<?php else: ?>До списку товарів<?php endif; ?></a>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">

	function save_component_menu(callback) {
		component_loader_show($('.component_loader'), '');
		$('#menu_form').ajaxSubmit({
			success:function (response) {
				if (response.success) {
					component_loader_hide($('.component_loader'), '');
					if ($.type(callback) === 'function') callback();
				}
			},
			dataType: 'json'
		});
	}

	function cancel_editing() {
		<?php if ($catalog == 0): ?>
		window.location.href = '<?=$this->uri->full_url('admin/menu/index?menu_index=' . $menu_index . '&menu_id=' . $menu_id);?>';
		<?php else: ?>
		window.location.href = '<?=$this->init_model->get_link($menu_id, '{URL}');?>';
		<?php endif; ?>
	}

	$(function () {
		$('.component_lang').on('click', 'a', function (e) {
			e.preventDefault();
			$(this).addClass('active').siblings().removeClass('active');
			$('.lang_tab').hide();
			$('.lang_tab_' + $(this).data('language')).show();
		});

		var api = null,
			menu_index = <?=$menu_index;?>,
			menu_id = <?=$item_id;?>,
			source = '<?php if ($menu['image'] != ''): ?>/upload/menu/<?=$menu['id'];?>/s_<?=$menu['image'];?><?php endif; ?>',
			t_w = <?=$_size[0];?>, t_h = <?=$_size[1];?>;

		$('#image_uploader')
			.fineUploader({
				request: {
					endpoint: '<?php echo $this->uri->full_url('admin/menu/upload_image'); ?>',
					inputName: 'image',
					params: {
						menu_id : menu_id,
						menu_index: menu_index
					}
				},
				multiple: false,
				text: {
					uploadButton: 'Виберіть або перетягніть файл зображення',
					dragZone: '',
					dropProcessing: ''
				},
				validation: {
					allowedExtensions: ['jpeg', 'jpg', 'png', 'gif'],
					sizeLimit: <?=(intval(ini_get('upload_max_filesize')) * 1048576);?>
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
					source = '/upload/menu/' + menu_id + '/s_' + response.file_name;

					$('#image_uploader_box').hide();
					$('#big_photo').html('<div style="width:' + t_w + 'px; height:' + t_h + 'px;" class="fm photo_holder"><div style="width:' + t_w + 'px; height:' + t_h + 'px;"><img src="/upload/menu/' + menu_id + '/' + response.file_name + '" style="max-width:' + t_w + 'px; max-height:' + t_h + 'px;" alt=""></div></div><div class="links"><a href="#" id="crop_image" class="fm fpc_edit" data-width="' + response.width + '" data-height="' + response.height + '"><b></b>Редагувати</a><a href="#" id="delete_image" class="fm fpc_delete"><b></b>Видалити</a></div>');
					$('#image_box').show();
				}
			});

		$('#image_box')
			.on('click', '#delete_image', function (e) {
				e.preventDefault();

				$.post(
					'<?=$this->uri->full_url('admin/menu/delete_image');?>',
					{
						menu_id: menu_id,
						menu_index: menu_index
					},
					function (response) {
						$('#image_box')
							.hide('')
							.find('#big_image').html('');
						$('#image_uploader_box').show();
						source = '';
					},
					'json'
				);
			})
			.on('click', '#crop_image', function (e) {
				e.preventDefault();

				if ($.type(api) === 'object') {
					api.destroy();
					api = null;
					$('#crop_image_box').html('');
				}

				var $link = $(this),
					width = $link.data('width') > 570 ? 570 : $link.data('width'),
					height = width * $link.data('height') / $link.data('width'),
					resizer = '',
					crop_config = {keySupport: false};

				$('#image_box').hide();

				crop_config = $.extend(crop_config, {
					setSelect: [0, 0, t_w, t_h],
					aspectRatio: t_w / t_h,
					onChange: function (coords) {
						crop_preview($('#crop_preview'), coords, t_w, t_h, width, height);
					}
				});
				resizer = '<div id="crop_preview" class="fm crop_footer" style="width:' + t_w + 'px;height:' + t_h + 'px;overflow:hidden;"><div><img src="' + source + '" alt=""></div></div><u><br></u><div class="fm crop_header"><a href="#" class="save_crop"></a><a href="#" class="cansel_crop"></a></div><div class="fm crop_area"><div class="fm ca_panel"><span class="controls"><label class="check_label"><i><input type="checkbox" name="proportion" checked="checked" value="1"></i>Пропорційно</label></span><a href="#" class="fmr ca_cencel"><b></b>Скасувати</a><a href="#" class="fmr ca_save"><b></b>Зберегти</a></div><img width="' + width + '" height="' + height + '" src="' + source + '"></div>';

				$('#crop_box')
					.html(resizer)
					.find('.crop_area').find('img').eq(0).Jcrop(
						crop_config,
						function () {
							api = this;

							$('.controls').style_input();

							$('[name="proportion"]').on('change', function () {
								api.setOptions($(this).prop('checked') ? {aspectRatio: <?=$_size[0]/$_size[1];?>} : {aspectRatio: 0});
								api.focus();
							});

							$('#crop_box')
								.find('.ca_cencel').off('click').on('click', function (e) {
									e.preventDefault();

									api.destroy();
									$('#crop_box').html('');
									$('#image_box').show();
								})
								.end()
								.find('.ca_save').on('click', function (e) {
									e.preventDefault();

									$.post(
										'<?=$this->uri->full_url('admin/menu/crop_image');?>',
										{
											menu_id: menu_id,
											menu_index: menu_index,
											coords: api.tellScaled(),
											width: width
										},
										function (response) {
											if (response.error === 0) {
												api.destroy();
												$('#crop_box').html('');
												$('#image_box').show().find('img').eq(0).attr('src', response.image);
											}
										},
										'json'
									);
								});
						});
			});

		$('.component_edit_links .save').add('.for_sucsess .save_adm').on('click', function (e) {
			e.preventDefault();
			save_component_menu(function () { cancel_editing(); });
		});

		$('.component_edit_links .apply').add('.for_sucsess .apply_adm').on('click', function (e) {
			e.preventDefault();
			save_component_menu(function () {});
		});

		$('.component_edit_links .cancel').add('.for_sucsess .cansel_adm').on('click', function (e) {
			e.preventDefault();
			cancel_editing();
		});
	});
</script>
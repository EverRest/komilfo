<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	$this->template_lib->set_js('admin/ckeditor/ckeditor.js');
	$this->template_lib->set_js('admin/jquery.form.js');
	$this->template_lib->set_js('admin/ui/jquery-ui-1.10.3.custom.min.js');
	$this->template_lib->set_js('admin/fineuploader/jquery.fineuploader-3.5.0.min.js');
	$this->template_lib->set_js('admin/jcrop/js/jquery.Jcrop.min.js');

	$this->template_lib->set_css('js/admin/fineuploader/fineuploader-3.5.0.css', TRUE);
	$this->template_lib->set_css('js/admin/jcrop/css/jquery.Jcrop.min.css', TRUE);
?>
<div class="admin_component" id="news_component">
	<div class="component_loader"><span></span></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="news"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save"><b></b>Зберегти</a>
			<a href="#" class="fm apply"><b></b>Застосувати</a>
			<a href="<?=$this->init_model->get_link($menu_id, '{URL}');?>" class="fm cancel"><b></b>До списку Послуг</a>
		</div>
		<?php if (count($languages) > 1): ?>
			<div class="fmr component_lang">
				<?php foreach ($languages as $key => $val): ?>
					<a href="#" class="flags <?=$key;?><?=(($key == LANG) ? ' active' : '');?>" data-language="<?=$key;?>"><img src="img/flags_<?=$key;?>.png"></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
	<form id="news_form" action="<?=$this->uri->full_url('admin/news/save');?>" method="post">
		<input type="hidden" name="menu_id" value="<?=$menu_id;?>">
		<input type="hidden" name="news_id" value="<?=$news_id;?>">
		<?php foreach ($languages as $key => $val): ?>
		<div id="news_tab_<?=$key;?>" class="news_tab"<?php if (LANG != $key) echo ' style="display: none"'; ?>>
			<div class="evry_title">
				<label class="block_label">Назва послуги:</label>
				<input type="text" name="title[<?=$key;?>]" value="<?=$news['title_' . $key];?>">
			</div>
			<div class="evry_title">
				<label class="block_label">Текст під фото:</label>
				<div class="no_float"><textarea class="news_anons" name="anons[<?=$key;?>]"><?=$news['anons_' . $key];?></textarea></div>
			</div>
			<div class="evry_title">
				<label class="block_label">Опис послуги:</label>
				<div class="no_float"><textarea class="news_text" name="text[<?=$key;?>]"><?=stripslashes($news['text_' . $key]);?></textarea></div>
			</div>

		</div>
		<?php endforeach; ?>

		<div class="evry_title">
			<label class="block_label">Зображення послуги:</label>
			<div id="news_image" class="no_float" style="width:78%;"></div>
		</div>
		<div class="evry_title">
			<label class="block_label">&nbsp;</label>
			<div class="no_float image_list" style="width:78%;">
				<ul id="images_list" style="width:100%;">
				<?php if (count($news_images) > 0): ?>
					<?php foreach ($news_images as $image): ?>
							<li data-id="<?=$image['image_id'];?>" style="float: left; width: 75px; height: 75px;">
								<div class="fm for_photo_cut">
									<div class="fm photo_cut" style="width: 75px; height: 75px;">
										<?php $sizes = getimagesize(ROOT_PATH . 'upload/news/' . $this->init_model->dir_by_id($image['news_id']) . '/' . $image['news_id'] . '/s_' . $image['file_name']); ?>
										<div style="width: 75px; height: 75px;"><img src="/upload/news/<?=$this->init_model->dir_by_id($image['news_id']);?>/<?=$image['news_id'];?>/t_<?=$image['file_name'] . '?t=' . time() . rand(10000, 1000000);?>" alt=""></div>
										<div class="links">
											<a href="#" class="fm fpc_edit" data-image-id="<?=$image['image_id'];?>" data-src="/upload/news/<?=$this->init_model->dir_by_id($image['news_id']);?>/<?=$image['news_id'];?>/s_<?=$image['file_name'];?>" data-width="<?=$sizes[0];?>" data-height="<?=$sizes[1];?>"><b></b>Редагувати</a>
											<?php if ($this->config->item('watermark') != ''): ?><a href="#" class="fm fpc_watermark" data-image-id="<?=$image['image_id'];?>" data-position="<?=$image['watermark_position'];?>" data-src="/upload/news/<?= $this->init_model->dir_by_id($image['news_id']);?>/<?= $image['news_id']?>/s_<?=$image['file_name'];?>" data-width="<?=$sizes[0];?>" data-height="<?=$sizes[1];?>"><b></b>Водяний знак</a><?php endif; ?>
											<a href="#" class="fm fpc_delete"><b></b>Видалити</a>
										</div>
									</div>
								</div>
							</li>
					<?php endforeach; ?>
				<?php endif; ?>
				</ul>
			</div>
		</div>
		<div class="fm for_sucsess">
			<div class="fmr save_links">
				<a href="#" class="fm save_adm"><b></b>Зберегти</a>
				<a href="#" class="fm apply_adm"><b></b>Застосувати</a>
				<a href="<?=$this->init_model->get_link($menu_id, '{URL}');?>" class="fm cansel_adm"><b></b>До списку послуги</a>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
//<![CDATA[
	function images_list() {
		$('#images_list')
			.find('li').removeClass('grey')
			.end()
			.find('li:even').addClass('grey')
			.end()
			.sortable({
				//axis: 'y',
				//handle: '.sorter a',
				//scroll: true,
				//crollSpeed: 1500,
				forcePlaceholderSize: true,
				placeholder: "ui-state-highlight",
				start: function(e, ui ){
					ui.placeholder.css({
						float: 'left',
						width: 75,
						height: 75
					});
				},
				update: function () {
					var items = [];

					$('#images_list').find('li').each(function (i) {
						items[i] = $(this).data('id');
					});

					component_loader_show($('.component_loader'));

					$.post(
						'<?=$this->uri->full_url('admin/news/images_position');?>',
						{
							menu_id: <?=$menu_id?>,
							items: items
						},
						function (response) {
							if (response.success) {
								images_list();
								component_loader_hide($('.component_loader'));
							}
						},
						'json'
					);
				}
			});
	}

	$(function () {

		var $li_width;

		$("div.image_list").each(function(){
			
			$ul = $(this).find('ul');
			$ul_width = $ul.width();
			$li_count = $ul.children().length;
			
			if($ul_width <= 500){ $count = 2; }else 
			if($ul_width >= 500 && $ul_width <= 700){ $count = 3; }else 
			if($ul_width >= 700 && $ul_width <= 1000){ $count = 4; }
			
			$li_sides_sum = $count*2*12;
			$li_width = ($ul_width-$li_sides_sum)/$count;
			$ul.find('li').css({"width":$li_width+"px", "height":"auto", "margin":"12px"});
			$ul.find('li').find('div').css({"width":$li_width+"px", "height":"auto"});
		});
		

		$('.news_text').ckeditor({height: 200});
		$('.news_anons').ckeditor({height: 100});

		$('.save, .save_adm, .apply, .apply_adm').on('click', function (e) {
			e.preventDefault();

			var redirect = ($(this).hasClass('apply_adm') || $(this).hasClass('apply')) ? false : true;

			$('.news_text').ckeditor({action: 'update'});
			$('.news_anons').ckeditor({action: 'update'});
			$('#news_form').ajaxSubmit({
				beforeSubmit: function () {
					component_loader_show($('.component_loader'));
				},
				success: function (response) {
					if (response.success) {
						component_loader_hide($('.component_loader'));
						if (redirect) window.location.href = $('.cansel_adm').attr('href');
					}
				},
				dataType: 'json'
			});
		});

		$('.component_lang').on('click', 'a', function (e) {
			e.preventDefault();

			$('.news_tab').hide();
			$('#news_tab_' + $(this).data('language')).show();
			$(this).addClass('active').siblings().removeClass('active');
		});

		images_list();

		/**
		 * Завантаження зображення
		 */
		var $news_image = $('#news_image');

		$news_image
			.fineUploader({
				request: {
					endpoint: '<?=$this->uri->full_url('admin/news/upload_image');?>',
					inputName: 'news_image',
					params: {
						news_id: <?=$news_id;?>,
						menu_id: <?=$menu_id;?>,
						component_id: <?=$news['component_id'];?>
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
				if (response.success) {
					$('.qq-upload-success').remove();

					var row = '<li data-id="' + response.image_id + '" style="float: left; width: 75px; height: 75px;">\
								<div class="fm for_photo_cut">\
									<div class="fm photo_cut" style="width: 75px; height: 75px;">\
										<div style="width: 75px; height: 75px;">\
											<img src="/upload/news/<?=$this->init_model->dir_by_id($news_id);?>/<?=$news_id;?>/' + 't_' + response.file_name + '" alt="">\
										</div>\
										<div class="links">\
											<a href="#" class="fm fpc_edit" data-image-id="' + response.image_id + '" data-src="/upload/news/<?=$this->init_model->dir_by_id($news_id);?>/<?=$news_id;?>/s_' + response.file_name + '" data-width="' + response.width + '" data-height="' + response.height + '"><b></b>Редагувати</a>\
											<?php if ($this->config->item('watermark') != ''): ?><a href="#" class="fm fpc_watermark" data-image-id="' + response.image_id + '" data-src="/upload/news/<?=$this->init_model->dir_by_id($news_id);?>/<?=$news_id;?>/t_' + response.file_name + '" data-width="' + response.width + '" data-height="' + response.height + '"><b></b>Водяний знак</a><?php endif; ?>\
											<a href="#" class="fm fpc_delete"><b></b>Видалити</a>\
										</div>\
									</div>\
								</div>\
							</li>';
					$('#images_list').append(row);
					images_list();
				}
			});
			$('#images_list')
			.on('click', '.fpc_delete', function (e) {
				e.preventDefault();
				var $link = $(this);
				confirmation('Видалити зображення?', function () {
					component_loader_show($('.component_loader'));
					$('.confirm_overlay').css('height', $(document).height());
					$.post(
						'<?=$this->uri->full_url('admin/news/delete_image');?>',
						{
							menu_id: <?=$menu_id?>,
							image_id: $link.closest('li').data('id')
						},
						function (response) {
							if (response.success) {
								$link.closest('li').slideUp().remove();
								component_loader_hide($('.component_loader'));
							}
						},
						'json'
					);
				});
			})
			.on('click', '.fpc_edit', function (event) {
				event.preventDefault();

				var $link = $(this),
					width = $link.data('width') > 600 ? 600 : $link.data('width'),
					height = width * $link.data('height') / $link.data('width'),
					crop_modal = '<div id="crop_overlay" class="confirm_overlay" style="display: block; opacity: 0.5; height:' + $(window).height() + 'px"></div><div id="crop_modal" class="crop_modal"><div class="fm crop_area"><div class="fm ca_panel"><a id="crop_cancel" href="#" class="fmr ca_cencel"><b></b>Скасувати</a><a id="crop_save" href="#" class="fmr ca_save"><b></b>Зберегти</a><span class="controls"><label class="check_label active"><i><input type="checkbox" name="proportion" checked="checked" value="1"></i>Пропорційно</label></span></div><div id="crop_preview" class="fm crop_review"><div style="overflow: hidden" class="crop_prew_border"><img src="' + $link.data('src') + '" alt=""></div></div><div id="crop_source" class="fm crop_source" style="margin-left: 100px"><img width="' + width + '" height="' + height + '" src="' + $link.data('src') + '"></div></div></div>';
				$('body').append(crop_modal);
				$('#crop_modal').css('top', $(document).scrollTop() + 50);
				$('#crop_source').find('img').Jcrop({
					keySupport: false,
					aspectRatio: 75/75,
					setSelect: [0, 0, 75, 75],
					realSizes: [$link.data('width'), $link.data('height')],
					onChange: function (coords) {
						crop_preview($('#crop_preview').find('div'), coords, 75, 75, width, height);
					}
				}, function () {
					var api = this;
					$('[name="proportion"]').off('change').on('change', function () {
						if ($(this).prop('checked')) {
							$(this).closest('label').addClass('active');
							api.setOptions({aspectRatio: 75/75});
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
							'<?=$this->uri->full_url('admin/news/crop_image');?>',
							{
								menu_id: <?=$menu_id?>,
								image_id: $link.data('image-id'),
								width: width,
								coords: api.tellScaled()
							},
							function (response) {
								if (response.success) {
									api.destroy();
									$link.closest('.for_photo_cut').find('img').attr('src', response.image);
									$('#crop_modal').add('#crop_overlay').remove();
									component_loader_hide($('.component_loader'));
								}
							},
							'json'
						);
					});
				});
				$(this).closest('li').removeClass('active').on('mouseover', function () {
					return false;
				});
			})
			.on('click', '.fpc_watermark', function (e) {
				e.preventDefault();

				var $link = $(this),
					width = $link.data('width') > 500 ? 500 : $link.data('width'),
					height = width * $link.data('height') / $link.data('width'),
					watermark_modal = '<div id="watermark_overlay" class="confirm_overlay" style="display: block; opacity: 0.5; height:' + $(document).height() + 'px"></div><div class="fm watermark_area"><div id="watermark_modal" class="watermark_modal" style="width: ' + width + 'px; margin: 0 0 0 -' + Math.round(width/2) + 'px"><div class="fm ca_panel"><a id="cancel_watermark" href="#" class="fmr ca_cencel"><b></b>Скасувати</a><a id="save_watermark" href="#" class="fmr ca_save"><b></b>Зберегти</a></div><div id="watermark_tiles" class="watermark_tiles"><img width="' + width + '" height="' + height + '" src="' + $link.data('src') + '"><div><a href="#" data-value="7"></a><a href="#" data-value="8"></a><a href="#" data-value="9"></a><a href="#" data-value="4"></a><a href="#" data-value="5"></a><a href="#" data-value="6"></a><a href="#" data-value="1"></a><a href="#" data-value="2"></a><a href="#" data-value="3"></a></div></div></div><div style="width: 100%; height: 1px; clear: both"></div></div>';

				$('body').append(watermark_modal);


				if ($link.data('position') > 0) $('#watermark_tiles').find('[data-value="' + $link.data('position') + '"]').addClass('active');

				var a_w = Math.round(width / 3),
					a_h = Math.round(height / 3);

				$('#watermark_tiles').find('a').each(function (i, val) {
					$(this).css({
						width: (a_w - (i % 3 == 2 ? 2 : 1)) + 'px',
						height: (a_h - 1) + 'px',
						left: (i % 3) * a_w + 'px'
					});

					if (i < 3) $(this).css('top', 0);
					if (i >= 3 && i <= 5) $(this).css('top', a_h + 'px');
					if (i > 5) $(this).css('top', (a_h * 2) + 'px');
				});

				$('#watermark_tiles').find('a').off('click').on('click', function (e) {
					e.preventDefault();
					$(this).addClass('active').siblings().removeClass('active');
				});

				$('#cancel_watermark').off('click').on('click', function (e) {
					e.preventDefault();
					$('#watermark_modal').add('#watermark_overlay').remove();
				});

				$('#save_watermark').off('click').on('click', function (e) {
					e.preventDefault();

					component_loader_show($('.component_loader'), '');
					var position = $('#watermark_tiles').find('a.active').data('value');
					$.post(
						'<?=$this->uri->full_url('admin/news/watermark_image');?>',
						{
							menu_id: <?=$menu_id?>,
							image_id: $link.data('image-id'),
							position: position
						},
						function (response) {
							if (response.success) {
								$link.data('position', position);
								$('#crop_modal').add('#crop_overlay').remove();
								component_loader_hide($('.component_loader'), '');
							}
						},
						'json'
					);
				});
			});
	});
//]]>
</script>
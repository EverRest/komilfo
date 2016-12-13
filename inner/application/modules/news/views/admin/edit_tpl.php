<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');
	$this->template_lib->set_js('admin/ckeditor/ckeditor.js');
	$this->template_lib->set_js('admin/jquery.form.js');
	$this->template_lib->set_js('admin/ui/jquery-ui-1.10.3.custom.min.js');
	$this->template_lib->set_js('admin/ui/jquery.ui.datepicker-uk.js');
	$this->template_lib->set_js('admin/fineuploader/jquery.fineuploader-3.5.0.min.js');
	$this->template_lib->set_js('admin/jcrop/js/jquery.Jcrop.min.js');
	$this->template_lib->set_js('plugins/mustache.min.js');
	$this->template_lib->set_css('js/admin/ui/jquery-ui-1.10.3.custom.min.css', TRUE);
	$this->template_lib->set_css('js/admin/fineuploader/fineuploader-3.5.0.css', TRUE);
	$this->template_lib->set_css('js/admin/jcrop/css/jquery.Jcrop.min.css', TRUE);
	$social_comments = unserialize($news['social_comments']);
?>
<style type="text/css">
	.included {margin-left: 20px;}
	.included b {position: relative; float: left; top: 0; right: 0; width: 26px; height: 24px; background: url(../images/spritesheet.png) no-repeat; margin-right: 5px;}
	.included b.f1 {background-position: -0px -4px;}
	.included b.f2 {background-position: -29px -4px;}
	.included b.f3 {background-position: -62px -4px;}
	.included b.f4 {background-position: -96px -4px;}
	.included b.f5 {background-position: -126px -4px;}
	.included b.f6 {background-position: -158px -4px;}
	.included b.f7 {background-position: -190px -4px;}
	.included b.f8 {background-position: -224px -4px;}
	.included b.f9 {background: url(../images/icon_tv.png) no-repeat;}
</style>
<div class="admin_component" id="news_component">
	<div class="component_loader"><span></span></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="news"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save"><b></b>Зберегти</a>
			<a href="#" class="fm apply"><b></b>Застосувати</a>
			<a href="<?=$this->init_model->get_link($menu_id, '{URL}');?>" class="fm cancel"><b></b>До списку новин</a>
		</div>
		<?php if (count($languages) > 1): ?>
			<div class="fmr component_lang">
				<?php foreach ($languages as $key => $val): ?>
					<a href="#" class="flags <?=$key;?><?=(($key == LANG) ? ' active' : '');?>" data-language="<?=$key;?>"><img src="<?=base_url("img/flags_".$key.".png")?>"></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
	<form id="news_form" action="<?=$this->uri->full_url('admin/news/save');?>" method="post">
		<input type="hidden" name="menu_id" value="<?=$menu_id;?>">
		<input type="hidden" name="news_id" value="<?=$news_id;?>">
		<div class="evry_title">
			<label class="block_label" for="on_main" style="cursor:pointer;">Відображати на головній</label>
			<div class="no_float"><input type="checkbox" id="on_main" name="on_main"  <?php if($news['on_main'] == 1){echo "checked";}?> style="cursor:pointer;"></div>
		</div> 
		<?php foreach ($languages as $key => $val): ?>
		<div id="news_tab_<?=$key;?>" class="news_tab"<?php if (LANG != $key) echo ' style="display: none"'; ?>>
			<div class="evry_title">
				<label class="block_label">Назва новини:</label>
				<input type="text" name="title[<?=$key;?>]" value="<?=$news['title_' . $key];?>">
			</div>
			<div class="evry_title">
				<label class="block_label">Короткий опис новини:</label>
				<div class="no_float"><textarea class="news_text_small" name="anons[<?=$key;?>]"><?=stripslashes($news['anons_' . $key]);?></textarea></div>
			</div>
			<div class="evry_title">
				<label class="block_label">Текст новини:</label>
				<div class="no_float"><textarea class="news_text" name="text[<?=$key;?>]"><?=stripslashes($news['text_' . $key]);?></textarea></div>
			</div>
		</div>
		<?php endforeach; ?>
		<div class="evry_title">
			<label class="block_label">Зображення до новини:</label>
			<div id="news_image" class="no_float <?=(!empty($news_images))? "hidden" : "";?>" style="width:78%;"></div>
		</div>
		<div class="evry_title">
			<label class="block_label">&nbsp;</label>
			<div class="no_float images_list">
				<ul>
					<?php if (count($news_images) > 0): ?>
						<?php foreach ($news_images as $photo): ?>
							<li data-photo-id="<?=$photo['image_id'];?>">
								<?php $sizes = getimagesize(ROOT_PATH . 'upload/news/' . get_dir_code($photo['news_id']) . '/' . $photo['news_id'] . '/s_' . $photo['file_name']); ?>
								<div class="fm pc_photo" style="height: <?=$image_thumb[1];?>px">
									<div class="vertical">
										<img src="/upload/news/<?=get_dir_code($photo['news_id']);?>/<?=$photo['news_id'];?>/t_<?=$photo['file_name'] . '?t=' . time() . mt_rand(10000, 1000000);?>" alt="">
										<a href="#" class="fm fpc_delete"><b></b></a>
									</div>
								</div>
								<div class="fm links">
									<a href="#" class="fm fpc_edit" data-src="/upload/news/<?=get_dir_code($photo['news_id']);?>/<?=$photo['news_id'];?>/s_<?=$photo['file_name'];?>" data-width="<?=$sizes[0];?>" data-height="<?=$sizes[1];?>"><span>Редагувати</span></a>
									<?php if ((string)$this->config->item('watermark') !== ''): ?><a href="#" class="fm fpc_watermark" data-src="/upload/news/<?= get_dir_code($photo['news_id']);?>/<?=$photo['news_id'];?>/s_<?=$photo['file_name'];?>" data-width="<?=$sizes[0];?>" data-height="<?=$sizes[1];?>" data-position="<?=$photo['watermark_position'];?>"><span>Водяний знак</span></a><?php endif; ?>
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
				<a href="<?=$this->init_model->get_link($menu_id, '{URL}');?>" class="fm cansel_adm"><b></b>До списку новин</a>
			</div>
		</div>
	</form>
	<script id="photo_template" type="text/html">
		<li data-photo-id="{{ photo_id }}">
			<div class="fm pc_photo" style="height: <?=$image_thumb[1];?>px">
				<div class="vertical">
					<img src="/upload/news/{{ dir_by_id }}/<?=$news['news_id']?>/t_{{ file_name }}" alt="">
					<a href="#" class="fm fpc_delete"><b></b></a>
				</div>
			</div>
			<div class="fm links">
				<a href="#" class="fm fpc_edit" data-src="/upload/news/{{ dir_by_id }}/<?=$news['news_id']?>/s_{{ file_name }}" data-width="{{ width }}" data-height="{{ height }}"><span>Редагувати</span></a>
				<?php if ((string)$this->config->item('watermark') !== ''): ?><a href="#" class="fm fpc_watermark" data-src="/upload/news/{{ dir_by_id }}/<?=$news['news_id']?>/{{ file_name }}" data-width="{{ width }}" data-height="{{ height }}" data-position="0"><span>Водяний знак</span></a><?php endif; ?>
			</div>
		</li>
	</script>
	<?php $this->load->view('admin/image_tools_tpl'); ?>
</div>
<script type="text/javascript">
//<![CDATA[
	$(function () {
		$('.component_lang').on('click', 'a', function (e) {
			e.preventDefault();
			$('.news_tab').hide();
			$('#news_tab_' + $(this).data('language')).show();
			$(this).addClass('active').siblings().removeClass('active');
		});
		
		var big_w = <?=$big[0];?>,
			big_h = <?=$big[1];?>,
			thumb_w = <?=$image_thumb[0];?>,
			thumb_h = <?=$image_thumb[1];?>,
			menu_id = <?=$menu_id;?>,
			component_id = <?=$component_id;?>,
			language = LANG,
			$component = $('.admin_component');
		$component.find('.component_lang').on('click', 'a', function (e) {
			e.preventDefault();
			$(this).addClass('active').siblings().removeClass('active');
			$component.find('.lang_tab_' + language).addClass('adm_hidden');
			language = $(this).data('language');
			$component.find('.lang_tab_' + language).removeClass('adm_hidden');
		});
		$component.find('#news_image')
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
				multiple: false,
				tetx: {
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
				if (response.hasOwnProperty('success') && response.success) {
					$('.qq-upload-success').remove();
					$component.find('.images_list').find('ul').eq(0).append(
						Mustache.render(
							$component.find('#photo_template').html(),
							{
								photo_id: response.image_id,
								dir_by_id: Math.ceil(parseInt(response.image_id) / 100 )*100,
								file_name: response.file_name,
								width: response.width,
								height: response.height
							}
						)
					);
					global_helper.photo_grid($component.find('.images_list'), thumb_w);
					$component.find('#news_image').addClass('hidden');
				}
			});
		$component.find('.images_list').find('ul').eq(0).sortable({
			scroll: true,
			handle: 'img',
			forcePlaceholderSize: true,
			placeholder: 'ui-state-highlight',
			start: function(e, ui ){
				ui.placeholder.css({
					float: 'left',
					width: $(ui.item).width() - 2,
					height: $(ui.item).height() - 2
				}).show();
			},
			update: function () {
				global_helper.loader($component);
				var photos = [];
				$component.find('.images_list').find('ul').eq(0).children().map(function () {
					photos.push($(this).data('photo-id'));
				});
				$.post(
					full_url('admin/news/position_photos'),
					{
						menu_id: menu_id,
						component_id: component_id,
						photos: photos
					},
					function (response) {
						if (response.hasOwnProperty('success') && response.success) {
							global_helper.loader($component);
						}
					},
					'json'
				);
			}
		});
		$component.find('.images_list')
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
							full_url('admin/news/crop_image'),
							{
								menu_id : menu_id,
								component_id : component_id,
								image_id: $link.closest('li').data('photo-id'),
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
							full_url('admin/news/watermark_image'),
							{
								menu_id : menu_id,
								component_id : component_id,
								image_id: $link.closest('li').data('photo-id'),
								position: position
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
							full_url('admin/news/delete_image'),
							{
								menu_id: menu_id,
								image_id: $link.closest('li').data('photo-id')
							},
							function (response) {
								if (response.hasOwnProperty('success') && response.success) {
									$link.closest('li').remove();
									global_helper.loader($component);
								}
								$component.find('#news_image').removeClass('hidden');
							},
							'json'
						);
					}
				);
			});
		global_helper.photo_grid($component.find('.images_list'), thumb_w);
		$('.news_text_small').ckeditor({"height": 100});
		$('.news_text').ckeditor({"height": 200});
		$component.on('click', '.save, .save_adm, .apply, .apply_adm', function (e) {
			e.preventDefault();
			$('.news_text_small').ckeditor({action: 'update'});
			$('.news_text').ckeditor({action: 'update'});
			$component.find('#news_form').ajaxSubmit({
				beforeSubmit:function () {
					global_helper.loader($component);
				},
				success:function (response) {
					if (response.success) {
						if($(this).is('.apply_adm')){
							global_helper.loader($component);
						}else{
							document.location = "<?=$base_url;?>"
						}
					}
				},
				dataType: 'json'
			});
		});
	});
//]]>
</script>
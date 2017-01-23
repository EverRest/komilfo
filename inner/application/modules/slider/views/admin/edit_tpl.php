<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	$this->template_lib->set_css('js/admin/fineuploader/fineuploader-3.5.0.css', TRUE);
	$this->template_lib->set_js('admin/fineuploader/jquery.fineuploader-3.5.0.min.js');

	$this->template_lib->set_css('js/admin/jcrop/css/jquery.Jcrop.min.css', TRUE);
	$this->template_lib->set_js('admin/jcrop/js/jquery.Jcrop.min.js');

	$this->template_lib->set_js('admin/ckeditor/ckeditor.js');
	$this->template_lib->set_js('admin/jquery.form.js');
?>
<?php error_reporting( E_ERROR ); ?>
<div class="fm admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="gallery"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save"><b></b>Зберегти</a>
			<a href="#" class="fm apply"><b></b>Застосувати</a>
			<a href="<?=base_url();?>" class="fm cancel"><b></b>До списку слайдів</a>
		</div>
		<div class="fmr component_lang" <?php if (count($languages) == 1): ?> style="display:none"<?php endif; ?>>
			<?php foreach ($languages as $key => $val): ?>
				<a href="#" class="flags <?=$key;?><?=(($key == LANG) ? ' active' : '');?>" data-language="<?=$key;?>">
					<img src="<?=base_url('img/flags_' . $key . '.png');?>" alt="">
				</a>
			<?php endforeach; ?>
		</div>
	</div>
	<form id="slide_form" action="<?=$this->uri->full_url('admin/slider/save');?>" method="post">
		<input type="hidden" name="menu_id" value="<?=$menu_id;?>">
		<input type="hidden" name="slide_id" value="<?=$slide_id;?>">
		<?php foreach ($languages as $key => $val): ?>
		<?php
			$sizes = array(0, 0);
			if ($slide['file_name'] != '') $sizes = getimagesize(ROOT_PATH . 'upload/slider/' . $menu_id . '/' . $slide_id . '/s_' . $slide['file_name']);
		?>
		<div class="lang_tab" id="box_<?=$key;?>"<?php if (LANG != $key) echo ' style="display:none"'; ?>>
			
<!--			<div class="evry_title">-->
<!--				<label class="block_label">Ім'я:</label>-->
<!--				<input type="text" name="title[--><?//=$key;?><!--]" value="--><?//=$slide['title'];?><!--">-->
<!--			</div>-->
           <!--  <div class="evry_title">
                <label class="block_label">Відгук:</label>
                <input type="text" name="description[<?=$key;?>]" value="<?=$slide['description'];?>">
            </div> -->
            <div class="evry_title">
				<label for="ca_text_<?=$key;?>" class="block_label">Текст до слайду:</label>
				<div class="no_float"><textarea class="component_article" id="ca_text_<?=$key;?>" name="description[<?=$key;?>]" style="height: 400px"><?=stripslashes($slide['description']);?></textarea></div>
			</div>	

			<!-- <div class="evry_title">
				<label class="block_label">Посилання:</label>
				<input type="text" name="url[<?=$key;?>]" value="<?=$slide['url_' . $key];?>">
			</div> -->
		</div>
		<?php endforeach; ?>
		<div class="evry_title sliders"<?php if( $slide['file_name'] !='') echo "style='display:none'"; ?>>
			<label class="block_label">Зображення:</label>
			<div id="slide_image" class="no_float" ></div>
		</div>
		<div class="evry_title">
			<label class="block_label">&nbsp;</label>
			<div class="no_float image_list" style="width:78%;">
				<ul id="images_list" style="width:100%;">
					<?php if( $slide['file_name'] !=''):?>
						<li data-id="<?=$slide['slide_id'];?>" style="float: left; width:300px; height: 300px; margin-top: 2px; margin-left: 2px;">
								<div class="fm for_photo_cut">
									<div class="fm photo_cut" style="width:300px; height: 150px;">
										<?php $sizes = getimagesize(ROOT_PATH . 'upload/slider/'. $menu_id . '/' . $slide['slide_id'] . '/' . $slide['file_name']); ?>
										<div style="width:300px; height: 150px;"><img src="/upload/slider/<?=$menu_id;?>/<?=$slide['slide_id'];?>/<?=$slide['file_name'] . '?t=' . time() . rand(10000, 1000000);?>" alt="" style="width:300px; height: 150px;"></div>
										<div class="links">
											<a href="#" class="fm fpc_edit" data-image-id="<?=$slide['slide_id'];?>" data-src="/upload/slider/<?=$menu_id;?>/<?=$slide['slide_id'];?>/s_<?=$slide['file_name'];?>" data-width="<?=$sizes[0];?>" data-height="<?=$sizes[1];?>"><b></b>Редагувати</a>
											<a href="#" class="fm fpc_delete"><b></b>Видалити</a>
										</div>
									</div>
								</div>
							</li>
					<?php endif;?>
				</ul>
			</div>
		</div>
		<div class="fm for_sucsess">
			<div class="fmr save_links">
				<a href="#" class="fm save_adm"><b></b>Зберегти</a>
				<a href="#" class="fm apply_adm"><b></b>Застосувати</a>
				<a href="<?=base_url();?>" class="fm cansel_adm"><b></b>До списку слайдів</a>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		$('.component_article').ckeditor({height: 300});
		$('.component_lang').find('a').each(function () {
			var language = $(this).data('language');

			$(this).on('click', function (e) {
				e.preventDefault();
				var language = $(this).data('language');
				$(this).addClass('active').siblings().removeClass('active');
				$('.lang_tab').hide();
				$('#box_' + language).show();
					});
				});

			/**
			 * Завантаження зображення
			 */
					var $slider_image = $('#slide_image');
					$slider_image.fineUploader({
						request: {
							endpoint: '<?=$this->uri->full_url('admin/slider/upload');?>',
							inputName: 'slide_image',
							params: {
								menu_id: <?=$menu_id;?>,
								slide_id: <?=$slide_id;?>
							}
						},
						multiple: false ,
						text: {
							uploadButton: 'Виберіть або перетягніть файл зображення для статті',
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
						}).on('complete', function (event, id, fileName, response){
							if (response.success) {
								$('.qq-upload-success').remove();
								var row = '<li data-id="' + response.slide_id + '" style="float: left; width:300px; height: 150px; margin-left:2px; margin-top:2px;">\
										<div class="fm for_photo_cut">\
											<div class="fm photo_cut" style="width: 300px; height: 150px">\
												<div style="width: 300px; height: 150px">\
													<img src="/upload/slider/<?=$menu_id;?>/<?=$slide_id;?>/' + response.file_name + '" alt="" style="width: 300px; height: 150px;">\
												</div>\
												<div class="links">\
													<a href="#" class="fm fpc_edit" data-image-id="' + response.slide_id + '" data-src="/upload/slider/<?=$menu_id;?>/<?=$slide_id;?>/s_' + response.file_name + '" data-width="' + response.width + '" data-height="' + response.height + '"><b></b>Редагувати</a>\
													<a href="#" class="fm fpc_delete"><b></b>Видалити</a>\
												</div>\
											</div>\
										</div>\
									</li>';
								$('#images_list').append(row);
								$(".sliders").hide(200);
							}
						});

						$('#images_list').on('click', '.fpc_delete', function (e) {
							e.preventDefault();
							var $link = $(this);
							confirmation('Видалити зображення із слайдера?', function () {
								component_loader_show($('.component_loader'));
								$('.confirm_overlay').css('height', $(document).height());
								$.post(
									'<?=$this->uri->full_url('admin/slider/delete_photo');?>',
									{
										slide_id: $link.closest('li').data('id'),
										menu_id: <?=$menu_id;?>
									},
									function (response) {
										if (response.success) {
											$link.closest('li').slideUp().remove();
											component_loader_hide($('.component_loader'));
											$(".sliders").show(200);
										}
									},
									'json'
								);
							});
						}).on('click', '.fpc_edit', function (event) {
                            event.preventDefault();
                            var $link = $(this),
                                width = $link.data('width') > 600 ? 600 : $link.data('width'),
                                height = width * $link.data('height') / $link.data('width'),
                                crop_modal = '<div id="crop_overlay" class="confirm_overlay" style="display: block; opacity: 0.5; height:' + $(window).height() + 'px"></div><div id="crop_modal"  class="crop_modal" style="height: 520px;"><div class="fm crop_area" style="z-index:600;"><div class="fm ca_panel"><a id="crop_cancel" href="#" class="fmr ca_cencel"><b></b>Скасувати</a><a id="crop_save" href="#" class="fmr ca_save"><b></b>Зберегти</a><span class="controls"><label class="check_label active"><i><input type="checkbox" name="proportion" checked="checked" value="1"></i>Пропорційно</label></span></div><div id="crop_preview" class="fm crop_review" style="width: 300px; height: 300px;"><div style="overflow: hidden" class="crop_prew_border"><img src="' + $link.data('src') + '" style="width:auto;" alt=""></div></div><div id="crop_source" class="fm crop_source" style="width: 600px"><img width="' + width + '" height="' + height + '" src="' + $link.data('src') + '"></div></div></div>';
                            $('body').append(crop_modal);
//                                $('#crop_preview').find('img').css({'width':'auto';});
                            $('#crop_modal').css('top', $(document).scrollTop() + 50);
                            $('#crop_source').find('img').Jcrop({
                                keySupport: false,
                                aspectRatio: 300/300,
                                setSelect: [0, 0, 300, 300],
                                realSizes: [$link.data('width'), $link.data('height')],
                                onChange: function (coords) {
                                    crop_preview($('#crop_preview').find('div'), coords, 300, 300, width, height);
                                }
                            }, function () {
                                var api = this;
                                $('[name="proportion"]').off('change').on('change', function () {
                                    if ($(this).prop('checked')) {
                                        $(this).closest('label').addClass('active');
                                        api.setOptions({aspectRatio: 300/300});
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
                                        '<?=$this->uri->full_url('admin/slider/crop');?>',
                                        {
                                            slide_id: $link.data('image-id'),
                                            menu_id: <?= $menu_id;?>,
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
                        });
		/**
		 * Відправка форми
		 */
		$('.save_adm').add('.component_edit_links .save').on('click', function (e) {
			e.preventDefault();
			$('.component_article').ckeditor({action: 'update'});
			$('#slide_form').ajaxSubmit({
				beforeSubmit:function () {
					component_loader_show($('.component_loader'), '');
				},
				success:function (response) {
					if (response.success) {
						component_loader_hide($('.component_loader'), '');
						window.location.href = '<?=base_url();?>';
					}
				},
				dataType: 'json'
			});
		});
		/**
		 * Відправка форми
		 */
		$('a.apply_adm').add('.component_edit_links .apply').on('click', function (e) {
			e.preventDefault();

			$('#slide_form').ajaxSubmit({
				beforeSubmit:function () {
					component_loader_show($('.component_loader'), '');
				},
				success:function (response) {
					if (response.success) {
						component_loader_hide($('.component_loader'), '');
					}
				},
				dataType: 'json'
			});
		});

	});
</script>
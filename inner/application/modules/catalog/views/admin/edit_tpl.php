<?php defined('ROOT_PATH') OR exit('No direct script access allowed');
	/**
	 * @var int $menu_id
	 * @var int $catalog_id
	 * @var array $catalog
	 * @var array $catalog_markers
	 * @var array $languages
	 * @var array $markers
	 * @var array $locations
	 */
	$this->template_lib
		->set_js('admin/ckeditor/ckeditor.js')
		->set_css('js/admin/fineuploader/fineuploader-3.5.0.css', true)
		->set_js('admin/fineuploader/jquery.fineuploader-3.5.0.min.js')
		->set_css('js/admin/jcrop/css/jquery.Jcrop.min.css', true)
		->set_js('admin/jcrop/js/jquery.Jcrop.min.js')
		->set_js('admin/jquery.form.js')
		->set_js('plugins/mustache.min.js')
		->set_css('js/admin/ui/jquery-ui-1.10.3.custom.min.css', true)
		->set_js('admin/ui/jquery-ui-1.10.3.custom.min.js')
		->set_js('admin/ui/jquery.ui.datepicker-uk.js');
		
	$sizes = array();
	$dir = get_dir_path('upload/catalog/' . get_dir_code($catalog_id) . '/' . $catalog_id, false);
	// echo "<pre>";
	// print_r( $component_id);
	// echo "</pre>";exit();
?>
<div class="fm admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="catalog"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save save_catalog"><b></b>Зберегти</a>
			<a href="#" class="fm apply save_catalog ajax_save"><b></b>Застосувати</a>
			<a href="<?=$this->init_model->get_link($menu_id, '{URL}');?>" class="fm cancel"><b></b>До списку суконь</a>
		</div>
		<div class="fmr component_lang" <?php if (count($languages) === 1): ?> style="display:none"<?php endif; ?>>
			<?php foreach ($languages as $key => $val): ?>
				<a href="#" class="flags <?=$key;?><?=(($key === LANG) ? ' active' : '');?>" data-language="<?=$key;?>">
					<img src="<?=base_url('img/flags_' . $key . '.png');?>" alt="">
				</a>
			<?php endforeach; ?>
		</div>
	</div>
	<form id="catalog_form" action="<?=$this->uri->full_url('admin/catalog/save');?>" method="post">
		<input type="hidden" name="menu_id" value="<?=$menu_id;?>">
		<input type="hidden" name="catalog_id" value="<?=$catalog_id;?>">
		<?php foreach ($languages as $key => $val): ?>
		<div class="lang_tab lang_tab_<?=$key;?>"<?php if (LANG !== $key): ?> style="display:none"<?php endif; ?>>
			<div class="evry_title">
				<label class="block_label">Назва:</label>
				<input type="text" name="title[<?=$key;?>]" value="<?=$catalog['title_' . $key];?>">
			</div>
			<div class="evry_title">
				<label class="block_label">Опис:</label>
				<div class="no_float"><textarea class="catalog_text" name="text[<?=$key;?>]" ><?=$catalog['text_' . $key];?></textarea></div>
			</div>
		</div>
		<?php endforeach; ?>
		<?
		if($catalog['shops'] != ''){
			$shops_c = unserialize($catalog['shops']);
			foreach ($shops_c as $key => $value) {
				$shops[$value] = $value;
			}
		}
		$place = array ();
		

		foreach ($menu[0] as $country): 
			$place[$country['id']] = array ();
			if(isset($menu[$country['id']])){
				foreach ($menu[$country['id']] as $key => $city_arr):
						$place[$city_arr['parent_id']][$city_arr['id']] = $city_arr;
				endforeach; 
			}
		endforeach; 
		?>
		<div class="evry_title">
			<label class="block_label"><h3>Магазини</h3></label>
			<div class="no_float"></div>
		</div>
		<div class="evry_title">
			<label class="block_label" for="check_all" style="cursor:pointer;">Вибрати всі</label>
			<div class="no_float"><input type="checkbox" id="check_all" name="check_all" style="cursor:pointer;"></div>
			<ul class="city_place">
				<?php foreach ($menu[0] as $country): ?>
					<li id="country" data-cid="<?=$country['id']?>"><div class="no_float"><b><?=$country['name']?></b></div>
						<ul>	
							<?php if (!empty($place[$country['id']])): ?>
								<?php foreach ($place[$country['id']] as $key => $city): ?>
									<?php $found = false; $isset = false;?>
									<li id='city' data-sid="<?=$city['id']?>"><div class="no_float"><?=$city['name']?></div>
										<ul>
											<?php foreach ($markers as $key => $shop): if(in_array($city['id'], $shop)) if(!$found) $found = true; endforeach?>
											<?php $i=-1; foreach ($markers as $key => $shop): ?>
												<?php  if ($shop['menu_id'] == $country['id']):
													unset($markers[$key]);
												 endif ?>
												<?php if (!$found && !$isset): ?>
													<li style="color:red;"><div class="no_float"> - Список магазинів порожній. Додайте магазини.</div></li>
													<? $isset = true;?>
												<?php endif ?>
												<?php if ($shop['menu_id'] == $city['id']): ?>
													<li><div class="no_float"><input type="checkbox" id="shop" name="on_main_<?=$i+1?>" value="<?=$shop['component_id']?>" data-city="<?=$city['id']?>" data-country="<?=$country['id']?>" <?php if(isset($shops[$shop['component_id']]) && $shops[$shop['component_id']] != ''){echo "checked";}?> style="cursor:pointer;"><?=$shop['title']?></div></li>
												<?php endif ?>
											<?php endforeach; ?>
										</ul>
									</li>
								<?php endforeach; ?>
							<?php else: ?>
								<li style="color:red;"">Список міст порожній. Додайте міста.</li>
							<?php endif ?>
						</ul>
					</li>
				<?php endforeach; ?>
			</ul>
		</div> 
		<div class="evry_title">
			<label class="block_label">Зображення:</label>
			<div class="fm" id="photo_uploader_box">
				<div id="photo_uploader"></div>
			</div>
			<div id="photo_box" class="no_float images_list">
				<ul>
					<?php if (!empty($catalog_images)): 
						foreach ($catalog_images as $key => $value): ?>
							<?php $sizes = getimagesize($dir . $value['photo']); ?>
							<li data-image-id="<?=$value['image_id']?>">
								<div class="fm pc_photo" style="height: <?=$thumb[1];?>px">
									<div class="vertical">
										<img src="/upload/catalog/<?=get_dir_code($catalog_id);?>/<?=$catalog_id;?>/<?=$value['photo'] . '?t=' . time() . mt_rand(10000, 1000000);?>" alt="">
										<a href="#" class="fm fpc_delete"><b></b></a>
									</div>
								</div>
								<div class="fm links">
									<!-- <a href="#" class="fm fpc_edit" data-src="/upload/catalog/<?=get_dir_code($catalog_id);?>/<?=$catalog_id;?>/s_<?=$value['photo'];?>" data-width="<?=$sizes[0];?>" data-height="<?=$sizes[1];?>"><span>Редагувати</span></a> -->
									<?php if ((string)$this->config->item('watermark') !== ''): ?><a href="#" class="fm fpc_watermark" data-src="/upload/catalog/<?=get_dir_code($catalog_id);?>/<?=$catalog_id;?>/<?=$value['photo'];?>" data-width="<?=$thumb[0]*2;?>" data-height="<?=$thumb[1]*2;?>" data-position="<?=$value['watermark_position'];?>"><span>Водяний знак</span></a><?php endif; ?>
								</div>
							</li>
						<? endforeach; ?>
					<?php endif; ?>
				</ul>
			</div>
		</div>
		<div class="fm for_sucsess">
			<div class="fmr save_links">
				<a href="#" class="fm save_adm save_catalog"><b></b>Зберегти</a>
				<a href="#" class="fm apply_adm save_catalog ajax_save"><b></b>Застосувати</a>
				<a href="<?=$this->init_model->get_link($menu_id, '{URL}');?>" class="fm cansel_adm"><b></b>До списку суконь</a>
			</div>
		</div>
	</form>
	<script id="photo_template" type="text/html">
		<li data-image-id="{{ image_id }}">
			<div class="fm pc_photo" style="height: <?=$thumb[1];?>px">
				<div class="vertical">
					<img src="/upload/catalog/<?=get_dir_code($catalog_id);?>/<?=$catalog_id;?>/{{ file_name }}" alt="">
					<a href="#" class="fm fpc_delete"><b></b></a>
				</div>
			</div>
			<div class="fm links">
			<!-- 	<a href="#" class="fm fpc_edit" data-src="/upload/catalog/<?=get_dir_code($catalog_id);?>/<?=$catalog_id;?>/s_{{ file_name }}" data-width="{{ width }}" data-height="{{ height }}"><span>Редагувати</span></a> -->
				<?php if ((string)$this->config->item('watermark') !== ''): ?><a href="#" class="fm fpc_watermark" data-src="/upload/catalog/<?=get_dir_code($catalog_id);?>/<?=$catalog_id;?>/{{ file_name }}" data-width="{{ width }}" data-height="{{ height }}" data-position="0"><span>Водяний знак</span></a><?php endif; ?>
			</div>
		</li>
	</script>
	<?php $this->load->view('admin/image_tools_tpl'); ?>
</div>
<script type="text/javascript">
function serialize( mixed_val ) {    
    switch (typeof(mixed_val)){
        case "number":
            if (isNaN(mixed_val) || !isFinite(mixed_val)){
                return false;
            } else{
                return (Math.floor(mixed_val) == mixed_val ? "i" : "d") + ":" + mixed_val + ";";
            }
        case "string":
            return "s:" + mixed_val.length + ":\"" + mixed_val + "\";";
        case "boolean":
            return "b:" + (mixed_val ? "1" : "0") + ";";
        case "object":
            if (mixed_val == null) {
                return "N;";
            } else if (mixed_val instanceof Array) {
                var idxobj = { idx: -1 };
		var map = []
		for(var i=0; i<mixed_val.length;i++) {
			idxobj.idx++;
                        var ser = serialize(mixed_val[i]);
 
			if (ser) {
                        	map.push(serialize(idxobj.idx) + ser)
			}
		}                                       
                return "a:" + mixed_val.length + ":{" + map.join("") + "}"
            }
            else {
                var class_name = get_class(mixed_val);
 
                if (class_name == undefined){
                    return false;
                }
 
                var props = new Array();
                for (var prop in mixed_val) {
                    var ser = serialize(mixed_val[prop]);
 
                    if (ser) {
                        props.push(serialize(prop) + ser);
                    }
                }
                return "O:" + class_name.length + ":\"" + class_name + "\":" + props.length + ":{" + props.join("") + "}";
            }
        case "undefined":
            return "N;";
    }
 
    return false;
}
function in_array(needle, haystack, strict) {	
	var found = false, key, strict = !!strict;
	for (key in haystack) {
		if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
			found = true;
			break;
		}
	}
	return found;
}
	$(document).ready(function () {



		var $component = $('.admin_component');
		$component.find('[type="checkbox"]#on_main').on('click',function(event) {
			if(!$(this).prop('checked')){
				$('input#check_all').prop('checked', false);		
			}
		});
		$component.find('input#check_all').on('click', function(event) {
			$this = $(this);
			check = $(this).prop("checked");
			
			$component.find('[type="checkbox"]').each(function (i){
				$(this).prop( "checked", check );
				
			})
		});
		$component.find('.component_lang').on('click', 'a', function (e) {
			// $(this).on('click', function (e) {
				e.preventDefault();
				$(this).addClass('active').siblings().removeClass('active');
				$('.lang_tab').hide();
				$('.lang_tab_' + $(this).data('language')).show();
			// });
		});
		/**
		 * Відправка форми
		 */

		$('.catalog_text').ckeditor({"height": 200});

		$component
			.on('ichange', '[name="open"]', function () {
				$component.find('.open_box').toggleClass('adm_hidden');
			})
			.on('click', '.save_catalog', function (e) {
				e.preventDefault();

				$('.catalog_text').ckeditor({action: 'update'});

				var $link = $(this), request = new Object(), city = new Array(), country = new Array(), i=0;
				$('input[type="checkbox"]#shop').each(function(index) {
					if($(this).prop('checked')){
						country_id = $(this).closest('li#country').attr("data-cid");
						city_id = $(this).closest('li#city').attr("data-sid");
						request['on_main_'+i] = $(this).val();	
						if (!in_array(country_id, country)) { country.push(country_id); }
						if (!in_array(city_id, city) ) { city.push(city_id); }
						
						request['count'] = i;
						i++;
					}
				});
				request["country"] = serialize(country);
				request["city"] = serialize(city);
				
				$.ajaxSetup({async:false});
				
				$component.find('#catalog_form').ajaxSubmit({
					beforeSubmit:function () {
						var count;
						global_helper.loader($component);
						
					},
					data : request,
					success:function (response) {
						if (response.success) {
							if ($link.hasClass('ajax_save')) {
								global_helper.loader($component);
							} else {
								window.location.href = '<?=$this->init_model->get_link($menu_id, '{URL}');?>';
							}
						}
					},
					dataType: 'json'
				});
			});
	
		var menu_id = <?=$menu_id;?>,
			catalog_id = <?=$catalog_id;?>,
			thumb_w = <?=$thumb[0];?>,
			thumb_h = <?=$thumb[1];?>,
			block_w = <?=$thumb[0];?>;
		global_helper.photo_grid($component.find('.images_list'), block_w);
		$component.find('#photo_uploader')
			.fineUploader({
				request: {
					endpoint: full_url('admin/catalog/upload_photo'),
					inputName: 'image',
					params: {
						menu_id: menu_id,
						catalog_id: catalog_id
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
					// $component.find('#photo_uploader_box').addClass('adm_hidden');
                    $component.find('#photo_box').removeClass('adm_hidden').find('ul').eq(0).append(
						Mustache.render(
							$component.find('#photo_template').html(),
							{
								image_id: response.image_id,
								file_name: response.photo,
								width: response.width,
								height: response.height
							}
						)
					);
					global_helper.photo_grid($component.find('.images_list'), block_w);
				}
			});
		$component.find('#photo_box')
			.on('click', '.fpc_edit', function (e) {
				e.preventDefault();
				var $link = $(this);
				global_helper.photo_crop(
					$link,
					$component.find('#crop_template'),
					thumb_w,
					thumb_h,
					thumb_w,
					thumb_h,
					function (api, width) {
						global_helper.loader($component);
						$.post(
							full_url('admin/catalog/crop_photo'),
							{
								menu_id: menu_id,
								image_id: $link.closest('li').attr('data-image-id'),
								catalog_id: catalog_id,
								width: width,
								coords: api.tellScaled()
							},
							function (response) {
								if (response.success) {
									$link.closest('li').find('.pc_photo').find('img').attr('src', response.src);
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
							full_url('admin/catalog/watermark_photo'),
							{
								menu_id: menu_id,
								image_id: $link.closest('li').attr('data-image-id'),
								catalog_id: catalog_id,
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
							full_url('admin/catalog/remove_photo'),
							{
								menu_id: menu_id,
								image_id: $link.closest('li').attr('data-image-id'),
								catalog_id: catalog_id
							},
							function (response) {
								if (response.hasOwnProperty('success') && response.success) {
									$link.closest('li').remove();
									// $component.find('#photo_uploader_box').removeClass('adm_hidden');
									// $component.find('#photo_box').addClass('adm_hidden');
									global_helper.loader($component);
								}
							},
							'json'
						);
					}
				);
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
						photos.push($(this).data('image-id'));
					});
					$.post(
						full_url('admin/catalog/position_photos'),
						{
							menu_id: menu_id,
							catalog_id: catalog_id,
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
	});
</script>
<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');

	$this->template_lib->set_js('admin/ckeditor/ckeditor.js');
	$this->template_lib->set_js('admin/jquery.form.js');

	if ($menu_index == 2) {
		$this->template_lib->set_js('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places', FALSE);
	}
?>
<div class="admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="article"></div>
		</div>
		<div class="fm component_edit_links">
        	<div class="fm only_text"><div>Редагування статті</div></div>
			<a href="#" class="fm save"><b></b>Зберегти</a>
			<a href="#" class="fm apply"><b></b>Застосувати</a>
			<a href="#" class="fm cancel"><b></b>Скасувати</a>
		</div>
		<?php if (count($languages) > 1): ?>
		<div class="fmr component_lang">
			<?php foreach ($languages as $key => $val): ?>
				<a href="#" class="flags <?=$key;?><?=(($key == LANG) ? ' active' : '');?>" data-language="<?=$key;?>"><img src="img/flags_<?=$key;?>.png"></a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
	<div class="fm admin_view_article">
		<form id="component_article_form" action="<?=$this->uri->full_url('/admin/article/update_article');?>" method="post">
			<input type="hidden" name="component_id" value="<?=$component_id;?>">
			<input type="hidden" name="menu_id" value="<?=$menu_id;?>">
			<?php if ($menu_index == 2): ?>
				<?php foreach ($languages as $key => $val): ?>
					<div id="article_tab_<?=$key;?>" class="article_tab"<?=(($key != LANG) ? ' style="display: none"' : ''); ?>>
						<div class="evry_title">
							<label for="ca_title_shop_<?=$key;?>" class="block_label">Назва Магазину:</label>
							<input type="text" id="ca_title_shop_<?=$key;?>" name="title_shop[<?=$key;?>]" value="<?=$article['title_shop_' . $key];?>">
						</div>


						<div class="evry_title">
							<label for="ca_address_<?=$key;?>" class="block_label">Адреса 1:</label>
							<input type="text" id="ca_address_<?=$key;?>" name="address[<?=$key?>]" value="<?=$article['address_'.$key];?>" class="short">
						</div>

						<div class="evry_title">
							<label for="ca_address_2_<?=$key;?>" class="block_label">Адреса 2:</label>
							<input type="text" id="ca_address_2_<?=$key;?>" name="address_2[<?=$key?>]" value="<?=$article['address_2_'.$key];?>" class="short">
						</div>

					</div>
				<? endforeach; ?>
				<div class="evry_title">
					<label for="ca_phone" class="block_label">Телефон:</label>
					<input type="text" id="ca_phone" name="phone" value="<?=$article['phone'];?>" class="short">
				</div>
				<div class="evry_title">
					<label for="ca_facebook" class="block_label">Facebook:</label>
					<input type="text" id="ca_facebook" name="facebook" value="<?=$article['facebook'];?>" class="short">
				</div>

				<div class="evry_title padding">
					<div class="no_float bold">Позначка на мапі</div>
				</div>
				<div class="evry_title">
					<label for="ca_lat" class="block_label">Широта:</label>
					<input type="text" id="ca_lat" name="lat" value="<?=$article['lat'];?>" class="short">
				</div>
				<div class="evry_title">
					<label for="ca_lng" class="block_label">Довгота:</label>
					<input type="text" id="ca_lng" name="lng" value="<?=$article['lng'];?>" class="short">
				</div>
				<div class="evry_title padding">
					<div class="no_float"><a href="#" id="map_centration">Центрувати автоматично</a></div>
				</div>
				<div class="evry_title">
					<label for="ca_zoom" class="block_label">Збільшення:</label>
					<input type="text" id="ca_zoom" name="zoom" value="<?=$article['zoom'];?>" class="short">
				</div>
				<div class="evry_title">
					<label for="map_zoom" class="block_label">Знайти місце на карті:</label>
					<div id="search_panel"><input id="google_map_search" type="text" placeholder=""></div>
				</div>
				<div class="evry_title padding">
					<div class="fm admin_map_edit" id="google_map_edit_block"></div>
				</div>

			<?php else: ?>
				<?php foreach ($languages as $key => $val): ?>
					<div id="article_tab_<?=$key;?>" class="article_tab"<?=(($key != LANG) ? ' style="display: none"' : ''); ?>>
						<div class="evry_title">
							<label for="ca_title_<?=$key;?>" class="block_label">Назва статті:</label>
							<input type="text" id="ca_title_<?=$key;?>" name="title[<?=$key;?>]" value="<?=$article['title_' . $key];?>">
						</div>
						<div class="evry_title">
							<label for="ca_text_<?=$key;?>" class="block_label">Текст статті:</label>
							<div class="no_float"><textarea class="component_article" id="ca_text_<?=$key;?>" name="text[<?=$key;?>]" style="height: 400px"><?=stripslashes($article['text_' . $key]);?></textarea></div>
						</div>
					</div>
				<?php endforeach; ?>
				<input type="hidden" name="lat" value="">
				<input type="hidden" name="lng" value="">
				<input type="hidden" name="zoom" value="">
			<?php endif; ?>
			<div class="fm for_sucsess">
				<div class="fmr save_links">
					<a href="#" class="fm save_adm"><b></b>Зберегти</a>
					<a href="#" class="fm apply_adm"><b></b>Застосувати</a>
					<a href="#" class="fm cansel_adm"><b></b>Скасувати</a>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">

	function cancel_editing() {
		window.location.href = '<?=$this->init_model->get_link($menu_id, '{URL}');?>';
	}

	var request = {};
	
	$(function () {

		$(".admin_view_article .check_label").find('input').on('click', function() {
			if ($(this).attr('type') !== undefined && $(this).attr('type').toLowerCase() === 'checkbox') {
				request[$(this).attr('name')] = $(this).prop('checked') ? $(this).val() : null;
			}
		});

		$('.component_article').ckeditor({height: 300});

		$('.component_lang').find('a').on('click', function (e) {
			e.preventDefault();

			$(this).closest('div').find('.active').removeClass('active');
			$(this).addClass('active');

			$('.article_tab').hide();
			$('#article_tab_' + $(this).data('language')).show();
		});

		$('.component_edit_links .save, .for_sucsess .save_adm').on('click', function (e) {
			e.preventDefault();
			save_component_article(function () {
				cancel_editing();
			});
		});

		$('.component_edit_links .apply, .for_sucsess .apply_adm').on('click', function (e) {
			e.preventDefault();
			save_component_article('');
		});

		$('.component_edit_links .cancel, .for_sucsess .cansel_adm').on('click', function (e) {
			e.preventDefault();
			cancel_editing();
		});

		<?php if ($menu_index == 2): ?>

		var marker = null,
			regex_coords = /\-?[0-9]+[\.]{0,1}[0-9]*/,
			mapOptions = {
				center: new google.maps.LatLng(<?=($article['lat'] != '' ? $article['lat'] : '49.250855732520556');?>, <?=($article['lng'] != '' ? $article['lng'] : '32.94451661742571');?>),
				zoom: <?=($article['zoom'] > 0 ? $article['zoom']: 5);?>,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
		var map = new google.maps.Map(document.getElementById("google_map_edit_block"), mapOptions);
		var searchBox = new google.maps.places.SearchBox(document.getElementById('google_map_search'));

		<?php if ($article['lat'] != '' AND $article['lng'] != ''): ?>
		marker = new google.maps.Marker({
			map: map,
			draggable: true,
			animation: google.maps.Animation.DROP,
			position: new google.maps.LatLng(<?=$article['lat'];?>, <?=$article['lng'];?>)
		});

		google.maps.event.addListener(marker, 'dragend', function() {
			$('#ca_lat').val(marker.getPosition().lat());
			$('#ca_lng').val(marker.getPosition().lng());
		});
		<?php endif; ?>

		google.maps.event.addListener(searchBox, 'places_changed', function() {
			var places = searchBox.getPlaces();
			if (places[0]) {
				var bounds = new google.maps.LatLngBounds();
				bounds.extend(places[0].geometry.location);
				map.fitBounds(bounds);

				marker = new google.maps.Marker({
					map: map,
					draggable:true,
					animation: google.maps.Animation.DROP,
					position: places[0].geometry.location
				});

				google.maps.event.addListener(marker, 'dragend', function() {
					$('#ca_lat').val(marker.getPosition().lat());
					$('#ca_lng').val(marker.getPosition().lng());
				});

				$('#ca_lat').val(places[0].geometry.location.lat());
				$('#ca_lng').val(places[0].geometry.location.lng());
				$('#ca_zoom').val(map.getZoom());
			}
		});

		/**
		 * Change map zoom
		 */
		google.maps.event.addListener(map, 'zoom_changed', function() {
			$('#ca_zoom').val(map.getZoom());
		});

		$('#ca_zoom').on('blur', function () {
			var zoom = $(this).val();
			if ($.isNumeric(zoom)) map.setZoom(parseInt(zoom));
		});

		$('#ca_lat, #ca_lng').on('blur', function () {
			var lat = $('#ca_lat').val(),
				lng = $('#ca_lng').val();

			if (($.isNumeric(lat) && regex_coords.test(lat)) && ($.isNumeric(lng) && regex_coords.test(lng))) map.setCenter(new google.maps.LatLng(lat, lng));
		});

		$('#map_centration').on('click', function (e) {
			e.preventDefault();

			var lat = $('#ca_lat').val(),
				lng = $('#ca_lng').val();

			if (($.isNumeric(lat) && regex_coords.test(lat)) && ($.isNumeric(lng) && regex_coords.test(lng))) map.setCenter(new google.maps.LatLng(lat, lng));
		});

		$('#ca_lat, #ca_lng').on('blur', function () {
			var lat = $('#ca_lat').val(),
				lng = $('#ca_lng').val();

			if (($.isNumeric(lat) && regex_coords.test(lat)) && ($.isNumeric(lng) && regex_coords.test(lng))) {
				var marker_location = new google.maps.LatLng(lat, lng);

				if (marker === null) {
					marker = new google.maps.Marker({
						map: map,
						draggable:true,
						animation: google.maps.Animation.DROP,
						position: marker_location
					});

					google.maps.event.addListener(marker, 'dragend', function() {
						$('#ca_lat').val(marker.getPosition().lat());
						$('#ca_lng').val(marker.getPosition().lng());
					});
				}
				else
				{
					marker.setPosition(marker_location);
				}

				var bounds = new google.maps.LatLngBounds();
				bounds.extend(marker_location);
				map.fitBounds(bounds);
			}
		});

		<?php endif; ?>
		
	});

	function save_component_article(callback) {
		global_helper.loader($('.admin_component'));
		
		$('.component_article').ckeditor({action: 'update'});
		$('#component_article_form').ajaxSubmit({
			data: request,
			success:function (response) {
				global_helper.loader($('.admin_component'));
				if ($.type(callback) === 'function') callback();
			},
			dataType: 'json'
		});
	}
</script>
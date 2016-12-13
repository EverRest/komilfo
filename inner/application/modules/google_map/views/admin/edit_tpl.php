<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');

	$this->template_lib->set_js('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places', FALSE);
	$this->template_lib->set_js('admin/jquery.form.js');
?>
<div class="admin_component">
	<div class="component_loader"><span></span></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="map"></div>
		</div>
		<div class="fm component_edit_links">
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
		<form id="component_google_map_form" action="<?=$this->uri->full_url('admin/google_map/update_map');?>" method="post">
			<input type="hidden" name="component_id" value="<?=$component_id;?>">
			<input type="hidden" name="menu_id" value="<?=$menu_id;?>">
			<?php foreach ($languages as $key => $val): ?>
			<div class="google_map_tab google_map_tab_<?=$key;?>"<?=(($key != LANG) ? ' style="display: none"' : ''); ?>>
				<div class="evry_title">
					<label for="ca_title_<?=$key;?>" class="block_label">Назва мапи:</label>
					<input type="text" id="ca_title_<?=$key;?>" name="title[<?=$key;?>]" value="<?=$map['title_' . $key];?>">
				</div>
			</div>
			<?php endforeach; ?>
			<div class="evry_title padding">
				<div class="no_float bold">Позначка на мапі</div>
			</div>
			<div class="evry_title">
				<label for="map_marker_lat" class="block_label">Широта:</label>
				<input type="text" id="map_marker_lat" name="marker_lat" value="<?=$map['marker_lat'];?>" class="short">
			</div>
			<div class="evry_title">
				<label for="map_marker_lng" class="block_label">Довгота:</label>
				<input type="text" id="map_marker_lng" name="marker_lng" value="<?=$map['marker_lng'];?>" class="short">
			</div>
			<div class="evry_title">
				<label for="map_zoom" class="block_label">Знайти місце на карті:</label>
				<div id="search_panel"><input id="google_map_search" type="text" placeholder=""></div>
			</div>
			<div class="evry_title padding">
				<div class="fm admin_map_edit" id="google_map_edit_block"></div>
			</div>
			<div class="evry_title padding">
				<div class="no_float bold">Позиція центру мапи</div>
			</div>
			<div class="evry_title">
				<label for="map_center_lat" class="block_label">Широта:</label>
				<input type="text" id="map_center_lat" name="center_lat" value="<?=$map['center_lat'];?>" class="short">
			</div>
			<div class="evry_title">
				<label for="map_center_lng" class="block_label">Довгота:</label>
				<input type="text" id="map_center_lng" name="center_lng" value="<?=$map['center_lng'];?>" class="short">
			</div>
			<div class="evry_title padding">
				<div class="no_float"><a href="#" id="map_centration">Центрувати автоматично</a></div>
			</div>
			<div class="evry_title padding">
				<div class="no_float bold">Збільшення мапи</div>
			</div>
			<div class="evry_title">
				<label for="map_zoom" class="block_label">Зум:</label>
				<input type="text" id="map_zoom" name="zoom" value="<?=$map['zoom'];?>" class="short-er">
			</div>
            <?php foreach ($languages as $key => $val): ?>
			<div class="google_map_tab google_map_tab_<?=$key;?>"<?=(($key != LANG) ? ' style="display: none"' : ''); ?>>
				<div class="evry_title">
					<label for="map_marker_description_<?=$key;?>" class="block_label">Опис позначки:</label>
					<textarea id="map_marker_description_<?=$key;?>" name="description[<?=$key;?>]"><?=$map['description_' . $key];?></textarea>
				</div>
			</div>
			<?php endforeach; ?>
			<div class="fm for_sucsess">
				<div class="sucsess" style="display: none">Збережено</div>
				<div class="loader" style="display: none"></div>
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

	function save_component_google_map(callback) {
		$('#component_google_map_form').ajaxSubmit({
			beforeSubmit:function () {
				global_helper.loader($('.component_loader'), '');
			},
			success:function (response) {
				global_helper.loader($('.component_loader'), 'Зміни збережено');

				if ($.type(callback) == 'function') callback();
			},
			dataType: 'json'
		});
	}

	function cancel_editing() {
		window.location.href = '<?=$this->init_model->get_link($menu_id, '{URL}');?>';
	}

	$(function () {

		var marker = null,
			regex_coords = /\-?[0-9]+[\.]{0,1}[0-9]*/,
			mapOptions = {
			center: new google.maps.LatLng(<?=($map['center_lat'] != '' ? $map['center_lat'] : '49.250855732520556');?>, <?=($map['center_lng'] != '' ? $map['center_lng'] : '32.94451661742571');?>),
			zoom: <?=($map['zoom'] > 0 ? $map['zoom']: 5);?>,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(document.getElementById("google_map_edit_block"), mapOptions);
		var searchBox = new google.maps.places.SearchBox(document.getElementById('google_map_search'));

		<?php if ($map['marker_lat'] != '' AND $map['marker_lng'] != ''): ?>
		marker = new google.maps.Marker({
			map: map,
			draggable:true,
			animation: google.maps.Animation.DROP,
			position: new google.maps.LatLng(<?=$map['marker_lat'];?>, <?=$map['marker_lng'];?>)
		});

		google.maps.event.addListener(marker, 'dragend', function() {
			$('#map_marker_lat').val(marker.getPosition().lat());
			$('#map_marker_lng').val(marker.getPosition().lng());
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
					$('#map_marker_lat').val(marker.getPosition().lat());
					$('#map_marker_lng').val(marker.getPosition().lng());
				});

				$('#map_center_lat, #map_marker_lat').val(places[0].geometry.location.lat());
				$('#map_center_lng, #map_marker_lng').val(places[0].geometry.location.lng());
				$('#map_zoom').val(map.getZoom());
			}
		});

		/**
		 * Change map zoom
		 */
		google.maps.event.addListener(map, 'zoom_changed', function() {
			$('#map_zoom').val(map.getZoom());
		});

		/**
		 * Change map center
		 */
		google.maps.event.addListener(map, 'center_changed', function() {
			var map_center = map.getCenter();

			$('#map_center_lat').val(map_center.lat());
			$('#map_center_lng').val(map_center.lng());
		});

		$('#map_zoom').on('blur', function () {
			var zoom = $(this).val();
			if ($.isNumeric(zoom)) map.setZoom(parseInt(zoom));
		});

		$('#map_center_lat, #map_center_lng').on('blur', function () {
			var lat = $('#map_center_lat').val(),
				lng = $('#map_center_lng').val();

			if (($.isNumeric(lat) && regex_coords.test(lat)) && ($.isNumeric(lng) && regex_coords.test(lng))) map.setCenter(new google.maps.LatLng(lat, lng));
		});

		$('#map_centration').on('click', function (e) {
			e.preventDefault();

			var lat = $('#map_marker_lat').val(),
				lng = $('#map_marker_lng').val();

			$('#map_center_lat').val(lat);
			$('#map_center_lng').val(lng);

			if (($.isNumeric(lat) && regex_coords.test(lat)) && ($.isNumeric(lng) && regex_coords.test(lng))) map.setCenter(new google.maps.LatLng(lat, lng));
		});

		$('#map_marker_lat, #map_marker_lng').on('blur', function () {
			var lat = $('#map_marker_lat').val(),
				lng = $('#map_marker_lng').val();

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
						$('#map_marker_lat').val(marker.getPosition().lat());
						$('#map_marker_lng').val(marker.getPosition().lng());
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

		$('.component_lang').find('a').on('click', function (e) {
			e.preventDefault();

			$(this).closest('div').find('.active').removeClass('active');
			$(this).addClass('active');

			$('.google_map_tab').hide();
			$('.google_map_tab_' + $(this).data('language')).show();
		});

		$('.component_edit_links .save, .for_sucsess .save_adm').on('click', function (e) {
			e.preventDefault();
			save_component_google_map(function () {
				cancel_editing();
			});
		});

		$('.component_edit_links .apply, .for_sucsess .apply_adm').on('click', function (e) {
			e.preventDefault();
			save_component_google_map('');
		});

		$('.component_edit_links .cancel, .for_sucsess .cansel_adm').on('click', function (e) {
			e.preventDefault();
			cancel_editing();
		});
	});
</script>
<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');

$this->template_lib->set_js('admin/ckeditor/ckeditor.js');
$this->template_lib->set_js('plugins/mustache.js/mustache.min.js');
//    ->set_css('js/admin/ui/jquery-ui-1.10.3.custom.min.css', true)
//    ->set_js('admin/ui/jquery-ui-1.10.3.custom.min.js')
$this->template_lib->set_js('http://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyB9f4vaqJWN_wp_rxw5lDTwveJbD4Qo-Lg', FALSE);
//    ->set_css('js/admin/fineuploader/fineuploader-3.5.0.css', true)
//    ->set_js('admin/fineuploader/jquery.fineuploader-3.5.0.min.js')
//    ->set_css('js/admin/jcrop/css/jquery.Jcrop.min.css', true)
//    ->set_js('admin/jcrop/js/jquery.Jcrop.min.js')
$this->template_lib->set_js('admin/jquery.form.js');
$sizes = array();?>

//$dir = get_dir_path('upload/google_map/' . get_dir_code($component_id) . '/' . $component_id, false);
?>
<div class="admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="google_map"></div>
		</div>
		<div class="fm component_edit_links">
        	<div class="fm only_text"><div>Редагування компоненти</div></div>
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
	<div class="fm admin_view_google_map">
		<form id="component_google_map_form" action="<?=$this->uri->full_url('/admin/google_map/update_google_map');?>" method="post">
			<input type="hidden" name="component_id" value="<?=$component_id;?>">
			<input type="hidden" name="menu_id" value="<?=$menu_id;?>">
			<div id="google_map_tab_ua" class="google_map_tab">
                <div class="evry_title">
                    <label for="ca_information_ua" class="block_label">Контактна інформація:</label>
                    <div class="no_float"><textarea class="component_google_map" id="ca_information_ua" name="information" style="height: 400px"><?=stripslashes($google_map['information_ua']);?></textarea></div>
                </div>
				<div class="evry_title">
					<label for="ca_schedule_ua" class="block_label">Графік роботи:</label>
					<div class="no_float"><textarea class="component_google_map" id="ca_schedule_ua" name="schedule" style="height: 400px"><?=stripslashes($google_map['schedule_ua']);?></textarea></div>
				</div>
                <div class="evry_title">
                    <label for="ca_sale_ua" class="block_label">Акція:</label>
                    <div class="no_float"><textarea class="component_google_map" id="ca_sale_ua" name="sale" style="height: 400px"><?=stripslashes($google_map['sale_ua']);?></textarea></div>
                </div>
			</div>
            <div class="col-md-8 col-md-offset-2">
                <div class="evry_title">
                    <label for="map_marker_lat" class="block_label">Широта:</label>
                    <input type="text" id="map_marker_lat" name="marker_lat" value="<?=$google_map['marker_lat'];?>" class="short">
                </div>
                <div class="evry_title">
                    <label for="map_marker_lng" class="block_label">Довгота:</label>
                    <input type="text" id="map_marker_lng" name="marker_lng" value="<?=$google_map['marker_lng'];?>" class="short">
                </div>
                <div class="evry_title">
                    <label for="map_zoom" class="block_label">Знайти місце на карті:</label>
                    <div id="search_panel"><input id="google_map_search" type="text" placeholder=""></div>
                </div>
                <div class="google_map_edit_block">
                </div>
            </div>
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

        var menu_id = <?=$menu_id;?>,
            component_id = <?=$component_id;?>,
            thumb_w = 500,
            thumb_h = 300,
//            thumb_w = <?//=$thumb[0];?>//,
//            thumb_h = <?//=$thumb[1];?>//,
//            block_w = <?//=$thumb[0];?>//,
            $component = $('.admin_component');
//        global_helper.photo_grid($component.find('.images_list'), block_w);
//        $component.find('#photo_uploader')
//            .fineUploader({
//                request: {
//                    endpoint: full_url('admin/google_map/upload_photo'),
//                    inputName: 'image',
//                    params: {
//                        menu_id: menu_id,
//                        component_id: component_id
//                    }
//                },
//                multiple: false,
//                text: {
//                    uploadButton: 'Виберіть або перетягніть файл зображення',
//                    dragZone: '',
//                    dropProcessing: ''
//                },
//                validation: {
//                    allowedExtensions: ['jpeg', 'jpg', 'png', 'gif'],
//                    sizeLimit: <?//=((int)ini_get('upload_max_filesize') * 1048576);?>
//                },
//                messages: {
//                    typeError: "Дозволено завантажувати: {extensions}.",
//                    sizeError: "Розмір файлу не повинен перевищувати {sizeLimit}.",
//                    tooManyproductsError: "Дозволено завантажувати файлів: {productLimit}."
//                }
//            })
//            .on('complete', function (event, id, fileName, response) {
//                if (response.hasOwnProperty('success') && response.success) {
//                    $('.qq-upload-success').remove();
//                    $component.find('#photo_uploader_box').addClass('adm_hidden');
//                    $component.find('#photo_box').removeClass('adm_hidden').find('ul').eq(0).append(
//                        Mustache.render(
//                            $component.find('#photo_template').html(),
//                            {
//                                image_id: response.image_id,
//                                file_name: response.photo,
//                                width: response.width,
//                                height: response.height
//                            }
//                        )
//                    );
//                    global_helper.photo_grid($component.find('.images_list'), block_w);
//                }
//            });
//        $component.find('#photo_box')
//            .on('click', '.fpc_edit', function (e) {
//                e.preventDefault();
//                var $link = $(this);
//                global_helper.photo_crop(
//                    $link,
//                    $component.find('#crop_template'),
//                    thumb_w,
//                    thumb_h,
//                    function (api, width) {
//                        global_helper.loader($component);
//                        $.post(
//                            full_url('admin/google_map/crop_photo'),
//                            {
//                                menu_id: menu_id,
//                                image_id: $link.closest('li').attr('data-image-id'),
//                                component_id: component_id,
//                                width: width,
//                                coords: api.tellScaled()
//                            },
//                            function (response) {
//                                if (response.success) {
//                                    $link.closest('li').find('.pc_photo').find('img').attr('src', response.src);
//                                    global_helper.loader($component);
//                                }
//                            },
//                            'json'
//                        );
//                    }
//                );
//            })
//            .on('click', '.fpc_watermark', function (e) {
//                e.preventDefault();
//                var $link = $(this);
//                global_helper.photo_watermark(
//                    $link,
//                    $component.find('#watermark_template'),
//                    function (position) {
//                        global_helper.loader($component);
//                        $.post(
//                            full_url('admin/google_map/watermark_photo'),
//                            {
//                                menu_id: menu_id,
//                                image_id: $link.closest('li').attr('data-image-id'),
//                                component_id: component_id,
//                                position: position
//                            },
//                            function (response) {
//                                if (response.success) {
//                                    $link.data('position', position);
//                                    global_helper.loader($component);
//                                }
//                            },
//                            'json'
//                        );
//                    }
//                );
//            })
//            .on('click', '.fpc_delete', function (e) {
//                e.preventDefault();
//                var $link = $(this);
//                global_helper.confirmation(
//                    'Видалити зображення?',
//                    function () {
//                        global_helper.loader($component);
//                        $.post(
//                            full_url('admin/google_map/remove_photo'),
//                            {
//                                menu_id: menu_id,
//                                image_id: $link.closest('li').attr('data-image-id'),
//                                component_id: component_id
//                            },
//                            function (response) {
//                                if (response.hasOwnProperty('success') && response.success) {
//                                    $link.closest('li').remove();
//                                    $component.find('#photo_uploader_box').removeClass('adm_hidden');
//                                    $component.find('#photo_box').addClass('adm_hidden');
//                                    global_helper.loader($component);
//                                }
//                            },
//                            'json'
//                        );
//                    }
//                );
//            });
//        $component.find('.images_list').find('ul').eq(0).sortable({
//            scroll: true,
//            handle: 'img',
//            forcePlaceholderSize: true,
//            placeholder: 'ui-state-highlight',
//            start: function(e, ui ){
//                ui.placeholder.css({
//                    float: 'left',
//                    width: $(ui.item).width() - 2,
//                    height: $(ui.item).height() - 2
//                }).show();
//            },
//            update: function () {
//                global_helper.loader($component);
//                var photos = [];
//                $component.find('.images_list').find('ul').eq(0).children().map(function () {
//                    photos.push($(this).data('image-id'));
//                });
//                $.post(
//                    full_url('admin/google_map/position_photos'),
//                    {
//                        menu_id: menu_id,
//                        component_id: component_id,
//                        photos: photos
//                    },
//                    function (response) {
//                        if (response.hasOwnProperty('success') && response.success) {
//                            global_helper.loader($component);
//                        }
//                    },
//                    'json'
//                );
//            }
//        });

        var marker = null,
            regex_coords = /\-?[0-9]+[\.]{0,1}[0-9]*/,
            mapOptions = {
                center: new google.maps.LatLng(<?=($google_map['center_lat'] != '' ? $google_map['center_lat'] : '49.250855732520556');?>, <?=($google_map['center_lng'] != '' ? $google_map['center_lng'] : '32.94451661742571');?>),
                zoom: <?=($google_map['zoom'] > 0 ? $google_map['zoom']: 5);?>,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

        var map = new google.maps.Map(document.getElementById("google_map_edit_block"), mapOptions);
        var searchBox = new google.maps.places.SearchBox(document.getElementById('google_map_search'));

        <?php if ($google_map['marker_lat'] != '' AND $google_map['marker_lng'] != ''): ?>
        marker = new google.maps.Marker({
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            position: new google.maps.LatLng(<?=$google_map['marker_lat'];?>, <?=$google_map['marker_lng'];?>)
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
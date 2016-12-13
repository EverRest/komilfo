$(function () {
	var $component = $('#c_pharmacy_map'),
		map = new google.maps.Map(
			document.getElementById('pharmacy_map'),
			{
				center: new google.maps.LatLng(49.83968300000001, 24.029717000000005),
				zoom: 14,
				mapTypeId: google.maps.MapTypeId.ROADMAP//,
				//scrollwheel: false
			}
		),
		markers = [],
		markerCluster,
		c_styles = [
			{
				url: base_url('images/map_little.png'),
				height: 34,
				width: 34,
				textColor: '#ffffff',
				textSize: 16
			},
			{
				url: base_url('images/map_midi.png'),
				height: 47,
				width: 47,
				textColor: '#ffffff',
				textSize: 16
			},
			{
				url: base_url('images/map_big.png'),
				height: 60,
				width: 60,
				textColor: '#ffffff',
				textSize: 16
			}
		],
		infowindow = new InfoBox({
			content: '',
			maxWidth: 352,
			boxStyle: {
				background: '#fff',
				border: '1px solid #838385',
				width: '352px'
			},
			pixelOffset: new google.maps.Size(45, -265),
			closeBoxURL: base_url('images/close.png')
		}),
		city_id = null,
		c_markers = [],

		my_position = null;

	google.maps.event.addListener(infowindow, 'domready', function(){
		var $iw = $('.infoBox');

		if ($iw.length) {
			var zindex = 1000,
				$closest = $iw.closest('.gm-style');

			$closest.css('z-index', zindex);
			$closest.find('div').eq(0).css('z-index', zindex);

			$iw.find('img').eq(0).css({
				'position': 'absolute',
				'top': '2px',
				'right': '2px',
				'margin': 0,
				'z-index': zindex + 1
			});
		}
	});

	google.maps.event.addListener(infowindow, 'closeclick', function(){
		var $iw = $('.infoBox');

		if ($iw.length) {
			var $closest = $iw.closest('.gm-style');

			$closest.css('z-index', 0);
			$closest.find('div').eq(0).css('z-index', 0);
		}
	});

	if ($component.find('.dropdown').data('value')) {
		city_id = parseInt($component.find('.dropdown').data('value'));
	}

	if ($component.find('.markers_selector').find('.active').length > 0) {
		c_markers.push(parseInt($component.find('.markers_selector').find('.active').data('marker-id')));
	}

	$.post(
		full_url('pharmacy/get_markers'),
		function (response) {
			if (response.success && response.hasOwnProperty('markers')) {

				$.each(response.markers, function (i, v) {
					v['position'] = new google.maps.LatLng(v.map_lat, v.map_lng);
					markers.push(v);
				});

				set_map();
			}
		},
		'json'
	);

	$component.find('.dropdown')
		.dropdown_list({
			arrow: '<b></b>',
			input: true,
			hide_on_select: false
		})
		.on('change', function (e, data) {
			if (data.value !== '') {
				city_id = parseInt(data.value);
				my_position = null;
			} else {
				city_id = null;
			}

			$component.find('.my_point').removeClass('active');

			set_map();

		});

	if (!('geolocation' in navigator)) {
		$component.find('.my_point').addClass('hidden');
	} else {
		$component.find('.my_point').on('click', function (e) {
			e.preventDefault();

			var $link = $(this);
			$link.toggleClass('active');

			if ($link.hasClass('active')) {
				$component.find('.dropdown').trigger('reset');
				city_id = null;

				if ($link.data('position')) {
					my_position = $link.data('position');
					set_map();
				} else {
					navigator.geolocation.watchPosition(function (position) {
						my_position = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

						set_map();
						$link.data('position', my_position);
					});
				}
			} else {
				my_position = null;
				set_map();
			}
		});
	}

	$component.find('.all_map').on('click', function (e) {
		e.preventDefault();

		city_id = null;
		c_markers = [];
		my_position = null;

		$component.find('.my_point').removeClass('active');
		$component.find('.dropdown').trigger('reset');

		set_map();
	});

	$component.find('.markers_selector').on('click', 'a', function (e) {
		e.preventDefault();

		$(this).toggleClass('active');

		c_markers = [];

		$component.find('.markers_selector').find('.active').map(function () {
			c_markers.push($(this).data('marker-id'));
		});

		set_map();
	});

	function set_map() {
		var _markers = markers, _m = [], zoom = 0, bounds = new google.maps.LatLngBounds();

		if ($.type(markerCluster) === 'object') {
			markerCluster.clearMarkers();
		}

		if (my_position !== null) {
			var __m = [], mk;

			_markers = [];

			$.each(markers, function (i, v) {
				__m.push([i, google.maps.geometry.spherical.computeDistanceBetween(my_position, v.position) / 1000]);
			});

			__m.sort(function(a, b) {return a[1] - b[1]});

			for (var i = 0; i < __m.length; i++) {
				if (__m[i][1] <= 1 || (i < 3 && __m[i][1] < 10)) {
					mk = markers[__m[i][0]];

					if (i === 0) {
						mk['icon'] = base_url('images/map_cross_active.png');
					}

					_markers.push(mk);
				}
			}
		}

		$.each(_markers, function (i, v) {
			var vs_c = false, vs_m = false;

			if (city_id === null && c_markers.length === 0) {
				vs_c = true;
				vs_m = true;
			} else {
				if (c_markers.length > 0) {
					$.each(v.markers, function (_i, _v) {
						if ($.inArray(_v, c_markers) > -1) {
							vs_m = true;
						}
					});
				} else {
					vs_m = true;
				}

				if (city_id !== null) {
					if (city_id === v.city_id) {
						vs_c = true;
					}
				} else {
					vs_c = true;
				}
			}

			if (vs_c && vs_m) {
				var marker = new google.maps.Marker({
						map: map,
						position: v.position,
						icon: v.icon ? v.icon : base_url('images/map_cross.png'),
						pharmacy_id: parseInt(v.pharmacy_id)
					});

				google.maps.event.addListener(marker, 'click', function() {
					infowindow.setContent('<div class="map_loader">...</div>');
					infowindow.open(map, marker);

					$.post(
						full_url('pharmacy/get_pharmacy'),
						{
							pharmacy_id: this.pharmacy_id
						},
						function (response) {
							if (response.success && response.hasOwnProperty('item')) {
								infowindow.setContent(response.item);
								infowindow.open(map, marker);
							}
						},
						'json'
					);
				});

				_m.push(marker);
				zoom = v.map_zoom;
				bounds.extend(v.position);
			}
		});

		if (_m.length > 0) {
			map.fitBounds(bounds);

			if ($.type(markerCluster) !== 'object') {
				markerCluster = new MarkerClusterer(map, _m, {gridSize: 50, maxZoom: 10, styles: c_styles});
			} else {
				markerCluster.addMarkers(_m);
			}

			if (_m.length === 1) {
				map.setZoom(zoom);
			}
		}
	}
});
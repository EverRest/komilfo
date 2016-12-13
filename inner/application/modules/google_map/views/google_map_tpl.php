<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');
	$this->template_lib->set_h1();
	// $this->template_lib->set_js('http://maps.googleapis.com/maps/api/js?v=3&sensor=true', FALSE);
?>
<section class="google_map">
	<div class="center">
		<?php if (isset($map) AND count($map) > 0 AND $map['center_lat'] != 0 AND $map['center_lng'] != 0): ?>
			<?php if ($map['title'] != ''): ?><div class="sect-title"><?=$map['title'];?></div><?php endif; ?>
			<div id="google_map_<?=$component_id;?>" class="map_area"></div>
			<script type="text/javascript">
				$(function () {
					var mapOptions = {
						center: new google.maps.LatLng(<?=$map['center_lat'];?>, <?=$map['center_lng'];?>),
						zoom: <?=$map['zoom'];?>,
						styles : [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#7d7d7d"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#EBEBEB"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#DBDBDB"},{"visibility":"on"}]}],
						mapTypeId: google.maps.MapTypeId.ROADMAP,
						scrollwheel: false
					};
					var map = new google.maps.Map(document.getElementById("google_map_<?=$component_id;?>"), mapOptions);
					<?php if ($map['marker_lat'] != '' AND $map['marker_lng'] != ''): ?>
					var marker = new google.maps.Marker({
						map: map,
						position: new google.maps.LatLng(<?=$map['marker_lat'];?>, <?=$map['marker_lng'];?>)
					});
					var infowindow = new google.maps.InfoWindow({
						content: '<?=$map['description'];?>'
					});
					google.maps.event.addListener(marker, 'click', function() {
						infowindow.open(map,marker);
					});
					<?php endif; ?>
				});
			</script>
		<?php else: ?>
			<div class="sect-title"><?=lang('map');?></div>
		<?php endif; ?>
	</div>
</section>
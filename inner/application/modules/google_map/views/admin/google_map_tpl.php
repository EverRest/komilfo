<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');

	$this->template_lib->set_js('http://maps.googleapis.com/maps/api/js?v=3&sensor=true', FALSE);
?>
<div class="admin_component" id="admin_component_<?=$component_id;?>" data-menu-id="<?=$menu_id;?>" data-component-id="<?=$component_id;?>" data-module="google_map" data-css-class="map" data-visibility-url="<?=$this->uri->full_url('admin/components/toggle_visibility');?>" data-delete-url="<?=$this->uri->full_url('admin/google_map/delete_component');?>">
	<div class="component_loader"><span></span></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="<?=(($hidden == 0) ? 'map' : 'hidden');?>"></div>
		</div>
		<div class="fm component_edit_links">
			<div class="fm only_text"><div>Google мапа</div></div>
			<a href="<?=$this->uri->full_url('/admin/google_map/edit?menu_id=' . $menu_id . '&component_id=' . $component_id);?>" class="fm edit"><b></b>Редагувати</a>
			<a href="#" class="fm show_hide"><b></b><?=(($hidden == 0) ? 'Приховати' : 'Показати');?></a>
		</div>
		<div class="fmr component_del">
			<a href="#" class="fm delete_component"><b></b></a>
		</div>
		<div class="fmr component_pos">
			<a href="#" class="fm up_component"><b></b></a>
			<a href="#" class="fm down_component"><b></b></a>
		</div>
	</div>
	<article class="fm google_map">
	<?php if (isset($map) AND count($map) > 0 AND $map['center_lat'] > 0 AND $map['center_lng'] > 0): ?>
		<header><?php if (!$h1): ?><h1><?=$map['title'];?></h1><?php else: ?><h2><?=$map['title'];?></h2><?php endif; ?></header>
		<div id="google_map_<?=$component_id;?>" class="admin_map"></div>
		<script type="text/javascript">
			$(function () {
				var mapOptions = {
					center: new google.maps.LatLng(<?=$map['center_lat'];?>, <?=$map['center_lng'];?>),
					zoom: <?=$map['zoom'];?>,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				var map = new google.maps.Map(document.getElementById("google_map_<?=$component_id;?>"), mapOptions);

				<?php if ($map['marker_lat'] != '' AND $map['marker_lng'] != ''): ?>
				var marker = new google.maps.Marker({
					map: map,
					position: new google.maps.LatLng(<?=$map['marker_lat'];?>, <?=$map['marker_lng'];?>)
				});
				var infowindow = new google.maps.InfoWindow({
					content: '<?=str_replace(array("\n", "\r", "\t"), '', nl2br($map['description'], FALSE));?>'
				});
				google.maps.event.addListener(marker, 'click', function() {
					infowindow.open(map,marker);
				});
				<?php endif; ?>
			});
		</script>
	<?php else: ?>
		<header><h2>Нова мапа</h2></header>
	<?php endif; ?>
	</article>
</div>
<script type="text/javascript">
	$(function () {
		$('#admin_component_<?=$component_id;?>').component({
			onDelete: function () {
				$('a.google').show();
			}
		});
	});
</script>
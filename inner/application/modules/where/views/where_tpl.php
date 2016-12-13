<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');
	// $this->template_lib->set_js('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places', FALSE);
?>
<?php if (isset($menu) AND count($menu) > 0): ?>

    <section class="sect-shops fm">
        <div class="sect-title fm"><?=lang('shop')?></div>
        <div class="shops-wrapper fm">
            <div class="center">
                <div class="shops-box fm">
                    <div class="shops-box_title fm"><?=lang('choose_country')?></div>
                    <div class="shops-box_dropdown fm">
                        <div class="dropdown" id="w_country">
                            <div class="overflow">
                                <span><?=lang('country')?></span>
                                <div class="drop-arrow"></div>
                            </div>
                            <ul>
                                <?php foreach ($menu[0] as $row): ?>
                                    <li><a href="#" data-value="<?=$row['id'];?>"><?=$row['name'];?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="shops-box_dropdown fm">
                        <div class="dropdown" id="w_city">
                            <div class="overflow">
                                <span><?=lang('city')?></span>
                                <div class="drop-arrow"></div>
                            </div>
                            <ul>
                                <?php foreach ($menu[0] as $row): ?>
                                    <?php if (isset($menu[$row['id']])): ?>
                                        <?php foreach ($menu[$row['id']] as $_row): ?>
                                            <li class="country_<?=$row['id'];?> hidden" data-city="<?=$_row['id'];?>"><a href="#" data-value="<?=$_row['id'];?>" data-lat="<?=$_row['lat'];?>" data-lng="<?=$_row['lng'];?>" data-zoom="<?=$_row['zoom'];?>" ><?=$_row['name'];?></a></li>
                                        <?php endforeach; ?>
                                    <?php endif ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="shop-contacts fm">
                        <?php foreach ($menu[0] as $row): ?>
                            <?php if (isset($menu[$row['id']])): ?>
                                <?php foreach ($menu[$row['id']] as $_row): ?>
                                    <div class="article_dec" id="city_<?=$_row['id'];?>" style="display:none;"> 
                                        <div class="shop-social">
                                            <a href="<?=$_row['facebook']?>" class="fb" target="_blank"><i class="icon-facebook"></i></a>
                                        </div>
                                        <div class="shop-contacts_wrapper fm" >
                                            <div class="shop-name fm"><?=$_row['title_shop']?></div>
                                            <?//=strval(stripslashes($_row['text']));?>
                                            <div class="shop-address fm">
                                                <span><?=$_row['address']?></span>
                                                <span><?=$_row['address_2']?></span>
                                                <span><?=$_row['phone']?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="shop-map fm">
                    <div id="w_map" class="adm_map" style="width: 100%; height: 100%;"></div>
                </div>
                
            </div>
        </div>
    </section>



<script type="text/javascript">
	function set_map() {
        var markers = [], 
        	i = 0, 
        	bounds, 
        	id,
        	map,
        	lat = 0,
        	lng = 0,
        	zoom = 12,
        	text = 0;
		var mapOptions = {
				center: new google.maps.LatLng(<?=($markers[0]['lat'] != ''? $markers[0]['lat']: '49.250855732520556');?>, <?=($markers[0]['lng'] != '' ? $markers[0]['lng'] : '32.94451661742571');?>),
				zoom: 12,
                scrollwheel: false,
                styles : [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#7d7d7d"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#EBEBEB"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#DBDBDB"},{"visibility":"on"}]}],
				mapTypeId: google.maps.MapTypeId.ROADMAP
			},
			map = new google.maps.Map(document.getElementById("w_map"), mapOptions),
			bounds = new google.maps.LatLngBounds(),
			
			zoom = 12;
		
			$('#w_city').find('li').each(function () {
				if (!$(this).hasClass('map_hidden')) {
					var $a = $(this).find('a');
					if ($a.data('lat') != '' && $a.data('lng') != '') {
						var position = new google.maps.LatLng($a.data('lat'), $a.data('lng'));
							// infowindow = new google.maps.InfoWindow({ content: $a.data('sign') });
						markers[i] = new google.maps.Marker({ map: map, position: position });
						google.maps.event.addListener(markers[i], 'click', function() { infowindow.open(map, markers[i]); });
						bounds.extend(position);

						if ($a.data('zoom') != '' && $a.data('zoom') > 0) zoom = $a.data('zoom');
						i++;
					}
				}
			});

        map.setCenter(bounds.getCenter());
        if (markers.length > 1) {
            map.fitBounds(bounds);
        } else {
// console.log(zoom); 

			map.setZoom(zoom);
		}
		// map.setZoom(zoom);
	}
	
	$(function () {
		$('#w_country').on('click', '.overflow', function(e){
            $ul = $(this).closest('#w_country').find('ul');
            
            $ul.toggleClass('show');
            if($ul.is('.show')){
                $ul.slideDown();
            }else{
                $ul.slideUp();
            }
            var dval;
            
            $('#w_city').find('li').addClass('hidden');
            
            $ul.find('li').on('click', 'a', function(event) {
                event.preventDefault();
                $('.shop-contacts').find('.article_dec').each(function(){
                    $(this).hide();
                });
                $this = $(this);
                // $('#w_city').show();
                
                $('#w_city').find('li.country_'+$this.attr("data-value")).each(function(){
                    $(this).addClass('map_hidden');
                    if($('[data-city="'+$(this).attr('data-city')+'"]').length > 1){
                        $('[data-city="'+$(this).attr('data-city')+'"]:eq(0)').addClass('hidden');
                    }
                    if($(this).hasClass('country_'+$this.attr("data-value"))){
                        $(this).removeClass('hidden').removeClass('map_hidden');
                    }
                });
                
                dval = '';
                
                $ul.prev().find('span').html($(this).text()+"<b></b>");
                $('#w_city').find('span').html("<?=lang('choose_city')?> <b></b>");
                $ul.removeClass('show');
                $ul.slideUp();
                set_map();
            });
        });
        $('#w_city').on('click', '.overflow', function(e){
            $ul = $(this).closest('#w_city').find('ul');
            
            $ul.toggleClass('show');
            if($ul.is('.show')){
                $ul.slideDown();
            }else{
                $ul.slideUp();
            }

            $('#w_city').find('li').addClass('map_hidden');
            $ul.find('li').on('click', 'a', function(event) {
                event.preventDefault();
                val = $(this).attr('data-value');
                $('#w_city').find('li').each(function(){
                    if($(this).find('a').attr('data-value') == val){
                        $(this).removeClass('map_hidden');
                    }
                });
                
                $('.shop-contacts').find('.article_dec').hide();
                id = $(this).attr('data-value');
                
                $('.shop-contacts').find('.article_dec').each(function(){
                    console.log($(this).is('#city_'+id));
                    if($(this).is('#city_'+id)){
                        $(this).show();
                    }
                })
                
                set_map();
                $ul.prev().find('span').html($(this).text()+"<b></b>");
                $ul.removeClass('show');
                $ul.slideUp();
            });
        });
		set_map();
	});
</script>
<?php endif; ?>
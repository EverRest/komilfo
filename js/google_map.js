
		$(function () {
			var mapOptions = {
				center: new google.maps.LatLng(49.858367,24.019715),
				zoom: 16,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
                                scrollwheel: false
                                
			};
			var map = new google.maps.Map(document.getElementById("data_map"), mapOptions);
						var marker = new google.maps.Marker({
				map: map,
				position: new google.maps.LatLng(49.858367,24.019715)
			});
			var content='<div style="width:150px; height:60px; text-align:center;">http://tiensmed.com.ua/<br>Лікування остеохондрозу</div>';
            var infowindow = new google.maps.InfoWindow({
    		content:content
   			});
			google.maps.event.addListener(marker, 'click', function() {
				infowindow.open(map,marker);
			});
                       		
    
    });
                                        
            


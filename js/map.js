/**
 * Created by PROGRAMERIUA on 16.12.2016.
 */
(function () {
     function init () {
        // Basic options for a simple Google Map
        // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions

        //Remove standart google labels
        var mapOptions = {
            // How zoomed in you want the map to start at (always required)
            zoom: 11,

            // The latitude and longitude to center the map (always required)
            center: new google.maps.LatLng(40.6700, -73.9400), // New York
            // This is where you would paste any style found on Snazzy Maps.
            styles: [{
                "featureType": "all",
                "elementType": "all",
                "stylers": [{"hue": "#ff0000"}, {"saturation": -100}, {"lightness": -30}]
            }, {
                "featureType": "all",
                "elementType": "labels.text.fill",
                "stylers": [{"color": "#ffffff"}]
            }, {
                "featureType": "all",
                "elementType": "labels.text.stroke",
                "stylers": [{"color": "#353535"}]
            }, {
                "featureType": "all",
                "elementType": "labels.icon",
                "stylers": [{"visibility": "off"}]
            }, {
                "featureType": "administrative",
                "elementType": "geometry.fill",
                "stylers": [{"visibility": "on"}, {"color": "#ff0000"}]
            }, {
                "featureType": "landscape",
                "elementType": "geometry",
                "stylers": [{"color": "#656565"}]
            }, {
                "featureType": "landscape",
                "elementType": "geometry.fill",
                "stylers": [{"visibility": "on"}, {"color": "#666666"}, {"lightness": "-14"}]
            }, {
                "featureType": "landscape.man_made",
                "elementType": "geometry.fill",
                "stylers": [{"visibility": "off"}, {"color": "#ff0000"}]
            }, {
                "featureType": "poi",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#505050"}, {"visibility": "off"}]
            }, {
                "featureType": "poi",
                "elementType": "geometry.stroke",
                "stylers": [{"color": "#808080"}]
            }, {
                "featureType": "poi",
                "elementType": "labels.text.fill",
                "stylers": [{"visibility": "on"}, {"color": "#ffffff"}]
            }, {
                "featureType": "poi.attraction",
                "elementType": "geometry.fill",
                "stylers": [{"visibility": "off"}, {"color": "#ef0000"}]
            }, {
                "featureType": "road",
                "elementType": "geometry",
                "stylers": [{"color": "#454545"}, {"visibility": "on"}]
            }, {
                "featureType": "road",
                "elementType": "labels.text",
                "stylers": [{"visibility": "simplified"}]
            }, {
                "featureType": "road",
                "elementType": "labels.text.fill",
                "stylers": [{"visibility": "on"}, {"color": "#ffffff"}, {"weight": "0.50"}]
            }, {
                "featureType": "road",
                "elementType": "labels.text.stroke",
                "stylers": [{"visibility": "on"}, {"color": "#000000"}, {"weight": "1.00"}, {"gamma": "0"}]
            }, {
                "featureType": "transit",
                "elementType": "labels",
                "stylers": [{"hue": "#ff0000"}, {"saturation": 100}, {"lightness": -40}, {"invert_lightness": true}, {"gamma": 1.5}, {"visibility": "simplified"}]
            }, {
                "featureType": "transit.station",
                "elementType": "geometry",
                "stylers": [{"visibility": "off"}]
            }, {
                "featureType": "transit.station",
                "elementType": "geometry.fill",
                "stylers": [{"visibility": "off"}]
            }, {
                "featureType": "transit.station",
                "elementType": "geometry.stroke",
                "stylers": [{"visibility": "off"}]
            }, {
                "featureType": "transit.station",
                "elementType": "labels.text",
                "stylers": [{"visibility": "off"}]
            }, {
                "featureType": "transit.station",
                "elementType": "labels.icon",
                "stylers": [{"visibility": "off"}]
            }, {
                "featureType": "water",
                "elementType": "geometry.fill",
                "stylers": [{"visibility": "on"}, {"color": "#414344"}, {"weight": "1.00"}]
            }, {
                "featureType": "water",
                "elementType": "labels.text.fill",
                "stylers": [{"visibility": "on"}, {"color": "#ffffff"}, {"gamma": "1.00"}]
            }, {
                "featureType": "water",
                "elementType": "labels.text.stroke",
                "stylers": [{"visibility": "on"}, {"color": "#000000"}]
            }],
            zoomControl: false,
            mapTypeControl: false,
            scaleControl: false,
            streetViewControl: false,
            rotateControl: false

        };

        var mapElement = document.getElementById('map');
        // Create the Google Map using our element and options defined above
        var map = new google.maps.Map(mapElement, mapOptions);
        // Let's also add a marker while we're at it

        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(40.6700, -73.9400),
            map: map,
            title: 'Home!'
        });

    }

    // if ($("#map").length) {
    //     google.maps.event.addDomListener(window, 'load', init);
    // }
})();
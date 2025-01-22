function initMap() {
    var location = { lat: 23.684994, lng: 90.356331 };
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: location,
        map: map,
        mapTypeControl: false,
        zoomControl: false,
        scaleControl: false,
        streetViewControl: false,
        fullscreenControl: false,
        scrollwheel: false
    });
    var market = new google.maps.Marker({
        position: location,
        map: map,
    });
}
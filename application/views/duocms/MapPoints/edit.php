<h2 class="pull-left">Edytuj punkt zainteresowania</h2><a href="<?= site_url('duocms/points');?>" class="btn btn-primary pull-right">< Powrót</a>
<div style="clear:both"></div><hr>
<form method="POST">
<p>Nazwa punktu</p>
<div class="col-sm-12">
    <input type="text" name="name" class="form-control" value="<?=$point->name; ?>">
</div>
<p>Adres</p>
<div class="col-sm-10">
    <input type="text" name="address" class="form-control" value="<?=$point->address; ?>">
</div>
<div class="col-sm-2">
    <a class="btn btn-primary" id="addressButton">Znajdź</a>
</div>
<p>Współrzedne</p>
<div class="col-sm-6">
     <input type="text" name="lat" class="form-control" value="<?=$point->lat; ?>">
</div>
<div class="col-sm-6">
     <input type="text" name="lng" class="form-control" value="<?=$point->lng; ?>">
</div>
<?php /*<p>Obrazek</p>
<div class="col-sm-12">
    <input type="text" name="icon_marker" class="form-control" onclick="openKCFinderImage(this, 'images')" readonly="true" value="<?=$point->icon_marker; ?>">
</div> */ ?>
<p>Powiązana galeria</p>
<div class="col-sm-12">
    <select name="gallery_id" class="form-control">
        <option value="0">Brak</option>
        <?php foreach($galleries as $cat): ?>
        <option value="<?= $cat->id; ?>" <?= $cat->id == $point->gallery_id ? 'selected' : '';?> ><?= $cat->getTranslation(LANG)->name; ?></option>
        <?php      endforeach; ?>
    </select>
</div>

<div class="col-sm-12">
    <br>
    <button type="submit" class="btn btn-primary form-control">Zapisz</button>
    <hr>
</div>
</form>
<div class='col-sm-12'>
<div style="width: 100%; height: 500px" id='map-create'>
    
</div>
</div>
<script defer src="https://maps.googleapis.com/maps/api/js?key=<?= get_option('gmap_key'); ?>">
            </script>
<script>
    $(document).ready(function(){
    var map = null;
    var marker = null;
    var geocoder = null;
   
        
    var myLatlng = new google.maps.LatLng(<?=$point->lat; ?>, <?=$point->lng; ?>);
        
    var mapOptions = {
        center: myLatlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
        zoom: 16,
      zoomControl: true
    };
    map = new google.maps.Map(document.getElementById("map-create"), mapOptions);

    maker = new google.maps.Marker({
        position: myLatlng,
        map: map
    });
    google.maps.event.addListener(map, 'click', function(event) {
        var lat = event.latLng.lat();
        var lng = event.latLng.lng();
        $('[name="lat"]').val(lat);
        $('[name="lng"]').val(lng);
        if(marker != null){
        marker.setMap(null);
        }
        marker = new google.maps.Marker({
            position: event.latLng,
            map: map,
            title: 'Meeting point'
        });
});
    
    $('#addressButton').click(function(e){
        if(geocoder === null){
            geocoder = new google.maps.Geocoder();
        }
        var address = $('[name="address"]').val();
         geocoder.geocode({'address': address}, function (results, status) {
        if (status === 'OK') {
            map.setCenter(results[0].geometry.location);
            
        if(marker != null){
        marker.setMap(null);
        }
        $('[name="lat"]').val(results[0].geometry.location.lat());
        $('[name="lng"]').val(results[0].geometry.location.lng());
            marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location,
                title: $('[name="name"]').val()
            });

        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
    });
    
}); 
</script>
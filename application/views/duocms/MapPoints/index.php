<h2 class="pull-left"> Punkty do galerii</h2><a href="<?= site_url('duocms/points/create');?>" class="btn btn-primary pull-right">+ Dodaj</a>
<div style="clear:both"></div><hr>
<div style="width: 100%; height: 500px" id="map-list">
    
</div>

<div class="table-responsive">
    <?php if(!empty($points)): ?>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>id</th>
                <th>nazwa</th>
                <th>adres</th>
                <th>akcja</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($points as $p): ?>
            <tr data-lat="<?=$p->lat; ?>" data-lng="<?=$p->lng;?>" data-name="<?=$p->name;?>" data-icon="<?=$p->icon_marker;?>">
                <td><?= $p->id; ?></td>
                <td><?= $p->name; ?></td>
                <td><?= $p->address; ?></td>
                <td>
                     <?php printf(ADMIN_BUTTON_EDIT, site_url('duocms/points/edit/'.$p->id)); ?>
                     <?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/points/delete/'.$p->id)); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else : ?>
    Brak puntów.
    <?php endif; ?>
</div>
<script defer src="https://maps.googleapis.com/maps/api/js?key=<?= get_option('gmap_key'); ?>">
            </script>
<script>
 $(document).ready(function(){
    var map = null;
    var marker = null;
    var geocoder = null;
   var locations = [];
    $('tbody > tr').each(function(){
        var lat = $(this).attr('data-lat');
        var lng = $(this).attr('data-lng');
        var name = $(this).attr('data-name');
        var icon = $(this).attr('data-icon');
        locations.push([lat, lng, name, icon]);
    });    
    var myLatlng = new google.maps.LatLng(52.21520123615745, 21.01141223828131);
        
    var mapOptions = {
        center: myLatlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
        zoom: 7,
      zoomControl: true
    };
    map = new google.maps.Map(document.getElementById("map-list"), mapOptions);
        var i;
        var infowindow = new google.maps.InfoWindow();
      for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][0], locations[i][1]),
        map: map,
//        icon: locations[i][3]
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][2]);
          infowindow.open(map, marker);
        }
      })(marker, i));
  }
    });
</script>
    
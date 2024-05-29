<div class="container">

    <div class="row">
        <div class="col-sm-12 wow fadeInLeft">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h4 class="naglowek-podstrona"><?= (new CustomElementModel('10'))->getField('Przyklady_wdrozen'); ?></h4>
        </div>
    </div>
   
<!--    <div class="row">
        <div class="col-sm-12">
            <div id="map">
                
            </div>
        </div>
    </div>-->
    
    <div class="gallery_section">      
        <div class="row">
            <?php foreach($galleries as $index => $gal) : 
                $tgal = $gal->getTranslation(LANG); ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="gallery-row" id="gal<?= getAlias($gal->id, $tgal->name); ?>"
                     <?php if(!empty($gal->point)): ?>
                     lat="<?= $gal->point->lat; ?>"
                     lng="<?= $gal->point->lng; ?>"
                     
                     <?php endif; ?>
                     >
                    <h3 class="header_title"><?= $tgal->name;?></h3>
                    <?php $photos = $gal->findAllPhotos();
                    $gallery_widget['photos'] = $photos;
                    $this->load->view('Gallery/module_integrator', $gallery_widget);
                    ?>
                </div>
            <?php endforeach; ?>
        </div> 
    </div>
</div>
</div>
<script>
$(document).ready(function(){
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 16,
        center: new google.maps.LatLng(50.0230165, 21.0448265),
        mapTypeId: 'roadmap'
    });
    
    var bounds = new google.maps.LatLngBounds();
    $('.gallery-row').each(function(index, item){
        pos = new google.maps.LatLng($(item).attr('lat'), $(item).attr('lng'));
        marker = new google.maps.Marker({
            position: pos,
            map: map,
            moveToId: $(item).attr('id')
        });
        bounds.extend(pos);
        google.maps.event.addListener(marker, 'click', (function(marker) {
        return function() {
            console.log(marker);
        var pos = $('#'+marker.moveToId).offset().top;
         $("body, html").animate({
            scrollTop: pos - 100
        } /* speed */);
        }
      })(marker));
      
      
    });
    map.fitBounds(bounds);
    
    
     var hash = location.hash;
       if(hash.length > 0){
       
        var position = $(hash).offset().top;

        $("body, html").animate({
            scrollTop: position-100
        } /* speed */);
    }
});
</script>
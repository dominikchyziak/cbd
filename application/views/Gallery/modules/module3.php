<?php if(count($photos) > 2): ?>
<div class="row gallery_module_separator">
    <div class="col-xs-12 col-sm-12 col-md-4 equalizer">
        <a href="<?= $photos[0]->getUrl(); ?>" data-lightbox="gallery">
        <div class="gallery_module gallery_module_3 wow fadeInUp" style="background-image: url('<?= $photos[0]->getUrl($mini); ?>');"></div>
        </a>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 equalizer">
        <a href="<?= $photos[1]->getUrl(); ?>" data-lightbox="gallery">
        <div class="gallery_module gallery_module_3 wow fadeInUp" style="background-image: url('<?= $photos[1]->getUrl($mini); ?>');" data-wow-delay="0.2s"></div>
        </a>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 equalizer">
        <a href="<?= $photos[2]->getUrl(); ?>" data-lightbox="gallery">
        <div class="gallery_module gallery_module_3 wow fadeInUp" style="background-image: url('<?= $photos[2]->getUrl($mini); ?>');" data-wow-delay="0.4s"></div>
        </a>
    </div>
</div>
<?php endif;?>
<?php if(count($photos) > 1): ?>
<div class="row gallery_module_separator">
    <div class="col-xs-12 col-sm-12 col-md-6 equalizer">
        <a href="<?= $photos[0]->getUrl(); ?>" data-lightbox="gallery">
        <div class="gallery_module gallery_module_2 wow fadeInUp" style="background-image: url('<?= $photos[0]->getUrl($mini); ?>');"></div>
        </a>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 equalizer">
        <a href="<?= $photos[1]->getUrl(); ?>" data-lightbox="gallery">
        <div class="gallery_module gallery_module_2 wow fadeInUp" style="background-image: url('<?= $photos[1]->getUrl($mini); ?>');" data-wow-delay="0.2s"></div>
        </a>
    </div>
</div>
<?php endif;?>
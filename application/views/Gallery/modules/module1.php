<?php if(count($photos) > 0): ?>
<div class="row gallery_module_separator equalizer">
    <div class="col-sm-12">
        <a href="<?= $photos[0]->getUrl(); ?>" data-lightbox="gallery">
        <div class="gallery_module gallery_module_1 wow fadeInUp" style="background-image: url('<?= $photos[0]->getUrl($mini); ?>');"></div>
        </a>
    </div>
</div>
<?php endif;?>
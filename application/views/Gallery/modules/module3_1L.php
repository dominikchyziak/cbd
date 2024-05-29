<?php if (count($photos) > 2): ?>
    <div class="row gallery_module_separator">  
        <div class="col-xs-12 col-sm-12 col-md-4">
            <div class="row">
                <div class="col-xs-12">
                    <a href="<?= $photos[0]->getUrl(); ?>" data-lightbox="gallery">
                    <div class="gallery_module gallery_module_3_small gallery_module_3_small_top wow fadeInUp" style="background-image: url('<?= $photos[0]->getUrl($mini); ?>');"></div>
                    </a>
                    <a href="<?= $photos[1]->getUrl(); ?>" data-lightbox="gallery">
                    <div class="gallery_module gallery_module_3_small gallery_module_3_small_bottom wow fadeInUp" style="background-image: url('<?= $photos[1]->getUrl($mini); ?>');" data-wow-delay="0.2s"></div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-8">
            <a href="<?= $photos[2]->getUrl(); ?>" data-lightbox="gallery" >
            <div class="gallery_module gallery_module_3_big wow fadeInUp" style="background-image: url('<?= $photos[2]->getUrl($mini); ?>');" data-wow-delay="0.4s"></div>
            </a>
        </div>
    </div>
<?php endif; ?>
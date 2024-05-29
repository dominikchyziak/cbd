<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1 class="naglowek-podstrona wow fadeInLeft"><?= stolarczyk_title($gallery->name); ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 wow fadeInRight description-gallery">
            <?= $gallery->description; ?>
        </div> 
    </div>
    <?php /*if(count($photos >= 1)) : ?>
    <div class="row">
        <?php foreach ($photos as $photo) : ?>
        <div class="clo-xs-12 col-sm-6 col-md-6 col-lg-6 wow zoomIn">
            <div class="gallery-photo-container">    
                <a href="<?php echo $photo->getUrl(); ?>" data-lightbox="1">
                     <div class="gallery-photo" style="background-image: url('<?= $photo->getUrl(); ?>')"></div>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div> 
    <?php endif; */?>
    <div class="row">
        <div class="col-sm-12">
              <?php $this->load->view('Gallery/module_integrator', $gallery_widget); ?>
        </div>
    </div>
</div>
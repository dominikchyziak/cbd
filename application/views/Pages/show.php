<?php $translation = $page->getTranslation(LANG); ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1 class="header_title wow fadeInLeft"><?php echo $translation->title; ?></h1>
        </div>
    </div>
    <?php if(empty($gallery_widget)) { ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="opis-zastosowanie"><?php echo $translation->body; ?></div>
                </div>
            </div>
        <?php } else { ?>    
            <div class="row">
                <div class="col-sm-12">
                    <div class="opis-zastosowanie"><?php echo $translation->body; ?></div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-12">
                    <?php $this->load->view('Gallery/module_integrator', $gallery_widget); ?>
                </div>
            </div>
        <?php } ?>
</div>
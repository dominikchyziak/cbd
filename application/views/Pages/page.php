<?php $translation = $page->getTranslation(LANG); ?>
<div class="container">
    <div class="row wow fadeInLeft">
        <div class="col-sm-12">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1 class="header_title"><?php echo $translation->title; ?></h1>
        </div>
    </div>
    <div class="row wow fadeInUp" >
        <div class="col-sm-12">
            <div class="opis-zastosowanie ck-body"><?php echo $translation->body; ?></div>
        </div>
    </div> 
    <?php if (!empty($gallery_widget)): ?>
                <div class="row wow fadeInUp">
                    <div class="col-sm-12">
                        <?php $this->load->view('Gallery/module_integrator', $gallery_widget); ?>
                    </div>
                </div>
    <?php endif; ?>
</div>
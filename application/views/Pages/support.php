<?php $translation = $page->getTranslation(LANG); ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1 class="header_title wow fadeInLeft"><?php echo $translation->title; ?></h1>
        </div>
    </div>        
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">       
            <div class="opis-zastosowanie"><?php echo $translation->body; ?></div>             
        </div>                                                
    </div>                                             
</div>
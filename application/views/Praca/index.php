<?php $translation = $page->getTranslation(LANG); ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <?php $this->load->view('partials/breadcrumbs'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">  
            <h1 class="page-header title"><?php echo $translation->title; ?></h1>
            <?php echo $translation->body; ?>
            <br><br>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <?php $this->load->view('partials/formularz-praca'); ?>
        </div>
    </div>
</div>

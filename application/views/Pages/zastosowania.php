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
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 wow zoomIn">
            <a href="<?= site_url('/zastosowania/edukacja'); ?>">
                <div class="sekcja-terminale-box">
                    <div class="sekcja-terminale-box-ob">
                        <div class="sekcja-terminale-box-ob-zaw" style="background-image:url('<?= (new CustomElementModel('21'))->getField('Kafel1_obraz'); ?>');"></div> 
                    </div>
                    <h3 class="sekcja-terminale-box-nag"><?= (new CustomElementModel('21'))->getField('Kafel1_naglowek'); ?></h3>
                </div>
            </a>            
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 wow zoomIn">
            <a href="<?= site_url('/zastosowania/biznes'); ?>">
                <div class="sekcja-terminale-box">
                    <div class="sekcja-terminale-box-ob">
                        <div class="sekcja-terminale-box-ob-zaw" style="background-image:url('<?= (new CustomElementModel('21'))->getField('Kafel2_obraz'); ?>');"></div> 
                    </div>
                    <h3 class="sekcja-terminale-box-nag"><?= (new CustomElementModel('21'))->getField('Kafel2_naglowek'); ?></h3>
                </div>
            </a>            
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 wow zoomIn">
            <a href="<?= site_url('/zastosowania/administracja'); ?>">
                <div class="sekcja-terminale-box">
                    <div class="sekcja-terminale-box-ob">
                        <div class="sekcja-terminale-box-ob-zaw" style="background-image:url('<?= (new CustomElementModel('21'))->getField('Kafel3_obraz'); ?>');"></div> 
                    </div>
                    <h3 class="sekcja-terminale-box-nag"><?= (new CustomElementModel('21'))->getField('Kafel3_naglowek'); ?></h3>
                </div>
            </a>            
        </div>                  
    </div>                                               
</div>
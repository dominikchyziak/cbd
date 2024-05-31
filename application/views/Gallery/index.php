<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1 class="naglowek-podstrona wow fadeInLeft"><?= stolarczyk_title((new CustomElementModel('10'))->getField('Przyklady_wdrozen')->value); ?></h1>
        </div>
    </div>
        
    <?php /*<div id="referencje">
        <div class="row">
            <?php foreach($gallery as $index => $gal){
                $translation = $gal->gettranslation(LANG);
                $second = $gal->findAllPhotos()[1]->getUrl();    
                ?>
            <div class="referencja col-md-4 col-sm-6 col-xs-12 wow fadeInUp" data-wow-delay="<?= number_format($index*0.3,1,".",".");?>s">
                <div class="img" style="background-image:url('<?= $gal->getUrl();?>')"></div>
                <a href="">
                    <div class="name"><?= $translation->name; ?></div>
                </a>
                <div class="desc">
                    <a href="<?= $second ?>" data-lightbox="<?= $index ?>">
                        <div class="img2" style="background-image: url('<?= $second ?>'); background-size:cover;" ></div>
                    </a>
                </div>
                </div> <?php } ?>
        </div>
    </div> */?>
</div>
<div class="container">
    <div class="row">
         <?php
            $i = 0;
            foreach ($galleries as $gal) {
                $translation = $gal->getTranslation(LANG);
                $i++;
                
                    ?>

                    <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12 wow fadeInLeft">
                        <a href="<?= $gal->getPermalink(); ?>">
                            <div class="sekcja-realizacje-pozycja">
                                <div class="sekcja-realizacje-ob">
                                    <div class="sekcja-realizacje-ob-img" style="background-image:url('<?= $gal->getUrl(); ?>');">                    
                                    </div>                                      
                                </div>
                                <h4 class="sekcja-realizacje-nag">
                                <?= $translation->name; ?>
                                </h4>                  
                            </div>
                        </a>
                    </div>  
    <?php ?>
    <?php } ?>
    </div>
</div>

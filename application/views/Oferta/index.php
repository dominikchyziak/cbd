 <div class="container zawartosc-podstrony">
     <div class="row">
        <div class="col-sm-12">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1 class="naglowek-podstrona wow fadeInLeft"><?= (new CustomElementModel('10'))->getField('Oferta'); ?></h1>
        </div>
    </div>
    <div class="row wow fadeInUp">  
        <?php foreach ($categories as $category): $translation=$category->getTranslation(LANG); ?>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="sekcja-naj-poz">
                <a href="<?=$category->getPermalink(); ?>">
                  <div class="sekcja-naj-poz-ob">
                    <div class="sekcja-naj-poz-ob-ikona sekcja-naj-poz-ob-ikona-a" style="background-image:url('<?= $category->getField(7, $category->id,LANG);?>');"></div>
                    <div class="sekcja-naj-poz-ob-img">
                      <div class="sekcja-naj-poz-ob-img-o" style="background-image:url('<?= $category->getUrl(); ?>');"></div>
                    </div>
                  </div>
                  <h4 class="sekcja-naj-poz-nag"><?= $translation->name; ?></h4>
                </a>
              </div>            
            </div>         
        <?php endforeach; ?>
    </div> 
 </div>





<?php /* <div class="subpage subpage-offer">
    <h1 class="page-header"><?php echo lang('oferta_header'); ?>
    <?php
        if(!is_null($offerCategory->id)){
            $translation = $offerCategory->getTranslation(LANG); 
            echo ' / ' .$translation->name;
        }
    ?>   
    </h1>
</div>

<?php if (is_null($offerCategory->id)): ?>
    <div class="subpage-offer-tresc-glowna">
        <div class="col-xs-12 tresc-glowna">
            <?php $this->load->view('partials/kafelki_male'); ?>
        </div>
    </div>
<?php endif ?>

<?php if (! is_null($offerCategory->id)): ?>
    <?php $this->load->view('Oferta/category', [
        'offerCategory' => $offerCategory,
        'offerCategoryProducts' => $offerCategoryProducts,
    ]); ?>
<?php endif ?>

*/ ?>
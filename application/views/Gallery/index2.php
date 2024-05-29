<div class="container">

    <div class="row">
        <div class="col-sm-12 wow fadeInLeft">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h4 class="naglowek-podstrona"><?= (new CustomElementModel('10'))->getField('Przyklady_wdrozen'); ?></h4>
        </div>
    </div>
   
    
    <div class="gallery_section">      
        <div class="row">
            <?php foreach($gallery as $index => $gal) : ?>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <a href="<?= $gal->getPermalink();?>">
        <div class="sekcja-aktualnosci-bloki">
            <div class="sekcja-aktualnosci-bloki-ob">
                <div class="sekcja-aktualnosci-bloki-ob-img" style="background-image:url('<?= $gal->getUrl('mini'); ?>');"></div>
            </div>
            <div class="sekcja-aktualnosci-data"><?= $gal->name;?></div>
        </div>        
    </a>            
</div>               
            <?php endforeach; ?>
        </div> 
    </div>
    <?php echo $this->pagination->create_links(); ?>
</div>

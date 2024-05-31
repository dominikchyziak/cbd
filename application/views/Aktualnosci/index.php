<div class="container subpage-content">
    <div class="col-sm-12">
        <?php $this->load->view('partials/breadcrumbs'); ?>
        <div class="row">
            <div class="col-sm-12">
                <h1 class="title wow fadeInLeft"><?= (new CustomElementModel('10'))->getField('aktualnosci naglowek'); ?></h1>
                <hr>
            </div>  
            <div class="col-xs-12 col-sm-12 wow fadeInUp">

                <?php
                if (!empty($pages)) {
                    foreach ($pages as $k => $p) {
                        ?>
                        <div class="sekcja-uslugi-oferty">
                            <div class="sekcja-uslugi-oferty-poz wow zoomInLeft" data-wow-delay="<?= number_format(0.2 * $k + 0.3, 1, '.', '.'); ?>s">
                                <div class="sekcja-uslugi-oferty-poz-p">
                                    <a href="<?= $p['link']; ?>">
                                        <div class="sekcja-uslugi-oferty-poz-zaw">
                                            <div class="sekcja-uslugi-oferty-poz-ob">
                                                <div class="sekcja-uslugi-oferty-poz-ob-zaw" style="background-image:url('<?= $p['image'] ?>');"></div>
                                            </div>
                                            <h3 class="sekcja-uslugi-oferty-poz-nag"><span><?= $p['name']; ?></span></h3>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>        			
            </div>
        </div>
    </div>
</div>

<?php if(!empty($news)): 
    $tnews = $news->getTranslation(LANG); ?>

    <div class="col-12 col-md-12 col-lg-4 col-xl-4">
       <a href="<?= $news->getPermalink(); ?>">
            <div class="sekcja-aktualnosci-blok">                                        
                <div class="sekcja-aktualnosci-blok-ob">
                    <div class="sekcja-aktualnosci-blok-data"><?=(new DateTime($news->started_at))->format('d'); ?><span><?=$news->getMonthName((new DateTime($news->started_at))->format('n'), LANG); ?></span></div>
                    <picture>
                        <?php if (!empty($tnews->image)): ?>
                            <img src="<?= $tnews->image; ?>" alt="<?= $tnews->title; ?>" title="<?= $tnews->title; ?>">
                        <?php else: ?>
                            <img src="<?=(new CustomElementModel('9'))->getField('domyslna grafika kafla aktualnosci'); ?>" alt="<?= $tnews->title; ?>" title="<?= $tnews->title; ?>">
                        <?php endif; ?>
                    </picture>
                </div>
                <div class="sekcja-aktualnosci-blok-tekst">
                    <h4 class="sekcja-aktualnosci-blok-naglowek"><?= $tnews->title; ?></h4>
                    <?= $tnews->excerpt; ?> 
                </div>
            </div>
        </a>
    </div>

<?php endif;?>
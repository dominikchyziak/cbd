<?php if (!empty($product)): 
   $p = is_object($product) && get_class($product) != null ? $product : $product['data'];
   $tp = is_object($product) && get_class($product) != null ? $p->getTranslation(LANG) : $product['product']; ?>

    <div class="col-12 col-md-6 col-lg-6 col-xl-3 p-5">
       <a href="<?= $p->getPermalink(); ?>">
            <div class="sekcja-produkty-blok">
                <h2 class="sekcja-produkty-blok-naglowek ustaw-czcionka-czarna"><?= $tp->name; ?></h2>
                <div class="sekcja-produkty-blok-ob">
                    <picture>
                        <img src="<?=$p->getUrl('mini'); ?>">
                    </picture>
                </div>
                <div class="sekcja-produkty-blok-polecane">
                    <span><?= (new CustomElementModel('25'))->getField('etykieta polecane'); ?></span>
                </div>
                <div class="sekcja-produkty-blok-opis">
                    <p><?= $tp->slogan; ?></p>
                </div>
                <div class="sekcja-produkty-blok-cena zastosuj-wysrodkowanie">
                <?php $cena=explode(",", number_format(floatval($p->getPrice()),2,',',' '));
                if (!$p->getField(14, $p->id,LANG)): ?>
                    <span class="sekcja-produkty-blok-cena-akt bez-promocji"><?=$cena[0]; ?><sup><?=$cena[1]; ?></sup> zł</span>
                <?php else:
                    $scena=explode(",", number_format(floatval($p->getField(14, $p->id,LANG)),2,',',' ')); ?>
                    <span class="sekcja-produkty-blok-cena-przek"><?=$scena[0]; ?><sup><?=$scena[1]; ?></sup> zł</span>
                    <span class="sekcja-produkty-blok-cena-akt"><?=$cena[0]; ?><sup><?=$cena[1]; ?></sup> zł</span>
                <?php endif; ?>
                </div>
            </div>
        </a>
        <?php ($p->quantity > 0) ? $product=true : $product=false; ?>
        <div class="sekcja-produkty-blok-przycisk <?=(!$product) ? 'zablokuj-przycisk' : ''; ?>">
           <a href="<?=($product) ? $p->getBasketLink() : '#'; ?>" class="przycisk"><?=($product) ? (new CustomElementModel('25'))->getField('dodaj do koszyka') : (new CustomElementModel('25'))->getField('produkt niedostepny'); ?></a> 
        </div>                                 
    </div>

<?php endif; ?>
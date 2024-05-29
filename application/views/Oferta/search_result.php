
<?php
if (!empty($products)) {
    foreach ($products as $product) {
        ?>
       <div class="col-md-4 col-sm-6 col-xs-12">
    <div class="box-2">
        <figure>
            <a href="<?= $product['data']->getPermalink();?>"><img src="<?=$product['data']->getUrl('mini');?>" alt="" /></a>
        </figure>
        <div class="description equalizer">
            <div class="price"><?= $product['data']->price; ?><span><?= (new CustomElementModel('16'))->getField('waluta'); ?></span></div>
            <div class="txt-1"><a href="<?= $product['data']->getPermalink(); ?>"><?= $product['product']->name;?></a></div>
            <div class="txt-2"><?= $product['product']->slogan; ?></div>
        </div>
        <a href="" class="btn-1 btn-1-1" style="text-transform: uppercase;"><?= (new CustomElementModel('9'))->getField('dodaj do koszyka'); ?></a>
    </div>
</div>
        <?php
    }
} else {
    echo 'Brak produktów.';
}
?>
          
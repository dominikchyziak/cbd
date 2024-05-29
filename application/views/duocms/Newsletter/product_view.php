<div style="width:32.5%; height: auto; float: left; text-align: center; padding: 25px;">
    <img src="<?= $product->getUrl();?>" alt="product_image" style="max-width: 100%; float: left;">
    <div style="clear:both;"></div>
    <hr>
    <h4><?= $translation->name;?></h4><br>
    <strong><?= $product->price;?> zł</strong>
</div>
<?php foreach ($offerCategoryProducts as $i => $offerProduct): ?>
    <?php
    $translation = $offerProduct->getTranslation(LANG);
    $photos = $offerProduct->findAllPhotos();
    ?>
    <div class="subpage subpage-offer product">
        <div class="col-sm-6 col-md-3">
            Menu
        </div>
        <div class="col-sm-12 col-md-9">
            <div class="col-sm-12 col-md-6">
                <h3><?php echo $translation->name; ?></h3>
                <?php echo $translation->body; ?>
            </div>
            <div class="col-sm-12 col-md-6">
                <?php if (! empty($photos)): ?>
                    <div class="product-images clearfix">
                        <div class="row">
                            <?php foreach ($photos as $Photo): ?>
                                        <img src="<?php echo $Photo->getUrl(); ?>" alt="<?php echo $translation->name; ?>">
                            <?php endforeach ?>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
<?php endforeach ?>

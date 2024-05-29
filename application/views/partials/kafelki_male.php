<?php
$this->load->model('OfferCategoryModel');
$Categories = (new OfferCategoryModel())->findAllForHome(0, 999);
?>
<!-- kategorie jedna pod drugą -->
<?php if ($Categories): ?>
    <?php foreach ($Categories as $category): ?>
        <div class="row category-item">
            <div class="col-sm-12 col-md-6">
                <h3><?php echo $category->name; ?></h3>
                <?php echo $category->body; ?>
                <a href="<?php echo site_url('oferta/' . getAlias($category->id, $category->name)); ?>">
                    <?php echo lang('layout_read_more'); ?>
                </a>
            </div>
            <div class="col-sm-12 col-md-6">
                <img src="<?php echo $category->getUrl(); ?>" alt="<?php echo htmlspecialchars($category->name); ?>">
            </div>
        </div>

    <?php endforeach ?>
<?php endif ?>
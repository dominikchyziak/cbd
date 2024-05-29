<?php $translation = $offerCategory->getTranslation(LANG); ?>
<div class="subpage subpage-offer category">
    <?php echo $translation->body; ?>
</div>


<?php if (! empty($offerCategoryProducts)): ?>
    <?php $this->load->view('Oferta/product-list', [
        'offerCategoryProducts' => $offerCategoryProducts,
    ]); ?>
<?php endif ?>

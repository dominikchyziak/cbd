<div class="container">

    <div class="row">
        <div class="col-sm-12">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1 class="header_title wow fadeInLeft"><?= (new CustomElementModel('10'))->getField('Produkty'); ?></h1>
        </div>
    </div>

</div>

<section class="sekcja-oferta">
    <div class="container">        
        <div class="row">
            <?php foreach ($new_products as $pp): ?>
                <?php
                $this->load->view('Oferta/partials/product_view', ['product' => $pp]);

            endforeach;
            ?>                                                   
        </div>                                                
    </div>
</section>


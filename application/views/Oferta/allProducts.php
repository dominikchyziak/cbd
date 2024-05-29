<div class="container">
    <div class="row wow fadeInLeft">
        <div class="col-sm-12">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1 class="naglowek-podstronat"><?= (new CustomElementModel('10'))->getField('produkty naglowek'); ?></h1>
        </div>
    </div>
        <div class="row sekcja-produkty-zaw  wow fadeInUp">
           <?php if(!empty($products)):
           foreach($products as $p):
                $this->load->view('Oferta/partials/product_view', [ 'product' => $p ]);
           endforeach; endif; ?>
        </div>
</div>
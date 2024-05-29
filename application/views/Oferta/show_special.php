 <?php $this->load->view('partials/breadcrumbs'); ?>
<div class="szer-container">
<div class="container products-list-container zawartosc-podstrony">
    <div class="row">
        <div class="col-sm-12">
    <h1 class="naglowek-podstrona wow fadeInLeft" ><?php echo $title; ?></h1>  
        </div>
    </div>
        <div class="row wow fadeInUp">
            <?php if (!empty($products)):
                    foreach($products as $product):
                        $this->load->view('Oferta/partials/product_view', array( 'product' => $product, 'attr' => $special ));
                    endforeach; 
                endif; ?>
        </div>
    </div>
</div>
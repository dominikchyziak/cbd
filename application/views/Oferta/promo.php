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
                  <?php
                        foreach ($promo_products as $pp): ?>
           <div class="col-md-4 col-sm-6 col-xs-12">
						<div class="box-2">
							<figure>
								<a href="<?= $pp['data']->getPermalink();?>"><img src="<?= $pp['data']->getUrl();?>" alt="" /></a>
							</figure>
							<div class="description equalizer">
								<div class="price"><?= $pp['data']->price;?><span><?= (new CustomElementModel('16'))->getField('waluta'); ?></span></div>
								<div class="txt-1"><a href="<?= $pp['data']->getPermalink();?>"><?= $pp['product']->name;?></a></div>
								<div class="txt-2"><?= $pp['product']->slogan;?></div>
							</div>
							<a href="" class="btn-1 btn-1-1" style="text-transform: uppercase;"><?= (new CustomElementModel('9'))->getField('dodaj do koszyka'); ?></a>
						</div>
					</div>
                        
                            <?php
                  endforeach; 
                       // }
                        ?>                                                   
        </div>                                                
      </div>
    </section>
   

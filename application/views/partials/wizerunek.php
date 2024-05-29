<?php
$this->load->model("CustomElementModel");
if ($wizerunki):
    ?>
    <div class="baner-kon">    
        <div class="baner">
            <div class="cycle-slideshow"
                 data-cycle-fx="fadeout"
                 data-cycle-speed="1500"
                 data-cycle-timeout="4000"
                 data-cycle-slides="> div"
                 data-cycle-pager=".baner-strony">  

                
                <?php foreach($products as $product):
                if($product->slider == 1):
                    if (!empty($product)): 
                        $p = is_object($product) && get_class($product) != null ? $product : $product['data'];
                        $tp = is_object($product) && get_class($product) != null ? $p->getTranslation(LANG) : $product['product'];
                ?>
                
                <div class="baner-slajd">
                    <div class="baner-slajd-tlo">
                        <picture>
                            <img src="<?= (!empty($product->getField(22, $product->id,LANG))) ? $product->getField(22, $product->id,LANG) : (new CustomElementModel('9'))->getField('domyslne tlo slajdera produktu'); ?>" class="baner-slajd-tlo-ob">
                        </picture>
                        <div class="baner-slajd-ob-produkt">
                            <div class="baner-slajd-ob-produkt-zaw">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12 col-md-9 col-lg-8 col-xl-7">   
                                            <picture>
                                                <a href="<?= $p->getPermalink(); ?>">
                                                    <img src="<?=$p->getUrl('mini'); ?>">
                                                </a>
                                            </picture>
                                        </div>
                                    </div>    
                                </div>
                            </div>                                    
                        </div>                             
                    </div>
                </div> 

                <?php endif; endif; endforeach;?>
                
                <?php foreach($wizerunki as $wizerunek):
                    $translation = $wizerunek->getTranslation(LANG);
                ?> 

                <div class="baner-slajd">
                    <div class="baner-slajd-tlo">
                        <picture>
                            <img src="<?= $wizerunek->getField(12, $wizerunek->id,LANG);?>" alt="" title="" class="baner-slajd-tlo-ob">
                        </picture>
                        <div class="baner-slajd-ob-produkt">
                            <div class="baner-slajd-ob-produkt-zaw">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12 col-md-9 col-lg-8 col-xl-7">   
                                            <picture>
                                                <a href="<?=$translation->href; ?>">
                                                    <img src="<?= $wizerunek->getUrl(); ?>" alt="" title="">
                                                </a>
                                            </picture>
                                        </div>
                                    </div>    
                                </div>
                            </div>                                    
                        </div>                             
                    </div>
                </div> 

                <?php endforeach;?>
            </div>
            <div class="baner-strony"></div> 
        </div>
    </div>
<?php endif;  ?>

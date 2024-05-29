<div class="content-box content-box-2">
    <div class="wrapper">
            <h4 class="naglowek-podstrona" ><?= (new CustomElementModel(2))->getField('Naglowek 2'); ?></h4>
        <div class="boxes">
            
        </div>
    </div>
</div>

    <div class="container main-categories">
         <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <h5 class="naglowek-strona"><?= (new CustomElementModel('16'))->getField('Kategorie'); ?></h5>
                <nav class="strona-kolumny-menu">
                    <ul>
                        <?php
                        if (!empty($categories)) {
                            foreach ($categories as $cat) {
                                ?>
                                <li>
                                    <a href="<?= $cat->getPermalink(); ?>"><?= $cat->getTranslation(LANG)->name; ?></a>
                                    <ul>
                                        <?php
                                        if (!empty($cat->children)) {
                                            foreach ($cat->children as $c_child) {
                                                ?>
                                                <li><a href="<?= $c_child->getPermalink(); ?>"><?= $c_child->getTranslation(LANG)->name; ?></a></li>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                </li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
                </nav>
            </div> 
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
        <?php /*
        if (count($products) > 0) {
            $i = 0;
            echo '<div class="row row10">';
            foreach ($products as $product) {
                $i++;
                ?>
                <?php if (!empty($product["photos"])) { ?>
                    <div class="col-sm-4 col-xs-12">
                        <a href="<?php echo site_url('product/' . getAlias($product["product"]->product_id, $product["product"]->name)); ?>" class="box equalizer">
                            <img src="<?= $product["photos"][0]->getUrl(); ?>" alt="<?= $product['product']->name; ?>" alt="" />
                            <span class="btn-1"><?= $product["product"]->slogan ?></span>
                        </a>
                    </div>
                <?php
                }
            }
            echo '</div>';
        } 
        if(!empty($categories)){
            foreach($categories as $category){
                if($category->parent_id > 0){
                    continue;
                }
                ?>
        <div class=" col-lg-4 col-md-4 col-sm-6 col-xs-12 box">
            <a href="<?= $category->getPermalink();?>">
                <div style="background-image: url('<?= !empty($category->getUrl()) ? $category->getUrl() : (new CustomElementModel('9'))->getField('obrazek kategorii')->value;?>');" class="img-box"></div>
                <h3>
                    <?= $category->name;?>
                </h3>
            </a>
        </div>
        <?php
            }
        }*/
        ?>
             <div class="container-fluid">        
                    <div class="row row-kasuj-m">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h1 class="naglowek-strona naglowek-strona-dod"><?= (new CustomElementModel('2'))->getField('Nasze nowosci'); ?></h1>
                        </div>
                    </div>
                    <div class="row row-kasuj-m">
                        <?php
                        if (!empty($new_products)) {
                            $this->load->view('Oferta/search_result', array('products' => $new_products));
                        }
                        ?>                             
                    </div>
                    <div class="row row-kasuj-m">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h1 class="naglowek-strona naglowek-strona-dod"><?= (new CustomElementModel('2'))->getField('Bestsellery'); ?></h1>
                        </div>
                    </div>
                    <div class="row row-kasuj-m">
                        <?php
                        if (!empty($bestsellers)) {
                            $this->load->view('Oferta/search_result', array('products' => $bestsellers));
                        }
                        ?>                                
                    </div>
                    <div class="row row-kasuj-m">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h1 class="naglowek-strona naglowek-strona-dod"><?= (new CustomElementModel('2'))->getField('Promocje'); ?></h1>
                        </div>
                    </div>
                    <div class="row row-kasuj-m">
                       <?php
                        if (!empty($bestsellers)) {
                            $this->load->view('Oferta/search_result', array('products' => $promo_products));
                        }
                        ?>                             
                    </div>                             
                </div>
    </div>
    </div>

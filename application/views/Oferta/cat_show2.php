<div class="content-box">
    <div class="wrapper">
        <?php $this->load->view('partials/breadcrumbs'); ?>
        <div class="products">
            <div class="row">
                <div class="col-sm-12">
                    <div class="title">
                        <h2><?= $category->name;?></h2>
                    </div>
                </div>
            </section>
                <div class="gallery_box">
                    <div class="container">
                    <div class="col-sm-12 title">
                    <?php
                    if (count($products) > 0) {
                        $i = 0;
                        foreach ($products as $product) {
                            if($product["type"] == 1){
                                echo "<h2><strong style='color: #FFF;'>" . $product["product"]->name . "</strong></h2>";
                                echo "<p>" . $product["product"]->body . "</p>";
                                if (!empty($product["photos"][0])) { ?>
                        <div class="row">
                        <ul class="gallery_slider row">
                            <?php
                                foreach($product["photos"] as $photo){
                            ?>
                            <li class="col-sm-3">
                                <a href="<?php echo $photo->getUrl(); ?>" data-lightbox="product-<?php echo $product["product"]->id; ?>" class="product-img"
                                                            data-title="<?= $photo->get_description($photo->id,LANG)->description;?>">
                                    <img src="<?= $photo->getUrl("mini"); ?>" alt="" />
                                </a>
                            </li>
                            <?php
                                }
                            ?>
                        </ul>
                        </div>
                                    <?php }
                            }
                        }
                    }?>
                </div>
                    </div>
                </div>
            <section class="container">
             
        </div>
    </div>
</div>
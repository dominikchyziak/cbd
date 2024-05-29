<div class="container">
    <div class="row wow fadeInLeft">
        <div class="col-sm-12">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1><?= $product->name; ?></h1>
        </div>
    </div>
    <div class="row"> 
        <div class="col-sm-12 col-md-6 wow fadeInUp"> 
            <div class="row">
                <?php if (!empty($photos)): ?>
                <div class="col-sm-12 slajder-produktu">  
                    
                    <div class="cycle-slideshow kontener-obrazu" 
                         data-cycle-fx=scrollHorz
                         data-cycle-swipe=true
                         data-cycle-swipe-fx=scrollHorz
                         data-cycle-timeout=0
                         data-cycle-pager="#adv-custom-pager"
                         data-cycle-pager-template="<a href='#'><img src='{{src}}' width=100 height=85></a>">
                        
                        <?php if (count($photos) > 1 ): ?>
                        <div class="cycle-prev przycisk-poprzedni"></div>
                        <div class="cycle-next przycisk-nastepny"></div>
                        <?php endif; ?>
                        
                        <?php foreach ($photos as $photo): ?>
                            <img src="<?=$photo->getUrl(); ?>">
                        <?php endforeach; ?> 
                    </div>
                    <div id=adv-custom-pager class="center external miniaturki-produktu"></div>
                </div>
                <?php endif; ?>
                <div class="col-sm-12">
                    <?= $product->body; ?>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 wow fadeInRight">
            <div class="row">
                <div class="col-sm-12 produkt-cena text-center text-sm-center text-md-center text-lg-right">
                    <?php if(!$product_data->getField(14, $product_data->id,LANG)):
                        echo number_format($product_data->getPrice(), 2, ',', ' ').' zł </br>';
                    else: ?>
                    <s><?=$product_data->getField(14, $product_data->id,LANG); ?> zł </s></br>
                        <span class="cena-promocyjna"><?=number_format($product_data->getPrice(), 2, ',', ' '); ?> zł </span></br>
                    <?php endif;
                    ($product_data->quantity > 0) ? $productAvailable=true : $productAvailable=false; ?>
                        <span><?=($productAvailable) ? (new CustomElementModel('25'))->getField('produkt dostepny') : (new CustomElementModel('25'))->getField('produkt niedostepny'); ?></span>
                </div>
                <div class="col-sm-12">
                    <div class="row ">
                    <?php if ($productAvailable): ?>
                        <div class="col-lg-7 col-sm-6 col-md-12 col-xs-6">
                            <div class="produkt-kontrola-ilosc">
                                <div class="produkt-etykieta-ilosc"><?= (new CustomElementModel('25'))->getField('ilosc'); ?></div>
                                <div class="item-quantity" max-quantity='<?= $product_data->quantity; ?>'>
                                    <div class="item-quantity-minus"></div>
                                    <div class="item-quantity-number"> 1 </div>
                                    <div class="item-quantity-plus"></div>
                                </div>
                            </div>   
                        </div>
                        <div class="col-sm-6 col-lg-5 col-md-12 col-xs-6">
                            <div class="sekcja-produkty-blok-przycisk">
                                <form method="POST">
                                    <input type="hidden" name="product_id" value="<?= $product_data->id; ?>" />
                                    <input type="hidden" name="quantity" value="1" />
                                    <input type="submit" name="buy" value="<?= (new CustomElementModel('25'))->getField('dodaj do koszyka'); ?>" class="przycisk wyrownaj-do-prawej" />
                                </form>
                            </div>
                        </div>
                        <?php endif; ?>
                        <script>
						
							var lang = document.documentElement.lang;
						
                            function increase_count(evt) {
                                var container = $(this).parents('.item-quantity');
                                var current = parseInt($(container).children('.item-quantity-number').html());
                                var max = parseInt($(container).attr('max-quantity'));
                                if (current === max) {
								  if (lang === "pl"){
										toastr.error('Nie można dodać więcej produktu danego typu.');
								  } else {
										toastr.error('No more of this type can be added.');
								  }
                                } else {
                                    $('input[name="quantity"]').val(current + 1);
                                    $(container).children('.item-quantity-number').html(current + 1);
                                }
                            }
                            function decrease_count(evt) {
                                var container = $(this).parents('.item-quantity');
                                var current = parseInt($(container).children('.item-quantity-number').html());
                                if (!(current > 1)) {
								  if (lang === "pl"){
									toastr.error('Nie można zmniejszyć ilości poniżej jednego, proszę usunąć produkt.');
								  } else {
									toastr.error('The quantity cannot be reduced below one, please remove the product.');
								  }
                                } else {
                                    $('input[name="quantity"]').val(current - 1);
                                    $(container).children('.item-quantity-number').html(current - 1);
                                }
                            }
                            $(document).ready(function () {
                                $('.item-quantity-plus').on('click', increase_count);
                                $('.item-quantity-minus').on('click', decrease_count);
                            });
                        </script>
                    </div>
                </div>
            </div>                   
            <div class="row kontener-produkt-dodatkowe-info">                       
                <div class="col-sm-12">
                    <h2><?= (new CustomElementModel('25'))->getField('etykieta dodatkowe'); ?></h2>
                </div>
                <div class="col-sm-12">
                    <p>
                        <span><?= (new CustomElementModel('25'))->getField('etykieta sklad').'</span> '.$product_data->getField(15, $product_data->id,LANG); ?>
                    </p>
                    <p>
                        <span><?= (new CustomElementModel('25'))->getField('etykieta kannabinoidy').'</span> '.$product_data->getField(16, $product_data->id,LANG); ?>
                    </p>
                    <p>
                        <span><?= (new CustomElementModel('25'))->getField('etykieta stezenie').'</span> '.$product_data->getField(17, $product_data->id,LANG); ?>
                    </p>
                    <p>
                        <span><?= (new CustomElementModel('25'))->getField('etykieta objetosc').'</span> '.$product_data->getField(18, $product_data->id,LANG); ?>
                    </p>
                    <p>
                        <span><?= (new CustomElementModel('25'))->getField('etykieta terpenoidy').'</span> '.$product_data->getField(19, $product_data->id,LANG); ?>
                    </p>
                    <p>
                        <span><?= (new CustomElementModel('25'))->getField('etykieta dzialanie').'</span> '.$product_data->getField(20, $product_data->id,LANG); ?>
                    </p>
                    <p>
                        <span><?= (new CustomElementModel('25'))->getField('etykieta walory').'</span> '.$product_data->getField(21, $product_data->id,LANG); ?>
                    </p>
                    <p>
                        <span><?= (new CustomElementModel('25'))->getField('etykieta polecane').'</span> '.$product->slogan; ?>
                    </p>
                </div>
            </div>
        </div>                                  
     </div>
</div>
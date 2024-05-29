<div class="container">
    <div class="row">
        <div class="col-sm-12 wow fadeInLeft">
            <h1 class="koszyk-breadcrumbsy"><?= (new CustomElementModel('10'))->getField('Twój koszyk'); ?><span> > <?= (new CustomElementModel('10'))->getField('dane do wysylki naglowek'); ?> > <?= (new CustomElementModel('10'))->getField('platnosci dostawa naglowek'); ?></span></h1>
        </div>  
        <div class="col-sm-12 col-lg-12 col-xl-9 order-xl-1 order-md-2 order-2 wow fadeInUp">
            
            <div class="basket-items-container mb-5">
                <?php if (!empty($products)): ?>
                    <?php foreach($products as $i => $product): ?>
                    <div class="i-row d-flex flex-md-column flex-lg-row" data-product-id="<?=$product['product_data']->id; ?>">
                        <div class="item-product-info">
                        <div class="item-mini">
                           <?= !empty($product['photos']) ? '<img src="' . $product['photos'][0]->getUrl('mini') . '" alt="" />' : ''; ?>
                        </div>
                        <div class="item-name">
                             <a href="<?= $product['product_data']->getPermalink(); ?>">
                                                <?= $product['product']->name; ?> 
                             </a>
                        </div>
                        </div>
                        <div class="item-basket-info">
                            <div class="item-price" price="<?=$product['price'];?>">
                                  <?= number_format($product['price'], 2, ',', ' '); ?>&nbsp;<?= 'zł'; //get_active_currency_code(); ?>
                            </div>
                            <div class="item-quantity" max-quantity='<?= $product['product_data']->quantity+$product['quantity']; ?>'>
                                <div class="item-quantity-minus"></div>
                                <div class="item-quantity-number"> <?= $product['quantity']; ?> </div>
                                <div class="item-quantity-plus"></div>
                            </div>
                            <div class="item-value" row-value="<?= $product['price']*$product['quantity']; ?>">
                                <?= number_format($product['price']*$product['quantity'], 2, ',', ' '); ?>&nbsp;zł
                            </div>
                            <div class="item-delete">
                                <a href="<?= site_url('zamowienie/delete_item/' . $product['product_id']); ?>">x</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                <p> <?= (new CustomElementModel('16'))->getField('pusty koszyk tekst'); ?> </p>
                <?php endif; ?>
            </div>

			<div class="row discount-codes-container wow fadeInUp">
				<?php if(!empty($products)):
				  /* if (!empty($userdata['email'])) { */
					$discount = !empty($this->session->userdata['discount']) ? $this->session->userdata['discount'] : null;
					?>
					<div class="col-sm-12">
						<div class="coupon row">
							<div class="col-sm-12">
								<h2 class="mb-3">
									<?= (new CustomElementModel('16'))->getField('Kod rabatowy'); ?>
								</h2>
							</div>
							
							<div class="col-sm-12">
								<div class="row">
									<div class="col-sm-12">
										<?= (new CustomElementModel('16'))->getField('Opis kodu rabatowego'); ?>
										<?php
										if (!empty($discount['code'])) {
										?>
										<div class="alert alert-info">
											<p><?= $discount['name']; ?></p>
											<?= (new CustomElementModel('16'))->getField('Kod wliczony'); ?>
										</div>
										<?php
										}
										?>
									</div>
									<div class="col-sm-12">
										<form method="POST" style="width: 100%;">
											<div class="row">
												<div class="col-sm-12 col-md-8 formularz">
													<input type="text" name="code" value="<?= (!empty($discount['code']) && !($discount['id'] == 1)) ? $discount['code'] : ''; ?>" class="form-control" />
												</div>
												<div class="col-sm-12 col-md-4 text-right">
													<input type="submit" name="set_code" value="<?= (new CustomElementModel('16'))->getField('Wykorzystaj kod'); ?>" class="przycisk-koszyk przycisk przycisk-kupon" />
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>

					<?php /*
				} else {
					?>
					<div class="col-sm-12">
						<div class="alert alert-info">
							<?= (new CustomElementModel('16'))->getField('Jesli posiadasz kod'); ?>
						</div>
					</div>

				  <?php
				  } */ endif; 
				?>
			</div>
			
            <div class="row">
                <div class="col-sm-12 col-md-7">
                    <div class="basket-back-to-shopping">
                        <a href="<?= site_url(); ?>">
                            <img src="<?= assets('img/arrow.png'); ?>" />
                            <?= (new CustomElementModel('16'))->getField('powrót z koszyka tekst'); ?>
                        </a>
                    </div>
                </div>
                <div class="col-sm-12 col-md-5 d-flex align-items-end justify-content-end">
                    <?php if(!empty($products)): ?>
                    <a href="<?=site_url('koszyk?step=2');?>" class="przycisk przejdz-do-kasy-marginesy-rwd">
                        <?= (new CustomElementModel('16'))->getField('przejdz do kasy tekst'); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-lg-12 col-xl-3 order-xl-2 order-1 wow fadeInRight">
           
            <div class="basket-summary">
                <div class="basket-summary-details">
                    <div class="basket-sumary-row" id="basket-product-summary">
                        <?php $total_quantity = basket('quantity'); ?>
                        <span><?= $total_quantity;?><?php switch($total_quantity){
                            case 1:
                                echo (LANG === "pl") ? ' produkt' : ' product';
                                break;
                            case 2:
                            case 3:
                            case 4:
                                echo (LANG === "pl") ? ' produkty' : ' products';
                                break;
                            default:
                                echo (LANG === "pl") ? ' produktów' : ' products';
                                break;
                        } ?></span>
                        <span><?= number_format($sum_price,2,',',' ');?>&nbsp;zł</span>
                    </div>
                    <div class="basket-sumary-row" id="basket-summary-delivery">
                        <span><?= (new CustomElementModel('16'))->getField('wysylka od tekst'); ?></span>
                        <?php 
                        $ac = get_active_currency()->id; 
                        //$del_price = $sum_price > $initial_free_delivery->prices[$ac]['max_price'] ? $initial_free_delivery->prices[$ac]['price'] : 0;
                        
                        if ($total_quantity > 0){
                            $delivPrice = $initial_free_delivery->prices[$ac]['price'];    
                        }
                        else{
                            $delivPrice = 0.00;
                        }
                        ?>
                        <span delivery-price="<?= $delivPrice; ?>"><?= number_format($initial_free_delivery->prices[$ac]['price'],2,',',' '); ?>&nbsp;zł</span>
                    </div>
                </div>
                <div class="basket-summary-total">
                    <div class="basket-sumary-row">
                        <span><strong><?= (new CustomElementModel('16'))->getField('razem tekst'); ?></strong></span>
                        <span><?= number_format($sum_price+$delivPrice,2,',',' '); ?>&nbsp;zł</span>
                    </div>
                </div>
            </div>
                <?php /*
                if($initial_free_delivery):
                    if ($free_delivery > 0): ?>
                <h3>Brakuje ci <span style="color:#207b5e; text-decoration: underline"><?= number_format($free_delivery, 2, ',',' ');?></span> zł do darmowej dostawy</h3>
                <?php else: ?>
                 <h3>Możesz skorzystać z darmowej dostawy.</h3>
                    <?php endif; endif; */ ?>
        </div>
    </div>
</div>
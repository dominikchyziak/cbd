<div class="container">
    <div class="row">
        <div class="col-sm-12 wow fadeInLeft">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1 class="koszyk-breadcrumbsy"><?= (new CustomElementModel('10'))->getField('Twój koszyk'); ?> > <?= (new CustomElementModel('10'))->getField('dane do wysylki naglowek'); ?><span> > <?= (new CustomElementModel('10'))->getField('platnosci dostawa naglowek'); ?></span></h1>
        </div>
        
            <div class="col-sm-12 wow fadeInLeft">
                <div class="my-header">
                    <img src="<?= (new CustomElementModel('16'))->getField('zloz zamowienie grafika'); ?>">
                    <h2 class="naglowek-podstrona"><?= (new CustomElementModel('16'))->getField('zloz zamowienie tekst'); ?></h2>
                </div>
              
    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
                
                
 
            </div>
        <div class="col-sm-12 col-xl-9 order-md-2 order-xl-1 order-2 wow fadeInUp">
       
   
<form method="POST" id="basket-client-data">
<input type="hidden" name="weight" value="<?= !empty($sum_weight) ? $sum_weight : '0'; ?>" />                
                    <div class="row formularz">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <input type="text" name="full_name" style="margin-bottom: 43px;" value="<?= !empty($userdata['first_name']) ? $userdata['first_name'].' '.$userdata['last_name'] : ""; ?>" placeholder="<?= (new CustomElementModel('11'))->getField('Imie'); ?>" class="form-control" required="true"/>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" value="<?= !empty($userdata['email']) ? $userdata['email'] : ""; ?>" placeholder="<?= (new CustomElementModel('11'))->getField('Email'); ?>" class="form-control" required="true"/>
                            </div>
                            <div class="form-group">
                                <input type="tel" name="phone" style="margin-bottom: 41px;" value="<?= !empty($userdata['phone']) ? $userdata['phone'] : ""; ?>" placeholder="<?= (new CustomElementModel('11'))->getField('Telefon'); ?>" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12 col-md-7 col-lg-8">
                                        <input type="text" id="city" name="city" placeholder="<?= (new CustomElementModel('11'))->getField('Miasto'); ?>" class="form-control" required="true" value="<?= !empty($userdata['city']) ? $userdata['city'] : ''; ?>"/>
                                    </div>
                                    <div class="col-sm-12 col-md-5 col-lg-4">
                                        <input type="text" name="zip_code" placeholder="<?= (new CustomElementModel('11'))->getField('kod pocztowy'); ?>" class="form-control" required="true" value="<?= !empty($userdata['zip_code']) ? $userdata['zip_code'] : ''; ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <input type="text" id="street" name="street" placeholder="<?= (new CustomElementModel('11'))->getField('ulica i nr'); ?>" class="form-control" value="<?= !empty($userdata['street']) ? $userdata['street'] : ''; ?>"/>
                                    </div>
                                    <div class="col-sm-12 col-md-3">
                                        <input type="text" id="street2" name="building_number" placeholder="<?= (new CustomElementModel('11'))->getField('nr'); ?>" class="form-control" value="<?= !empty($userdata['building_number']) ? $userdata['building_number'] : ''; ?>"/>
                                    </div>
                                    <div class="col-sm-12 col-md-3">
                                        <input type="text" name="flat_number" placeholder="<?= (new CustomElementModel('11'))->getField('nr_mieszkania'); ?>" class="form-control" value="<?= !empty($userdata['flat_number']) ? $userdata['flat_number'] : ''; ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="uwagi" class="my-label" style="margin-top: 54px; margin-bottom: 13px;"><?= (new CustomElementModel('11'))->getField('Informacje dodatkowe'); ?></label>
                                <textarea class="form-control" id="uwagi" name="comment"><?= !empty($_POST['comment']) ? $_POST['comment'] : ''; ?></textarea>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-6">
                          
                            <div class="form-group">
                                <label class="label-control my-label-checkbox" >
                                    <input type="checkbox" class="border-success mb-2" name="invoice" value="1" id="i-want-invoice"/> <?= (new CustomElementModel('11'))->getField('prosba o fakture'); ?>
                                </label>
                            </div>
                            
                            <div class="form-group invoice-data default-hide">
                                <div class="form-group">
                                    <input type="text" name="company_name" placeholder="<?= (new CustomElementModel('11'))->getField('nazwa firmy'); ?>" value="" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <input type="text" name="company_nip" placeholder="NIP" value="" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <input type="text" name="company_address" placeholder="<?= (new CustomElementModel('11'))->getField('adres firmy'); ?>" value="" class="form-control" />
                                </div>
                            </div>
                            
                            
                            <?php /*
                            <div class="form-group">
                                <label class="label-control my-label-checkbox" >
                                    <input type="checkbox" name="other-shipping-address" value="1" id="other-shipping-address"/> <?= (new CustomElementModel('11'))->getField('inny adres wysylki'); ?>
                                </label>
                            </div>
                            
                            <div class="form-group other-shipping-address-data default-hide">
                                <div class="form-group">
                                    <input type="text" name="secondary_full_name" value="<?= !empty($userdata['secondary_full_name']) ? $userdata['secondary_full_name'] : ""; ?>" placeholder="<?= (new CustomElementModel('11'))->getField('Imie'); ?>" class="form-control"/>
                                </div>

                                <div class="form-group">
                                    <input type="tel" name="secondary_phone" style="margin-bottom: 41px;" value="<?= !empty($userdata['secondary_phone']) ? $userdata['secondary_phone'] : ""; ?>" placeholder="<?= (new CustomElementModel('11'))->getField('Telefon'); ?>" class="form-control"/>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-7 col-lg-8">
                                            <input type="text" id="city" name="secondary_city" placeholder="<?= (new CustomElementModel('11'))->getField('Miasto'); ?>" class="form-control" value="<?= !empty($userdata['secondary_city']) ? $userdata['secondary_city'] : ''; ?>"/>
                                        </div>
                                        <div class="col-sm-12 col-md-5 col-lg-4">
                                            <input type="text" name="secondary_zip_code" placeholder="<?= (new CustomElementModel('11'))->getField('kod pocztowy'); ?>" class="form-control" value="<?= !empty($userdata['secondary_zip_code']) ? $userdata['secondary_zip_code'] : ''; ?>"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <input type="text" id="street" name="secondary_street" placeholder="<?= (new CustomElementModel('11'))->getField('ulica i nr'); ?>" class="form-control" value="<?= !empty($userdata['secondary_street']) ? $userdata['secondary_street'] : ''; ?>"/>
                                        </div>
                                        <div class="col-sm-12 col-md-3">
                                            <input type="text" id="street2" name="secondary_building_number" placeholder="<?= (new CustomElementModel('11'))->getField('nr'); ?>" class="form-control" value="<?= !empty($userdata['secondary_building_number']) ? $userdata['secondary_building_number'] : ''; ?>"/>
                                        </div>
                                        <div class="col-sm-12 col-md-3">
                                            <input type="text" name="secondary_flat_number" placeholder="<?= (new CustomElementModel('11'))->getField('nr_mieszkania'); ?>" class="form-control" value="<?= !empty($userdata['secondary_flat_number']) ? $userdata['secondary_flat_number'] : ''; ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div> */ ?>                  
                              <?php if(empty($this->session->userdata('login')['user'])): ?> 
                     
                                    <div class="form-group">
                                       <label class="label-control my-label-checkbox" >
                                           <input type="checkbox" name="register" value="1" id="register-form"/> <?= (new CustomElementModel('16'))->getField('rejestracja przy zakupie'); ?>
                                       </label>
                                   </div>
                                    <div class="">
                                        <div class="form-group register-form-data default-hide">
                                            <div class="form-group">
                                                <input type="password" name="password" value="" placeholder="<?= (new CustomElementModel('11'))->getField('haslo'); ?>" class="form-control"/>
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="password2" value="" placeholder="<?= (new CustomElementModel('11'))->getField('Powtorz haslo'); ?>" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                
                    <?php endif; ?>

                        </div>
                        <div class="col-sm-12 d-flex justify-content-end">
                            <input type="submit"  class="przycisk przejdz-do-kasy-marginesy-rwd" name="send" value="<?= (new CustomElementModel('16'))->getField('przejdz dalej tekst'); ?>" />
                        </div>
                    </div>
                     


 </form>
        </div>
        <div class="col-xl-3 col-md-12 col-sm-12 order-md-1 order-xl-2 order-1 wow fadeInRight">
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
                        <?php $ac = get_active_currency()->id; 
                        //$del_price = $sum_price > $initial_free_delivery->prices[$ac]['max_price'] ? $initial_free_delivery->prices[$ac]['price'] : 0;
                        $delivPrice = $initial_free_delivery->prices[$ac]['price'];
                        ?>
                        <span delivery-price="<?= $delivPrice; ?>"><?= number_format($delivPrice,2,',',' '); ?>&nbsp;zł</span>
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

<script>
 $(document).ready(function(){
    $('#i-want-invoice').click(function(){
        $('.invoice-data').toggle();
    });
    $('#register-form').click(function(){
        $('.register-form-data').toggle();
    });
 });
</script>
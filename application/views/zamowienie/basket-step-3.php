<div class="container">
    <div class="row">
        <div class="col-sm-12 wow fadeInLeft">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1 class="koszyk-breadcrumbsy"><?= (new CustomElementModel('10'))->getField('Twój koszyk'); ?> > <?= (new CustomElementModel('10'))->getField('dane do wysylki naglowek'); ?> > <?= (new CustomElementModel('10'))->getField('platnosci dostawa naglowek'); ?></h1>
            <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
        </div>
        
        <form method="POST" id="basket-client-data" style="width: 100%;">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-lg-5 col-sm-12 wow fadeInUp">
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
                                   $del_price = $sum_price > $initial_free_delivery->prices[$ac]['max_price'] ? $initial_free_delivery->prices[$ac]['price'] : 0;
                                   ?>
                                   <span id="delivPrice" delivery-price="<?= $del_price; ?>"><?= number_format($del_price,2,',',' '); ?>&nbsp;zł</span>
                               </div>
                           </div>
                           <div class="basket-summary-total">
                               <div class="basket-sumary-row">
                                   <span><strong><?= (new CustomElementModel('16'))->getField('razem tekst'); ?></strong></span>
                                   <span id="totalPrice"><?= number_format($sum_price+$del_price,2,',',' '); ?>&nbsp;zł</span>
                               </div>
                           </div>
                       </div>
                    </div>
                    <div class="col-lg-7 col-sm-12 wow fadeInRight">
                        <?php $this->load->view('zamowienie/basket-delivery-payment'); ?>
                            <label>
                                <input type="checkbox" name="accept_term" value="1" class="my-label-checkbox" required="1" <?= !empty($_POST['accept_term']) ? 'checked' : ''; ?>><p style="font-size: 16px; display: inline-block; margin-left: 15px;"><?= (new CustomElementModel('11'))->getField('akceptuje'); ?> <a href="<?= site_url("regulamin-sklepu-internetowego"); ?>"><?= (new CustomElementModel('11'))->getField('regulamin'); ?></a> i <a href="<?= site_url('polityka-prywatnosci'); ?>">politykę prywatności</a>.</p>
                            </label>
                        <?php /*
                            <label>
                                <input type="checkbox" name="accept_newsletter" value="1" class="my-label-checkbox" <?= !empty($_POST['accept_newsletter']) ? 'checked' : ''; ?>><p style="font-size: 16px; display: inline-block; margin-left: 15px; ">Chcę otrzymywać informację newsletter o ofertach specjalnych.</p>
                            </label>
                         */ ?>
                            <div class="g-recaptcha rejestracja" data-sitekey="<?= get_option('recaptcha_site_key'); ?>"></div>  
                            <div class="checkbox wow fadeInUp">
                                <button class="przycisk pull-right zamawiam-marginesy-rwd">
                                    <span><?= (new CustomElementModel('11'))->getField('Przejdź do podsumowania'); ?></span>
                                </button>
                            </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
            
            <?php /*
<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
              <?php /*  <div class="row sklep-dostawa-platnosc">
                    <?php $this->load->view('zamowienie/basket-delivery-payment'); ?>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12 form-group">


                        <label>
                            <input type="checkbox" name="accept_term" value="1" class="my-label-checkbox" required="1" <?= !empty($_POST['accept_term']) ? 'checked' : ''; ?>><p style="font-size: 16px; display: inline-block; margin-left: 15px;"><?= (new CustomElementModel('11'))->getField('akceptuje'); ?> <a href="<?= site_url("regulamin"); ?>"><?= (new CustomElementModel('11'))->getField('regulamin'); ?></a> i <a href="<?= site_url('polityka-prywatnosci'); ?>">politykę prywatności</a>.</p>
                        </label>
                        <label>
                            <input type="checkbox" name="accept_newsletter" value="1" class="my-label-checkbox" <?= !empty($_POST['accept_newsletter']) ? 'checked' : ''; ?>><p style="font-size: 16px; display: inline-block; margin-left: 15px; ">Chcę otrzymywać informację newsletter o ofertach specjalnych.</p>
                        </label>
                        <div class="g-recaptcha rejestracja" data-sitekey="<?= get_option('recaptcha_site_key'); ?>"></div>
                    </div>     
                </div> */ ?>



    </div>   
</div>
            
            <?php /*


            <div class="row zamowienie-margin">
                    <div class="col-sm-12 col-md-12 col-lg-9">
                        <div class="checkbox wow fadeInUp">
                            <button class="przycisk-koszyk pull-right">
                                <span><?= (new CustomElementModel('11'))->getField('Przejdź do podsumowania'); ?></span>
                            </button>
                        </div>
                    </div>
                </div>
             </form>  
        
</div>
    </div>
</div>
        */ ?>
<script>
    function number_format(number, decimals, dec_point, thousands_sep) {
        // Strip all characters but numerical ones.
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
//koszt całkowity do zapłaty
    var deliveries = [];
<?php
if (!empty($deliveries)) {
    $ac = get_active_currency()->id;
    foreach ($deliveries as $delivery) {
        ?>
            deliveries[<?= $delivery->delivery_id; ?>] = <?= $sum_price > $delivery->prices[$ac]['max_price'] ? '0' : $delivery->prices[$ac]['price']; ?>;
        <?php
    }
}
?>

$(document).ready(function(){
  //uzupełnianie ukrytego pola info o paczkomacie
  $('#inpost_locker').on('change',function(){
  $('#delivery_additional').val($(this).find('option:selected').text());
  });

  $('#delivery').on('change',function(){
  var val = this.value;
  var texts = $(this).find(':selected').text();
  var f_pay = deliveries[val]+<?= $sum_price * 1; ?>;
  $('#for_pay').html(number_format((Math.round(f_pay*100)/100), 2, ',', ' ') + " <?= get_active_currency_code(); ?>");
 

  if(texts.indexOf('osobisty') != -1 || texts.indexOf('pobrani') != -1 || texts.indexOf('Pobrani') != -1 || texts.indexOf('on delivery') != -1){
  $("#pay_method").val('upon_receipt');
  $("#pay_method option:selected").siblings().hide();
  } else {
  $('#pay_method option').show();
  $("#pay_method option[value=upon_receipt]").hide();
  document.getElementById('pay_method').selectedIndex = 0;
  }
  });
  });
  </script>
<div class="col-sm-12">                           
    <div class="my-header">
        <img src="<?= (new CustomElementModel('16'))->getField('sposob dostawy grafika'); ?>">
        <h2 class="naglowek-do-menu-select"><?= (new CustomElementModel('16'))->getField('sposob dostawy etykieta'); ?></h2>
        <div class="form-group">
            <select onchange="setPrice(this.options[this.selectedIndex].getAttribute('price'));" name="delivery" id="delivery" required="true" class="form-control delivery-payment">
                <option value="" style="width: 100px;" ><?= (new CustomElementModel('11'))->getField('Wybierz sposob dostawy'); ?></option>
                <?php
                if(!empty($deliveries)){
                $last_cat = 0;
                $ac = get_active_currency()->id;
                foreach($deliveries as $delivery){

                ?>
                <option value="<?= $delivery->delivery_id;?>" price="<?=$delivery->prices[$ac]['price'];?>">
                <?= $delivery->name; ?> -
                <?= $sum_price >= $delivery->prices[$ac]['max_price'] ? '0' : $delivery->prices[$ac]['price'];?> <?= get_active_currency_code(); ?></option>
                <?php
                }
                }
                ?>
            </select>
            <input type="hidden" name="delivery_additional" value="" id="delivery_additional"/>
            <input type="hidden" name="inpost_locker" value="" id="locker"/>
      </div> 
    <div class="form-group" id="inpost_locker"></div>
    </div>  
</div>
<div class="col-sm-12">                           
    <div class="my-header">
        <img src="<?= (new CustomElementModel('16'))->getField('sposob platnosci grafika'); ?>">
        <h2 class="naglowek-do-menu-select"><?= (new CustomElementModel('16'))->getField('sposob platnosci etykieta'); ?></h2>                                                        
        <div class="form-group">
          <select name="method" required="true" id="pay_method" class="form-control delivery-payment">
            <option value=""><?= (new CustomElementModel('11'))->getField('Wybierz metodę płatności'); ?></option>
            <?php if(get_option('payu_active')) {?>
            <option value="payu"><?= (new CustomElementModel('16'))->getField('payu'); ?></option>
            <?php } ?>
            <?php if(get_option('p24_active')) {?>
            <option value="p24"><?= (new CustomElementModel('16'))->getField('p24'); ?></option>
            <?php } ?>
            <?php if(get_option('paypal_active')) {?>
            <option value="paypal"><?= (new CustomElementModel('16'))->getField('paypal'); ?></option>
            <?php } ?>
            <option value="bank"><?= (new CustomElementModel('11'))->getField('Zwykły przelew'); ?></option>
            <option value="upon_receipt"><?= (new CustomElementModel('11'))->getField('place przy odbiorze'); ?></option>
          </select>
        </div>                  
    </div>  
</div>  

<script>  
    function setPrice(selectedPrice) { 
        if (typeof onlyProductsPrice === 'undefined') {
            onlyProductsPrice = parseFloat(document.getElementById("totalPrice").innerHTML.replace(',', '.'));
        }   
        var delivPrice = parseFloat(selectedPrice.replace(',', '.'));
        var sumPrice = onlyProductsPrice + delivPrice;
    $("#delivPrice").text(delivPrice.toFixed(2).toString().replace(".", ",") +' zł');  
    $("#totalPrice").text(sumPrice.toFixed(2).toString().replace(".", ",") +' zł'); 
    }
</script>
    
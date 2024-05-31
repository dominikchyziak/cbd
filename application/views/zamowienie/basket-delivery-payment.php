<style>
    
    @media only screen and (min-width: 769px) {
        .paczkomat-floating-div {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            padding: 10px;
            padding-bottom: 70px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            text-align: center;
            z-index: 10000;
        }
    }
   

    @media only screen and (max-width: 768px) {
        .paczkomat-floating-div {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            padding: 10px;
            padding-bottom: 70px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            text-align: center;
            z-index: 10000;
        }
    }
</style>

<div class="col-sm-12">                           
    <div class="my-header">
        <img src="<?= (new CustomElementModel('16'))->getField('sposob dostawy grafika'); ?>">
        <h2 class="naglowek-do-menu-select"><?= (new CustomElementModel('16'))->getField('sposob dostawy etykieta'); ?></h2>
        <div class="form-group">
            <select onchange="setPrice(this.options[this.selectedIndex].getAttribute('price')); showGeoWidget();" name="delivery" id="delivery" required="true" class="form-control delivery-payment">
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

            <div class="paczkomat-floating-div" id="paczkomatDiv">
                <link rel="stylesheet" href="https://geowidget.inpost.pl/inpost-geowidget.css" />
                <script src='https://geowidget.inpost.pl/inpost-geowidget.js' defer></script>
                <script>
                </script>
                <inpost-geowidget onpoint="onpointselect"
                token='eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJzQlpXVzFNZzVlQnpDYU1XU3JvTlBjRWFveFpXcW9Ua2FuZVB3X291LWxvIn0.eyJleHAiOjIwMzIzNDU2OTAsImlhdCI6MTcxNjk4NTY5MCwianRpIjoiOWY4YjI0YmYtYWM5NS00Y2M0LWI4MzYtYzA2N2FiYTRkOTM3IiwiaXNzIjoiaHR0cHM6Ly9sb2dpbi5pbnBvc3QucGwvYXV0aC9yZWFsbXMvZXh0ZXJuYWwiLCJzdWIiOiJmOjEyNDc1MDUxLTFjMDMtNGU1OS1iYTBjLTJiNDU2OTVlZjUzNTpHaUFLNXpLYktKLUZ5N0ZkMG1leWtpeDAyNktSNVc0VWhIN2xFR2FoU3BLOF9hQ0lIeVRwdkRsR3ZrQkxsV193IiwidHlwIjoiQmVhcmVyIiwiYXpwIjoic2hpcHgiLCJzZXNzaW9uX3N0YXRlIjoiNmFlMjFiYTYtMzI1OC00YzVjLWJhMjEtMWRhZWIyMmE1MTkxIiwic2NvcGUiOiJvcGVuaWQgYXBpOmFwaXBvaW50cyIsInNpZCI6IjZhZTIxYmE2LTMyNTgtNGM1Yy1iYTIxLTFkYWViMjJhNTE5MSIsImFsbG93ZWRfcmVmZXJyZXJzIjoiMTI3LjAuMC4xLCouZHVvdGVzdC5wbCIsInV1aWQiOiJmNGM2ZWEyMS1lM2VjLTRjZTYtODc0YS1hYzFhOTZiOTkwMzcifQ.ROiFdkN-KN7ryvI3CeYkcbpFfCLQHd2V20t9ILfMlePXT_3y7VRm92l-neuBU1zc8G_jBehPAQ_Af5xnxyfYyTTGHPJPwCcKcDY7_FyatrL7TBaDrokWTIjvhYgIzl5ViM9DG3TzNjBTDAw1jRN0LN460LZ76Nj7IfNa5qMNTCrzeG3r3i3lrelxsqeFZTAby0E40Ij31kMJLrtcl0EXTY5WP6bS8aRruVQwUPh0NnISB84gKqmPzRoGKUV1wGy0yGaxmssVs9eIWLZ9KG-se7l5ZeWR_U-8Go49u3cr7hAMppJGUGKb84Ue0JmTxkhB5QoGaD7xwfAUw6a8nE9VtQ'
                language='pl' config='parcelcollect'></inpost-geowidget>
                <button class="przycisk" onclick="hideGeoWidget();" >Zamknij</button>
            </div>

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

    function showGeoWidget(){
        var deliveryId = $("#delivery").val();

        if(deliveryId == 39){
            $('#paczkomatDiv').fadeIn();
        }
    }

    document.addEventListener('onpointselect', (event) => onPaczkomatPointSelect(event));
    function onPaczkomatPointSelect(event){
        $("#locker").val(event.detail.name);

        hideGeoWidget();
    }

    function hideGeoWidget(){
        if($("#locker").val() === ""){
            $("#delivery").val("");
        }

        $('#paczkomatDiv').fadeOut();
    }
  
</script>
    
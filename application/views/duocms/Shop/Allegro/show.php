<?php
if (empty($allegro_session)) {
    ?>
    <div class="alert alert-info">
        Dodawanie produktów wymaga autoryzacji allegro. 
        Aby pozyskać autoryzację<br> <a href="<?= $allegro_login_link; ?>" class="btn btn-warning">Zaloguj się</a>
    </div>
    <?php
} else {
    if (!empty($offer->validation) && !empty($offer->validation->errors)) {
        foreach ($offer->validation->errors as $error) {
            ?>
            <div class="alert alert-danger">
                <?= $error->message; ?>
            </div>
            <?php
        }
    } else {
        if(!empty($offer->publication->status) && $offer->publication->status == "ACTIVE"){
            ?>
<div class="alert alert-success">
    Twoja aukcja jest aktywna
</div>
<?php
        } else {
            ?>
<div class="alert alert-warning">
    Twoja aukcja jest nieaktywna, aktywacja może chwilę potrwać, poczekaj chwilę i odśwież stronę lub 
    <a href="">Odśwież teraz</a>
</div>
<?php
        }
    }
    if (!empty($offer->validation) && !empty($offer->validation->errors)) {
    ?>
<form method="POST">
            <p><strong>Id kategorii allegro: <?= $allegro_category_id;?></strong></p>
            <input type="hidden" name="id" value="<?= $offer->id;?>" />
            <input type="hidden" name="category[id]" value="<?= $allegro_category_id;?>" />
            <p><strong>Nazwa</strong></p>
            <input type="text" name="name" value="<?= !empty($offer->name) ? $offer->name : '';?>" class="form-control" />
            <p><strong>Opis</strong></p>
            <input type="hidden" name="description[sections][0][items][0][type]" value="TEXT" />
            <textarea name="description[sections][0][items][0][content]" class="form-control" rows="15"><?= !empty($offer->description->sections[0]->items[0]->content) ? $offer->description->sections[0]->items[0]->content : '';?></textarea>
            <?php
            if(!empty($fields)){
                $p = 0;
                foreach($fields as $field){
                    if(!$field->required){
                        continue;
                    }
                    $fval = '';
                    //automatyczne uzupełnianie pól
                    switch ($field->id) {
                    case 1:
                        $fval = $product_translation->name;
                        break;
                    case 2:
                        $fval = $allegro_category_id;
                        break;
                    case 9:
                        $fval = 228;
                        break;
                    case 20634:
                        $fval = 1;
                        break;
                    case 24:
                        $fval = $product_translation->body;
                        break;
                    }
                    $tmp_parameters = array();
                    if(!empty($offer->parameters)){
                        foreach($offer->parameters as $tmp_par){
                            $tmp_parameters[$tmp_par->id]['values'] = $tmp_par->values;
                            $tmp_parameters[$tmp_par->id]['valuesIds'] = $tmp_par->valuesIds;
                        }
                    }
                    
                    if(!empty($tmp_parameters[$field->id])){
                        $fval = empty($tmp_parameters[$field->id]['values']) ? $tmp_parameters[$field->id]['valuesIds'][0] : $tmp_parameters[$field->id]['values'][0];
                    }
                   ?>
            <p>
                <strong>
                   <?= $field->id;?>. <?= $field->name;?> <?= $field->required ? '(Wymagane)' : '';?> 
                </strong>
            </p>
            <input type="hidden" name="parameters[<?= $p;?>][id]" value="<?= $field->id;?>" />
            <input type="hidden" name="parameters[<?= $p;?>][rangeValue]" value="null" />
            <?php
                   switch ($field->type) :
                   case 'string':
                       ?>
            <input type="text" name="parameters[<?= $p;?>][values]" value="<?= $fval;?>" placeholder="" class="form-control" />
            <?php
                       break;
                   case 'integer':
                       ?>
            <input type="number" name="parameters[<?= $p;?>][values][]" value="<?= $fval;?>" placeholder="" class="form-control" />
            <?php
                       break;
                   case 'float':
                       ?>
            <input type="number" step="0.01" name="parameters[<?= $p;?>][values]" value="<?= $fval;?>" placeholder="" class="form-control" />
            <?php
                       break;
                   case 'dictionary':
                       ?>
            <select name="parameters[<?= $p;?>][valuesIds][]" class="form-control">
                <?php
                if(!$field->required){
                    ?>
                <option value="">- wybierz -</option>
                <?php
                }
                if(!empty($field->dictionary)){
                    foreach ($field->dictionary as $key=>$opt){
                        ?>
                <option value="<?= $opt->id;?>" <?= ($opt->id == $fval) ? 'selected' : '';?>><?= $opt->value;?></option>
                <?php
                    }
                }
                ?>
            </select>
            <?php
                       break;
                   endswitch;
            ?>
            <?php
            $p++;
                }
                ?>
            <div class="form-group">
                <p>Zdjęcia (pierwsze główne)</p>
                <?php
                if(!empty($uploaded_photos)){
                    foreach($uploaded_photos as $ph){
                        ?>
                <input type="hidden" name="images[][url]" value="<?= $ph->location;?>" />
                <?= $ph->location;?><br>
                <?php
                    }
                }
                ?>
            </div>
            <div class="form-group">
                <select name="sellingMode[format]">
                    <option value="BUY_NOW">Kup teraz</option>
                </select>
            </div>
            <div class="form-group">
                <p>Cena</p>
                <input type="number" step="0.01" name="sellingMode[price][amount]" value="<?= !empty($offer->sellingMode->price->amount) ? $offer->sellingMode->price->amount : '0';?>" />
                <input type="hidden" name="sellingMode[price][currency]" value="PLN" />
                
            </div>
            <div class="form-group">
                <p>Dostępna ilość</p>
                <input type="number" name="stock[available]" value="<?= empty($offer->stock->available) ? '' : $offer->stock->available;?>"/>
                <input type="hidden" name="stock[unit]" value="UNIT" />
            </div>
            <div class="form-group">
                <p>Czas trwania</p>
                <select name="publication[duration]" class="form-control">
                    <option value="P3D" <?= $offer->publication->duration == "PT72H" ? 'selected' : '';?>>3 dni</option>
                    <option value="P5D" <?= $offer->publication->duration == "PT120H" ? 'selected' : '';?>>5 dni</option>
                    <option value="P7D" <?= $offer->publication->duration == "PT168H" ? 'selected' : '';?>>7 dni</option>
                    <option value="P10D" <?= $offer->publication->duration == "PT240H" ? 'selected' : '';?>>10 dni</option>
                    <option value="P20D" <?= $offer->publication->duration == "PT480H" ? 'selected' : '';?>>20 dni</option>
                    <option value="null" <?= $offer->publication->duration == "null" ? 'selected' : '';?>>Do wyczerpania zapasów</option>
                </select>
            </div>

                <input type="hidden" name="publication[status]" value="ACTIVE">

            <div class="form-group">
                <p>Dostawa</p>
                <?php
                $shippings = json_decode($shipping_rates);
                ?>
                <select name="delivery[shippingRates][id]" class="form-control">
                    <?php
                    
                    if(!empty($shippings->shippingRates[0]->id)){
                        foreach($shippings->shippingRates as $ship){
                            ?>
                    <option value="<?= $ship->id;?>" <?= $offer->delivery->shippingRates->id == $ship->id ? 'selected' : '';?>><?= $ship->name;?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
                <p>Wysyłka w ciągu</p>
                <select name="delivery[handlingTime]" class="form-control">
                    <option value="PT24H" <?= $offer->delivery->handlingTime == "PT24H" ? 'selected' : '';?>>24 godziny</option>
                    <option value="P2D" <?= $offer->delivery->handlingTime == "PT48H" ? 'selected' : '';?>>2 dni</option>
                    <option value="P7D" <?= $offer->delivery->handlingTime == "PT168H" ? 'selected' : '';?>>7 dni</option>
                    <option value="P14D" <?= $offer->delivery->handlingTime == "PT336H" ? 'selected' : '';?>>14 dni</option>
                </select>
            </div>
            <div class="form-group">
                <p>Płatność</p>
                <select name="payments[invoice]" class="form-control">
                    <option value="VAT" <?= $offer->payments->invoice == "VAT" ? 'selected' : '';?>>Faktura VAT</option>
                    <option value="VAT_MARGIN" <?= $offer->payments->invoice == "VAT_MARGIN" ? 'selected' : '';?>>Vat marża</option>
                    <option value="WITHOUT_VAT" <?= $offer->payments->invoice == "WITHOUT_VAT" ? 'selected' : '';?>>Faktura bez vat</option>
                    <option value="NO_INVOICE" <?= $offer->payments->invoice == "NO_INVOICE" ? 'selected' : '';?>>Nie wystawiam faktury</option>
                </select>
            </div>
            <div class="form-group">
                <input type="hidden" name="afterSalesServices[impliedWarranty][id]" value="<?= $offer->afterSalesServices->impliedWarranty->id;?>" />
                <input type="hidden" name="afterSalesServices[returnPolicy][id]" value="<?= $offer->afterSalesServices->returnPolicy->id;?>" />
            </div>
            <div class="form-group">
                <p>Lokalizacja</p>
                <input type="hidden" name="location[countryCode]" value="PL" />
                <input type="text" name="location[province]" value="<?= $offer->location->province;?>" />
                <input type="text" name="location[city]" value="<?= $offer->location->city;?>" />
            </div>
            <input type="hidden" name="createdAt" value="<?= $offer->createdAt;?>" />
            <input type="hidden" name="updatedAt" value="<?= $offer->updatedAt;?>" />
            
            <div class="form-group">
                <input type="submit" value="Aktualizuj aukcję" class="btn btn-success" />
            </div>
            <?php
            } else {
                echo 'Prawdopodobnie kategoria nie jest spięta z kategorią allegro.';
            }
            ?>
            
        </form>
<?php
    }

}

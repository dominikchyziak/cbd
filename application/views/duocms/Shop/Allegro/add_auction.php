<?php $this->load->view('duocms/Shop/menu'); ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12">
                <strong>Nowa aukcja</strong>
                <a href="<?php echo site_url('duocms/allegro/product/' . $product_id); ?>" class="btn btn-primary pull-right">< powrót do listy</a>
            </div>
        </div>        
    </div>
    <div class="panel-body">
        <?php
        if (empty($allegro_session)) {
            ?>
            <div class="alert alert-info">
                Dodawanie produktów wymaga autoryzacji allegro. 
                Aby pozyskać autoryzację<br> <a href="<?= $allegro_login_link; ?>" class="btn btn-warning">Zaloguj się</a>
            </div>
            <?php
        } else {
            if (!empty($error)) {
                ?>
                <div class="col-sm-12">
                    <div class="alert alert-danger">
                        <?= $error; ?>
                    </div>
                </div>

                <?php
            }
            ?>

            <form method="POST">
                <div class="col-sm-12">
                <p><strong>Id kategorii allegro: <?= $allegro_category_id; ?></strong></p>
                <input type="hidden" name="category[id]" value="<?= $allegro_category_id; ?>" />
                <p><strong>Nazwa</strong></p>
                <input type="text" name="name" value="<?= empty($_POST['name']) ? $product_translation->name : $_POST['name']; ?>" class="form-control" />
                <p><strong>Opis</strong>(w celu zmiany kolejności zdjęć zmień kolejność zdjęć w produkcie)</p>
                </div>
                <div class="row">
                    <div class="col-sm-6" style="height:300px;">
                    <input type="hidden" name="description[sections][0][items][0][type]" value="TEXT" />
                        <textarea name="description[sections][0][items][0][content]" class="form-control" rows="12">
                            <?= empty($_POST['description']['sections'][0]['items'][0]['content']) ? strip_tags($product_translation->body, '<p><ul><li><h1><h2>') : $_POST['description']['sections'][0]['items'][0]['content']; ?>
                        </textarea>
                    </div>
                    <div class="col-sm-6" style="height:300px;">
                        <img src="<?= $uploaded_photos[0]->location; ?>" style="max-width: 100%; max-height: 95%;"/>
                    </div>
                    <div style="clear: both;"></div>
                    <div class="col-sm-12">
                    <hr>
                    </div>
                    <div class="col-sm-6" style="height:300px;">
                        <img src="<?= $uploaded_photos[1]->location; ?>" style="max-width: 100%; max-height: 95%;"/>
                    </div>
                    <div class="col-sm-6" style="height:300px;">
                        <input type="hidden" name="description[sections][1][items][0][type]" value="TEXT" />
                        <textarea name="description[sections][1][items][0][content]" class="form-control" rows="12">
                            <?= empty($_POST['description']['sections'][0]['items'][0]['content']) ? strip_tags($product_translation->body, '<p><ul><li><h1><h2>') : $_POST['description']['sections'][0]['items'][0]['content']; ?>
                    </textarea>
                    </div>
                        <div style="clear: both;"></div>
                    <div class="col-sm-12">
                    <hr>
                    </div>
                    <div class="col-sm-6" style="height:300px;">
                    <input type="hidden" name="description[sections][2][items][0][type]" value="TEXT" />
                        <textarea name="description[sections][2][items][0][content]" class="form-control" rows="12">
                            <?= empty($_POST['description']['sections'][0]['items'][0]['content']) ? strip_tags($product_translation->body, '<p><ul><li><h1><h2>') : $_POST['description']['sections'][0]['items'][0]['content']; ?>
                        </textarea>
                    </div>
                    <div class="col-sm-6" style="height:300px;">
                        <img src="<?= $uploaded_photos[2]->location; ?>" style="max-width: 100%; max-height: 95%;"/>
                    </div>
                    <div style="clear: both;"></div>
                    <div class="col-sm-12">
                    <hr>
                    </div>
                    <?php for($j=3;$j<(count($uploaded_photos)-1);$j++):
                        ?>
                    <div class="col-sm-12 text-center" style="height:300px;">
                                <img src="<?= $uploaded_photos[$j]->location; ?>" style="max-width: 100%; max-height: 95%;"/>
                            </div>
                            <div style="clear: both;"></div>
                            <div class="col-sm-12">
                                <hr>
                            </div>  
                            <?php
                    endfor; ?>
                    <div class="col-sm-6" style="height:300px;">
                        <input type="hidden" name="description[sections][<?= count($uploaded_photos)-1;?>][items][0][type]" value="TEXT" />
                        <textarea name="description[sections][<?= count($uploaded_photos)-1;?>][items][0][content]" class="form-control" rows="12">
                            <?= empty($_POST['description']['sections'][0]['items'][0]['content']) ? strip_tags($product_translation->body, '<p><ul><li><h1><h2>') : $_POST['description']['sections'][0]['items'][0]['content']; ?>
                        </textarea>
                    </div>
                    <div class="col-sm-6" style="height:300px;">
                        <img src="<?= $uploaded_photos[count($uploaded_photos)-1]->location; ?>" style="max-width: 100%; max-height: 95%;"/>
                    </div>
                    <div style="clear: both;"></div>
                    <div class="col-sm-12">
                    <hr>
                    </div>
                </div>

                <div style="clear: both;"></div>
                <div class="col-sm-12">
                <?php
                if (!empty($fields)) {
                    $p = 0;
                    foreach ($fields as $field) {
                        if (!$field->required) {
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
                        if (!empty($_POST[$field->id])) {
                            $fval = $_POST[$field->id];
                        }
                        if (!empty($field->restrictions->max)) {
                            $fmin = $field->restrictions->min;
                            $fmax = $field->restrictions->max;
                            $fval = round(($fmax - $fmin) / 2);
                        }
                        ?>
                        <p>
                            <strong>
                                <?= $field->id; ?>. <?= $field->name; ?> <?= $field->required ? '(Wymagane)' : ''; ?> 
                            </strong>
                        </p>
                        <input type="hidden" name="parameters[<?= $p; ?>][id]" value="<?= $field->id; ?>" />
                        <input type="hidden" name="parameters[<?= $p; ?>][rangeValue]" value="null" />
                        <?php
                        switch ($field->type) :
                            case 'string':
                                ?>
                                <input type="text" name="parameters[<?= $p; ?>][values][]" value="<?= $fval; ?>" placeholder="" class="form-control" />
                                <?php
                                break;
                            case 'integer':
                                ?>
                                <input type="number" name="parameters[<?= $p; ?>][values][]" value="<?= $fval; ?>" placeholder="" class="form-control" />
                                <?php
                                break;
                            case 'float':
                                ?>
                                <input type="number" step="0.01" name="parameters[<?= $p; ?>][values][]" value="<?= $fval; ?>" placeholder="" class="form-control" />
                                <?php
                                break;
                            case 'dictionary':
                                ?>
                                <select name="parameters[<?= $p; ?>][valuesIds][]" class="form-control">
                                    <?php
                                    if (!$field->required) {
                                        ?>
                                        <option value="">- wybierz -</option>
                                        <?php
                                    }
                                    if (!empty($field->dictionary)) {
                                        foreach ($field->dictionary as $key => $opt) {
                                            ?>
                                            <option value="<?= $opt->id; ?>" <?= ($opt->id == $fval) ? 'selected' : ''; ?>><?= $opt->value; ?></option>
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
                        if (!empty($uploaded_photos)) {
                            foreach ($uploaded_photos as $ph) {
                                ?>
                                <input type="hidden" name="images[][url]" value="<?= $ph->location; ?>" />
                                <?= $ph->location; ?><br>
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
                        <input type="number" name="sellingMode[price][amount]" value="<?= empty($_POST['sellingMode']['price']['amount']) ? round($product->price * 100) / 100 : $_POST['sellingMode']['price']['amount']; ?>" />
                        <input type="hidden" name="sellingMode[price][currency]" value="PLN" />

                    </div>
                    <div class="form-group">
                        <p>Dostępna ilość</p>
                        <input type="number" name="stock[available]" value="<?= empty($_POST['stock']['available']) ? ($product->quantity == -1 || $product->quantity == 0 ? 1 : $product->quantity) : $_POST['stock']['available']; ?>"/>
                        <input type="hidden" name="stock[unit]" value="UNIT" />
                    </div>
                    <div class="form-group">
                        <p>Czas trwania</p>
                        <select name="publication[duration]" class="form-control">
                            <option value="P10D">10 dni</option>
                            <option value="null">Do wyczerpania zapasów</option>
                            <option value="P3D">3 dni</option>
                            <option value="P5D">5 dni</option>
                            <option value="P7D">7 dni</option>
                            <option value="P20D">20 dni</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <p>Status oferty</p>
                        <select name="publication[status]" class="form-control">
                            <option value="ACTIVE">Aktywna</option>
                            <option value="INACTIVE">Nie aktywna</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <p>Dostawa</p>
                        <?php
                        $shippings = json_decode($shipping_rates);
                        ?>
                        <select name="delivery[shippingRates][id]" class="form-control">
                            <?php
                            if (!empty($shippings->shippingRates[0]->id)) {
                                foreach ($shippings->shippingRates as $ship) {
                                    ?>
                                    <option value="<?= $ship->id; ?>"><?= $ship->name; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <p>Wysyłka w ciągu</p>
                        <select name="delivery[handlingTime]" class="form-control">
                            <option value="P2D">2 dni</option>
                            <option value="PT24H">24 godziny</option>
                            <option value="P7D">7 dni</option>
                            <option value="P14D">14 dni</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <p>Płatność</p>
                        <select name="payments[invoice]" class="form-control">
                            <option value="VAT">Faktura VAT</option>
                            <option value="VAT_MARGIN">Vat marża</option>
                            <option value="WITHOUT_VAT">Faktura bez vat</option>
                            <option value="NO_INVOICE">Nie wystawiam faktury</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <?php $warranty = json_decode($impliedWarranty)->impliedWarranties; ?>
                        <p>Gwarancja</p>
                        <select name="afterSalesServices[impliedWarranty][id]" class="form-control">
                            <?php foreach ($warranty as $war): ?>
                                <option value="<?= $war->id; ?>"><?= $war->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p>Polityka zwrotów</p>
                        <?php $returnPol = json_decode($returnPolicy)->returnPolicies; ?>
                        <select name="afterSalesServices[returnPolicy][id]" class="form-control">
                            <?php foreach ($returnPol as $rp): ?>
                                <option value="<?= $rp->id; ?>"><?= $rp->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <p>Lokalizacja</p>
                        <input type="hidden" name="location[countryCode]" value="PL" />
                        <input type="text" name="location[province]" value="<?= get_option('admin_modules_allegro_woj'); ?>" />
                        <input type="text" name="location[city]" value="<?= get_option('admin_modules_allegro_city'); ?>" />
                        <input type="text" name="location[postCode]" value="<?= get_option('admin_modules_allegro_zipcode'); ?>" />
                    </div>
                    <input type="hidden" name="createdAt" value="<?= date('Y-m-d') . 'T' . date('H:i:s') . 'Z'; ?>" />
                    <input type="hidden" name="updatedAt" value="<?= date('Y-m-d') . 'T' . date('H:i:s') . 'Z'; ?>" />

                    <div class="form-group">
                        <input type="submit" value="Dodaj aukcję" class="btn btn-success" />
                    </div>
                    <?php
                } else {
                    echo 'Prawdopodobnie kategoria nie jest spięta z kategorią allegro.';
                }
                ?>
</div>
            </form>
        <?php } ?>
    </div>
</div>
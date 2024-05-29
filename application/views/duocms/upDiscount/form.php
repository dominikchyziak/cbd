<div class="col-sm-12">
    <h2>Dodawanie specjalnej ceny</h2>
    <p>
        <a href="<?= site_url('duocms/UserProductDiscount/index');?>" class="btn btn-primary">< Powrót</a>
    </p>
</div>
<form method="post">
    <div class="ui-tabs" style="float:left; width:100%;">
        <ul>
            <li><a href="#formularz">Formularz</a></li>
        </ul>
        <div id="formularz">
            <?= validation_errors('<div class="alert alert-danger">', '</div>');?>
            <input type="hidden" name="discountId" value="<?= !empty($discount->getId()) ? $discount->getId() : '';?>" />
            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="user">Użytkownik</label>
                    <select name="user" class="form-control">
                        <?php
                            if (!empty($users)) {
                                foreach ($users as $user) {
                                    $selected = '';
                                    if (!empty($discount->getUserId())) {
                                        if ($discount->getUserId() == $user->id) {
                                            $selected = 'selected';
                                        }
                                    }
                                    echo "<option value='$user->id' $selected>$user->email</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="product">Produkt</label>
                    <select name="product" class="form-control">
                        <?php
                        if (!empty($products)) {
                            foreach ($products as $product) {
                                $selected = '';
                                if (!empty($discount->getProductId())) {
                                    if ($discount->getProductId() == $product->id) {
                                        $selected = 'selected';
                                    }
                                }
                                echo "<option value='$product->id' $selected>$product->name | $product->price zł</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-12 col-md-12">
                <div class="row">
                    <div class="col-sm-12 col-md-7 col-lg-9">
                        <div class="form-group">
                            <label for="price">Cena(PLN)</label>
                            <input type="number" name="price" value="<?= !empty($discount->getPrice()) ? $discount->getPrice() : '';?>"  class="form-control"/>
                        </div>
                    </div>
                </div>
            </div>
            <?php /*  <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="discount">Rabat</label>
                <select name="discount" class="form-control">
                    <option value="0">Brak</option>
                    <?php
                    if(!empty($rebate_groups)){
                        foreach($rebate_groups as $r_g){
                            ?>
                    <option value="<?= $r_g->discount;?>" <?= $r_g->discount == $discount ? 'selected="true"' : '';?>>
                            <?= $r_g->name;?> <?= $r_g->discount;?> %
                    </option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div> */ ?>
            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <input type="submit" name="submit" value="<?= !empty($discount->getId()) ? 'Aktualizuj' : 'Dodaj specjaną cenę';?>" class="btn-primary btn" />
                </div>
            </div>
        </div>
    </div>
</form>



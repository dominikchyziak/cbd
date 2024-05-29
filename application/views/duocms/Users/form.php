<div class="col-sm-12">
    <h2><?= !empty($id) ? 'Edycja' : 'Dodawanie';?> użytkownika</h2>
    <p>
        <a href="<?= site_url('duocms/users/index');?>" class="btn btn-primary">< Powrót</a>
    </p>
</div>
<form method="POST">
    <div class="ui-tabs" style="float:left; width:100%;">
    <ul>
        <li><a href="#formularz">Formularz</a></li>
    </ul>
    <div id="formularz">
        <?= validation_errors('<div class="alert alert-danger">', '</div>');?>
        <input type="hidden" name="user_id" value="<?= !empty($user->id) ? $user->id : '0';?>" />
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" value="<?= !empty($email) ? $email : ''?>" required="true" class="form-control"/>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="type">Typ</label>
                <select name="type" class="form-control">
                    <option value="0">Administrator</option>
                    <option value="1" <?= !empty($type) ? 'selected="true"' : ''?>>Zwykły</option>
                </select>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="password">Hasło</label>
                <input type="password" name="password" value=""  class="form-control"/>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="password_repeat">Powtórz hasło</label>
                <input type="password" name="password_repeat" value=""  class="form-control"/>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="first_name">Imię</label>
                <input type="text" name="first_name" value="<?= !empty($first_name) ? $first_name : ''?>" class="form-control"/>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="last_name">Nazwisko</label>
                <input type="text" name="last_name" value="<?= !empty($last_name) ? $last_name : ''?>"  class="form-control"/>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="row">
                <div class="col-sm-12 col-md-7 col-lg-9">
                    <div class="form-group">
                        <label for="address">Ulica</label>
                        <input type="text" name="street" value="<?= !empty($street) ? $street : ''?>"  class="form-control"/>
                    </div>
                </div>
                <div class="col-sm-12 col-md-5 col-lg-3">
                    <div class="form-group">
                        <label for="address">Nr</label>
                        <input type="text" name="building_number" value="<?= !empty($building_number) ? $building_number : ''?>"  class="form-control"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="phone">Telefon</label>
                <input type="text" name="phone" value="<?= !empty($phone) ? $phone : ''?>"  class="form-control"/>
            </div>
        </div>
        
        <div class="col-sm-12 col-md-6">
            <div class="row">
                <div class="col-sm-12 col-md-5 col-lg-3">
                    <div class="form-group">
                        <label for="address">Kod pocztowy</label>
                        <input type="text" name="zip_code" value="<?= !empty($zip_code) ? $zip_code : ''?>"  class="form-control"/>
                    </div>
                </div>
                <div class="col-sm-12 col-md-7 col-lg-9">
                    <div class="form-group">
                        <label for="address">Miasto</label>
                        <input type="text" name="city" value="<?= !empty($city) ? $city : ''?>"  class="form-control"/>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control">
                    <option value="0">Aktywny</option>
                    <option value="1" <?= !empty($status) ? 'selected="true"' : ''?>>Nie aktywny</option>
                </select>
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
                <input type="submit" name="submit" value="<?= !empty($id) ? 'Aktualizuj' : 'Dodaj';?> użytkownika" class="btn-primary btn" />
            </div>
        </div>
    </div>
    </div>
</form>

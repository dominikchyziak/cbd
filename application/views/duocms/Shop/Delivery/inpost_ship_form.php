<?= form_open(site_url('duocms/delivery/add_inpost_ship/'.$order_id));?>
<div class="col-sm-12">
    <div style="    float: left;
    width: 100%;
    background: white;
    padding: 15px;">
    <h4>Odbiorca</h4>
    <div class="form-group">
        <?= form_input(array(
            'name' => 'receiver[first_name]',
            'class' =>'form-control',
            'placeholder' => 'Imię',
            'value' => $data['receiver']['first_name']
        ));?>
    </div>
    <div class="form-group">
        <?= form_input(array(
            'name' => 'receiver[last_name]',
            'class' =>'form-control',
            'placeholder' => 'Nazwisko',
            'value' => $data['receiver']['last_name']
        ));?>
    </div>
    <div class="form-group">
        <?= form_input(array(
            'name' => 'receiver[phone]',
            'class' =>'form-control',
            'placeholder' => 'Telefon',
            'value' => $data['receiver']['phone']
        ));?>
    </div>
    <div class="form-group">
        <?= form_input(array(
            'name' => 'receiver[email]',
            'class' =>'form-control',
            'placeholder' => 'Email',
            'value' => $data['receiver']['email']
        ));?>
    </div>
    <div class="form-group">
        <?= form_input(array(
            'name' => 'receiver[address][street]',
            'class' =>'form-control',
            'placeholder' => 'Ulica',
            'value' => $data['receiver']['address']['street']
        ));?>
    </div>
    <div class="form-group">
        <?= form_input(array(
            'name' => 'receiver[address][building_number]',
            'class' =>'form-control',
            'placeholder' => 'Numer budynku',
            'value' => $data['receiver']['address']['building_number']
        ));?>
    </div>
    <div class="form-group">
        <?= form_input(array(
            'name' => 'receiver[address][city]',
            'class' =>'form-control',
            'placeholder' => 'Miasto',
            'value' => $data['receiver']['address']['city']
        ));?>
    </div>
    <div class="form-group">
        <?= form_input(array(
            'name' => 'receiver[address][post_code]',
            'class' =>'form-control',
            'placeholder' => 'Ulica',
            'value' => $data['receiver']['address']['post_code']
        ));?>
    </div>
    <div class="form-group">
        <?= form_input(array(
            'name' => 'receiver[address][country_code]',
            'class' =>'form-control',
            'placeholder' => 'Ulica',
            'value' => empty($data['receiver']['address']['country_code']) ? 'PL' : $data['receiver']['address']['country_code']
        ));?>
    </div>
    <h4>Parametry przeszyłki</h4>
    <div class="form-group">
        <?= form_hidden(
            'parcels[0][id]',
            //'class' =>'form-control',
            //'placeholder' => 'Id paczki',
             $data['parcels'][0]['id']
        );?>
    </div>
    <div class="form-group">
        <?= form_hidden(
            'parcels[0][template]',
            //'class' =>'form-control',
            //'placeholder' => 'Szablon',
             $data['parcels'][0]['template']
        );?>
    </div>
    <div class="form-group">
        <?php /* form_input(array(
            'name' => 'parcels[0][dimensions][length]',
            'class' =>'form-control',
            'placeholder' => 'Długość',
            'value' => $data['parcels'][0]['dimensions']['length']
        ));?>
    </div>
    <div class="form-group">
        <?= form_input(array(
            'name' => 'parcels[0][dimensions][width]',
            'class' =>'form-control',
            'placeholder' => 'Szerokość',
            'value' => $data['parcels'][0]['dimensions']['width']
        ));?>
    </div>
    <div class="form-group">
        <?= form_input(array(
            'name' => 'parcels[0][dimensions][height]',
            'class' =>'form-control',
            'placeholder' => 'Wysokość',
            'value' => $data['parcels'][0]['dimensions']['height']
        ));?>
    </div>
    <div class="form-group">
        <?= form_input(array(
            'name' => 'parcels[0][dimensions][unit]',
            'class' =>'form-control',
            'placeholder' => 'Jednostka wymiaru',
            'value' => $data['parcels'][0]['dimensions']['unit']
        ));*/?>
    </div>
    <div class="form-group">
        <table class="table table-striped">
            <thead>
                <th>Lp</th>
                <th>Zawartosc</th>
                <th>Waga</th>
            </thead>
            <tbody>
                <?php 
                $i = -1; $this->load->model("ProductModel");
                $this->load->model("ProductTranslationModel"); 
                $pobj = new ProductTranslationModel(); 
                foreach($data['parcels'] as $par): ?>
                <tr>
                    <td><?= ($i++)+2;?></td>
                    <td><?php
                        $pdata = $parcels_data[$i];
                        foreach ($pdata as $key => $pd) {
                            if($key == 'total_weight'){ continue; }
                            echo $pd. 'x '.$pobj->findByProductAndLang((new ProductModel($key)), LANG)->name;
                        }
                    ?></td>
                    <td><?= $par['weight']['amount']; ?></td>
                </tr>
                <?php
                endforeach;
                ?>
            </tbody>
        </table>
    </div>
<!--    <div class="form-group">
        <?= form_input(array(
            'name' => 'parcels[0][weight][amount]',
            'class' =>'form-control',
            'placeholder' => 'Waga',
            'value' => $data['parcels'][0]['weight']['amount']
        ));?>
    </div>
    <div class="form-group">
        <?= form_input(array(
            'name' => 'parcels[0][weight][unit]',
            'class' =>'form-control',
            'placeholder' => 'Jednostka wagi',
            'value' => $data['parcels'][0]['weight']['unit']
        ));?>
    </div>-->
    <?php if(!empty($data['custom_attributes']['target_point'])){ ?>
    <div class="form-group">
        <p>Paczkomat</p>
        <?= form_input(array(
            'name' => 'custom_attributes[target_point]',
            'class' =>'form-control',
            'placeholder' => 'Punkt docelowy',
            'value' => $data['custom_attributes']['target_point']
        ));?>
    </div>
    <?php } ?>
    <div class="form-group">
        <?php /* form_input(array(
            'name' => 'insurance[amount]',
            'class' =>'form-control',
            'placeholder' => 'Kwota ubezpieczenia',
            'value' => $data['insurance']['amount']
        ));?>
    </div>
    <div class="form-group">
        <?= form_input(array(
            'name' => 'insurance[currency]',
            'class' =>'form-control',
            'placeholder' => 'Waluta',
            'value' => $data['insurance']['currency']
        ));?>
    </div> */ ?>
    <div class="form-group">
    <?php
        if(!empty($data['cod']['amount'])){
            echo '<p>Pobranie</p>';
            echo form_input(array(
                'name' => 'cod[amount]',
                'class' =>'form-control',
                'placeholder' => 'Wartość pobrania',
                'value' => $data['cod']['amount']
            ));
            echo '<p>Ubezpieczenie, (min wartość przesyłki)</p>';
            echo form_input(array(
                'name' => 'insurance[amount]',
                'class' =>'form-control',
                'placeholder' => 'Wartość pobrania',
                'value' => $data['insurance']['amount']
            ));
        }
    ?>
    </div><?php /*
    <div class="form-group">
        <?= form_input(array(
            'name' => 'cod[currency]',
            'class' =>'form-control',
            'placeholder' => 'Waluta',
            'value' => $data['cod']['currency']
        ));*/?>
    </div>
    <div class="form-group">
        <?= form_hidden(
             'service',
            //'class' =>'form-control',
            //'placeholder' => 'Usługa',
             $data['service']
        );?>
    </div>
    <div class="form-group">
        <input type="submit" value="Dodaj przesyłkę" class="btn btn-primary" />
    </div>
    </div>
</div>
<?= form_close();?>
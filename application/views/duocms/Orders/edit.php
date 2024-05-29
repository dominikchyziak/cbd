<?php $this->load->view('duocms/Shop/menu'); ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <strong><span id="order-title">Zamówienie nr: <?= $order->id;?></span></strong><a class='pull-right btn btn-info' href="<?=site_url('duocms/orders/show/'.$order->id); ?>" >Powrót</a>
    </div>
    <div class="panel-body">
        <form method="POST">
            <div class='form-group'>
                <label for='first_name'>Imie</label>
                <input name='first_name' type='text' value='<?=$order->first_name; ?>' class='form-control' />
            </div>
            <div class='form-group'>
                <label for='last_name'>Nazwisko</label>
                <input name='last_name' type='text' value='<?=$order->last_name; ?>' class='form-control' />
            </div>
            <div class='form-group'>
                <label for='email'>Email</label>
                <input name='email' type='text' value='<?=$order->email; ?>' class='form-control' />
            </div>
            <div class='form-group'>
                <label for='phone'>Telefon</label>
                <input name='phone' type='text' value='<?=$order->phone; ?>' class='form-control' />
            </div>
            <div class='form-group'>
                <label for='street'>Ulica</label>
                <input name='street' type='text' value='<?=$order->street; ?>' class='form-control' />
            </div>
            <div class='form-group'>
                <label for='building_number'>Nr budynku</label>
                <input name='building_number' type='text' value='<?=$order->building_number; ?>' class='form-control' />
            </div>
            <div class='form-group'>
                <label for='flat_number'>Nr lokalu</label>
                <input name='flat_number' type='text' value='<?=$order->flat_number; ?>' class='form-control' />
            </div>
            <div class='form-group'>
                <label for='zip_code'>Kod pocztowy</label>
                <input name='zip_code' type='text' value='<?=$order->zip_code; ?>' class='form-control' />
            </div>
            <div class='form-group'>
                <label for='city'>Miasto</label>
                <input name='city' type='text' value='<?=$order->city; ?>' class='form-control' />
            </div>
            <input type='submit' value ='Edytuj' class='form-control btn btn-info' />
        </form>
    </div>
</div>


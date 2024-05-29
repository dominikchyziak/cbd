<?php $this->load->view('duocms/Shop/menu'); ?>
<div class="panel panel-default">
    <div class="panel-heading"><strong>Klienci</strong></div>
    <div class="panel-body">
<div class="table-responsive">
    <table class="table table-striped table-hover t1">
        <thead>
            <tr>
                <th>Lp</th>
                <th>E-mail</th>
                <th>Imie</th>
                <th>Nazwisko</th>
                <th>Adres</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(!empty($clients)){
                $i = 1;
                foreach($clients as $order){
                    ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $order->email;?></td>
                <td><?= $order->first_name;?></td>
                <td><?= $order->last_name;?></td>
                <td><?= $order->zip_code;?> <?= $order->city;?> <br><?= $order->street;?></td>
            </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>
</div>
</div>
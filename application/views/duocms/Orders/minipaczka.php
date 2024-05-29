<?php $this->load->view('duocms/Shop/menu');?>
<div class="panel panel-default">
    <div class="panel-heading"><strong>Zamówienia</strong></div>
    <div class="panel-body">
<div class="table-responsive">
    <form method="POST">
    <table class="table table-striped table-hover t1">
        <thead>
            <tr>
                <th>Id</th>
                <th>Dane kontaktowe</th>
                <th>Wartość zam.</th>
                <th>Nr listu przewozowego</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        
            <?php
            if(!empty($orders)){
                foreach($orders as $order){
                    ?>
            <tr>
                <td><a href="<?= site_url('duocms/orders/show/'.$order->id);?>"><?= $order->id;?></a></td>
                <td><a href="<?= site_url('duocms/orders/show/'.$order->id);?>"><?= $order->email;?>
                        <br><?= $order->first_name;?> <?= $order->last_name;?>
                        <br><?= $order->phone;?>
                        <br><?= $order->zip_code;?> <?= $order->city;?><br>
                        <?= $order->street;?> <?= $order->building_number; ?> <?= (!empty($order->flat_number)) ? 'm '.$order->flat_number: '';?>
                    </a></td>
                    
                <td><a href="<?= site_url('duocms/orders/show/'.$order->id);?>"><?= $order->price;?></a></td>
                
                <td>
                    <input type="text" name="inpost_locker[<?=$order->id;?>]" value="<?= $order->inpost_locker; ?>" />
                </td>
                <td><a href="<?= site_url('duocms/orders/show/'.$order->id);?>"><?= (new CustomElementModel('14'))->getField('status '.$order->status);?></a></td>
                
            </tr>
                    <?php
                }
            }
            ?>

        </tbody>
    </table>
        <input type="submit" name="minipaczka" value="Generuj xml do e-nadawcy" class="btn btn-success" />
            </form>
</div>
</div>
</div>
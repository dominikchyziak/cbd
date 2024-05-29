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
                <th>Identyfikator punktu dostawy</th>
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
                    <a href="#" class="a_<?=$order->id;?>" data-id="<?=$order->id;?>" data-spec-name="<?=$order->special_name;?>"> Wybierz punkt</a>
                </td>
                <td><a href="<?= site_url('duocms/orders/show/'.$order->id);?>"><?= (new CustomElementModel('14'))->getField('status '.$order->status);?></a></td>
                
            </tr>
<!--            <tr><td colspan="5">
                <div id="<?= 'div'.$order->id; ?>" class="bp-widget"></div>
                
            </td></tr>-->
                    <?php
                }
            }
            ?>

        </tbody>
    </table>
        <input type="submit" name="bliskapaczka" value="Generuj csv do bliskiejpaczki" class="btn btn-info" />
            </form>
    <div class="bp-widget" id="bpWidget"></div>
</div>
</div>
</div>

<script>
    $(document).ready(function(){
        $('a[class^="a_"]').click(function(){
        var id = $(this).attr('data-id');
        var spec_name = $(this).attr('data-spec-name');
        $('#bpWidget').css('display', 'block');
        var bp = document.getElementById('bpWidget');
        var operators = null;
        switch(spec_name){
            case 'odbior_w_pp':
            case 'odbior_w_pp-pobranie':
                operators = [{ operator: "POCZTA" }];
                break;
            case 'paczka_w_ruchu':
            case 'paczka_w_ruchu-pobranie':
                operators = [{ operator: "RUCH" }];
                break;
        }
        BPWidget.init(bp,
            {
            googleMapApiKey: '<?= get_option('gmap_key'); ?>',
            callback: function(point) {
                $('input[name="inpost_locker['+ id +']"]').val(point.code);
            },
            initialAddress: $('input[name="inpost_locker['+ id +']"]').val(),
            posType: 'DELIVERY',
            operators: operators
    });
        });
    });
</script>
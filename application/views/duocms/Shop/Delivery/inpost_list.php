<?php $this->load->view('duocms/Shop/menu'); ?>
<div class="panel panel-default">
    <div class="panel-heading"><strong>Przesyłki inpost</strong></div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Status</th>
                        <th>Nr śledzenia</th>
                        <th>Odbiorca</th>
                        <th>Akcja</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(!empty($shipments->items)){
                        foreach ($shipments->items as $item){
                            ?>
                    <tr>
                        <td><?= $item->id;?></td>
                        <td><?= $statuses[$item->status];?></td>
                        <td><?= $item->tracking_number;?></td>
                        <td>
                            <?= $item->receiver->name;?> <?= $item->receiver->first_name;?> <?= $item->receiver->last_name;?><br>
                            <?= $item->receiver->email;?> <?= $item->receiver->phone;?><br>
                            <?= $item->receiver->address->street;?> <?= $item->receiver->address->building_number;?> <br>
                            <?= $item->receiver->address->post_code;?> <?= $item->receiver->address->city;?> <?= $item->receiver->address->country_code;?>
                        </td>
                        <td>
                            <a href="<?= site_url('duocms/Delivery/inpost_show/'.$item->id);?>"><span class="glyphicon glyphicon-eye-open"></span></a>
                            <?php
                                if($item->status == "created" || $item->status == "offers_prepared"){
                                 ?> / 
                            <?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/Delivery/delete_inpost/'.$item->id)); ?>
                            <?php
                                }
                            ?>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
                <tr>
                    
                </tr>
                
            </table>
        </div>
        <?php //json_encode($shipments);
                    ?>
    </div>
</div>

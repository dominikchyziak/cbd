<?php $this->load->view('duocms/Shop/menu'); ?>
<div class="panel panel-default">
    <div class="panel-heading"><strong>Przesyłka <?= $shipment->id;?></strong></div>
    <div class="panel-body">
        <div class="col-sm-12 col-md-6">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <tr>
                        <td><b>Status</b></td>
                        <td><?= $statuses[$shipment->status];?></td>
                    </tr>
                    <tr>
                        <td><b>Nr śledzenia</b></td>
                        <td><?= $shipment->tracking_number;?></td>
                    </tr>
                     <tr>
                        <td><b>Usługa</b></td>
                        <td><?= (new CustomElementModel('18'))->getField($shipment->service);?></td>
                    </tr>
                    <tr>
                        <td><b>Pobranie</b></td>
                        <td><?= !empty($shipment->cod) ? json_encode($shipment->cod->amount) . 'PLN' : 'Nie';?></td>
                    </tr>
                    <tr>
                        <td><b>Punkt docelowy</b></td>
                        <td><?= !empty($shipment->custom_attributes->target_point) ? $shipment->custom_attributes->target_point : '';?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <tr>
                        <td><b>Imię</b></td>
                        <td><?= $shipment->receiver->first_name;?></td>
                    </tr>
                    <tr>
                        <td><b>Nazwisko</b></td>
                        <td><?= $shipment->receiver->last_name;?></td>
                    </tr>
                    <tr>
                        <td><b>Email</b></td>
                        <td><?= $shipment->receiver->email;?></td>
                    </tr>
                    <tr>
                        <td><b>Telefon</b></td>
                        <td><?= $shipment->receiver->phone;?></td>
                    </tr>
                    <tr>
                        <td><b>Adres</b></td>
                        <td>
                            <?= $shipment->receiver->address->street;?> 
                            <?= $shipment->receiver->address->building_number;?>, 
                            <?= $shipment->receiver->address->post_code;?> 
                            <?= $shipment->receiver->address->city;?> 
                        </td>
                    </tr>
                    
                </table>
            </div>
        </div>
        <?php /* json_encode($shipment);*/?>
          
    </div>
</div>
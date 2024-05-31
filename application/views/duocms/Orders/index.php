<?php $this->load->view('duocms/Shop/menu'); ?>
<div class="panel panel-default">
    <div class="panel-heading"><strong>Złożone zamówienia</strong>
        <!--<a href="<?= site_url('duocms/orders/allegro'); ?>" class="btn btn-info pull-right">Pobierz zamówienia z allegro</a>-->
        <a href="<?= site_url('duocms/bilans/orders'); ?>" class="btn btn-info pull-right">Pobierz plik z zamówieniami</a>
    </div>
    <div class="panel-body">
<div class="table-responsive">
  <div>
        <form method="POST">
            <input type="hidden" name="filtruj" value="filtruj" />
            <div class="col-sm-12">
                <strong>Filtry</strong><div class="pull-right"><?= $this->pagination->create_links();?></div></div>
        <div class="col-sm-5">
        Status:
        <select name="statusid" class="form-control">
            <option value="-99">Wszystkie</option>
            <?php
            if(!empty($statuses_info)):
                foreach ($statuses_info as $stati) : 
                $stat = $stati->status; ?>
            <option value="<?= $stat; ?>" <?= ($stat ==$selected_filters['statusid']) ? "selected" : "" ?>><?= (new CustomElementModel('14'))->getField('status '.$stat);?></option>
                    <?php
                endforeach;
            endif;
            ?>
        </select>
        </div>
        <div class="col-sm-5">
        Dostawa:

        <select name="deliveryid" class="form-control">
            <option value="-99">Wszystkie</option>
            <?php
            if(!empty($delivery_info)):
                foreach ($delivery_info as $delivery_id => $delivery_name) :  ?>
            <option value="<?= $delivery_id; ?>" <?= ($delivery_id ==$selected_filters['deliveryid']) ? "selected" : "" ?>><?= $delivery_name;?></option>
                    <?php
                endforeach;
            endif;
            ?>
        </select>
        </div> 
        <div class="col-sm-2"><br>
            <button type="submit" class="btn btn-info"> Filtruj </button>
        </div>
        </form>
    </div>
    <form method="POST">
    <table class="table table-striped table-hover t1">
        <thead>
            <tr>
                <!--<th></th>-->
                <th>Id</th>
                <th>E-mail</th>
                <th>Dane</th>
                <th>Dostawa</th>
                <th>Wartość zam.</th>
                <th>Status</th>
                <th>Metoda</th>
                <th>Data zamówienia</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(!empty($orders)){
                foreach($orders as $order){
                    ?>
            <tr>
                <!--<td><input type="checkbox" name="order[<?= $order->id;?>]" ></td>-->
                <td><a href="<?= site_url('duocms/orders/show/'.$order->id);?>"><?= $order->id;?></a></td>
                <td><a href="<?= site_url('duocms/orders/show/'.$order->id);?>"><?= $order->email;?></a></td>
                <td><a href="<?= site_url('duocms/orders/show/'.$order->id);?>"><?php if(!empty($order->company_name)): ?>
                        <?= $order->company_name.'<br>NIP: '.$order->company_nip; ?>
                        <?php else: ?>
                        <?= $order->first_name;?> <?= $order->last_name;?>
                        <?php endif; ?>
                        <br><?= $order->phone;?><br><?= $order->zip_code;?> <?= $order->city;?> <br>
                            <?= $order->street. ' '. $order->building_number;?>
                            <?= (!empty($order->flat_number))? "/". $order->flat_number : ''?>
                    </a></td>
                <td><a href="<?= site_url('duocms/orders/show/'.$order->id);?>"><?= $order->name;?>
                    <?php if ($order->delivery == 39): ?>
                        <?= "(". $order->inpost_locker. ")" ?>
                    <?php endif; ?></a>
                </td>
                <td><a href="<?= site_url('duocms/orders/show/'.$order->id);?>"><?= $order->price;?> <?= $currencies[$order->currency_id]; ?></a></td>
                <td><a href="<?= site_url('duocms/orders/show/'.$order->id);?>"><?= (new CustomElementModel('14'))->getField('status '.$order->status);?></a></td>
                <td><a href="<?= site_url('duocms/orders/show/'.$order->id);?>"><?= (new CustomElementModel('16'))->getField($order->method); ?></a></td>
                <td><a href="<?= site_url('duocms/orders/show/'.$order->id);?>"><?= $order->created_at;?></a></td>
                  <?php /*<td><?php if(!empty($order->package_id)): ?>
                    <a href="<?= site_url('duocms/orders/inpost_download/'.$order->id); ?>">POBEIRZ INPOST</a>
                    <?php elseif($inpost_delivery[$order->delivery]): ?>
                    <a href="<?= site_url('duocms/orders/inpost_generate/'.$order->id); ?>">GENERUJ INPOST</a>
                    <?php endif; */ ?>
                </td>
                <td>
                    <?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/orders/delete_order/'.$order->id)); ?>
                </td>
            </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
        <?php if(get_option('admin_module_bliskapaczka_active')): ?>
        <input type="submit" name='bliskapaczka' value='Generuj csv Bliska Paczka' class="btn btn-info" />
        <?php endif; ?>
        <?php if(get_option('admin_module_inpost_active')): ?>
        <?php /* <input type="submit" name='inpost' value='Generuj csv Inpost' class="btn btn-danger" /> */ ?>
        <?php endif; ?>
        <?php if(get_option('admin_module_enadawca_active')): ?>
        <input type="submit" name='minipaczka' value='Nadaj minipaczki' class="btn btn-success" />
        <?php endif; ?>
        
<!--        <input type="submit" name='print' value='Dane klientów' class="btn btn-success" />-->
    </form>
</div>
</div>
</div>
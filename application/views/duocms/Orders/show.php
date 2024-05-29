<?php $this->load->view('duocms/Shop/menu'); ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <strong><span id="order-title">Zamówienie nr: <?= $order->id;?></span></strong>
        <a class="btn btn-info pull-right" href="<?= site_url('duocms/orders/edit/'.$order->id);?>" >Edytuj dane</a>
        <button class="btn btn-info pull-right" id="btn-print">Drukuj</button>
    </div>
    <div class="panel-body">
        <div class="print-box" id="print-box">
    <div class="">
        <div class="col-sm-12 col-md-7">
            <h3>Zamówione produkty</h3>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-print">
                <thead>
                    <th>LP</th>
                    <th class="print-hide"></th>
                    <th>EAN</th> 
                    <th>Nazwa</th>
                    <th>Cena</th>
                    <th>Ilość</th>
                    <th>Wartość</th>
                </thead>
                <tbody>
                    <?php 
                    if(!empty($products)){
                        $i = 0;
                        $cena = 0;
                        foreach($products as $product){
                            $i++;
                            ?>
                    <tr>
                        <td><?= $i;?></td> 
                        <td class="print-hide"><?= !empty($product['photos']) ? '<img src="' . $product['photos'][0]->getUrl('mini') . '" alt="" class="basket_photo" style="max-width:100px;"/>' : '';?></td>
                        <td><?= $product['product_data']->code; ?></td>
                        <td><?= $product['product']->name;?> <?= !empty($product['option']['name']) ? '<br>'.$product['option']['name'] : '';?>
                         <?php if(!empty($product['additional'])): ?>
                                            <?php $add = json_decode($product['additional']); ?>
                                        <br><span class="product_attribute"><?=$add->mirror_width.' x '. $add->mirror_height;?></span> 
                                        <?php foreach($add->mirror_extras as $ex): ?>
                                        <?php if(!empty($ex)): ?>
                                        <br><span class="product_attribute"><?= $this->ProductModel->get_detail_details($ex); ?></span>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                        <?php endif; ?></td>
                        <td><?= number_format($product['price'],2,',',' ');?>&nbsp;<?=$currency->code;?></td>
                        <td><?= $product['quantity'];?><?= !empty($product['product_data']->number_in_package) ? 'm<sup>2</sup><br>'.round($product['quantity'] / $product['product_data']->number_in_package). 'opak'  : 'szt'; ?></td>
                        <td><?= number_format($product['price'] * $product['quantity'],2); ?>&nbsp;<?=$currency->code;?></td>
                    </tr>
                            <?php
                            $cena += $product['price'] * $product['quantity'];
                        }
                        $i++;
                        ?>
                <tr>
                        <td><?= $i; ?>
                        <td class="print-hide"></td>
                        <td></td>
                        <?php 
                        if($cena >= $delivery['prices'][$currency->id]['max_price']){
                            $del_price = 0;
                        } else {
                            $del_price = ($order->method == 'allegro') ? ($order->price - $cena ) : $delivery['prices'][$currency->id]['price'];
                        }  ?>
                        <td><?= $delivery['translations']['pl']['name'];?></td>
                        <td><?= $del_price; ?>&nbsp;<?=$currency->code;?></td>
                        <td>1</td>
                        <td><?= $del_price; ?>&nbsp;<?=$currency->code;?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            </div>
            <div class="col-sm-12">
                <?php
                if(!empty($order->discount_id)){
                    ?>
                <div class="alert alert-warning">
                    Tutaj został zastosowany rabat
                </div>
                <?php
                }
                ?>
            </div>
            <p>
                <strong><big>Suma zamówienia: <?= number_format($order->price, 2);?> <?=$currency->code;?></big></strong>
            </p>
            <p>
                Bieżący status: <b><?= (new CustomElementModel('14'))->getField('status '.$order->status);?></b>
                <br>
            </p>
            <div class="form-section print-hide">
                <form method="POST">
                    <div class="form-group">
                        <p>Zmiana statusu</p>
                        <select name="new_status" class="form-control">
                            <?php
                                foreach($this->order_statuses as $key=>$s){
                                    echo '<option value="' . $key . '" '. ($key == 10 ? 'selected' : '') .'>' . (new CustomElementModel('14'))->getField('status '.$key) .  '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <p>Czy wysłać maila</p>
                        <select name="mail" class="form-control">
                            <option value="1">Tak</option>
                            <option value="0">Nie</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <p>Notatka <small>(Będzie dołączona do maila)</small></p>
                        <textarea name="note" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Zmień status" class="button" />
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-12 col-md-5">
            <h3>Dane zamówienia</h3>
            <table class="table table-striped">
                <tbody>
                    <?php if(!empty($order->company_name)): ?>
                    <tr>
                        <td>Dane do faktury:</td>
                        <td><?= $order->company_name; ?><br>NIP: <?= $order->company_nip; ?><br><?= $order->company_address; ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td>Imię:</td>
                        <td><?= $order->first_name;?></td>
                    </tr>
                    <tr>
                        <td>Nazwisko:</td>
                        <td><?= $order->last_name;?></td>
                    </tr>
                    <tr>
                        <td>E-mail:</td>
                        <td><?= $order->email;?></td>
                    </tr>
                    <tr>
                        <td>Telefon:</td>
                        <td><?= $order->phone;?></td>
                    </tr>
                    <tr>
                        <td>Adres:</td>
                        <td><?= $order->zip_code;?> <?= $order->city;?> <br><?= $order->street. ' '. $order->building_number;?><?= (!empty($order->flat_number))? $order->flat_number : ''?></td>
                    </tr>
                    <tr>
                        <td>Komentarz:</td>
                        <td><?= $order->comment;?></td>
                    </tr>
                    <tr>
                     <td>Sposób dostawy:</td>
                     <td><?= $delivery['translations']['pl']['name'];?></td>
                    <?php /*var_dump($order); die(); */?>
                    </tr>
                </tbody>
            </table>
            <!--<div class="print-hide">
                <h3>Przesyłka</h3>
                <p>
                    <?php 
                    if($shipment_id > 0){
                        ?>
                    <a href="<?= site_url('duocms/Delivery/inpost_show/'.$shipment_id);?>">Zobacz paczkę</a>
                    <?php
                    } else {
                        ?>
                    <a href="<?= site_url('duocms/Delivery/inpost_ship_form/'.$order->id);?>" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#shipment_form">+ Dodaj przesyłkę inpost</a>
                    <?php
                    }
                    ?>
                </p>
            </div>-->
            
            <h3>Historia zamówienia</h3>
            <table class="table table-striped table-hover">
                <?php
                if(!empty($story)){
                    foreach ($story as $story_item){
                        ?>
                        <tr>
                            <td>Zmiana statusu na</td>
                            <td><b><?= (new CustomElementModel('14'))->getField('status '.$story_item->new_status);?></b></td>
                        </tr>
                        <tr>
                            <td><?= $story_item->created_at;?></td>
                            <td><?= $story_item->note;?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
        </div>
    </div>
</div>
    </div>
</div>

<!-- Modal -->
<div id="shipment_form" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Dodawanie paczki</h4>
      </div>
      <div class="modal-body">
        <p>formularz</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
      </div>
    </div>

  </div>
</div>

<script>
    $(document).ready(function(){
       $('#btn-print').click(function(){
           var elem = 'print-box';
            PrintElem(elem);
       }); 
    });
function PrintElem(elem)
{
    var mywindow = window.open('', 'PRINT', 'height=700,width=1200');
    var title = document.getElementById('order-title').innerHTML;
    mywindow.document.write('<html><head><title>' + title  + '</title>');
    mywindow.document.write('</head><body >');
    mywindow.document.write('<h1>' + title  + '</h1>');
    var el = $('#'+elem).clone();
    el.find('.table-print td').css('border','1px solid black');
    el.find('.table-print td').css('padding','0px 5px');
    el.find('.table-print th').css('padding','0px 5px');
    el.find('.table-print table').css('border','1px solid black');
    el.find('.table-print th').css('border','1px solid black');
    el.find('.table-print table').css('border-collapse','collapse');
    el.find('.table-print table').css('border-spacing','0px');
    el.find('.print-hide').css('display','none');
    mywindow.document.write(el.html());
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    mywindow.close();

    return true;
}
</script>
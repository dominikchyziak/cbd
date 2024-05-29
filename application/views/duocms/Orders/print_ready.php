<head>
    <link rel="stylesheet" href="<?php echo assets('css/bootstrap.min.css'); ?>" type="text/css"  />    
            <script    type="text/javascript" src="<?php echo assets('js/jquery-3.2.0.min.js'); ?>"></script>
</head>
<button class="btn btn-info pull-right" id="btn-print">Drukuj</button>
<div class="" id="print-box">
    <?php 
if(!empty($orders)):
    foreach($orders as $order):
      ?>
<table class=" table table-bordered table-print">
    <tbody>
        <tr>
            <td colspan="4">Zamówienie nr. <?= $order->id; ?></td>
        </tr>
        <tr>
            <td>
            Kupujący: <?= $order->first_name.' '.$order->last_name; ?>
            </td>
            <td colspan="3">
                <?php $flat_num = !empty($order->flat_number) ? 'm'.$order->flat_number :''; ?>
            Adres wysyłki: <?= $order->street.' '.$order->building_number.$flat_num.' '.$order->zip_code.' '. $order->city; ?>
            </td>
        </tr>
        <tr>
            <td>
                Nazwa przedmiotu:
            </td>
            <td>
                Sztuk:
            </td>
            <td>
                Cena:
            </td>
            <td>
                Cena * ilość:
            </td>
        </tr>
        <?php
        foreach($order->items as $product_id => $item_data): ?>
        <tr>
            <td>
            <?php
            $prod = new ProductModel($product_id);
            echo $prod->getTranslation(LANG)->name;
            ?>
            </td>
            <td>
                <?= $item_data[0]; ?>
            </td>
            <td>
                <?= number_format($prod->price, 2); ?>
            </td>
            <td>
                <?= number_format($prod->price * $item_data[0], 2); ?>
            </td>
        </tr>
        <?php endforeach;
        ?>
        <tr>
            <?php $delivery = (new Delivery_Model())->get_delivery($order->delivery); ?>
            <td >Dostawa: <?= $delivery['translations']['pl']['name']; ?></td>
            <td> 1</td>
            <td><?= number_format($delivery['delivery_price'],2); ?></td>
            <td><?= number_format($delivery['delivery_price'],2); ?></td>
        </tr>
        <tr>
            <td colspan="3">
                UWAGI: <?=$order->comment; ?>
            </td>
            <td>
                RAZEM: <?= number_format($order->price,2); ?>
            </td>
        </tr>
    </tbody>
</table>
    <br>
    <?php  
    endforeach;
endif;
?>
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
    var title = 'Sportplaza - zamówienia';
    mywindow.document.write('<html><head><title>' + title  + '</title>');
    mywindow.document.write('</head><body >');
    var el = $('#'+elem).clone();
    el.find('.table-print td').css('border','1px solid black');
    el.find('.table-print td').css('padding','0px 5px');
    el.find('.table-print th').css('padding','0px 5px');
    el.find('.table-print table').css('border','1px solid black');
    el.find('.table-print th').css('border','1px solid black');
    el.find('.table-print table').css('border-collapse','collapse');
    el.find('.table-print table').css('border-spacing','0px');
    mywindow.document.write(el.html());
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    mywindow.close();

    return true;
}
</script>
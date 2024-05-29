<?= (new CustomElementModel('14'))->getField('naglowek maila'); ?>
<h3><?= (new CustomElementModel('16'))->getField('mail nowe zamowienie'); ?> <?= $order_id; ?></h3> 


<table class="table table-hover table-striped" style="width: 600px;">
    <thead style="background:#4b4b4b; color:white;">
    <th style="padding: 10px;"><?= (new CustomElementModel('11'))->getField('LP'); ?></th>
    <th style="padding: 10px;"><?= (new CustomElementModel('11'))->getField('Sygnatura'); ?></th>
    <th style="padding: 10px;"><?= (new CustomElementModel('11'))->getField('Nazwa'); ?></th>
    <th style="padding: 10px;"><?= (new CustomElementModel('11'))->getField('Cena'); ?></th>
    <th style="padding: 10px;"><?= (new CustomElementModel('11'))->getField('Ilość'); ?></th>
    <th style="padding: 10px;"><?= (new CustomElementModel('11'))->getField('Wartość'); ?></th>
</thead>
<tbody>
    <?php
    if (!empty($products)) {
        $i = 0; $total = 0;
        foreach ($products as $product) {
            $i++;
            ?>
            <tr>
                <td style="padding: 10px;"><?= $i; ?></td>
                <td><?= $product['product_data']->code; ?></td>
                <td style="padding: 10px;">
                    <?= $product['product']->name; ?><br>
                    <span class="product_size">
                        <?= !empty($product['option']['name']) ? $product['option']['name'] : ''; ?>
                    </span><br>
                    <span class="product_attributes">
                        <?php
                        if (!empty($product['attributes'])) {
                            foreach ($product['attributes'] as $attribute) {
                                ?>
                                <span class="product_attribute" style="margin: 4px; float:left; width: auto; padding: 5px; background-color: #4b4b4b; color: #ffffff;">
                                    <?= $attribute['translations'][LANG]['name']; ?>
                                </span>
                                <?php
                            }
                        }
                        ?>
                    </span>

                </td>
                <td style="padding: 10px;">
                    <?php if (!empty($product['product_data']->number_in_package)): ?>
                        <?= $product['price'] * $product['product_data']->number_in_package; ?>
                    <?php else: ?>
                        <?= number_format($product['price'],2,',',' '); ?>
                    <?php endif; ?>
                    <?= get_active_currency_code(); ?></td>
                <td style="padding: 10px;">
                    <?php if (!empty($product['product_data']->number_in_package)): ?>
                        <?= round($product['quantity'] / $product['product_data']->number_in_package); ?>
                    <?php else: ?>
                        <?= $product['quantity']; ?>
                    <?php endif; ?>
                </td>
                <?php $rowTotal = $product['price'] * $product['quantity'];
                    $total += $rowTotal;
                ?>
                <td style="padding: 10px;"><?= number_format($rowTotal, 2,',',' '); ?> 
                    <?= get_active_currency_code(); ?></td>
            </tr>
            <?php
        }
        $i++;
    }
    ?>
    <tr>
        <td style="padding: 10px;"><?= $i; ?></td>
        <td></td>
        <td style="padding: 10px;"><?= $delivery['translations'][LANG]['name']; ?></td>
        <td style="padding: 10px;">
            <?php $dprice = $delivery['prices'][1]['max_price'] > $total ? $delivery['prices'][1]['price'] : 0; ?>
            <?= number_format($dprice,2,',',' '); ?> 
            <?= (new CustomElementModel('16'))->getField('waluta'); ?>.
        </td>
    </tr>
<br><br>
<tr>
    <td colspan="3" style="padding: 10px;"><?= (new CustomElementModel('16'))->getField('Do zaplaty'); ?></td>
    <td style="padding: 10px;"><?= $order['price']; ?> <?= get_active_currency_code(); ?>.</td>
</tr>
</tbody>
</table>
<hr>
<h3><?= (new CustomElementModel('16'))->getField('dane zamowienia'); ?></h3>
<table class="table table-striped">
    <tbody>
        <tr>
            <td><?= (new CustomElementModel('11'))->getField('Imie'); ?>:</td>
            <td><?= $order['first_name'] . ' ' . $order['last_name']; ?></td>
        </tr>
        <tr>
            <td><?= (new CustomElementModel('11'))->getField('Email'); ?>:</td>
            <td><?= $order['email']; ?></td>
        </tr>
        <tr>
            <td><?= (new CustomElementModel('11'))->getField('Telefon'); ?>:</td>
            <td><?= $order['phone']; ?></td>
        </tr>
        <tr>
            <td><?= (new CustomElementModel('11'))->getField('Adres'); ?>:</td>
            <td><?= $order['zip_code'] . ' ' . $order['city'] . '<br>' . $order['street']; ?></td>
        </tr>
        <tr>
            <td><?= (new CustomElementModel('16'))->getField('Komentarz'); ?>:</td>
            <td><?= $order['comment']; ?></td>
        </tr>
    </tbody>
</table>
<br><br>
<?= (new CustomElementModel('14'))->getField('status opis 0'); ?>
<hr>
<p>
    <strong><big><?= (new CustomElementModel('16'))->getField('Do zaplaty'); ?>: <?= $order['price']; ?> <?= get_active_currency_code(); ?>.</big></strong>
</p>
<hr>
<?= (new CustomElementModel('16'))->getField('adres zamowienia'); ?>:<br>
<?php $zam_link = site_url('zamowienie/summary/' . $order_id . '/' . $order['key']); ?>
<a href="<?= $zam_link; ?>"><?= $zam_link; ?></a>

<?= (new CustomElementModel('14'))->getField('stopka maila'); ?>

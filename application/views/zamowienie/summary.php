<div class="container order-summary">
  <div class="row wow fadeInLeft">
    <div class="col-sm-12">
      <?php $this->load->view('partials/breadcrumbs'); ?>
      <h1 class="title"><?= (new CustomElementModel('16'))->getField('Podsumowanie zamowienia'); ?></h1>
    </div>
  </div>
  <div class="row wow fadeInUp">
    <div class="col-sm-12 col-md-7 podsumowanie">
      <h3><?= (new CustomElementModel('16'))->getField('zamowione produkty'); ?></h3>
      <div class="table-responsive">
        <table class="table table-hover table-striped">
          <thead>
            <th><?= (new CustomElementModel('11'))->getField('LP'); ?></th>
            <th></th>
            <th><?= (new CustomElementModel('11'))->getField('Nazwa'); ?></th>
            <th><?= (new CustomElementModel('11'))->getField('Cena'); ?></th>
            <th><?= (new CustomElementModel('11'))->getField('Ilość'); ?></th>
            <th><?= (new CustomElementModel('11'))->getField('Wartość'); ?></th>
          </thead>
          <tbody>
            <?php
            if (!empty($products)) {
              $i = 0;
              $cena = 0;
              foreach ($products as $product) {
                $i++;
            ?>
                <tr>
                  <td><?= $i; ?></td>
                  <td width="50px">
                    <?= !empty($product['photos']) ? '<img src="' . $product['photos'][0]->getUrl('') . '" alt="" class="basket_photo"/>' : ''; ?>
                  </td>
                  <td>
                    <span class="product_name"><strong><?= $product['product']->name; ?> </strong></span><br>
                    <span class="product_size">
                      <?= !empty($product['option']['name']) ? $product['option']['name'] : ''; ?>
                    </span>
                    <span class="product_attributes">
                      <?php
                      if (!empty($product['attributes'])) {
                        foreach ($product['attributes'] as $attribute) {
                      ?>
                          <span class="product_attribute">
                            <?= $attribute['translations'][LANG]['name']; ?>
                          </span>
                      <?php
                        }
                      }
                      ?>
                      <?php if (!empty($product['additional'])) : ?>
                        <?php $add = json_decode($product['additional']); ?>
                        <?php if (!empty($add->mirror_width) && !empty($add->mirror_height)) : ?>
                          <span class="product_attribute"><?= $add->mirror_width . ' x ' . $add->mirror_height; ?></span>
                        <?php endif; ?>
                        <?php foreach ($add->mirror_extras as $ex) : ?>
                          <?php if (!empty($ex)) : ?>
                            <span class="product_attribute"><?= $this->ProductModel->get_detail_details($ex); ?></span>
                          <?php endif; ?>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </span>
                  </td>
                  <?php $cena += ($product['price'] * $product['quantity']); ?>
                  <th><?php if (!empty($product['product_data']->number_in_package)) : ?>
                      <?= $product['price'] * $product['product_data']->number_in_package; ?> <?= $currency->code; ?>
                    <?php else : ?>
                      <?= number_format($product['price'], 2, ',', ' '); ?> <?= $currency->code; ?>
                    <?php endif; ?>
                  </th>
                  <td><?php if (!empty($product['product_data']->number_in_package)) : ?>
                      <?= round($product['quantity'] / $product['product_data']->number_in_package); ?> opak
                    <?php else : ?>
                      <?= $product['quantity']; ?> <?= (new CustomElementModel('16'))->getField('podsumowanie sztuki'); ?>
                    <?php endif; ?>
                  </td>
                  <td><?= number_format($product['price'] * $product['quantity'], 2, ',', ' '); ?> <?= $currency->code;  ?></td>
                </tr>
              <?php
              }
              ?>
              <tr>
                <td colspan="3"><?= (new CustomElementModel('11'))->getField('Koszt dostawy'); ?>
                  <?= $delivery['translations'][LANG]['name']; ?></td>
                <td colspan="3">
                  <?= number_format($cena >= $delivery['prices'][$order->currency_id]['max_price'] ? 0 : $delivery['prices'][$order->currency_id]['price'], 2, ',', ' '); ?>
                  PLN</td>
              </tr>
            <?php
            } else {
              echo '<tr><td colspan="5" class="text-center">' . (new CustomElementModel('9'))->getField('Koszyk jest pusty') . '</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
      <p>
        <strong><big><?= (new CustomElementModel('16'))->getField('Do zaplaty'); ?>:
            <?= number_format($order->price, 2, ',', ' '); ?> <?= $currency->code; ?>.</big></strong>
      </p>
      <p>
        <?php
        if ($order->method == 'p24' && $order->status == 0 || $order->method == 'payu' && $order->status == 0 || $order->method == 'paypal' && $order->status == 0) {
          $p24_crc = get_option('p24_crc');
          $p24_id = get_option('p24_id');
          $p24_session_id = $order->id . '_' . date('U');
          $p24_amount = $order->price * 100;
          $p24_currency = "PLN";
          $customer_ip = $_SERVER['REMOTE_ADDR'];
          $description = (new CustomElementModel('16'))->getField('Tytul zamowienia');

          if (get_option('paypal_active')) {
        ?>
      <form class="paypal" method="POST" id="paypal_form" target="_blank">
        <input type="hidden" name="paypal" value="1" />
        <input type="hidden" name="cmd" value="_xclick" />
        <input type="hidden" name="no_note" value="1" />
        <input type="hidden" name="lc" value="PL" />
        <input type="hidden" name="currency_code" value="PLN" />
        <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
        <input type="hidden" name="first_name" value="<?= $order->first_name; ?>" />
        <input type="hidden" name="last_name" value="<?= $order->last_name; ?>" />
        <input type="hidden" name="payer_email" value="<?= $order->email; ?>" />
        <input type="hidden" name="item_number" value="<?= $order->id; ?>" />
        <button type="submit" formtarget="_blank" class="btn-pay">
          <img src="<?= assets('img/paypal_button.png'); ?>" alt="<?= (new CustomElementModel('16'))->getField('zaplac przez paypal'); ?>" title="<?= (new CustomElementModel('16'))->getField('zaplac przez paypal'); ?>" />
        </button>
      </form>
    <?php
          }
          if (get_option('p24_active')) {
    ?>
      <form method="POST" action="<?= get_option('p24_link'); ?>">
        <input type="hidden" name="p24_merchant_id" value="<?= $p24_id; ?>" />
        <input type="hidden" name="p24_pos_id" value="<?= $p24_id; ?>" />
        <input type="hidden" name="p24_sign" value="<?= md5($p24_session_id . '|' . $p24_id . '|' . $p24_amount . '|' . $p24_currency . '|' . $p24_crc); ?>" />

        <input type="hidden" name="p24_session_id" value="<?= $p24_session_id; ?>" />
        <input type="hidden" name="p24_amount" value="<?= $p24_amount; ?>" />
        <input type="hidden" name="p24_currency" value="<?= $p24_currency; ?>" />
        <input type="hidden" name="p24_description" value="<?= $description; ?>" />
        <input type="hidden" name="p24_email" value="<?= $order->email; ?>" />
        <input type="hidden" name="p24_country" value="PL" />
        <input type="hidden" name="p24_url_status" value="<?= site_url('api/payments/przelewy24'); ?>" />
        <input type="hidden" name="p24_url_return" value="<?= site_url('zamowienie/pay_success'); ?>" />
        <input type="hidden" name="p24_api_version" value="3.2" />
        <?php
            if (!empty($products)) {
              $i = 0;
              foreach ($products as $product) {
                $i++;
                echo '<input type="hidden" name="p24_name_' . $i . '" value="' . $product['product']->name . '" />';
                echo '<input type="hidden" name="p24_quantity_' . $i . '" value="' . $product['quantity'] . '" />';
                echo '<input type="hidden" name="p24_price_' . $i . '" value="' . ($product['product_data']->price * 100) . '" />';
              }
              if ($delivery['delivery_price'] > 0) {
                $i++;
                echo '<input type="hidden" name="p24_name_' . $i . '" value="Dostawa" />';
                echo '<input type="hidden" name="p24_quantity_' . $i . '" value="1" />';
                echo '<input type="hidden" name="p24_price_' . $i . '" value="' . ($delivery['delivery_price'] * 100) . '" />';
              }
            }
        ?>



        <button type="submit" formtarget="_blank" class="btn-pay"><img src="<?= assets('img/p24_button.png'); ?>" alt="p24" /></button>
      </form>
    <?php }
          if (get_option('payu_active')) { ?>
      <form method="POST">
        <!--https://secure.snd.payu.com/api/v2_1/orders-->
        <input type="hidden" name="payu" value="1" />
        <button type="submit" formtarget="_blank" class="btn-pay"><img src="<?= assets('img/payu_button.png'); ?>" title="payu" alt="payu" /></button>
      </form>
  <?php
          }
        } else if ($order->method == 'bank' && $order->status == 0) {
          echo (new CustomElementModel('16'))->getField('platnosc na konto');
        } else if ($order->method == 'upon_receipt') {
        } else {
          echo (new CustomElementModel('16'))->getField('zamowienie oplacone');
        }
  ?>

  <br><br><br>
  </p>
    </div>
    <div class="col-sm-12 col-md-5">
      <h3><?= (new CustomElementModel('16'))->getField('dane zamowienia'); ?></h3>
      <table class="table table-striped">
        <tbody>
          <tr>
            <td><?= (new CustomElementModel('11'))->getField('Imie'); ?>:</td>
            <td><?= $order->first_name . ' ' . $order->last_name; ?></td>
          </tr>
          <tr>
            <td><?= (new CustomElementModel('11'))->getField('Email'); ?>:</td>
            <td><?= $order->email; ?></td>
          </tr>
          <tr>
            <td><?= (new CustomElementModel('11'))->getField('Telefon'); ?>:</td>
            <td><?= $order->phone; ?></td>
          </tr>
          <tr>
            <td><?= (new CustomElementModel('11'))->getField('Adres'); ?>:</td>
            <td><?= $order->zip_code; ?> <?= $order->city; ?> <br>
              <?= $order->street . ' ' . $order->building_number; ?><?= (!empty($order->flat_number)) ? ' m ' . $order->flat_number : ''; ?>
            </td>
          </tr>
          <tr>
            <td><?= (new CustomElementModel('11'))->getField('Wybierz sposob dostawy'); ?></td>
            <td>
              <?= $delivery['translations'][LANG]['name']; ?><br>
              <span class="delivery_description"><?= $delivery['translations'][LANG]['description']; ?></span>
            </td>
          </tr>
          <?php
          if (!empty($order->delivery_additional)) {
          ?>
            <tr>
              <td><?= (new CustomElementModel('18'))->getField('paczkomat'); ?></td>
              <td><?= $order->delivery_additional; ?></td>
            </tr>
          <?php
          }
          ?>
          <tr>
            <td><?= (new CustomElementModel('11'))->getField('Informacje dodatkowe'); ?>:</td>
            <td><?= $order->comment; ?></td>
          </tr>
          <tr>
            <td><?= (new CustomElementModel('11'))->getField('Wybierz metodę płatności'); ?>:</td>
            <td><?= (new CustomElementModel('16'))->getField($order->method); ?></td>
          </tr>
          <?php if (!empty($order->company_name)) : ?>
            <tr>
              <td>Dane do faktury: </td>
              <td>
                <?= $order->company_name . '<br />' . $order->company_nip . '<br />' . $order->company_address; ?>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
</div>
<?php
$order_id = $order->id;
$sum =  number_format($order->price, 2, '.', ' ');
?>

<div style="display:none">
  <p id="orderid"><?php echo $order_id; ?></p>
  <p id="sum"><?php echo $sum; ?></p>
</div>


<script>
  var orderID = document.getElementById('orderid').innerText;
  var summary = document.getElementById('sum').innerText;
  window.dataLayer = window.dataLayer || [];
  dataLayer.push({
    'event': 'conversion',
    'currencyCode': 'PLN',
    'transactionTotal': `${summary}`,
    'transactionId': `${orderID}`
  })
</script>
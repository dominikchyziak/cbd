<!-- Latest compiled and minified CSS -->
<link rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>



<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <?php $this->load->view('partials/breadcrumbs'); ?>
    </div>
  </div>
</div>

<div id="basket-inner">
  <?php $this->load->view('zamowienie/basket-inner'); ?>
</div>


<?php if (!empty($products)) { ?>


<div class="basket-loader">
  <svg version="1.1" class="svg-loader" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
    x="0px" y="0px" viewBox="0 0 80 80" xml:space="preserve">

    <path fill="#7AB933" d="M10,40c0,0,0-0.4,0-1.1c0-0.3,0-0.8,0-1.3c0-0.3,0-0.5,0-0.8c0-0.3,0.1-0.6,0.1-0.9c0.1-0.6,0.1-1.4,0.2-2.1
	c0.2-0.8,0.3-1.6,0.5-2.5c0.2-0.9,0.6-1.8,0.8-2.8c0.3-1,0.8-1.9,1.2-3c0.5-1,1.1-2,1.7-3.1c0.7-1,1.4-2.1,2.2-3.1
	c1.6-2.1,3.7-3.9,6-5.6c2.3-1.7,5-3,7.9-4.1c0.7-0.2,1.5-0.4,2.2-0.7c0.7-0.3,1.5-0.3,2.3-0.5c0.8-0.2,1.5-0.3,2.3-0.4l1.2-0.1
	l0.6-0.1l0.3,0l0.1,0l0.1,0l0,0c0.1,0-0.1,0,0.1,0c1.5,0,2.9-0.1,4.5,0.2c0.8,0.1,1.6,0.1,2.4,0.3c0.8,0.2,1.5,0.3,2.3,0.5
	c3,0.8,5.9,2,8.5,3.6c2.6,1.6,4.9,3.4,6.8,5.4c1,1,1.8,2.1,2.7,3.1c0.8,1.1,1.5,2.1,2.1,3.2c0.6,1.1,1.2,2.1,1.6,3.1
	c0.4,1,0.9,2,1.2,3c0.3,1,0.6,1.9,0.8,2.7c0.2,0.9,0.3,1.6,0.5,2.4c0.1,0.4,0.1,0.7,0.2,1c0,0.3,0.1,0.6,0.1,0.9
	c0.1,0.6,0.1,1,0.1,1.4C74,39.6,74,40,74,40c0.2,2.2-1.5,4.1-3.7,4.3s-4.1-1.5-4.3-3.7c0-0.1,0-0.2,0-0.3l0-0.4c0,0,0-0.3,0-0.9
	c0-0.3,0-0.7,0-1.1c0-0.2,0-0.5,0-0.7c0-0.2-0.1-0.5-0.1-0.8c-0.1-0.6-0.1-1.2-0.2-1.9c-0.1-0.7-0.3-1.4-0.4-2.2
	c-0.2-0.8-0.5-1.6-0.7-2.4c-0.3-0.8-0.7-1.7-1.1-2.6c-0.5-0.9-0.9-1.8-1.5-2.7c-0.6-0.9-1.2-1.8-1.9-2.7c-1.4-1.8-3.2-3.4-5.2-4.9
	c-2-1.5-4.4-2.7-6.9-3.6c-0.6-0.2-1.3-0.4-1.9-0.6c-0.7-0.2-1.3-0.3-1.9-0.4c-1.2-0.3-2.8-0.4-4.2-0.5l-2,0c-0.7,0-1.4,0.1-2.1,0.1
	c-0.7,0.1-1.4,0.1-2,0.3c-0.7,0.1-1.3,0.3-2,0.4c-2.6,0.7-5.2,1.7-7.5,3.1c-2.2,1.4-4.3,2.9-6,4.7c-0.9,0.8-1.6,1.8-2.4,2.7
	c-0.7,0.9-1.3,1.9-1.9,2.8c-0.5,1-1,1.9-1.4,2.8c-0.4,0.9-0.8,1.8-1,2.6c-0.3,0.9-0.5,1.6-0.7,2.4c-0.2,0.7-0.3,1.4-0.4,2.1
	c-0.1,0.3-0.1,0.6-0.2,0.9c0,0.3-0.1,0.6-0.1,0.8c0,0.5-0.1,0.9-0.1,1.3C10,39.6,10,40,10,40z">

      <animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 40 40" to="360 40 40"
        dur="0.6s" repeatCount="indefinite" />
    </path>
  </svg>
</div>
<?php } ?>

<script>
function number_format(number, decimals, dec_point, thousands_sep) {
  // Strip all characters but numerical ones.
  number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}
//koszt całkowity do zapłaty
var deliveries = [];
<?php
if (!empty($deliveries)) {
    $ac = get_active_currency()->id;
    foreach ($deliveries as $delivery) {
        ?>
deliveries[<?= $delivery->delivery_id; ?>] =
  <?= $sum_price > $delivery->prices[$ac]['max_price'] ? '0' : $delivery->prices[$ac]['price']; ?>;
<?php
    }
}
?>

$(document).ready(function() {
  //uzupełnianie ukrytego pola info o paczkomacie
  $('#inpost_locker').on('change', function() {
    $('#delivery_additional').val($(this).find('option:selected').text());
  });

  $('#delivery').on('change', function() {
    var val = this.value;
    var texts = $(this).find(':selected').text();
    var f_pay = deliveries[val] + <?= str_replace(',', '.', $sum_price); ?>;
    $('#for_pay').html(number_format((Math.round(f_pay * 100) / 100), 2, ',', ' ') +
      " <?= get_active_currency_code(); ?>");


    if (texts.indexOf('osobisty') != -1 || texts.indexOf('pobrani') != -1 || texts.indexOf('Pobrani') != -1) {
      $("#pay_method").val('upon_receipt');
      $("#pay_method option:selected").siblings().hide();
    } else {
      $('#pay_method option').show();
      $("#pay_method option[value=upon_receipt]").hide();
      document.getElementById('pay_method').selectedIndex = 0;
    }
  });
});

var lang = document.documentElement.lang;

function increase_count(evt) {
  var container = $(this).parents('.item-quantity');
  var current = parseInt($(container).children('.item-quantity-number').html());
  var max = parseInt($(container).attr('max-quantity'));
  if (current >= max) {
	  if (lang === "pl"){
		toastr.error('Nie można dodać więcej produktu danego typu.');
	  } else {
		toastr.error('No more of this type can be added.');
	  }
  } else {
    $(container).children('.item-quantity-number').html(current + 1);
    recalculate_row($(this).parents('.i-row').attr('data-product-id'));
  }
}

function decrease_count(evt) {
  var container = $(this).parents('.item-quantity');
  var current = parseInt($(container).children('.item-quantity-number').html());
  if (!(current > 1)) {
	  if (lang === "pl"){
		toastr.error('Nie można zmniejszyć ilości poniżej jednego, proszę usunąć produkt.');
	  } else {
		toastr.error('The quantity cannot be reduced below one, please remove the product.');
	  }
  } else {
    $(container).children('.item-quantity-number').html(current - 1);
    recalculate_row($(this).parents('.i-row').attr('data-product-id'));
  }
}

function recalculate_row(pid) {
  var row = $('.i-row[data-product-id="' + pid + '"]');
  var quantity = parseInt($(row).find('.item-quantity-number').html());
  var price = parseFloat($(row).find('.item-price').attr('price'));
  var valueBox = $(row).find('.item-value');
  $(valueBox).attr('row-value', price * quantity);
  $(valueBox).html(number_format(price * quantity, 2, ',', ' ') + "&nbsp;zł");
  recalculate_basket_total();
}

function recalculate_basket_total() {
  var total_amount = 0;
  var total_price = 0;
  $('.basket-items-container .item-value').each(function(i, item) {
    total_price += parseFloat($(item).attr('row-value'));
  });
  $('.basket-items-container .item-quantity-number').each(function(i, item) {
    total_amount += parseInt($(item).html());
  });
  var total_amount_string = total_amount + ' ';
  switch (total_amount) {
    case 1:
      total_amount_string += 'produkt';
      break;
    case 2:
    case 3:
    case 4:
      total_amount_string += 'produkty';
      break;
    default:
      total_amount_string += 'produktów';
      break;

  }
  var delivery_price = parseFloat($('#basket-summary-delivery span:last-of-type').attr('delivery-price'));
  $('#basket-product-summary span:first-of-type').html(total_amount_string);
  $('#basket-product-summary span:last-of-type').html(number_format(total_price, 2, ',', ' ') + "&nbsp;zł");
  $('.basket-summary-total div span:last-of-type').html(number_format(total_price + delivery_price, 2, ',', ' ') +
    "&nbsp;zł");
  if (basketChangeTimeout !== null) {
    clearTimeout(basketChangeTimeout);
    basketChangeTimeout = null;
  }
  basketChangeTimeout = setTimeout(function() {
    basketUpdate();
  }, 800);
}

function basketUpdate() {
  $('.basket-loader').css('display', 'flex');
  var basketData = [];
  $('.basket-items-container .i-row').each(function(i, item) {
    var basketRowData = {
      'product_id': $(item).attr('data-product-id'),
      'quantity': $(item).find('.item-quantity-number').html()
    };
    basketData.push(basketRowData);
  });
  $.ajax({
    url: '<?= site_url('zamowienie/ajax_basket_update'); ?>',
    method: 'POST',
    data: {
      'data': JSON.stringify(basketData)
    },
    dataType: 'JSON',
    success: function(res) {
      if (res.error !== 0) {
        location.reload();
      } else {
        $('.menu-strony-dane-koszyk-d span').html(res.amount);
        $('.menu-strony-dane-koszyk-c').html(number_format(res.sum_price, 2, ',', ' '));
        $('.sklep-dostawa-platnosc').empty();
        $('.sklep-dostawa-platnosc').html(res.basket_delivery_payment);

        $('#basket-inner').empty();
        $('#basket-inner').html(res.basket_inner);
        basket_init();
        $('.basket-loader').css('display', 'none');
      }
    }
  });
}

function basket_init() {
  $('.item-quantity-plus').on('click', increase_count);
  $('.item-quantity-minus').on('click', decrease_count);
  $('.item-delete').on('click', function(ev) {
    $('.basket-loader').css('display', 'flex');
  });
}
$(document).ready(function() {
  basket_init();

});
var basketChangeTimeout = null;
</script>
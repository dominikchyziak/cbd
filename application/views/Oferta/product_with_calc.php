<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1 class="header_title wow fadeInLeft"><?= !empty($title) ? $title : $product->name; ?> <?= !empty($option) && empty($title) ? ' - ' . $option['name'] : ''; ?></h1>
        </div>
    </div>
    <div class="row">
         <div class="col-sm-12 col-md-6 text-center">
              <?php //$this->load->view('Gallery/module_integrator', $gallery_widget); ?>
             <div class="main_photos swiper-container">
                 <div class="swiper-wrapper">
                 <?php foreach($photos as $p): ?>
                 <div class="main_photo swiper-slide">
                     <div>
                         <a href="<?= $p->getUrl();?>" data-lightbox="product-gallery">
                            <img src="<?= $p->getUrl();?>" />
                         </a>
                     </div>
                 </div>
                 <?php endforeach; ?>
                 </div>
                  <div class="swiper-button-next swiper-button-black"></div>
                    <div class="swiper-button-prev swiper-button-black"></div>
             </div>
             <div class="mini_photos hidden-xs hidden-sm <?= count($photos) == 1 ? 'hidden' : ''; ?> swiper-container">
                 <div class="swiper-wrapper">
                  <?php foreach($photos as $p): ?>
                 <div class="mini_photo swiper-slide">
                     <div style="background-image: url('<?= $p->getUrl('mini');?>');"></div>
                 </div>
                 <?php endforeach; ?>
                 </div>
             </div>
             <?php if(count($photos) != 1): ?>
             
             <script>
             $(document).ready(function(){
                   var galleryThumbs = new Swiper('.mini_photos', {
      spaceBetween: 10,
      slidesPerView: 2,
      freeMode: true,
      watchSlidesVisibility: true,
      watchSlidesProgress: true,
    });
    var galleryTop = new Swiper('.main_photos', {
      spaceBetween: 10,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      thumbs: {
        swiper: galleryThumbs
      }
    });
             });
             </script>
            <?php  /*<script>
                 $(document).ready(function(){
                  $('.main_photos').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        infinite: true,
        adaptiveHeight: true,
        asNavFor: '.mini_photos'
    });
    $('.mini_photos').slick({
        slidesToShow: <?php $v = round(count($photos)/2); echo ($v > 3) ? 3 : 2; ?>,
        slidesToScroll: 1, 
        infinite: true,
        asNavFor: '.main_photos',
        focusOnSelect: true,
        arrows: false,
        dots: false
       
    });
    });
                 </script> */ ?>
                     <?php endif;?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 wow fadeInRight description-gallery">
                 <div class="form-section">
            <?php
            if (!empty($message)) {
                foreach ($message as $m) {
                    echo '<div class="col-sm-12">';
                    echo '<div class="alert alert-' . $m[0] . '">' . $m[1] . '</div>';
                    echo '</div>';
                }
            }
            if(TRUE) {
            ?>
            <form method="POST">
                <div class="row">
                    <?php if($product_data->options) { ?>
                    <div class="col-sm-12 form-group">
                        <select name="option" id="option" class="form-control" required="true">
                            <option value=""><?= (new CustomElementModel('16'))->getField('wybierz opcje'); ?></option>
                            <?php 
                                if(!empty($options)){
                                    foreach($options as $option){
                                        echo '<option value="' . $option->id . '">' . $option->name . ' - ' . $option->price_change . ' ' . (new CustomElementModel('16'))->getField('waluta') . ' '  . '</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <?php } ?>
                    <div class="col-sm-12" id="description">
                    </div>
                    
                    <div class="form-group">
                        <!--<div class="col-sm-6 col-md-3">
                        <?= (new CustomElementModel('11'))->getField('Ilość'); ?>:
                        </div>-->
                       
                        <div class="col-sm-12 col-md-12">
                            <div class='row'>
                                <div class='col-xs-12 col-sm-12 col-md-6'>
                            <p class="text-center">
        <big class="price">Cena: <span id="price"> <?= number_format($price, 2, ',', ' '); ?> <?= (new CustomElementModel('16'))->getField('waluta'); ?></span></big>
        <span id="bonus-price">
        <?php
        $bonus_price = $product_data->getField(23, $product_data->id, LANG);
        if (!empty($bonus_price) && $bonus_price > 0) {
            ?>       
                <?= number_format((float)$bonus_price, 2, ',', ' '); ?><?= (new CustomElementModel('16'))->getField('waluta'); ?>
            <?php
        }
        ?>
        </span>
        <span id="avilable"></span>
        </p>
                                </div>
                                <div class='col-xs-12 col-sm-12 col-md-6'>
                                     <div class="form-group text-center product-page-buttons">
                                <button class="btn btn-primary btn-basket"><?= (new CustomElementModel('16'))->getField('Do koszyka'); ?></button>
                                
                               <?php /* <a href="tel:<?= (new CustomElementModel('16'))->getField('zamow przez telefon - telefon'); ?>"
                                   class="btn btn-primary btn-phone">
                                <?= (new CustomElementModel('16'))->getField('zamow przez telefon'); ?> */ ?>
                                </a>
                            </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-12">
                            <div class="row">
                              
                                 <?php /* if(!empty($groups)):
                                    foreach($groups as $group):
                                    if(empty($group['attributes'])){ continue;}
                                    switch($group['group']->id){
                                    case 40: ?>
                                <div class="col-sm-12 col-md-6">
                                    <label for="mirror_width"><?= $group['group']->name; ?></label>
                                    <select name="mirror_width" class="form-control" id="mirror_width">
                                        <?php foreach($group['attributes'] as $detail): ?>
                                  
                                        <option value="<?= intval($detail->name);?>"><?= $detail->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php break;
                                    case 41: ?>
                                 <div class="col-sm-12 col-md-6">
                                    <label for="mirror_height"><?= $group['group']->name; ?></label>
                                    <select name="mirror_height" class="form-control" id="mirror_height">
                                        <?php foreach($group['attributes'] as $detail): ?>
                                     
                                        <option value="<?= intval($detail->name);?>"><?= $detail->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php
                                break;
                                    default:?>
                                <div class="col-sm-12 col-md-6">
                                    <label for="group[<?=$group['group']->id;?>]"><?= $group['group']->name; ?></label>
                                    <select name="group[<?=$group['group']->id;?>]" class="form-control details" id="group[<?=$group->id;?>]">
                                        <option value="0">Brak</option>
                                        <?php foreach($group['attributes'] as $detail): ?>
                                 
                                        <option value="<?=$detail->id; ?>"><?= $detail->name; ?> - <?=$detail->val;?>PLN</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                        <?php
                                        break;
                                    }?> 
                                <?php endforeach; ?>
                                <?php endif;  */ ?>
                                <?php  if(!empty($details)):
                                    foreach($details as $group):
                                    if(empty($group->details)){ continue;}
                                    switch($group->name){
                                    case "mirror_width": ?>
                                <div class="col-sm-12 col-md-6">
                                    <label for="mirror_width">Szerokość</label>
                                    <select name="mirror_width" class="form-control" id="mirror_width">
                                        <?php foreach($group->details as $detail): ?>
                                        <option value="<?=$detail->val;?>"><?= $detail->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php break;
                                    case "mirror_height": ?>
                                 <div class="col-sm-12 col-md-6">
                                    <label for="mirror_height">Wysokość</label>
                                    <select name="mirror_height" class="form-control" id="mirror_height">
                                        <?php foreach($group->details as $detail): ?>
                                        <option value="<?=$detail->val;?>"><?= $detail->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php
                                break;
                                    default:?>
                                <div class="col-sm-12 col-md-6">
                                    <label for="group[<?=$group->id;?>]"><?= $group->name; ?></label>
                                    <select name="group[<?=$group->id;?>]" class="form-control details" id="group[<?=$group->id;?>]">
                                        <option value="0">Brak</option>
                                        <?php foreach($group->details as $detail): ?>
                                        <option value="<?=$detail->id; ?>"><?= $detail->name; ?> - <?=$detail->price;?>PLN</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                        <?php
                                        break;
                                    }?> 
                                <?php endforeach; ?>
                                <?php endif;  ?>
                               
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <input type="hidden" name="product_id" value="<?= $product_data->id; ?>"  id="product_id"/>
                            <?php /*<div class="col-xs-3 col-sm-3">
                                <span class="btn btn-danger product-quantity-changer" id="p_minus">-</span>
                            </div>
                            <div class="col-xs-6 col-sm-6"> */?>
                            <input type="hidden" step="1" name="quantity" value="1"  class="form-control" id="quantity"/>
                           <?php /* </div>
                            <div class="col-xs-3 col-sm-3">
                                <span class="btn btn-success product-quantity-changer" id="p_plus">+</span>
                            </div> */?>
                           
                        </div>
                    </div>
                    
                    
                </div>
            </form>
            <?php
            } 
            ?>
                     <div style="clear:both;"></div>
        </div>
            <br> <strong>Dostępność: <?= $product_data->getField(3, $product_data->id,LANG);?></strong><br><br>
             <?= $product->body; ?>
        </div> 
        <div style="clear:both;"></div>
        <?php $cechy = $product_data->getField(1, $product_data->id,LANG);
        if(!empty($cechy)): ?>
        <div class="col-xs-12 product_features text-center">
            <h2 class="header_title">Cechy produktu</h2>
            <?= $cechy; ?>
        </div>
        <?php endif;?>
    </div>
</div>

<script>
     $(document).ready(function(){
      $('#p_minus').click(function(){
          var value = $('#quantity').val();
          if(value > 1){
              value = value*1 - 1;
          }
          $("#quantity").val(value);
          calculate();
      });  
      $('#p_plus').click(function(){
          var value = $('#quantity').val();
          value = value*1 + 1;
          $("#quantity").val(value);
          calculate();
      });  
    });
    function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
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

    $(document).ready(function(){
        calculate();
    });
    $('#option').change(function(){
        calculate();
    });
    $('.attribute_select').change(function(){
        calculate();
    });
    $("#quantity").change(function(){
        calculate();
    });
    $("#quantity").keyup(function(){
        calculate();
    });
    $('#mirror_width').change(function(){ calculate();});
    $('#mirror_height').change(function(){ calculate();});
    $('.details').change(function(){ calculate();});
    function calculate(){  

    //ściągam wszystkie atrybuty i opcje i kalkuluje na ich podstawie cenę
        var product_id = $('#product_id').val();
        var option = $('#option').val();
        var attributes = $(".attribute_select").map(function(){
            return $(this).val();
          }).get();
        var quantity = $("#quantity").val();
        var extras = [];
        $('.details').each(function(index,item){
            extras.push($(item).val());
        });
        var width123 = $('#mirror_width').val();
        var height123 = $('#mirror_height').val();
        var add = new Object();
            add.mirror_width = width123;
            add.mirror_height = height123;
            add.mirror_extras = extras;

        var add_json = JSON.stringify(add);
        $.ajax({
            url: '<?= site_url('oferta/ajax_calculate_price');?>',
            dataType: 'JSON',
            type: 'POST',
            data: {
                product_id: product_id,
                option: option,
                attributes: attributes,
                quantity: quantity,
                additional: add_json
            },
            success: function(res){
                $('#price').html(number_format(res['price'], 2, ',', ' ') + " <?= (new CustomElementModel('16'))->getField('waluta'); ?>");

                    $('#bonus-price').html(res['bonus_price']);
                
                $('#available').html("Dostępnych: " + res['quantity_left']);
                if(res['description'] > ' '){
                    $('#description').html('<div class="alert alert-info">'+res['description']+'</div>');
                } else {
                    $('#description').html('');
                }
            }
        });
    }
</script>


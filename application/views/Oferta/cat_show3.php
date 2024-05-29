<?php $this->load->view('partials/breadcrumbs'); ?>   


    <div class="szer-container">

        <?php if(!empty($category)):
            $categoryImg = $category_data->getField(8, $category_data->id,LANG); ?>
            <?php if(!empty($categoryImg)): ?>
        <div class="category-header" style="background-image: url('<?= $categoryImg; ?>'); width:100%;">      
            <div class="category-label-container">     
                <label><?= stolarczyk_title($category->name); ?></label>          
            </div>
        </div>
        <?php else: ?>
        <div class="container">
        <div class="category-header-name-only">
            <label><?= stolarczyk_title($category->name); ?></label>     
        </div>
        </div>
        <?php endif; ?>
        <?php else: ?>
        <div class="category-header" style="background-image: url('<?= (new CustomElementModel('24'))->getField('tlo wyszukiwarka'); ?>'); width:100%;">      
            <div class="category-label-container">     
                <label>Wyszukiwarka</label>          
            </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($cat_children)) : ?>
            <div class="szer-container-gray-bg">              
                <div class="category-subcategories">   
                    <?php foreach ($cat_children as $c): ?>            
                        <div>          
                            <a href="<?= $c->getPermalink(); ?>"><?= $c->getTranslation(LANG)->name; ?></a>               
                        </div>            
                    <?php endforeach; ?>      
                </div>
            </div>
        <?php endif; ?>
     <?php if(empty($category)): ?>
        <div class="szer-container-gray-bg">
            <div class="container">
                <form method="POST" class="formularz" id="szukajka-form-main">
                    <input type="text" name="search" id="szukajka_main" placeholder="Wprowadź nazwę produktu..." value="<?=$search_string; ?>" />
                    <div style="min-height: 25px;"></div>
                    <input type="submit" name="send" value="Szukaj" />
                </form>
            </div>
        </div>
     <?php endif; ?>
    <?php
    if (!empty($groups) && !empty($used_attr)): ?>
    <div class="filters" id="filtry">
         <form>
                <div class="filters-header">
                    <label><?= (new CustomElementModel('24'))->getField('filtry naglowek'); ?></label>
                </div>
        <div class="filters-content-container container" >
    <?php
        foreach ($groups as $group):
            $amount_active_attr_in_group = 0;
            ?>
            <div class="sekcja-filtry-poz filter-column">
                <div class="filtry-name" data-fgid="<?= $group['group']->id; ?>" ><?= $group['group']->name; ?></div>
                <div class="filtry-attr-box">   
                    <ul class="">
                        <?php
                        foreach ($group['attributes'] as $attr) :
                            if (!in_array($attr->id, $used_attr)) {
                                continue;
                            }
                            $checked = in_array($attr->id, $active_attr);
                            if ($checked) {
                                $amount_active_attr_in_group++;
                            }
                            ?>
                            <li>
                                <input type="checkbox" name="attributes[]" id="attr<?= $attr->id; ?>" value="<?= getAlias($attr->id, $attr->name); ?>" class="attr_id" <?= $checked ? 'checked' : ""; ?>>   
                                <label class="filtry-poz-lbel <?= $checked ? 'checked' : ''; ?>" for="attr<?= $attr->id; ?>"><?= $attr->name; ?></label>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                </div>
            </div>               
        <?php endforeach; ?>  
            <div style="clear:both"></div>
        </div>
        </form>
               </div>
    <?php endif; ?>
         
      

    </div>

    <div class="szer-container-gray-bg">
        <div class="container products-list-container">
                      
            <div class=" products-list-header">

                <div class="">
                    <label class="sort-by-label"><?= (new CustomElementModel('24'))->getField('sortowanie etykieta'); ?></label>
                    <select name="" id="" required="true" class="sort-by-select">
                        <option value="" <?= empty($_GET['sort'])? 'selected' : ''; ?>>Domyślnie</option>
                        <option value="cena_rosnaco" <?= !empty($_GET['sort']) && $_GET['sort'] == 'cena_rosnaco' ? 'selected' : ''; ?>><?= (new CustomElementModel('24'))->getField('sortowanie cena rosnaco'); ?></option>
                        <option value="cena_malejaco" <?= !empty($_GET['sort']) && $_GET['sort'] == 'cena_malejaco' ? 'selected' : ''; ?>><?= (new CustomElementModel('24'))->getField('sortowanie cena malejaco'); ?></option>
                    </select>   
                </div> 

                <div class="">               
                    <label class="filter-sale-button">
                         <input type="checkbox" class="filter-promo-checkbox" name="" value="" <?= !empty($_GET['promo']) ? 'checked' : ''; ?> />
                        <?= (new CustomElementModel('24'))->getField('promocje przycisk'); ?>
                    </label>                
                </div>

                <div class="">               
                    <label class="filter-show-only-available">
                        <input type="checkbox" class="filter-show-only-available-checkbox" name="" value="" <?= !empty($_GET['only_avaible']) ? 'checked' : ''; ?> /><?= (new CustomElementModel('24'))->getField('dostepne etykieta'); ?>
                    </label>                
                </div>

                <div class="">               
                    <label class="filter-number-of-products">
                        <?= (new CustomElementModel('24'))->getField('ilosc produktow etykieta'); ?>
                        <select name="" id="" required="true" class="number-of-products-select">
                            <?php for($i=16;$i<64;$i+=16): ?>
                            <option value="<?= $i; ?>" <?= !empty($_GET['per_page']) && $_GET['per_page'] == $i ? 'selected' : ''; ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>   
                    </label>                
                </div>

                  <?= $this->pagination->create_links(); ?>


            </div>


  <?php if (!empty($products)): ?>     
 
                <div class="row product-list-content">

                    <?php foreach ($products as $p): ?>

                        <?php $this->load->view('Oferta/partials/product_view', ['product' => $p, 'reduced' => true]); ?>

                    <?php endforeach; ?>
              

            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class='my-pagination-bottom'>
                  <?= $this->pagination->create_links(); ?>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="text-center">
                <span style="font-size: 35px; margin: 25px auto; ">Brak produktów.</span>
            </div>
  <?php endif; ?>
        </div>
    </div>
    <?php if(!empty($popular) && !empty($category)): ?>
    <div class="container products-list-container">
        <h2>Popularne w tej kategori</h2>
        <hr>
        <div class="row">
        <?php foreach($popular as $pop): ?>
            <?php $this->load->view('Oferta/partials/product_view', ['product' => $pop, 'reduced' => true]); ?>
        <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
    <form method="GET"  id="alter_search" action="<?= $permalink; ?>#filtry">
        <input type="hidden" name="attributes" value="<?= !empty($_GET['attributes']) ? $_GET['attributes'] : ''; ?>" id="alter_attributes"/>
        <input type="hidden" name="str" value="<?= !empty($_GET['str']) ? $_GET['str'] : ''; ?>" id="alter_str"/>
        <input type="hidden" name="sort" value="<?= !empty($_GET['sort']) ? $_GET['sort'] : ''; ?>" id="alter_sort" />
        <input type="hidden" name="per_page" value="<?= !empty($_GET['per_page']) ? $_GET['per_page'] : ''; ?>" id="alter_per_page" />
        <input type="hidden" name="only_avaible" value="<?= !empty($_GET['only_avaible']) ? $_GET['only_avaible'] : ''; ?>" id="alter_only_avaible" />
        <input type="hidden" name="promo" value="<?= !empty($_GET['promo']) ? $_GET['promo'] : ''; ?>" id="alter_promo" />
    </form>

    <script>
        $(document).ready(function () {
            $('.sekcja-filtry-poz').each(function (index, item) {
                var inputs = $(item).find('input');
                var contains = false;
                $.each(inputs, function (index2, item2) {
                    if ($(item2).is(':checked')) {
                        contains = true;
                    }
                });

                if (contains) {
                    $(item).find('.filtry-name').addClass('filtry-name-aktywny');
                }

            });
        });
    </script> 
  <?php /*  <script>
        $(document).ready(function () {
            $('.filtry-name').click(function () {
                if ($(this).hasClass('in')) {
                    $(this).siblings('div.filtry-attr-box').css('display', 'none');
                    $(this).removeClass('in');
                } else {
                    $('.filtry-name').removeClass('in');
                    $(this).addClass('in');
                    $('.filtry-attr-box').css('display', 'none');
                    $(this).siblings('div.filtry-attr-box').css('display', 'block');
                }
            });
            $('.clear_all').click(function () {
                var fgid = $(this).attr('data-fgid');
                $(".filtry-name[data-fgid='" + fgid + "']").siblings("div.filtry-attr-box").find('input').prop('checked', false);
                search_go();
                $('#alter_search').submit();
            });
            $('.clear_everything').click(function () {
                $(".filtry-name").siblings("div.filtry-attr-box").find('input').prop('checked', false);
                search_go();
                $('#alter_search').submit();
            });
            $('.pick_all').click(function () {
                var fgid = $(this).attr('data-fgid');
                $(".filtry-name[data-fgid='" + fgid + "']").siblings("div.filtry-attr-box").find('input').prop('checked', true);
                search_go();
                $('#alter_search').submit();
            });
        });
    </script> */ ?>

    <script>
        $(document).ready(function () {

<?php /*  $('input[name="page_num"]').change(function(){
  var page = $(this).val();
  if(page == 1){
  window.location = '<?= $pagination_conf['first_url']; ?>';
  } else {
  window.location = '<?= $pagination_conf['base_url']; ?>'+'/'+page+ '<?= $pagination_conf['suffix']; ?>'
  }
  });
  $('.pagi-prev').click(function(){
  var numb = $('input[name="page_num"]').val();
  if(numb > 1){
  $('input[name="page_num"]').val(numb-1);
  $('input[name="page_num"]').trigger('change');
  }
  });
  $('.pagi-next').click(function(){
  var numb = $('input[name="page_num"]').val();
  var max = $('input[name="page_num"]').attr('max');
  if(!(numb >= max)){
  $('input[name="page_num"]').val(numb*1+1);
  $('input[name="page_num"]').trigger('change');
  }
  }); */ ?>
            $("#clear_filters").click(function (e) {
                e.preventDefault();
                $('.cat_id').prop('checked', false);
                $('.attr_id').prop('checked', false);
                search_go();
                $('#alter_search').submit();
            });

            //odklik atrybutu
    //    $('.my_attr_click').click(function(e){
    //        e.preventDefault();
    //        var id = $(this).attr('attr_id');
    //        $(".attr_id[value^='"+id+"']").prop('checked',false);  
    //        $(this).find('input').prop('checked',false);
    //        //$(this).remove();
    //        console.log('action');
    //        search_go();
    //        $('#alter_search').submit();
    //        return 1;
    //    });

            //sortowanie
    //    $('.sort-type').click(function(e){
    //        e.preventDefault();
    //        var type = $(this).attr('data-sort');
    //        $('#alter_sort').val(type);
    //        search_go();
    //        $('#alter_search').submit();
    //    });
    //    search_go();
            $('input[id^="attr"]').change(function () {
                search_go();
                $('#alter_search').submit();
            });


    //    $('#zobacz_button').css('display','none');
    //   $('#szukajka').keyup(function(){
    //       search_go();
    //   }); 
    //   $('.search_go').click(function(){
    //       search_go();
    //       $('#alter_search').submit();
    //   });
    //   //mniej więcej
    //   $('.read_more').click(function(){
    //       var group_id = $(this).attr('group_id');
    //       var all = $(this).attr('all');
    //       if(all == 0){
    //           $('.more_'+group_id).css('display','block');
    //           $(this).attr('all',1);
    //           $(this).html("<?= (new CustomElementModel('9'))->getField('mniej'); ?>");
    //       } else {
    //           $('.more_'+group_id).css('display','none');
    //           $(this).attr('all',0);
    //           $(this).html("<?= (new CustomElementModel('9'))->getField('wiecej'); ?>");
    //       }
    //       
    //   });

            $('.number-of-products-select').change(function () {
                var pp = $(this).val();
                $('#alter_per_page').val(pp);
                $('#alter_search').submit();
            });
            
            $('.sort-by-select').change(function () {
                var sort = $(this).val();
                $('#alter_sort').val(sort);
                $('#alter_search').submit();
            });
            
            $('.filter-show-only-available').on('click', function () {
                var oa = $(this).find('input').is(':checked') ? 1 : '';
                $('#alter_only_avaible').val(oa);
                $('#alter_search').submit();
            });
            $('.filter-sale-button').on('click', function(){
                var sale = $(this).find('input').is(':checked') ? 1 : '';
                $('#alter_promo').val(sale);
                $('#alter_search').submit();
            });
        });
        function search_go() {

            ///my_filters();
    //    var str = $('#szukajka').val();
    //    var categories = '';
    //    var category_id = $(".cat_id").map(function(){
    //        if($(this).is(':checked')){
    //            var valu = $(this).val() ;
    //           categories += valu + '_';
    //            return $(this).val();
    //        }
    //    }).get();
            //category_id.push('<?= ''; //!empty($category) ? $category->offer_category_id : 'NULL'; ?>'); 

            var attributes_str = '';
            var attributes = $(".attr_id:checked").map(function () {
                attributes_str += $(this).val() + '_';
                return $(this).val();
            }).get();

    //    if(categories === ''){
    //        categories = '0';
    //    }

            $('#alter_attributes').val(attributes_str.substring(0, attributes_str.length - 1));
    //    $('#alter_str').val(str);

        }

<?php /* $(document).ready(function(){
  $(".zakres-cen").slider({
  range: true,
  min: <?= floor($price_range->min_price);?> ,
  max: <?= ceil($price_range->max_price);?>,
  step: 0.01,
  values: [ <?= !empty($_GET['min']) ? $_GET['min'] : floor($price_range->min_price);?>, <?= !empty($_GET['max']) ? $_GET['max'] : ceil($price_range->max_price);?> ],
  stop: function( event, ui ) {

  $( "#alter_min" ).val(ui.values[ 0 ]);
  $( "#alter_max" ).val(ui.values[ 1 ]);
  $('.zakres-cen-label span').html(ui.values[ 0 ]+" - "+ui.values[ 1 ]);
  search_go();
  $('#alter_search').submit();
  },
  slide: function(event,ui){
  $('.zakres-cen-label span').html(ui.values[ 0 ]+" - "+ui.values[ 1 ]);
  }
  });
  }); */ ?>
    </script>
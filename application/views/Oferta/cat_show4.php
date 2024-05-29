<div class="container">
    <?php $this->load->view('partials/breadcrumbs'); ?>
</div>
<div class="container">
            <h1 class="naglowek-podstrona wow fadeInLeft"><?= (new CustomElementModel('10'))->getField('wyszukiwarka'); ?></h1>             
</div>

<section class="sekcja-oferta">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <form method="POST" action="<?= site_url('oferta/main_search'); ?>" class="wyszukiwarka">
                    <input type="text" name="search" value="" placeholder="Szukaj produktu...."  />
                    <input type="submit" name="send" value="Znajdź" />
                </form>
            </div>
        </div>
        <?php
        if (!empty($products)) {
            ?>
            <div class="row">
                <div class="sekcja-filtry">
                    <form>
                        <?php
                        if (!empty($groups)):
                            foreach ($groups as $group):
                                $amount_active_attr_in_group = 0;
                                ?>
                                <div class="sekcja-filtry-poz">
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
                                                    <label class="filtry-poz-lbel <?= $checked ? 'checked' : ''; ?>" for="attr<?= $attr->id; ?>"><span></span><?= $attr->name; ?></label>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <div class="filltry-attr-box-down">
                                            Wybrano: <?= $amount_active_attr_in_group; ?>
                                            <?php if ($amount_active_attr_in_group != count($group['attributes'])): ?>
                                                <a class="pick_all" data-fgid="<?= $group['group']->id; ?>">Wybierz wszystkie</a>
            <?php else : ?>
                                                <a class="clear_all" data-fgid="<?= $group['group']->id; ?>">Wyczyść</a>
                                <?php endif; ?>
                                        </div>
                                    </div>
                                </div>               
        <?php endforeach; ?>  

                            <div class="sekcja-filtry-poz clear_everything">
                                <div class="filtry-name2">Wyczyść wszystkie</div>
                            </div>
    <?php endif; ?>
                    </form>
                </div>
            </div>
            <div class="row">
              <?php
                        foreach ($products as $pp): ?>
                    <div class="col-sm-12 col-md-4 col-lg-3"> <?php
                            $this->load->view('Oferta/partials/product_view', [ 'product' => $pp]); ?>
                        </div><?php
                        endforeach;
                        ?>
            </div>
            <div class="kasuj"></div>
         <?php /*   <div class="col-sm-12">
                <div class="custom-hr">
                </div>
                <div class="col-md-4 col-sm-12 per-page">
                    <span class="<?= (empty($_GET['per_page']) || $_GET['per_page'] == 16) ? 'aktiv' : ''; ?>">16</span>
                    <span class="<?= (!empty($_GET['per_page']) && $_GET['per_page'] == 32) ? 'aktiv' : ''; ?>">32</span> 
                    <span class="<?= (!empty($_GET['per_page']) && $_GET['per_page'] == 48) ? 'aktiv' : ''; ?>">48</span>
                </div>
                <div class="col-md-4 col-sm-12 text-center">
                    <div class="sort-select"> <a data-target="#sort-select-options" data-toggle="collapse">Sortowanie</a>
                        <div class="collapse" id="sort-select-options">
                            <ul>
                                <li data-sort="cena_rosnaco" class="<?= !empty($_GET['sort']) && $_GET['sort'] == 'cena_rosnaco' ? 'aktiv' : ''; ?>">Cena rosnąco</li>
                                <li data-sort="cena_malejaco" class="<?= !empty($_GET['sort']) && $_GET['sort'] == 'cena_malejaco' ? 'aktiv' : ''; ?>">Cena malejąco</li>
                                <li data-sort="alfabetycznie" class="<?= !empty($_GET['sort']) && $_GET['sort'] == 'alfabetycznie' ? 'aktiv' : ''; ?>">A-Z</li>
                                <li data-sort="odwrotnie_alfabetycznie" class="<?= !empty($_GET['sort']) && $_GET['sort'] == 'odwrotnie_alfabetycznie' ? 'aktiv' : ''; ?>">Z-A</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 text-right">
    <?= $this->pagination->create_links(); ?>
                </div>
            </div> */ ?>
        </div> 

        <form method="GET"  id="alter_search" action="<?= $permalink; ?>">
            <input type="hidden" name="attributes" value="<?= !empty($_GET['attributes']) ? $_GET['attributes'] : ''; ?>" id="alter_attributes"/>
            <input type="hidden" name="str" value="<?= !empty($_GET['str']) ? $_GET['str'] : ''; ?>" id="alter_str"/>
            <input type="hidden" name="sort" value="<?= !empty($_GET['sort']) ? $_GET['sort'] : ''; ?>" id="alter_sort" />
            <input type="hidden" name="per_page" value="<?= !empty($_GET['per_page']) ? $_GET['per_page'] : ''; ?>" id="alter_per_page" />
        </form>
<?php }else { ?>
    <p>Brak produktów spełniających kryteria. </p>
    <?php } ?>
    <?php /*
      </div>

      <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
      <div class="sekcja-oferta-bloki-all">
      <div class="container-fluid">
      <div class="row">

      <?php foreach($cat_children as $cat):
      $tcat = $cat->getTranslation(LANG);
      ?>
      <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
      <a href="<?= $cat->getPermalink();?>">
      <div class="sekcja-oferta-bloki">
      <div class="sekcja-oferta-bloki-ob">
      <div class="sekcja-oferta-bloki-ob-img" style="background-image:url('<?= $cat->getUrl();?>');"></div>
      </div>
      <h3 class="sekcja-oferta-bloki-nag"><?= $tcat->name; ?></h3>
      </div>
      </a>
      </div>
      <?php endforeach; ?>

      </div>
      <div class="row">

      <?php foreach($products as $prod):
      $this->load->view('Oferta/partials/product_view_big', ['product' => $prod]);
      endforeach; ?>
      </div>
      </div>
      </div>
      </div>
      </div>
      </div>
      </section>





      <?php  /*    <form method="GET"  id="alter_search" action="<?= $permalink;?>">
      <input type="hidden" name="attributes" value="<?= !empty($_GET['attributes']) ? $_GET['attributes'] : ''; ?>" id="alter_attributes"/>
      <!--                <input type="hidden" name="str" value="<?= !empty($_GET['str']) ? $_GET['str'] : ''; ?>" id="alter_str"/>
      <input type="hidden" name="sort" value="<?= !empty($_GET['sort']) ? $_GET['sort'] : ''; ?>" id="alter_sort" />-->
      </form>



      <script>
      $(document).ready(function(){
      //czyszczenie filtrów
      $("#clear_filters").click(function(e){
      e.preventDefault();
      $('.cat_id').prop('checked',false);
      $('.attr_id').prop('checked',false);
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
      $('input[id^="attr"]').change(function(){
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
      });

      function search_go(){

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
      //category_id.push('<?= ''; //!empty($category) ? $category->offer_category_id : 'NULL';?>');

      var attributes_str = '';
      var attributes = $(".attr_id:checked").map(function(){
      attributes_str += $(this).val() + '_';
      return $(this).val();
      }).get();

      //    if(categories === ''){
      //        categories = '0';
      //    }

      $('#alter_attributes').val(attributes_str.substring(0, attributes_str.length - 1));
      //    $('#alter_str').val(str);

      }
      <?php /*$(document).ready(function(){
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
      });
      </script>
     * 
     * */ ?>

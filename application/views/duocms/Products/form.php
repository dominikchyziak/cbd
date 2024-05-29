<?php
$this->load->model("ProductPhotoModel");
$pM = new ProductPhotoModel();
?>
<?php $this->load->view('duocms/Shop/menu'); ?>
<?php
    if(ENVIRONMENT == 'development'){
        new_custom_field_form('product', (!empty($product) ? $product->id : NULL));
    }
    ?>
<div class="panel panel-default">
    <div class="panel-heading"><strong><?= !empty($product) ? 'Edycja' : 'Dodawanie';?> oferty</strong>
        <?php if(!empty($product)) : ?>
        <a href="<?=  $product->getPermalink(); ?>" class="btn btn-info">Oferta</a>
        <?php if(!empty($allegro_id)) : ?>
        <a href="<?= get_option('admin_modules_allegro_login_link')."costam-i".$allegro_id. ".html"; ?>" class="btn btn-danger">Allegro</a>
        <?php endif; ?>
        <?php if(!empty($allegro_id)) : ?>
        <a href="<?= site_url('duocms/allegro/edit_auction/'. $product->id); ?>" class="btn btn-info pull-right">Edytuj aukcję</a>
        <?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="panel-body">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12">
                    <form action="" method="post">
                        <input type="hidden" name="action" value="add_product" />
                        <?php $languages = get_languages(); ?>
                        <div class="ui-tabs">
                            <ul>
                                <li><a href="#pl">Polski</a></li>
                                <?php
                                foreach ($languages as $lang) {
                                    if ($lang->short != "pl") {
                                        echo '<li><a href="#' . $lang->short . '">' . $lang->name . '</a></li>';
                                    }
                                }
                                ?>
                            </ul>
                            <div id="pl">
                                <?php if(!empty($product)) {$translation = $product->getTranslation('pl');} ?>
                                <?= !empty($translation) ? form_hidden('pl[id]', $translation->id) : ''; ?>
                           <?php if (get_option('admin_modules_product_new')) { ?>     
                                <p>Nowość:</p>
                                <?php echo form_dropdown('new', array('0' => 'Nie', '1' => 'Tak'), !empty($product) ? $product->new : 0, ' class="form-control" '); ?>
                            <?php } ?>  
                            <?php if (get_option('admin_modules_product_promo')) { ?>     
                                <p>Promocja:</p>
                                <?php echo form_dropdown('promo', array('0' => 'Nie', '1' => 'Tak'), !empty($product) ? $product->promo : 0, ' class="form-control" '); ?>
                            <?php } ?>            
                            <p>Widoczny w sliderze</p>
                            <select name="slider" class="form-control">
                                <option value="0" <?= !empty($product) && ($product->slider == 0) ? "selected" : ""; ?>>Nie</option>
                                <option value="1" <?= !empty($product) && $product->slider == 1 ? "selected" : ""; ?>>Tak</option>
                            </select>
                            <?php if (get_option('admin_modules_product_bestseller')) { ?>     
                                <p>Popluarny:</p>
                                <?php echo form_dropdown('bestseller', array('0' => 'Nie', '1' => 'Tak'), !empty($product) ? $product->bestseller : 0, ' class="form-control" '); ?>
                            <?php } ?> 
                            <?php if (get_option('admin_modules_product_category')) { ?>     
                            <p>Kategoria:</p>
                                <?php echo form_multiselect('categories[]', $categories, !empty($product) ? $product->categories : null, ' class="form-control" '); ?>
                            <?php } ?>
                            <?php if (get_option('admin_modules_product_producent')) { ?>
                            <p>Producent:</p>
                                <?php echo form_input('producent', !empty($product) ? $product->producent : '', 'class = "name form-control" '); ?>
                            <?php } ?> 
                            <?php if (get_option('admin_modules_product_code')) { ?> 
                                <p>Kod produktu:</p>
                                <?php echo form_input('code', !empty($product) ? $product->code : '', 'class = "name form-control" '); ?>
                            <?php } ?>
                            <?php if (get_option('admin_modules_product_name')) { ?> 
                                <p>Nazwa:</p>
                                <?php echo form_input('pl[name]', !empty($translation) ? $translation->name : '', 'class = "name form-control" '); ?>
                            <?php } ?>
                            <?php if (get_option('admin_modules_product_format')) { ?> 
                                <p>Format:</p>
                                <?php echo form_input(array(
                                    'name' => 'pl[format]', 
                                    'value' => !empty($translation) ? $translation->format : '', 
                                    'type' => 'number',
                                    'step' => '0.001',
                                    'min' => '0',
                                    'class' => "format form-control")); ?>
                            <?php } ?>
                                <?php /*
                                <p>
                                    Waga
                                </p>    
                                <?php echo form_input('weight', !empty($product) ? $product->weight : '', 'class = "form-control" '); */ ?>
                            <?php if (get_option('admin_modules_product_price')) { ?>     
                                <p>Cena:</p>
                                <?php if(!empty($currencies)) : 
                                    foreach($currencies as $cur) : ?>
                                <div class="col-sm-1"><?=$cur->code;?></div>
                                <div class="col-sm-11"><input type="number" value="<?= !empty($product) ? $product->getPrice($cur->id) : '0.00'; ?>" name="price[<?=$cur->id;?>]" step="0.01"  class = "price form-control"/></div>
                            <?php endforeach; endif;?>
                            <?php }?> 
                                
                                <p>Meta title:</p>
                                <?php echo form_input('pl[meta_title]', !empty($translation) ? $translation->meta_title : null,' class="form-control"'); ?>
                                <p>Meta description:</p>
                                <?php echo form_input('pl[meta_description]', !empty($translation) ? $translation->meta_description : null,' class="form-control"'); ?>
                                <p>Niestandarowy URL:</p>
                                <div class="row"><div class="col-sm-6"><?= site_url();?>produkt/</div><div class="col-sm-6">
                                <?php echo form_input('pl[custom_url]', !empty($translation) ? $translation->custom_url : null,' class="form-control"'); ?></div></div>   
                            <p>Stan magazynowy (wartość niedodatnia oznacza brak produktu):</p>
                            <?php echo form_input('quantity', !empty($product) ? $product->quantity : '', 'class = "quantity form-control" '); ?>
                           
                            <?php if (get_option('admin_modules_product_type')) { ?>     
                                <p>Typ</p>
                                <?php echo form_dropdown('type', $types, !empty($product) ? $product->type : '', " id='type' class='form-control' "); ?>
                            <?php } ?>
                            <?php if (get_option('admin_modules_product_slogan')) { ?> 
                                <p>Polecane w stanach:</p>
                                <?php echo form_input('pl[slogan]', !empty($translation) ? $translation->slogan : '', 'class="form-control"'); ?>
                            <?php } ?>
                            <?php if (get_option('admin_modules_product_body')) { ?> 
                                <p>Opis produktu:</p>
                                <?php echo form_textarea('pl[body]', !empty($translation) ? $translation->body : '', 'class="ckeditor"'); ?>
                            <?php } ?>
                                 <?php
                                 if(!empty($product->id)){
        //custom fields                    
                            get_custom_fields_form('product', !empty($product->id) ? $product->id : NULL, 'pl');
                                 } else {
                                     ?>
                                <br />
                                <div class="alert alert-info">Po dodaniu produktu będzie można dodać zdjęcia oraz ustawić dodatkowe parametry i atrybuty.</div>
                                         <?php
                                 }
                            ?>
                            <?php if (get_option('admin_modules_product_relations') && !empty($product)) {
                                $extra = array(
                                    'id' => 'prod_relations'
                                );?>
                                <p>Produkty powiązane</p>
                                <?php echo form_multiselect('relations[]', $products, $relations, $extra); ?>
                            <?php } ?>
                            <?php if (get_option('admin_modules_product_images') && !empty($product)) { ?>        
                                <p>Zdjęcia:</p>
                                <div id="uploader"></div>
                                <?php if (!empty($photos)): ?>
                                    <div class="product-gallery">
                                        <?php foreach ($photos as $photo): ?>
                                            <div class="item">
                                                <a href="<?php echo site_url('duocms/products/ajax_delete_photo/' . $photo->id); ?>"><i class="fa fa-times"></i></a>
                                                <img src="<?php echo $photo->getUrl('mini'); ?>" alt="">
                                                <input type="hidden" name="photo_order[]" value="<?php echo $photo->id; ?>">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            <?php } 
                            
                            ?>
                            </div>
                            <?php
                            foreach ($languages as $lang) {
                                if ($lang->short != "pl") {
                                    ?>
                                    <div id="<?= $lang->short; ?>">
                                        <?php if(!empty($product)) {
                                            $translation = $product->getTranslation($lang->short);
                                            echo form_hidden($lang->short . '[id]', $translation->id);
                                        }  ?>
                            <?php if (get_option('admin_modules_product_name')) { ?> 
                                        <p>Nazwa:</p>
                                        <?php echo form_input($lang->short . '[name]', !empty($translation) ? $translation->name : '', ' class="form-control"'); ?>
                             <?php } ?>    
								<p>Meta title:</p>
                                <?php echo form_input($lang->short . '[meta_title]', !empty($translation) ? $translation->meta_title : null,' class="form-control"'); ?>
                                <p>Meta description:</p>
                                <?php echo form_input($lang->short . '[meta_description]', !empty($translation) ? $translation->meta_description : null,' class="form-control"'); ?>
								<p>Niestandarowy URL:</p>
                                <div class="row"><div class="col-sm-6"><?= site_url();?>product/</div><div class="col-sm-6">
                                <?php echo form_input($lang->short.'[custom_url]', !empty($translation) ? $translation->custom_url : null,' class="form-control"'); ?></div></div>
							 
                             <?php if (get_option('admin_modules_product_format')) { ?> 
                                        <p>waga:</p>
                                        <?php echo form_input($lang->short . '[format]', !empty($translation) ? $translation->format : '', ' class="form-control"'); ?>
                             <?php } ?>   
                             <?php if (get_option('admin_modules_product_slogan')) { ?> 
                                        <p>Polecane w stanach:</p>
                                        <?php echo form_input($lang->short . '[slogan]', !empty($translation) ? $translation->slogan : '', ' class="form-control"'); ?>
                            <?php } ?>   
                            <?php if (get_option('admin_modules_product_body')) { ?> 
                                        <p>Treść:</p>
                                        <?php echo form_textarea($lang->short . '[body]', !empty($translation) ? $translation->body : '', 'class="ckeditor"'); ?>
                            <?php } 
                            get_custom_fields_form('product', !empty($product->id) ? $product->id : NULL, $lang->short);
                            ?>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <p>
                            <button type="submit" class="btn btn-primary" style="width: 50%;">Zapisz</button>
                            <a href="<?php echo site_url('duocms/products'); ?>" class="btn btn-warning" style="float: right;">Powrót</a>
                        </p>
                    </form>
                    
                <?php if (get_option('admin_modules_product_images_desc') && !empty($product)) { ?> 
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Opisy do zdjęć
                        </div>
                        <div class="panel-body">
                            
                        
      
                    <div class="">
                        <table>
                            <?php
                            if (!empty($photos)) {
                                foreach ($photos as $photo):
                                    ?>
                                    <tr>
                                        <td>
                                            <img src="<?php echo $photo->getUrl('mini'); ?>" alt="">
                                        </td>
                                        <td>
                                            <form method="POST">
                                                <input type="hidden" name="action" value="desc_photo" />
                                                <input type="hidden" name="id" value="<?= $photo->id; ?>" />
                                                <?php
                                                foreach ($languages as $lang) {
                                                    ?>
                                                    <label>Opis <?= $lang->short;?></label>
                                                <input type="text" name="description[<?= $lang->short;?>]" 
                                                       value="<?= !empty($pM->get_description($photo->id, $lang->short)->description) ? $pM->get_description($photo->id, $lang->short)->description : ""; ?>" />
                                                <?php
                                                }
                                                ?>
                                                <input type="submit" value="Opisz" />
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            }
                            ?>
                        </table>
                    </div>
                            </div>
                    </div>
                <?php } ?>    
                </div>
                <div class="col-sm-6">
                    <?php if (get_option('admin_modules_product_options') && !empty($product)) { ?> 
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Opcje
                        </div>
                        <div class="panel-body">
                            
                        
                    <form method="POST">
                        <input type="hidden" name="action" value="add_option" />
                        <div class="form-group">
                            <label for="name" class="label">Nazwa <br><small>(Widoczna na liście wyboru opcji danego produktu)</small></label>
                            <input type="text" name="name" value="" class="form-control" required="true" />
                        </div>
                        <div class="form-group">
                            <label for="description" class="label">Opis <br><small></small></label>
                            <input type="text" name="description" value="" class="form-control" required="true" />
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="price_change" class="label">Cena <br>
                                        <small>(Cena danej opcji)</small></label>
                                    <input type="number" step="0.01" name="price_change" value="<?= !empty($product->price) ? $product->price : '0.00'; ?>" class="form-control" required="true" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="quantity" class="label">Ilość <br>
                                        <small>(Ilość danej opcji. Jeśli nieograniczona -1)</small></label>
                                    <input type="number" step="1" name="quantity" value="-1" class="form-control" required="true" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <input type="checkbox" name="visibility" value="1" checked="true"/> Widoczna na stronie
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <input type="submit" name="add_option" value="Dodaj opcję" class="button" />
                                </div>
                            </div>
                        </div>
                    </form>
                    <form method="POST">
                        <input type="hidden" name="action" value="update_options"/>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped t1">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nazwa</th>
                                        <th>Opis</th>
                                        <th>Cena / <br>Cena przekreślona</th>
                                        <th>Waga</th>
                                        <th><!--Ilość<br>-->Pozostało</th>
                                        <th>Widoczna</th>
                                        <th></th>
                                        <th></th>
                                    </tr> 
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($product_options)) {
                                        foreach ($product_options as $option) {
                                            ?>
                                            <tr>
                                                <td><?= $option->id; ?>.</td>
                                                <td><?= $option->name; ?></td>
                                                <td><?= $option->description; ?></td>
                                                <td><input type="number" step="0.01" name="price[<?= $option->id; ?>]" value="<?= $option->price_change; ?>" style="width:65px;"/><br>
                                                <input type="number" step="0.01" name="old_price[<?= $option->id; ?>]" value="<?= $option->old_price; ?>"  style="width:65px;"/></td>
                                                <td><input type="number" step="0.001" name="weight[<?= $option->id; ?>]" value="<?= $option->weight; ?>"  style="width:65px;"/></td>
                                                <td><!--<?= $option->quantity; ?><br>-->
                                                    <input type="number" step="1" name="quantity_left[<?= $option->id; ?>]" value="<?= $option->quantity_left; ?>"  style="width:65px;"/>
                                                    </td>
                                                <td><?= $option->visibility; ?></td>
                                                <td>
                                                    <?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/products/delete_option/' . $option->id . '/' . $option->product_id)); ?> 
                                                </td>
                                                <td>
                                                    <?php printf(ADMIN_BUTTON_EDIT, site_url('duocms/products/edit_option/' . $option->id . '/' . $option->product_id)); ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                Brak opcji dla tego produktu
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <input type="submit" value="Aktualizuj" class="button" />     
                    </form>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if (get_option('admin_modules_product_attributes') && !empty($product)) { ?> 
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Atrybuty
                        </div>
                        <div class="panel-body">

                      <form method="POST">
                          <p>Atrybut</p>
                      <input type="hidden" name="action" value="add_attribute" />
                      
                      <select name="attribute" required="true" class="form-control attribute_select">
                      <option value="">Wybierz atrybut</option>
                      
                              <?php
                              if (!empty($groups)) {
                                  foreach ($groups as $group) {
                                      ?>
                          <optgroup label="<?= $group['group']->name;?>">
                              <?php
                              if(!empty($group['attributes'])){
                                  foreach($group['attributes'] as $attribute){
                                      ?>
                                <option value="<?= $attribute->id; ?>"><?= $attribute->name; ?></option>
                              <?php
                                  }
                              }
                              ?>      
                          </optgroup>            
                                      <?php
                                  }
                              }
                              ?>
                      
                      </select>
<!--                      <p>% zmiana ceny</p>
                      <input type="text" name="value" value="" class="form-control attribute_value" readonly=""/>-->
                      <input type="submit" value="Dodaj" class="button" />
                      </form>

                            <table class="table table-hover table-responsive table-striped">
                                <thead>
                                    <tr>
                                        <th>Nazwa</th>
<!--                                        <th>Cena</th>-->
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($product_attributes)) {
                                        foreach ($product_attributes as $p_at) {
                                            ?>
                                            <tr>
                                                <td><?= $p_at->name; ?></td>
<!--                                                <td><?= $p_at->value; ?> %</td>-->
                                                <td><?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/products/delete_attribute/' . $p_at->attribute_id . '/' . $p_at->product_id)); ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table> 
                            </div>
                    </div>
                    <?php } ?>
                    
                    <?php /*
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Dodatki
                        </div>
                        <div class="panel-body">
                            <p>mirror_width i mirror_height to 2 specjalne nazwy kategorii, na podstawie których obliczana jest cena lustra(nazwa => wyświetlana nazwa, nazwa wewn liczba centrymetrów bez jednoski,
                            cena niewykorzystana), dla pozostałych kategori nazwa kategori jest wyświetlana(jeśli chodzi o dodatki, to 'brak' dodatku jest automatycznie dodany, a nazwa wewn pominięta. </p>
                            <?php if(!empty($details)): ?>
                                <?php foreach($details as $group): ?>
                            <h4><?=$group->name; ?> <div class="pull-right"> <?php printf(ADMIN_BUTTON_EDIT, site_url('duocms/products/edit_detail_group/' . $group->id )); ?></div></h4>
                            <form method="POST">
                            <table class="table table-striped">
                                <thead>
                                    <tr><th>Nazwa</th>
                                        <th>Nazwa wewn</th>
                                        <th>Cena</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <?php foreach($group->details as $detail): ?>
                                <tr>
                                    <td><?= $detail->name; ?></td>
                                    <td><?= $detail->val; ?></td>
                                    <td><?= $detail->price; ?></td>
                                    <td>
                                         <?php printf(ADMIN_BUTTON_EDIT, site_url('duocms/products/edit_detail/' . $detail->id . '/' . $product->id )); ?>
                                         <?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/products/delete_detail/' . $detail->id )); ?> 
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td><input type="text" name="name" placeholder="Nazwa"></td>
                                    <td><input type="text" name="val" placeholder="Nazwa wewn."></td>
                                    <td><input type="number" step="0.01" name="price" placeholder="Cena"></td>
                                    <td><input type="hidden" name="action" value="add_detail" />
                                        <input type="hidden" name="group_id" value="<?= $group->id; ?>" />
                                        <input type="submit" value="Dodaj" class="form-control button btn-primary" />
                                    </td>
                                </tr>
                            </table>
                            </form>
                            <?php endforeach; ?>
                            <?php endif; ?>
                              <form method="POST">
                      <input type="hidden" name="action" value="add_detail_group" />
                      <input type="text" name="name" value="" placeholder="Dodaj nową grupę" class="form-control" />
                      <input type="submit" value="Dodaj" class="form-control button btn-primary" />
                      </form>
                            
                            </div>
                    </div> */ ?>
                    
                </div>
            </div>
        </div>


        <script>
            $('#type').on('change', function (e) {
                var optionSelected = $("option:selected", this);
                var valueSelected = this.value;
                if (valueSelected === "0") {
                    $('#uploader').hide();
                } else if (valueSelected === "1") {
                    $('#uploader').show();
                } else {
                    $('#uploader').show();
                }

            });
            domReadyQueue.push(function ($) {
                $('#uploader').pluploadQueue({
                    url: '<?php echo site_url('duocms/products/upload_photo'); ?>',
                    multipart_params: {
                        product_id: <?= !empty($product) ? $product->id : '0' ?>
                    }
                });

                $('.product-gallery .item a').click(function (e) {
                    e.preventDefault();

                    if (!confirm('Ta operacja jest nieodwracalna. Kontyunować?')) {
                        return false;
                    }

                    var self = $(this).parent();
                    var href = $(this).attr('href');

                    $.get(href, function (data) {
                        if (data.result == 1) {
                            self.remove();
                        }
                    }, 'JSON');
                });

                $('.product-gallery').sortable();
            });
            $(document).ready(function(){
                $('.attribute_select').change(function(){
                    var attribute_id = $('.attribute_select').val();
                    $.ajax({
                        url: '<?= site_url('duocms/Products_Attributes/ajax_get_default_value');?>/'+attribute_id,
                        success:function(value){
                            console.log(value);
                            $('.attribute_value').val(value);
                        }
                    });
                });
            });
            $(document).ready(function(){
            $('select[name="categories[]"').multiselect({
                   enableCaseInsensitiveFiltering: true
             });
            });
        </script>
    </div>
</div>
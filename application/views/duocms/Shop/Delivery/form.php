<?php $this->load->view('duocms/Shop/menu'); ?>

<div class="panel panel-default">
    <div class="panel-heading"><strong><?= !empty($delivery) ? 'Edycja' : 'Dodawanie';?> opcji dostawy</strong></div>
    <div class="panel-body">
        <form method="POST">
            <?php $languages = get_languages();?>
            <ul class="nav nav-tabs">
                <li><a href="#pl" data-toggle="tab">Polski</a></li>
			<?php
                        foreach($languages as $lang){
                            if($lang->short != "pl"){
                                echo '<li><a href="#' . $lang->short . '" data-toggle="tab">' . $lang->name . '</a></li>';
                            }
                        }
                        ?>
            </ul>

            <div class="tab-content">
                <div id="pl" class="tab-pane fade in active">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <p>Integracja</p>
                        <select name="category_id" class="form-control" id="category_id">
                            <option value="0" <?=(empty($delivery['category_id']))?'selected':'';?>>Brak</option>
                            <?php if(get_option('admin_module_enadawca_active')) : ?>
                            <option value="1" <?=(!empty($delivery['category_id']) && $delivery['category_id'] == 1)?'selected':'';?>>E-nadawca(Poczta Polska)</option>
                            <?php endif;?>
                            <?php if(get_option('admin_module_inpost_active')) : ?>
                            <option value="2" <?=(!empty($delivery['category_id']) &&$delivery['category_id'] == 2)?'selected':'';?>>Inpost</option>
                            <?php endif;?>
                            <?php if(get_option('admin_module_dpd_active')) : ?>
                            <option value="3" <?=(!empty($delivery['category_id']) &&$delivery['category_id'] == 3)?'selected':'';?>>DPD</option>
                            <?php endif; ?>
                            <?php if(get_option('admin_module_bliskapaczka_active')) : ?>
                            <option value="4" <?=(!empty($delivery['category_id']) &&$delivery['category_id'] == 4)?'selected':'';?>>Bliskapaczka</option>
                            <?php endif; ?>
                        </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <p>Rodzaj przesyłki/usługi</p>
                                <select type="text" name="special_name" id="special_name" class="form-control" >
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <p>Cena</p>
                                <?php if(!empty($currencies)) : 
                                    foreach($currencies as $cur) : ?>
                                <div class="col-sm-1"><?=$cur->code;?></div>
                                <div class="col-sm-11"><input type="number" value="<?= (!empty($delivery) && !empty($prices[$cur->id])) ? $prices[$cur->id]['price'] : '0.00'; ?>" name="prices[<?=$cur->id;?>]" step="0.01"  class = "price form-control"/></div>
                            <?php endforeach; endif;?>
                               
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <p>Darmowa od:</p>
                                  <?php if(!empty($currencies)) : 
                                    foreach($currencies as $cur) : ?>
                                <div class="col-sm-1"><?=$cur->code;?></div>
                                <div class="col-sm-11"><input type="number" value="<?= (!empty($delivery) && !empty($prices[$cur->id])) ? $prices[$cur->id]['max_price'] : '0.00'; ?>" name="max_prices[<?=$cur->id;?>]" step="0.01"  class = "price form-control"/></div>
                            <?php endforeach; endif;?>
                                
                            </div>
                        </div>
                    
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <p>Waga minimalna</p>
                                <input type="number" name="weight_min" value="<?= !empty($delivery['weight_min']) ? $delivery['weight_min'] : '0';?>" step="0.001" min="0" class="form-control" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                             <div class="form-group">
                                <p>Waga maksymalna</p>
                                <input type="number" name="weight_max" value="<?= !empty($delivery['weight_max']) ? $delivery['weight_max'] : '0';?>" step="0.001" min="0"  class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <p>Nazwa</p>
                        <input type="text" name="pl[name]" value="<?= !empty($delivery['translations']['pl']['name']) ? $delivery['translations']["pl"]['name'] : '';?>" class="form-control" />
                    </div>
                    <div class="form-group">
                        <p>Opis</p>
                        <textarea name="pl[description]" class="ckeditor" ><?= !empty($delivery['translations']['pl']['description']) ? $delivery['translations']['pl']['description'] : '';?></textarea>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="pl[visibility]" value="1" <?= !empty($delivery['translations']['pl']['visibility']) ? 'checked="1"' : '';?>/> Widoczna dla tego języka.
                    </div>
                    <br>
                    
                </div>
                <?php
                        foreach($languages as $lang){
                            if($lang->short != "pl"){
                ?>
                <div id="<?= $lang->short;?>" class="tab-pane fade">
                    <div class="form-group">
                        <p>Nazwa:</p>
                        <input type="text" name="<?= $lang->short;?>[name]" value="<?= !empty($delivery['translations'][$lang->short]['name']) ? $delivery['translations'][$lang->short]['name'] : '';?>">
                    </div>
                    <div class="form-group">
                        <p>Opis:</p>
			<textarea name="<?= $lang->short;?>[description]" class="ckeditor"><?= !empty($delivery['translations'][$lang->short]['description']) ? $delivery['translations'][$lang->short]['description'] : '';?></textarea>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="<?= $lang->short;?>[visibility]" value="1"  <?= !empty($delivery['translations'][$lang->short]['visibility']) ? 'checked="1"' : '';?>/> Widoczna dla tego języka.
                    </div>
		</div>
                <?php
                            }
                        }
                 ?>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <input type="submit" value="Zapisz" class="button" />
                 </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function(){
        var cat_id = $("#category_id").val();
        var special_name = <?= (!empty($delivery['special_name']))? "'".$delivery['special_name']."'" : 'null' ?>;
        if(cat_id == 1){
            $.ajax({
                url: "<?= site_url('duocms/delivery/ajax_get_pp_types_html');?>",
                dataType: 'JSON',
                method: 'GET',
                success: function(res){
                $('#special_name').append(res);
                if(special_name != 'null'){
                $('#special_name').val(special_name);
            }
                }
            });
            
        }  else if(cat_id == 2){
            $.ajax({
                url: "<?= site_url('duocms/delivery/ajax_get_inpost_types_html');?>",
                dataType: 'JSON',
                method: 'GET',
                success: function(res){
                $('#special_name').append(res);
                if(special_name != 'null'){
                $('#special_name').val(special_name);
            }
                }
            });
            
        } else if(cat_id == 4){
            $.ajax({
                url: "<?= site_url('duocms/delivery/ajax_get_bliskapaczka_types_html');?>",
                dataType: 'JSON',
                method: 'GET',
                success: function(res){
                $('#special_name').append(res);
                if(special_name != 'null'){
                $('#special_name').val(special_name);
            }
                }
            });
            
        }
    });
    $("#category_id").on('change', function(){
        $('#special_name').empty();
        if( this.value == 1 ){
            $.ajax({
                url: "<?= site_url('duocms/delivery/ajax_get_pp_types_html');?>",
                dataType: 'JSON',
                method: 'GET',
                success: function(res){
                $('#special_name').append(res);
                }
            });
        } else if( this.value == 2 ){
            $.ajax({
                url: "<?= site_url('duocms/delivery/ajax_get_inpost_types_html');?>",
                dataType: 'JSON',
                method: 'GET',
                success: function(res){
                $('#special_name').append(res);
                }
            });
        } else if( this.value == 4 ){
            $.ajax({
                url: "<?= site_url('duocms/delivery/ajax_get_bliskapaczka_types_html');?>",
                dataType: 'JSON',
                method: 'GET',
                success: function(res){
                $('#special_name').append(res);
                }
            });
        }
    });
</script>
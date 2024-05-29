<?php
$this->load->model("ProductPhotoModel");
$pM = new ProductPhotoModel();
?>
<?php $this->load->view('duocms/Shop/menu'); ?>

<div class="panel panel-default">
    <div class="panel-heading"><strong>Edycja produktu</strong></div>
    <div class="panel-body">
<div class="col-sm-12">
<div class="row">
    <div class="col-sm-12 col-md-7">
<form action="" method="post">
    <input type="hidden" name="action" value="add_product" />
	<?php $languages = get_languages();?>
	<div class="ui-tabs">
		<ul>
			<li><a href="#pl">Polski</a></li>
			<?php
                        foreach($languages as $lang){
                            if($lang->short != "pl"){
                                echo '<li><a href="#' . $lang->short . '">' . $lang->name . '</a></li>';
                            }
                        }
                        ?>
		</ul>
		<div id="pl">
			<?php $translation = $product->getTranslation('pl'); ?>
			<?php echo form_hidden('pl[id]', $translation->id); ?>
                        <p>Meta title:</p>
                        <?php echo form_input('pl[meta_title]', $translation->meta_title, ' class="form-control"'); ?>
                        <p>Meta description:</p>
                        <?php echo form_input('pl[meta_description]',$translation->meta_description,' class="form-control"'); ?>
                        <p>Niestandarowy URL:</p>
                        <?php echo form_input('pl[custom_url]',$translation->custom_url,' class="form-control"'); ?>
			<p>Kategoria:</p>
			<?php echo form_dropdown('offer_category_id', $categories, $product->offer_category_id); ?>
                        <p>Nazwa:</p>
			<?php echo form_input('pl[name]', $translation->name, 'class = "name" '); ?>
                        <p>Format:</p>
			<?php echo form_input('pl[format]', $translation->format, 'class = "format" '); ?>
                        <!--<p>Cena:</p>
                        <input type="number" value="<?= $product->price;?>" name="price" step="0.01"  class = "price"/> -->
                        <p>Typ</p>
                        <?php echo form_dropdown('type', $types, $product->type, " id='type'  ");?>
                        <p>Klasa:</p>
			<?php echo form_input('pl[slogan]', $translation->slogan, 'class = "slogan"  '); ?>
			
                        
			<p>Treść:</p>
			<?php echo form_textarea('pl[body]', $translation->body, 'class="ckeditor"'); ?>
			<p>Zdjęcia:</p>
			<div id="uploader"></div>
			<?php if ($photos): ?>
				<div class="product-gallery">
					<?php foreach ($photos as $photo): ?>
						<div class="item">
							<a href="<?php echo site_url('duocms/products/ajax_delete_photo/'.$photo->id); ?>"><i class="fa fa-times"></i></a>
							<img src="<?php echo $photo->getUrl('mini'); ?>" alt="">
							<input type="hidden" name="photo_order[]" value="<?php echo $photo->id; ?>">
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
            <?php
                        foreach($languages as $lang){
                            if($lang->short != "pl"){
                ?>
                <div id="<?= $lang->short;?>">
                        <?php $translation = $product->getTranslation($lang->short); ?>
			<?php echo form_hidden($lang->short.'[id]', $translation->id); ?>
			<p>Nazwa:</p>
			<?php echo form_input($lang->short.'[name]', $translation->name); ?>
                        <p>Format:</p>
			<?php echo form_input($lang->short.'[format]', $translation->format); ?>
                        <p>Klasa:</p>
			<?php echo form_input($lang->short.'[slogan]', $translation->slogan,' '); ?>
			<p>Treść:</p>
			<?php echo form_textarea($lang->short.'[body]', $translation->body, 'class="ckeditor"'); ?>
		</div>
                <?php
                            }
                        }
                 ?>
	</div>
	<p>
		<button type="submit" class="button" style="width: 50%;">Zapisz</button>
		<a href="<?php echo site_url('duocms/products'); ?>" class="button" style="float: right;">Powrót</a>
	</p>
</form>

<!--<h3>Opisy do zdjęć</h3>-->
<?php /*
<div class="">
    <table>
        <?php 
        if(!empty($photos)){
        foreach ($photos as $photo): ?>
        <tr>
            <td>
                <img src="<?php echo $photo->getUrl('mini'); ?>" alt="">
            </td>
            <td>
                <form method="POST">
                    <input type="hidden" name="action" value="desc_photo" />
                    <input type="hidden" name="id" value="<?= $photo->id;?>" />
                    <label>Opis polski</label>
                    <input type="text" name="description[pl]" value="<?= !empty($pM->get_description($photo->id, "pl")->description) ? $pM->get_description($photo->id, "pl")->description : "";?>" />
                    <!--<label>Opis angielski</label>
                    <input type="text" name="description[en]" value="<?= !empty($pM->get_description($photo->id, "en")->description) ? $pM->get_description($photo->id, "en")->description : "";?>" />
                    <label>Opis francuski</label>
                    <input type="text" name="description[fr]" value="<?= !empty($pM->get_description($photo->id, "fr")->description) ? $pM->get_description($photo->id, "fr")->description : "";?>" />
                    <label>Opis hiszpański</label>
                    <input type="text" name="description[es]" value="<?= !empty($pM->get_description($photo->id, "es")->description) ? $pM->get_description($photo->id, "es")->description : "";?>" />
                    <label>Opis niemiecki</label>
                    <input type="text" name="description[de]" value="<?= !empty($pM->get_description($photo->id, "de")->description) ? $pM->get_description($photo->id, "de")->description : "";?>" />
                    <label>Opis portugalski</label>
                    <input type="text" name="description[pt]" value="<?= !empty($pM->get_description($photo->id, "pt")->description) ? $pM->get_description($photo->id, "pt")->description : "";?>" />-->
                    <input type="submit" value="Opisz" />
                </form>
            </td>
        </tr>
        <?php endforeach; 
        }?>
    </table>
</div>
 * *
 */?>
    </div>
    <div class="col-sm-5">
        <h3>Opcje</h3>
        <!--<form method="POST">
            <input type="hidden" name="action" value="add_option" />
            <div class="form-group">
                <label for="name" class="label">Nazwa <br><small>(Widoczna na liście wyboru opcji danego produktu)</small></label>
                <input type="text" name="name" value="" class="form-control" required="true" />
            </div>
            <div class="form-group">
                <label for="description" class="label">Klasa <br><small></small></label>
                <input type="text" name="description" value="" class="form-control" required="true" />
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label for="price_change" class="label">Zmiana ceny <br>
                            <small>(Wartość, o którą zostanie zmieniona<br> cena bazowa po wyborze danej opcji)</small></label>
                        <input type="number" step="0.01" name="price_change" value="0" class="form-control" required="true" />
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label for="quantity" class="label">Ilość <br>
                            <small>(Ilość danej opcji.<br>Jeśli nieograniczona -1)</small></label>
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
        </form>-->
        <form method="POST">
            <input type="hidden" name="action" value="update_options"/>
        <div class="">
            <table class="table table-hover table-striped t1">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nazwa</th>
                        <th>Klasa</th>
                        <th>Cena</th>
                        <th>Stara cena</th>
                        <th>Waga</th>
                        <!--<th>Ilość</th>
                        <th>Pozostało</th>-->
                        <th>Widoczna</th>
                        <th></th>
                        <th></th>
                    </tr> 
                </thead>
                <tbody>
                    <?php
                    if(!empty($product_options)){
                        foreach($product_options as $option){
                            ?>
                        <tr>
                            <td><?= $option->id;?>.</td>
                            <td><?= $option->name;?></td>
                            <td><?= $option->description;?></td>
                            <td><input type="number" step="0.01" name="price[<?= $option->id;?>]" value="<?= $option->price_change;?>" style="width:65px;"/></td>
                            <td><input type="number" step="0.01" name="old_price[<?= $option->id;?>]" value="<?= $option->old_price;?>"  style="width:65px;"/></td>
                            <td><input type="number" step="0.001" name="weight[<?= $option->id;?>]" value="<?= $option->weight;?>"  style="width:65px;"/></td>
                            <!--<td><?= $option->quantity;?></td>
                            <td><?= $option->quantity_left;?></td>-->
                            <td><?= $option->visibility;?></td>
                            <td>
                                <!--<?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/products/delete_option/'.$option->id.'/'.$option->product_id)); ?> -->
                            </td>
                            <td>
                                <?php printf(ADMIN_BUTTON_EDIT, site_url('duocms/products/edit_option/'.$option->id.'/'.$option->product_id)); ?>
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
        <?php /*
        <h3>Atrybuty</h3>
        <form method="POST">
            <input type="hidden" name="action" value="add_attribute" />
            <select name="attribute" required="true" class="form-control">
                <option value="">Wybierz atrybut</option>
                <?php
                    if(!empty($attributes)){
                        foreach ($attributes as $attribute){
                            ?>
                <option value="<?= $attribute->id;?>"><?= $attribute->name;?></option>
                <?php  
                        }
                    }
                ?>
            </select>
            <!--<input type="text" value="" class="form-control"/>-->
            <input type="submit" value="Dodaj" class="button" />
        </form>
        <table class="table table-hover table-responsive table-striped">
            <thead>
                <tr>
                    <th>Nazwa</th>
                    <th>Cena</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(!empty($product_attributes)){
                    foreach ($product_attributes as $p_at){
                        ?>
                <tr>
                    <td><?= $p_at->name;?></td>
                    <td><?= $p_at->value;?> %</td>
                    <td><!--<?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/products/delete_attribute/'.$p_at->id.'/'.$p_at->product_id)); ?>--></td>
                </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>*/?>
    </div>
</div>
</div>


<script>
    $('#type').on('change', function (e) {
        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        if(valueSelected === "0"){
            $('#uploader').hide();
        } else if(valueSelected === "1"){
            $('#uploader').show();
        } else {
            $('#uploader').show();
        }
        
    });
	domReadyQueue.push(function ($) {
		$('#uploader').pluploadQueue({
			url: '<?php echo site_url('duocms/products/upload_photo'); ?>',
			multipart_params: {
				product_id: <?php echo $product->id ?>
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
</script>
    </div>
</div>
<?php $this->load->view('duocms/Shop/menu');?>
<h2><?= !empty($category) ? 'Edycja' : 'Dodawanie';?> kategorii produktów</h2>
<?php
    if(ENVIRONMENT == 'development'){
        new_custom_field_form('product_category', (!empty($category) ? $category->id : NULL));
    }
    ?>
<form action="" method="post" enctype="multipart/form-data">
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
                    <?php if (get_option('admin_modules_product_category_image')) { ?>   
			<?php if (!empty($category) && $category->image): ?>
				<p>
					<a href="<?php echo site_url('duocms/offer_categories/image_delete/'.$category->id); ?>" onclick="return confirm('Ta operacja jet nieodwracalna. Kontyunować?');">
						<img src="<?php echo $category->getUrl('mini'); ?>" alt="" style="border: 1px solid #666;">
					</a>
				</p>
				<p><em>Kliknij na zdjęcie aby usunąć.</em></p>
				<p>Nowy plik graficzny:</p>
			<?php endif; ?>
			<input type="file" name="image">
                    <?php 
                    } 
                    if (get_option('admin_modules_product_category_order')) {
                    ?>
			<p>Kolejność:</p>
			<?php echo form_input('order', !empty($category) ? $category->order : '', ' class="form-control" '); ?>
                    <?php 
                    } 
                    if (get_option('admin_modules_product_category_parent')) {
                    ?>
			<p>Rodzic:</p>
                        <p><?php echo form_dropdown('parent_id', $parents, !empty($category) ? $category->parent_id : null, ' class="form-control" '); ?></p>
                    <?php 
                    } 
                    ?>
                        <?php if(!empty($category)){ $translation = $category->getTranslation('pl');} ?>
			<?php echo form_hidden('pl[id]', !empty($translation) ? $translation->id : ''); 
                    if(!empty($category)){    
                    ?>
                        <p>Link do menu:<br> <?= $category->getPermalink();?></p>
                    <?php
                    }
                    if (get_option('admin_modules_product_category_allegro_id')) {
                    ?>     
			<p>Allegro id:</p>
			<?php echo form_input('allegro_id', !empty($category) ? $category->allegro_id : '', ' class="form-control" '); ?>
                    <?php 
                    } ?>
                        
                   
                    <?php    if (get_option('admin_modules_product_category_name')) {
                    ?>     
			<p>Nazwa:</p>
			<?php echo form_input('pl[name]', !empty($translation) ? $translation->name : '', ' class="form-control" '); ?>
                    <?php 
                    } ?>
                        <?php if(FALSE && !empty($category)) : ?>
                        <p>Wyświetlane filtry:</p>
                        <?php   $extra= array('id' => 'attr_categories'); 
                                $filtry = json_decode($category->filters);
                                echo form_multiselect('attr[]', $attribute_groups, $filtry, $extra); ?>
                        <?php endif;
                    if (get_option('admin_modules_product_category_slogan')) {
                    ?>    
                        <p>Slogan:</p>
			<?php echo form_input('pl[slogan]', !empty($translation) ?  $translation->slogan : '', ' class="form-control" '); ?>
                    <?php 
                    } 
                    if (get_option('admin_modules_product_category_body')) {
                    ?>
			<p>Opis:</p>
			<?php echo form_textarea('pl[body]',  !empty($translation) ? $translation->body : '', 'class="ckeditor"'); ?>
                    <?php 
                    } 
                    if (get_option('admin_modules_product_category_body1')) {
                    ?>
                        <p>Opis krótki:</p>
			<?php echo form_textarea('pl[body1]', !empty($translation) ?  $translation->body1 : '', 'class="ckeditor"'); ?>
                    <?php } 
                    get_custom_fields_form('product_category', !empty($category->id) ? $category->id : NULL, 'pl');
                    ?>
                       <p>Meta title:</p>
                    <?php echo form_input('pl[meta_title]', !empty($translation) ? $translation->meta_title : null,' class="form-control"'); ?>
                    <p>Meta description:</p>
                    <?php echo form_input('pl[meta_description]', !empty($translation) ? $translation->meta_description : null,' class="form-control"'); ?>
                    <p>Niestandarowy URL: <?php if(!empty($category->id)): ?><a class="btn btn-primary friendly_url_generator">Generuj</a><?php endif;?></p>
                    <?php echo form_input('pl[custom_url]', !empty($translation) ? $translation->custom_url : null,' class="form-control" id="custom_url"'); ?>
                      
                        
		</div>
                <?php
                        foreach($languages as $lang){
                            if($lang->short != "pl"){
                ?>
                <div id="<?= $lang->short;?>">
                        <?php if(!empty($category)) {$translation = $category->getTranslation($lang->short);} ?>
			<?php echo form_hidden($lang->short.'[id]', !empty($translation) ? $translation->id : ''); ?>
                    
                    <?php
                    if (get_option('admin_modules_product_category_name')) {
                    ?> 
			<p>Nazwa:</p>
			<?php echo form_input($lang->short.'[name]',  !empty($translation) ? $translation->name : '', ' class="form-control" '); ?>
                    <?php 
                    } 
                    if (get_option('admin_modules_product_category_slogan')) {
                    ?>   
                        <p>Slogan:</p>
			<?php echo form_input($lang->short.'[slogan]',  !empty($translation) ? $translation->slogan : '', ' class="form-control" '); ?>
                    <?php 
                    } 
                    if (get_option('admin_modules_product_category_body')) {
                    ?>
			<p>Opis:</p>
			<?php echo form_textarea($lang->short.'[body]',  !empty($translation) ? $translation->body : '', 'class="ckeditor"'); ?>
                    <?php 
                    } 
                    if (get_option('admin_modules_product_category_body1')) {
                    ?>
                        <p>Opis krótki:</p>
			<?php echo form_textarea($lang->short.'[body1]',  !empty($translation) ? $translation->body1 : '', 'class="ckeditor"'); ?>
                    <?php } 
                    get_custom_fields_form('product_category', !empty($category->id) ? $category->id : NULL, $lang->short);
                    ?>
		</div>
                <?php
                            }
                            
                        }
                 ?>
	</div>
	<p>
		<button type="submit" class="btn btn-primary" style="width: 50%;">Zapisz</button>
		<a href="<?php echo site_url('duocms/offer_categories'); ?>" class="btn btn-warning" style="float: right;">Powrót</a>
	</p>
</form>

<script>
$(document).ready(function(){
    $('#attr_categories').multiselect();
});    
</script>
<script>
    $(document).ready(function(){
        $('.friendly_url_generator').click(function(){
            $.ajax({
                method: 'GET',
                url: '<?= site_url('duocms/offer_categories/generate_friendly_url/'. $category->id);?>',
                dataType: 'text',
                success: function(res){
                    $('#custom_url').val(res);
                }
            });
        });
    });
</script>
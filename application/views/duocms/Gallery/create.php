<h2>Galeria</h2>
<?php
    if(ENVIRONMENT == 'development'){
        new_custom_field_form('gallery', (!empty($gallery) ? $gallery->id : NULL));
    }
    ?>
<?php $languages = get_languages();?>
<form action="" method="post">
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
                 
                    <p>Kolejność</p>
                        <?php echo form_input('order', 0,' class="form-control"'); ?>
                    <p>Kategoria</p>
                    <select name="category" class="form-control">
                        <option value="0">Brak</option>
                        <option value="1">Realizacje</option>
                        <option value="-1">Zintegrowane</option>
                    </select>
			<p>Nazwa:</p>
			<?php echo form_input('pl[name]',null,' class="form-control"'); ?>
                        <p>Opis</p>
                        <textarea name="pl[description]" class="ckeditor"></textarea>
			<p>Zdjęcia:</p>
			<p><em>Dodawanie zdjęć będzie możliwe po zapisaniu galerii.</em></p>
                           <p>Meta title:</p>
			<?php echo form_input('pl[meta_title]',null,' class="form-control"'); ?>
                    <p>Meta description:</p>
			<?php echo form_input('pl[meta_description]',null,' class="form-control"'); ?>
                    <p>Niestandarowy URL:</p>
                    <div class="row">
                <div class="col-sm-4">
            <?= site_url('realizacje');?>/</div><div class="col-sm-4">
			<?php echo form_input('pl[custom_url]',null,' class="form-control"'); ?></div></div>
                        <?php
                        get_custom_fields_form('gallery', !empty($gallery->id) ? $gallery->id : NULL, 'pl');
                        ?>
		</div>
            <?php
                        foreach($languages as $lang){
                            if($lang->short != "pl"){
                ?>
            <div id="<?= $lang->short;?>">
			<p>Nazwa:</p>
			<?php echo form_input( $lang->short.'[name]'); ?>
                        <textarea name="<?= $lang->short;?>[description]" class="ckeditor"></textarea>
                        
                         <?php 
                            get_custom_fields_form('gallery', !empty($gallery->id) ? $gallery->id : NULL, $lang->short);
                            ?>
		</div>
                <?php
                            }
                        }
                 ?>
	</div>
	<p>
		<button type="submit" class="btn btn-primary" style="width: 50%;">Zapisz</button>
		<a href="<?php echo site_url('duocms/gallery'); ?>" class="btn btn-primary" style="float: right;">Powrót</a>
	</p>
</form>

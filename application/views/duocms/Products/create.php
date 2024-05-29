<?php $this->load->view('duocms/Shop/menu'); ?>
<div class="header">Oferta: Elementy</div>
<form action="" method="post">
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
                    <p>Meta title:</p>
                    <?php echo form_input('pl[meta_title]',null,' class="form-control"'); ?>
                    <p>Meta description:</p>
                    <?php echo form_input('pl[meta_description]',null,' class="form-control"'); ?>
                    <p>Niestandarowy URL:</p>
                    <?php echo form_input('pl[custom_url]',null,' class="form-control"'); ?>
                    <p>Kategoria:</p>
                    <?php echo form_dropdown('offer_category_id', $categories); ?>
                    <p>Nazwa:</p>
                    <?php echo form_input('pl[name]'); ?>
                    <p>Format</p>
                    <?php echo form_input('pl[format]'); ?>
                    <!--<p>Cena:</p>
                    <input type="number" value="" name="price" step="0.01" />-->
                    <p>Klasa:</p>
                    <?php echo form_input('pl[slogan]'); ?>
                    <p>Typ</p>
                    <?php echo form_dropdown('type', $types);?>
                    <p>Treść:</p>
                    <?php echo form_textarea('pl[body]', null, 'class="ckeditor"'); ?>
                    <p>Zdjęcia:</p>
                    <p><em>Dodawanie zdjęć będzie możliwe po zapisaniu produktu.</em></p>
		</div>
                 <?php
                        foreach($languages as $lang){
                            if($lang->short != "pl"){
                ?>
                <div id="<?= $lang->short;?>">
			<p>Nazwa:</p>
			<input type="text" name="<?= $lang->short;?>[name]">
                        <p>Format:</p>
                        <input type="text" name="<?= $lang->short;?>[format]">
                        <p>Klasa:</p>
			<input type="text" name="<?= $lang->short;?>[slogan]">
			<p>Opis:</p>
			<textarea name="<?= $lang->short;?>[body]" class="ckeditor"></textarea>
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
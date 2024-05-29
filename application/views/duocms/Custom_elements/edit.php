<h2><?php echo $element->name; ?></h2>
 <a href="<?= base_url('duocms/custom_elements');?>" class="btn btn-primary">< Powrót</a> 
<br>
<?php $languages = get_languages();?>
<form action="" method="post" enctype="multipart/form-data">
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
			<?php foreach ($fields['pl'] as $field): ?>
				<?php $this->load->view('duocms/Custom_elements/field', ['field' => $field, 'element' => $element]); ?>
			<?php endforeach ?>
		</div>
            <?php
                        foreach($languages as $lang){
                            if($lang->short != "pl"){
                ?>
                <div id="<?= $lang->short;?>">
                        <?php foreach ($fields[$lang->short] as $field): ?>
				<?php $this->load->view('duocms/Custom_elements/field', ['field' => $field, 'element' => $element]); ?>
			<?php endforeach ?>
		</div>
                <?php
                            }
                        }
                 ?>
	</div>
	<p>
		<button type="submit" class="btn btn-primary" style="width: 50%;">Zapisz</button>
		<a href="<?php echo site_url('duocms/custom_elements'); ?>" class="btn btn-primary" style="float: right;">Powrót</a>
                <a href="<?= base_url('duocms/custom_elements/add_element/'. $element->id);?>" class="btn btn-warning">Dodaj element</a>
	</p>
</form>

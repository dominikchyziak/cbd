<h2>Strona</h2>
<?php $languages = get_languages(); ?>
<form action="" method="post" enctype="multipart/form-data">
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
			<?php $translation = $page->getTranslation('pl'); ?>
			<input type="hidden" name="pl[id]" value="<?php echo $translation->id; ?>">
                        <p>Link do menu:<br> /page/<?= getAlias($page->id, $translation->title);?></p>
                        <?php /*<p>Kategoria:</p>
                        <select name="category" class="form-control">
                            <option value="0">Brak</option>
                            <option value="1" <?= $page->category == 1 ? 'selected' : '';?>>Eventowo</option>
                            <option value="2" <?= $page->category == 2 ? 'selected' : '';?>>Kreatywnie</option>
                        </select> */?>
                        <p>Meta title:</p>
                        <?php echo form_input('pl[meta_title]', $translation->meta_title, ' class="form-control"'); ?>
                        <p>Meta description:</p>
                        <?php echo form_input('pl[meta_description]',$translation->meta_description,' class="form-control"'); ?>
                        <p>Niestandarowy URL:</p>
                        <div class="row"><div class="col-sm-4"><?= base_url();?></div><div class="col-sm-4">
                        <?php echo form_input('pl[custom_url]',$translation->custom_url,' class="form-control"'); ?></div></div>
                         
                        <?php /*<p>Powiązana galeria</p>
            <?= form_dropdown('gallery', $gallery_dropdown, $gallery, ['class' => 'form-control']); ?>
                         * */ ?>
			<p>Tytuł:</p>
			<input type="text" name="pl[title]" value="<?php echo htmlspecialchars($translation->title); ?>" class="form-control">
			<p>Treść:</p>
			<textarea name="pl[body]" class="ckeditor"><?php echo $translation->body; ?></textarea>
		</div>
                <?php
                foreach ($languages as $lang) {
                    if ($lang->short != "pl") {
                        ?>
                        <div id="<?= $lang->short; ?>">
                            <?php $translation = $page->getTranslation($lang->short); ?>
                            <input type="hidden" name="<?= $lang->short; ?>[id]" value="<?php echo $translation->id; ?>">
							<p>Niestandarowy URL:</p>
							<div class="row"><div class="col-sm-4"><?= base_url();?></div><div class="col-sm-4">
							<?php echo form_input($lang->short.'[custom_url]',$translation->custom_url,' class="form-control"'); ?></div></div>
							<p>Tytuł:</p>
                            <input type="text" name="<?= $lang->short; ?>[title]" value="<?php echo htmlspecialchars($translation->title); ?>" class="form-control">
                            <p>Treść:</p>
                            <textarea name="<?= $lang->short; ?>[body]" class="ckeditor"><?php echo $translation->body; ?></textarea>
                        </div>
                        <?php
                    }
                }
                ?>
	</div>
	<p>
		<button type="submit" class="btn btn-primary" style="width: 50%;">Zapisz</button>
		<a href="<?php echo site_url('duocms/pages/get'); ?>" class="btn btn-warning" style="float: right;">Powrót</a>
	</p>
</form>
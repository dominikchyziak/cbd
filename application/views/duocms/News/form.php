<h2><?= !empty($news) ? 'Edycja' : 'Dodawanie';?> wpisu na blogu</h2>
<?php
    if(ENVIRONMENT == 'development'){
        new_custom_field_form('news', (!empty($wizerunek) ? $wizerunek->id : NULL));
    }
    ?>
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
        <?php if(!empty($news)) { $translation = $news->getTranslation('pl');} ?>
        <div id="pl">
            <input type="hidden" name="pl[id]" value="<?= !empty($translation) ? $translation->id : ''; ?>">
   
        <?php if (get_option('admin_modules_news_created_at')) { ?>   
            <p>Data publikacji:</p>
            <input type="text" name="started_at[date]" class="datepicker" style="width: 200px; display: inline-block" value="<?= !empty($news) ? (new DateTime($news->started_at))->format('Y-m-d') : date('Y-m-d'); ?>">
            <input type="text" name="started_at[hour]" style="width: 50px; display: inline-block" value="<?= !empty($news) ? (new DateTime($news->started_at))->format('H') : date('H'); ?>"> :
            <input type="text" name="started_at[minute]" style="width: 50px; display: inline-block" value="<?= !empty($news) ? (new DateTime($news->started_at))->format('i') : date('i'); ?>">
        <?php } ?>
        <?php if (get_option('admin_modules_news_title')) { ?> 
            <p>Tytuł:</p>
            <input type="text" name="pl[title]" value="<?= !empty($translation) ? htmlspecialchars($translation->title) : ''; ?>" class="form-control">
        <?php } ?>
        <?php /*if (get_option('admin_modules_news_category')) { ?> 
            <p>Kategoria</p>
            <select name="category_id" class="form-control">
                <option value="1" <?= !empty($news) && $news->category_id == 1 ? "selected" : ""; ?>>Odbiorcy wyceny</option>
                <option value="2" <?= !empty($news) && $news->category_id == 2 ? "selected" : ""; ?>>Banki</option>
                <option value="3" <?= !empty($news) && $news->category_id == 3 ? "selected" : ""; ?>>Moi klienci</option>
            </select>
            <?php } */?>
        <?php if (get_option('admin_modules_news_excerpt')) { ?> 
            <p>Wstęp:</p>
            <textarea name="pl[excerpt]" class="ckeditor"><?= !empty($translation) ? $translation->excerpt : ''; ?></textarea>
            <?php } ?>
        <?php if (get_option('admin_modules_news_body')) { ?> 
            <p>Treść:</p>
            <textarea name="pl[body]" class="ckeditor"><?= !empty($translation) ? $translation->body : ''; ?></textarea>
            <?php } ?>
        <?php if (get_option('admin_modules_news_image')) { ?> 
            <p>Zdjęcie:</p>
            <input type="text" name="pl[image]" value="<?= !empty($translation) ? htmlspecialchars($translation->image) : ''; ?>" onclick="openKCFinderImage(this, 'images')"  class="form-control" readonly>
        <?php } 
        get_custom_fields_form('news', !empty($news->id) ? $news->id : NULL, 'pl');
        ?>         <p>Meta title:</p>
            <?php echo form_input($lang->short .'[meta_title]', !empty($translation) ? $translation->meta_title : null,' class="form-control"'); ?>
            <p>Meta description:</p>
            <?php echo form_input($lang->short .'[meta_description]', !empty($translation) ? $translation->meta_description : null,' class="form-control"'); ?>
            <p>Niestandarowy URL:</p>
            <div class="row">
                <div class="col-sm-4">
                    <?= site_url('blog'); ?>/
                </div>
                <div class="col-sm-4">
            <?php echo form_input('pl[custom_url]', !empty($translation) ? $translation->custom_url : null,' class="form-control"'); ?>
                </div>
            </div>
        </div>
        <?php
        foreach ($languages as $lang) {
            if ($lang->short != "pl") {
                ?>
                <?php if(!empty($news)) {$translation = $news->getTranslation($lang->short);} ?>
                <div id="<?= $lang->short; ?>">
                    <input type="hidden" name="<?= $lang->short; ?>[id]" value="<?= !empty($translation) ? $translation->id : ''; ?>" class="form-control">
        <?php if (get_option('admin_modules_news_title')) { ?>             
                    <p>Tytuł:</p>
                    <input type="text" name="<?= $lang->short; ?>[title]" value="<?= !empty($translation) ? htmlspecialchars($translation->title) : ''; ?>" class="form-control">
                    <?php } ?>
        <?php if (get_option('admin_modules_news_excerpt')) { ?> 
                    <p>Wstęp:</p>
                    <textarea name="<?= $lang->short; ?>[excerpt]" class="ckeditor"><?= !empty($translation) ? $translation->excerpt : ''; ?></textarea>
                    <?php } ?>
        <?php if (get_option('admin_modules_news_body')) { ?> 
                    <p>Treść:</p>
                    <textarea name="<?= $lang->short; ?>[body]" class="ckeditor"><?= !empty($translation) ? $translation->body : ''; ?></textarea>
                    <?php } ?>
        <?php if (get_option('admin_modules_news_image')) { ?> 
            <p>Zdjęcie:</p>
            <input type="text" name="<?= $lang->short; ?>[image]" value="<?= !empty($translation) ? htmlspecialchars($translation->image) : ''; ?>" onclick="openKCFinderImage(this, 'images')"  class="form-control" readonly>
                    <?php } 
                    get_custom_fields_form('news', !empty($news->id) ? $news->id : NULL, $lang->short);
                    ?>
					
				<p>Niestandarowy URL:</p>
				<div class="row">
					<div class="col-sm-4">
						<?= site_url('blog'); ?>/
					</div>
					<div class="col-sm-4">
				<?php echo form_input($lang->short .'[custom_url]', !empty($translation) ? $translation->custom_url : null,' class="form-control"'); ?>
					</div>
				</div>

                </div>
                <?php
            }
        }
        ?>


    </div>
    <p>
        <button type="submit" class="btn btn-success" style="width: 50%;">Zapisz</button>
        <a href="<?php echo site_url('duocms/news'); ?>" class="btn btn-warning" style="float: right;">Powrót</a>
    </p>
</form>
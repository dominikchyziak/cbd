<h2>Dodaj podstronę</h2>
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
            <?php /*<p>Kategoria:</p>
            <select name="category" class="form-control">
                <option value="0">Brak</option>
                <option value="1">Eventowo</option>
                <option value="2">Kreatywnie</option>
            </select> */?>
            <p>Meta title:</p>
            <?php echo form_input('pl[meta_title]',null,' class="form-control"'); ?>
            <p>Meta description:</p>
            <?php echo form_input('pl[meta_description]',null,' class="form-control"'); ?>
            <p>Niestandarowy URL:</p>
            <div class="row"><div class="col-sm-4"><?= base_url(); ?></div><div class="col-sm-4">
            <?php echo form_input('pl[custom_url]',null,' class="form-control"'); ?></div></div>
            <?php /*
            <p>Powiązana galeria</p>
            <?= form_dropdown('gallery', $gallery_dropdown, null, ['class' => 'form-control']); */ ?>
            <p>Tytuł:</p>
            <input type="text" name="pl[title]" value="" placeholder="Tytuł podstrony" required="true" class="form-control">
            <p>Treść:</p>
            <textarea name="pl[body]" class="ckeditor"></textarea>
        </div>
        <?php
        foreach ($languages as $lang) {
            if ($lang->short != "pl") {
                ?>
                <div id="<?= $lang->short; ?>">
					<p>Niestandarowy URL:</p>
					<div class="row"><div class="col-sm-4"><?= base_url(); ?></div><div class="col-sm-4">
					<?php echo form_input($lang->short . '[custom_url]',null,' class="form-control"'); ?></div></div>
                    <p>Tytuł:</p>
                    <input type="text" name="<?= $lang->short; ?>[title]" value="" placeholder="Tytuł angielski" class="form-control">
                    <p>Treść:</p>
                    <textarea name="<?= $lang->short; ?>[body]" class="ckeditor"></textarea>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <p>
        <button type="submit" class="btn btn-success" style="width: 50%;">Dodaj podstronę</button>
        <a href="<?php echo site_url('duocms/pages/get'); ?>" class="btn btn-warning" style="float: right;">Powrót</a> 
    </p>
</form>
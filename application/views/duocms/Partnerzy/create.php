<div class="header">Partner</div>
<?php $languages = get_languages(); ?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="ui-tabs">
        <ul>
            <li><a href="#pl">Polski</a></li>

        </ul>
        <div id="pl">
            <p>Plik graficzny:</p>
            <input type="file" name="image">
            <p>Kolejność:</p>
            <input type="text" name="order">
            <p>Url:</p>
            <input type="text" name="url">
        </div>
    </div>
    <p>
        <button type="submit" class="button" style="width: 50%;">Zapisz</button>
        <a href="<?php echo site_url('duocms/partnerzy'); ?>" class="button" style="float: right;">Powrót</a>
    </p>
</form>
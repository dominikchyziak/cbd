<div class="header">partnerzy</div>
<?php $languages = get_languages(); ?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="ui-tabs">
        <ul>
            <li><a href="#pl">Polski</a></li>
        </ul>
        <div id="pl">
            <p>Plik graficzny:</p>
            <?php if ($partnerzy->image): ?>
                <p>
                    <a href="<?php echo site_url('duocms/partnerzy/image_delete/' . $partnerzy->id); ?>" onclick="return confirm('Ta operacja jet nieodwracalna. Kontyunować?');">
                        <img src="<?php echo $partnerzy->getUrl('mini'); ?>" alt="" style="border: 1px solid #666;">
                    </a>
                </p>
                <p><em>Kliknij na zdjęcie aby usunąć.</em></p>
                <p>Nowy plik graficzny:</p>
            <?php endif; ?>
            <input type="file" name="image">
            <p>Kolejność:</p>
            <input type="text" name="order" value="<?php echo htmlspecialchars($partnerzy->order); ?>">
            <p>Url:</p>
            <input type="text" name="url" value="<?php echo htmlspecialchars($partnerzy->url); ?>">
        </div>

    </div>
    <p>
        <button type="submit" class="button" style="width: 50%;">Zapisz</button>
        <a href="<?php echo site_url('duocms/partnerzy'); ?>" class="button" style="float: right;">Powrót</a>
    </p>
</form>
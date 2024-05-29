<div class="header">
    <h2>Wizerunek</h2>
    <?php
    if(ENVIRONMENT == 'development'){
        new_custom_field_form('wizerunek', (!empty($wizerunek) ? $wizerunek->id : NULL));
    }
    ?>
</div>
<?php $languages = get_languages(); ?>
<form action="" method="POST" enctype="multipart/form-data">
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
            <p>Plik graficzny:</p>
            <?php if (!empty($wizerunek->image)): ?>
                <p>
                    <a href="<?php echo site_url('duocms/wizerunek/image_delete/' . $wizerunek->id); ?>" onclick="return confirm('Ta operacja jet nieodwracalna. Kontyunować?');">
                        <img src="<?php echo $wizerunek->getUrl('mini'); ?>" alt="" style="border: 1px solid #666;">
                    </a>
                </p>
                <p><em>Kliknij na zdjęcie aby usunąć.</em></p>
                <p>Nowy plik graficzny:</p>
            <?php endif; ?>
            <input type="file" name="image">
            <?php if (get_option('admin_modules_slider_order')) { ?>   
                <p>Kolejność:</p>
                <input type="text" name="order" value="<?= !empty($wizerunek->order) ? htmlspecialchars($wizerunek->order) : ''; ?>" class="form-control">
            <?php }?>    
                <?php if(!empty($wizerunek)) {$translation = $wizerunek->getTranslation('pl');} ?>     
                <input type="hidden" name="pl[id]" value="<?= !empty($translation->id) ? $translation->id : ''; ?>">
            <?php if (get_option('admin_modules_slider_title')) { ?>    
                <p>Tytuł:</p>
                <input type="text" name="pl[title]" value="<?= !empty($translation->title) ? htmlspecialchars($translation->title) : ''; ?>" class="form-control">
            <?php } ?>
            <?php if (get_option('admin_modules_slider_body')) { ?>  
                <p>Treść:</p>
                <input type="text" name="pl[body]" value="<?= !empty($translation->body) ? htmlspecialchars($translation->body) : ''; ?>" class="form-control">
            <?php } ?>
            <?php if (get_option('admin_modules_slider_href')) { ?>  
                <p>Odnośnik:</p>
                <input type="text" name="pl[href]" value="<?= !empty($translation->href) ? htmlspecialchars($translation->href) : ''; ?>" class="form-control">
            <?php } 
            ?>
                <?php /*
                <p>Kolor tytułu:</p>
                <input type="color" name="pl[color1]" value="<?= !empty($translation->color1) ? $translation->color1 : ''; ?>" class="form-control"/>
                
                <p>Kolor treści:</p>
                <input type="color" name="pl[color2]" value="<?= !empty($translation->color2) ? $translation->color2 : ''; ?>" class="form-control"/>
                 * */ ?>
                <?php
 get_custom_fields_form('wizerunek', !empty($wizerunek->id) ? $wizerunek->id : NULL, 'pl');
            ?>
                
        </div>
        <?php
        foreach ($languages as $lang) {
            if ($lang->short != "pl") {
                ?>
                <div id="<?= $lang->short; ?>">
                    <?php  if(!empty($wizerunek)) {$translation = $wizerunek->getTranslation($lang->short);} ?>
                    <input type="hidden" name="<?= $lang->short; ?>[id]" value="<?= !empty($translation->id) ?  $translation->id : ''; ?>" class="form-control">
                    <?php if (get_option('admin_modules_slider_title')) { ?>
                        <p>Tytuł:</p>
                        <input type="text" name="<?= $lang->short; ?>[title]" value="<?= !empty($translation->title) ?  htmlspecialchars($translation->title) : ''; ?>" class="form-control">
                    <?php } ?>
                    <?php if (get_option('admin_modules_slider_body')) { ?> 
                        <p>Treść:</p>
                        <input type="text" name="<?= $lang->short; ?>[body]" value="<?= !empty($translation->body) ? htmlspecialchars($translation->body) : ''; ?>" class="form-control">
                    <?php } ?>
                    <?php if (get_option('admin_modules_slider_href')) { ?>  
                        <p>Odnośnik:</p>
                        <input type="text" name="<?= $lang->short; ?>[href]" value="<?= !empty($translation->href) ? htmlspecialchars($translation->href) : ''; ?>" class="form-control">
                    <?php } 
                     get_custom_fields_form('wizerunek', !empty($wizerunek->id) ? $wizerunek->id : NULL, $lang->short);
                    ?>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <p>
        <button type="submit" class="btn btn-primary btn-block" style="width: 50%;">Zapisz</button>
        <a href="<?php echo site_url('duocms/wizerunek'); ?>" class="btn btn-warning pull-right">Powrót</a>
    </p>
</form>
<?php
$this->load->model("PhotoModel");
$pM = new PhotoModel();
$languages = get_languages();
?>
<h2>Edycja galerii: <?= $gallery->getTranslation('pl')->name; ?></h2>
<?php
    if(ENVIRONMENT == 'development'){
        new_custom_field_form('gallery', (!empty($gallery) ? $gallery->id : NULL));
    }
    ?>
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
            <?php $translation = $gallery->getTranslation('pl'); ?>
          
                <p>Kolejność</p>
            <?php echo form_input('order', $gallery->order, ' class="form-control"'); ?>
            <p>Kategoria</p>
            <select name="category" class="form-control">
                <option value="0" <?= $gallery->category == 0 ? 'selected' : '';?>>Brak</option> 
               <option value="1" <?= $gallery->category == 1 ? 'selected' : '';?>>Realizacje</option> 
                <option value="-1" <?= $gallery->category == -1 ? 'selected' : '';?>>Zintegrowane</option>     
            </select>
            <p>Nazwa:</p>
            <?php echo form_input('pl[name]', $translation->name, ' class="form-control"'); ?>
            <p>Opis</p>
            <textarea name="pl[description]" class="ckeditor"><?= $translation->description;?></textarea>
              <?php echo form_hidden('pl[id]', $translation->id); ?>
            <p>Meta title:</p>
            <?php echo form_input('pl[meta_title]', $translation->meta_title, ' class="form-control"'); ?>
            <p>Meta description:</p>
            <?php echo form_input('pl[meta_description]',$translation->meta_description,' class="form-control"'); ?>
            <p>Niestandarowy URL:</p>
            <div class="row">
                <div class="col-sm-4">
            <?= site_url('realizacje');?>/</div><div class="col-sm-4"><?php echo form_input('pl[custom_url]',$translation->custom_url,' class="form-control"'); ?>
            </div></div>
                <?php
                        get_custom_fields_form('gallery', !empty($gallery->id) ? $gallery->id : NULL, 'pl');
                        ?>
            <p>Zdjęcia:</p>
            <div id="uploader"></div>
            <?php if ($photos): ?>
                <div class="product-gallery">
                    <?php foreach ($photos as $photo): ?>
                        <div class="item">
                            <a href="<?php echo site_url('duocms/gallery/ajax_delete_photo/' . $photo->id); ?>"><i class="fa fa-times"></i></a>
<!--                            <img src="<?php echo $photo->getUrl('mini'); ?>" alt="">-->
                            <div class="gallery-item" style="background-image: url('<?= $photo->getUrl('mini'); ?>');"></div>
                            <input type="hidden" name="photo_order[]" value="<?php echo $photo->id; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
                        foreach($languages as $lang){
                            if($lang->short != "pl"){
                ?>
        <div id="<?= $lang->short;?>">
            <?php $translation = $gallery->getTranslation($lang->short); ?>
            <?php echo form_hidden($lang->short.'[id]', $translation->id); ?>
            <p>Nazwa:</p>
            <?php echo form_input($lang->short.'[name]', $translation->name, ' class="form-control"'); ?>
            <p>Opis</p>
            <textarea name="<?= $lang->short;?>[description]" class="ckeditor"><?= $translation->description;?></textarea>
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
<!--<h3>Opisy do zdjęć</h3>-->

<!--<div class="">
    <table>
        <?php /*
        if(!empty($photos)){
        foreach ($photos as $photo): ?>
        <tr>
            <td>
                <img src="<?php echo $photo->getUrl('mini'); ?>" alt="">
            </td>
            <td>
                <form method="POST">
                    <input type="hidden" name="action" value="desc_photo" />
                    <input type="hidden" name="id" value="<?= $photo->id;?>" />
                    <label>Opis polski</label>
                    <input type="text" name="description[pl]" value="<?= !empty($pM->get_description($photo->id, "pl")->description) ? $pM->get_description($photo->id, "pl")->description : "";?>" />
                    <?php
                        foreach($languages as $lang){
                            if($lang->short != "pl"){
                            ?>
                            <label>Opis  <?= $lang->short;?></label>
                            <input type="text" name="description[<?= $lang->short;?>]" value="<?= !empty($pM->get_description($photo->id, $lang->short)->description) ? $pM->get_description($photo->id, $lang->short)->description : "";?>" />
                            <?php
                            }
                        }
                 ?>
                    <input type="submit" value="Opisz" />
                </form>
            </td>
        </tr>
        <?php endforeach; 
        } */?>
    </table>
</div> -->
<script>
    domReadyQueue.push(function ($) {
        $('#uploader').pluploadQueue({
            url: '<?php echo site_url('duocms/gallery/upload_photo'); ?>',
            multipart_params: {
                product_id: <?php echo $gallery->id ?>
            }
        });

        $('.product-gallery .item a').click(function (e) {
            e.preventDefault();

            if (!confirm('Ta operacja jest nieodwracalna. Kontyunować?')) {
                return false;
            }

            var self = $(this).parent();
            var href = $(this).attr('href');

            $.get(href, function (data) {
                if (data.result == 1) {
                    self.remove();
                }
            }, 'JSON');
        });

        $('.product-gallery').sortable();
    });
</script>

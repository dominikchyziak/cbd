<select name="inpost_locker" class="form-control selectpicker" required="true"  data-live-search="true">
    <option value=""><?= (new CustomElementModel('18'))->getField('wybierz punkt'); ?></option>
<?php
if (!empty($points)) {
    foreach ($points as $point) {
        ?>
    <option value="<?= $point->name;?>"><?= $point->address_details->city;?> <?= $point->address_details->street;?> <?= $point->address_details->building_number;?> (<?= $point->name;?>)</option>
    <?php
    }
}    
?>
</select>

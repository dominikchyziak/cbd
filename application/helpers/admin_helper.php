<?php

/**
 * Wyświetla formularz dodawania własnego pola dla tego elementu
 */
function new_custom_field_form($element, $element_id) {
    ?>
    <div class="form-group">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_field">
            Dodaj pole
        </button>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="add_field" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Dodatkowe pole</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="<?= site_url('duocms/Custom_Fields/ajax_add_field'); ?>" class="ajax-form" id="add_field_form">

                    <div class="modal-body">
                        <input type="hidden" name="element" value="<?= $element; ?>" id="element"/>
                        <div class="form-group">
                            <select name="element_id" class="form-control" id="element_id">
                                <option value="NULL">Dla wszystkich tego typu</option>
                                <?php if (!empty($element_id)) { ?>
                                    <option value="<?= $element_id; ?>">Tylko dla tego elementu</option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <p>Typ</p>
                            <select name="type" class="form-control" id="type">
                                <option value="text">Tekstowe</option>
                                <option value="image">Obrazek</option>
                                <option value="ckeditor">CkEditor</option>
                                <option value="file">Plik</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <p>Nazwa</p>
                            <input type="text" name="name" class="form-control" id="name"/>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                        <input type="submit" class="btn btn-primary" value="Dodaj pole" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Wyświetla pola formularza w odpowiednich miejscach
 * @param string $element określa czy jest to wizerunek, page, product itd
 * @param int $element_id identyfikator wizerunku, page, productu itd
 * @param string $lang skrót języka
 */
function get_custom_fields_form($element, $element_id, $lang) {
    
    $elements = array(
        'wizerunek' => 'wizerunek',
        'product_category' => 'category',
        'product' => 'product',
        'news' => 'news',
        'gallery' => 'gallery'
    );
    
    if(!empty($element_id)){
        save_custom_fields($element, $element_id);
    }
    $CI = get_instance();
    $CI->load->model('CustomFieldsModel');
    $cf_obj = new CustomFieldsModel();
    $fields = $cf_obj->get_custom_fields_by_lang($element, $element_id, $lang);
    if (!empty($fields) && !empty($element_id)) {
        foreach ($fields as $field) {
            ?>
            <div class="form-group">
                <p>
                    <?= $field->name; ?>
                    <?php
                    if(ENVIRONMENT == 'development'){
                        ?>
                    <a href="javascript:vois(0);" data="<?= site_url('duocms/Custom_Fields/ajax_delete_field/'.$field->id);?>" class="ajax-action-link"> [Usuń to pole] </a>
                    <input type="text" value="&lt;?= $<?= $elements[$element];?>->getField(<?= $field->id;?>, $<?= $elements[$element];?>->id,LANG);?>"  class="form-control" readonly=""/>
                    <?php
                    }
                    ?>
                </p>
                <?php
                if ($field->type == 'text') {
                    ?>             
                    <input type="text" name="field[<?= $lang; ?>][<?= $field->id; ?>]" value="<?= !empty($field->value) ? $field->value : ''; ?>" class="form-control"/>
                     <a href="" class="wyczysc" data-clear="field[<?= $lang; ?>][<?= $field->id; ?>]" id="<?= $lang.$field->id;?>">Wyczyść</a><?php
                }
                if ($field->type == 'ckeditor') {
                    ?>
                    <textarea name="field[<?= $lang; ?>][<?= $field->id; ?>]" class="ckeditor"><?= !empty($field->value) ? $field->value : ''; ?></textarea>
                    <?php
                }
                if ($field->type == 'image') {
                    ?>
                    <input type="text"  name="field[<?= $lang; ?>][<?= $field->id; ?>]" value="<?= !empty($field->value) ? $field->value : ''; ?>" onclick="openKCFinderImage(this, 'images')" class="form-control" readonly>
                     <a href="" class="wyczysc" data-clear="field[<?= $lang; ?>][<?= $field->id; ?>]" id="<?= $lang.$field->id;?>">Wyczyść</a><?php
                }
                if ($field->type == 'file') {
                    ?>
                    <input type="text"  name="field[<?= $lang; ?>][<?= $field->id; ?>]" value="<?= !empty($field->value) ? $field->value : ''; ?>" onclick="openKCFinderImage(this, 'files')" class="form-control" readonly>
                    <a href="" class="wyczysc" data-clear="field[<?= $lang; ?>][<?= $field->id; ?>]" id="<?= $lang.$field->id;?>">Wyczyść</a>
                        <?php
                }
                if($field->type != 'ckeditor'):
                ?>
                   <script>$(document).ready(function(){
                    $('#<?= $lang.$field->id; ?>').on('click', function(e){
                        e.preventDefault();
                        $('input[name="'+$(this).attr('data-clear')+'"]').val('');
                    });
                });</script><?php endif;
                ?>
            </div>

            <?php
        }
    } else {
        if(ENVIRONMENT == 'development'){
        ?>
            <br><br>
            <div class="alert alert-info">
                Ewentualne dodatkowe pola pojawią się przy edycji
            </div>
        <?php
        }
    }
}

/**
 * Zapisuje własne pola dowolnego elementu
 * @param string $element typ elementu na przykłąd wizerunek
 * @param int $element_id Identyfikator danego wizerunku
 */
function save_custom_fields($element, $element_id){
    $CI = get_instance();
    $CI->load->model('CustomFieldsModel');
    $cf_obj = new CustomFieldsModel();
    $data = $CI->input->post('field');
    if(!empty($data)){
        foreach($data as $k => $d){
            $lang = $k;
            if(!empty($d)){
                foreach($d as $key => $r){
                    $cf_obj->save_field($key, $element_id, $r, $lang);
                }
            }
        }
        setAlert('success', 'Zapisano');
    }
}

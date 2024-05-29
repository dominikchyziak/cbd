<?php $this->load->view('duocms/Shop/menu'); ?>
        <?php
        if(empty($allegro_session)){
            ?>
        <div class="alert alert-info">
            Listowanie produktów wymaga autoryzacji allegro. 
            Aby pozyskać autoryzację<br> <a href="<?= $allegro_login_link;?>" class="btn btn-warning">Zaloguj się</a>
        </div>
        <?php } else {
        if(!empty($error)){
            ?>
        <div class="col-sm-12">
            <div class="alert alert-danger">
                <?= $error;?>
            </div>
        </div>
        
        <?php
        }}
        ?>
    
<?php
if(!empty($dost)): 
    echo form_open('');
    $i=0;
    $extra = array('class' => 'form-control');
    foreach ($dost as $dostawa) :?>
<div class="panel panel-default">
    <div class="panel-header"><?= $dostawa->name; ?></div>
    <div class="panel-body">
        <?php if(!empty($dostawa->rates)):
     foreach ($dostawa->rates as $dr) : 
            $id = $dr->deliveryMethod->id;
            //$comp_id = $dostawa->id.'_'.$id; 
        $comp_id = $id; ?>
            <?= $dma[$id]; ?>
            <?= form_dropdown('delivery['.$i.']', $cms_delivery, 
                    (!empty($selected_data[$comp_id])) ? $selected_data[$comp_id] : null, $extra); ?>
        <?= form_hidden('id['.$i.']', $comp_id ); ?>
        <hr>
    <?php $i++; endforeach;
        endif; ?>
    </div>
</div>
<?php 
endforeach;
echo form_submit('', 'Zapisz');
echo form_close();
endif;
?>

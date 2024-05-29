<?php
$this->load->view('duocms/Configuration/menu');
?>
<div class="col-sm-12">
    <div class="col-sm-12 col-md-6">
        <h2>Języki</h2>
        <?= form_open();?>
        <?= form_dropdown("add_lang", $lang_to_add);?>
        <br>
        <?= form_submit("submit","Dodaj język","class = 'btn btn-primary'");?>
        <?= form_close();?>
    </div>
    <div class="col-sm-12 col-md-6">
        <h3>Obecne języki</h3>
        <hr>
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Skrót</th>
                    <th>Nazwa</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(!empty($languages)){
                    foreach($languages as $lang){
                        ?>
                <tr>
                    <td><?= $lang->short;?></td>
                    <td><?= $lang->name;?></td>
                    <td><?php 
                    if($lang->short != 'pl'){
                        printf(ADMIN_BUTTON_DELETE, site_url('duocms/configuration/delete_lang/'.$lang->id)); 
                    }
                    ?></td>
                </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    
</div>


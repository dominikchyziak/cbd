<?php
$this->load->view('duocms/Configuration/menu');
?>
<div class="col-sm-12">
    <div class="col-sm-12 col-md-6">
        <h2><?= !empty($conf_name) ? $conf_name : '';?></h2>
        <?= form_open();?>
        <?php
        if(!empty($all_options)){
            foreach($all_options as $option){
                ?>
        <label for="<?= $option->key;?>" class="label" style="color:black;"><?= $option->name;?> (<?= $option->key;?>)</label>
        <input type="text" name="<?= $option->key;?>" value="<?= $option->value;?>" class="form-control"/>
                <?php
            }
        }
        ?><br>
        <?= form_submit("submit","Aktualizuj konfiguracje","class = 'btn btn-primary'");?>
        <?= form_close();?>
    </div>
	<div class="col-sm-12 col-md-6">
	Google merchant:<br>
    <a href="https://doctorscbd.pl/api/google/xml/pl">https://doctorscbd.pl/api/google/xml/pl</a><br><br>
	Google Search Console: <br>
    <a href="https://doctorscbd.pl/api/GoogleSiteMap/xml/pl">https://doctorscbd.pl/api/GoogleSiteMap/xml/pl</a>
    </div>
</div>


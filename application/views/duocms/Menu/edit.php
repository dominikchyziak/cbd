<?= form_open();?>
    <?= form_hidden("action","edit");?>
<div class="form-group">
    <label>Rodzic:</label>
    <?= form_dropdown("parent_id",$parents,$item["parent_id"],' class="form-control" ');?>
</div>
  <div class="form-group">  
    <label>Kolejność:</label><br>
    <?= form_input(array(
        "name" => "order_menu",
        "type" => "number",
        "value" => $item["order_menu"],
        "class" => "form-control"
        ));?>
    </div>

<?php
    if(!empty($item["translation"])){
        foreach ($item["translation"] as $trans){
            ?>
  <div class="form-group">  
            <label>Nazwa <?= $trans["lang"];?></label>
            <?= form_input(array(
                "name" => "name[".$trans["lang"]."]",
                "value" => $trans["name"],
                "class" => "form-control"
            ));?>
  </div>
  <div class="form-group">  
            <label>Link <?= $trans["lang"];?></label>
            <?= form_input(array(
                "name" => "link[".$trans["lang"]."]",
                "value" => $trans["link"],
                "class" => "form-control"
            ));?>
  </div>
        <?php
        }
    }
?>    
  <div class="form-group">  
    <?= form_submit(array(
        "name" => "submit",
        "value" => "Edytuj",
        "class" => "button"
    ));?>
  </div>
    <?= form_close();?>


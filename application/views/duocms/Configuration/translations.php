<?php
$this->load->view('duocms/Configuration/menu');
?>
<div class="col-sm-12">
    <h2>Tłumaczenia</h2>
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>Klucz</td>
                <?php
                if(!empty($languages)){
                    foreach($languages as $lang){
                        ?>
                <th><?= $lang->name;?></th>
                    <?php
                    }
                }
                ?>
                <th>Akcja</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?= form_open();?>
                <?= form_hidden("action", "add_string");?>
                <td>
                    <?= form_input("key",""," class='form-control' placeholder='Unikalny klucz'");?>
                </td>
                <?php
                if(!empty($languages)){
                    foreach($languages as $lang){
                        ?>
                            <td><?= form_input("translation[".$lang->short."]",""," placeholder='Wpisz tekst'");?></td>
                        <?php
                    }
                }
                ?>
                <td><?= form_submit("submit","Dodaj"," class='btn btn-primary'");?></td>
                <?= form_close();?>
            </tr>
            <?php
                if(!empty($translations)){
                    foreach($translations as $key=>$trans){
                         echo '<td>'. $key . '</td>';
                         if(!empty($languages)){
                            foreach($languages as $lang){
                                ?>
                                    <td><?= $trans[$lang->short];?></td>
                                <?php
                            }
                        }
                    }
                }
            ?>
        </tbody>
    </table>
</div>
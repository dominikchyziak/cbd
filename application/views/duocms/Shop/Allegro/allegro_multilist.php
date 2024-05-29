<?php $this->load->view('duocms/Shop/menu'); ?>
        <?php
        if(empty($allegro_session)){
            ?>
        <div class="alert alert-info">
            Listowanie produktów wymaga autoryzacji allegro. 
            Aby pozyskać autoryzację<br> <a href="<?= $allegro_login_link;?>" class="btn btn-warning">Zaloguj się</a>
        </div>
        <?php
        } else {
        if(!empty($error)){
            ?>
        <div class="col-sm-12">
            <div class="alert alert-danger">
                <?= $error;?>
            </div>
        </div>
        
        <?php
        }
        ?>
    
<?php
if(!empty($offers)){
    if($page == 0){ ?>
<a class="pull-right" href="<?= site_url('duocms/allegro/allegro_list/'.($page+1));?>">Następna strona</a>
    <?php }else { ?>
    <a class="pull-left" href="<?= site_url('duocms/allegro/allegro_list/'.($page-1));?>">Poprzednia strona</a>
    <a class="pull-right" href="<?= site_url('duocms/allegro/allegro_list/'.($page+1));?>">Następna strona</a>    
    <?php }
    $allegro_model = new AllegroModel();
    //echo form_open('duocms/Allegro/allegro_list/'.$page);
    
    var_dump($offers);
    echo '<table class="table table-stripped"><thead>'
            . '<tr>'
            . '<th></th>'
            . '<th>Powiązana <br>ze sklepem?</th>'
            . '<th>id</th>'
            . '<th>miniaturka</th>'
            . '<th>nazwa</th>'
            . '<th>Dodaj do sklepu</th>'
            . '</tr></thead><tbody>';


    echo '</tbody></table>';

    //echo '<button type="submit">Dodaj zaznaczone aukcje do sklepu</button>';
    //echo form_close();
    //echo '<button onclick="checkall()">Zaznacz wszystkie</button>';
        } else { echo 'brak wystawionych przedmiotow'; } }
?>

    <script>
    function checkall(){
        $(":checkbox").each( function(){
         if(!this.disabled){
            this.checked = true;
         }
        });
    }
    
    </script>
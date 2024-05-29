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
    $offers_array = json_decode($offers);
    echo form_open('duocms/Allegro/allegro_list/'.$page);
    echo '<table class="table table-stripped"><thead>'
            . '<tr>'
            . '<th></th>'
            . '<th>Powiązana <br>ze sklepem?</th>'
            . '<th>id</th>'
            . '<th>miniaturka</th>'
            . '<th>nazwa</th>'
            . '<th>Dodaj do sklepu</th>'
            . '</tr></thead><tbody>';
    if(!empty($offers_array->items->promoted)){
    foreach ($offers_array->items->promoted as $o) {
        $is_on_site_already = $allegro_model->check_allegro_product($o->id);
        $str = ($is_on_site_already == TRUE) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>';
        $str2 = ($is_on_site_already == TRUE) ? 'disabled' : '';
        echo '<tr>'
                .'<td><input type="checkbox" name="aukcja['. $o->id .']" '.$str2.'></td>' 
                .'<td>'. $str  .'</td>'
                . '<td>'. $o->id .'</td>'
                . '<td><img class="miniaturka-allegro" src="'. $o->images[0]->url.'"</td>'
                . '<td>'. $o->name. '</td>'
                . '<td><a href="'. site_url('duocms/allegro/add_from_allegro/' .$o->id).'" >Dodaj</a></td>'
                . '</tr>';
    }}
if(!empty($offers_array->items->regular)){
    foreach ($offers_array->items->regular as $o) {
        $is_on_site_already = $allegro_model->check_allegro_product($o->id);
        $str = ($is_on_site_already == TRUE) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>';
        $str2 = ($is_on_site_already == TRUE) ? 'disabled' : '';
        echo '<tr>'
            .'<td><input type="checkbox" name="aukcja['. $o->id .']" '.$str2.'></td>'          
            .'<td>'. $str  .'</td>'
            . '<td>'. $o->id .'</td>'
            . '<td><img class="miniaturka-allegro" src="'. $o->images[0]->url.'"</td>'
            . '<td>'. $o->name. '</td>'
            . '<td><a href="'. site_url('duocms/allegro/add_from_allegro/' .$o->id).'" >Dodaj</a></td>'
            . '</tr>';
}}
    echo '</tbody></table>';

    echo '<button type="submit">Dodaj zaznaczone aukcje do sklepu</button>';
    echo form_close();
    echo '<button onclick="checkall()">Zaznacz wszystkie</button>';
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
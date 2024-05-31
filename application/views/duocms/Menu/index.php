<div class="col-sm-12">
    <h3>Menu</h3>
    <div class="alert alert-info">
        W tym miejscu możesz zarządzać menu znajdującymi się na stronie. Można także dodawać menu, pamiętaj jednak że dodatkowo trzeba umieścić funkcję odpowiedzialną za wyświetlanie menu w 
        odpowiednim miejscu kodu strony.
    </div>
    <p>
        <a href="<?= site_url('duocms/menu/add_menu');?>" class="btn btn-primary">Dodaj menu +</a>
    </p>
    <div class="table-responsive">
        <table class="t1 table table-bordered table-hover table-striped">
            <thead>
                <tr>
                   <th>Id</th> 
                   <th style="width:90%;">Nazwa</th>
                   <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if(!empty($menus)){
                        foreach($menus as $m){
                            echo '<tr>';
                            echo '<td>' . $m->id . '</td><td>' . $m->name . '</td>';
                            echo '<td>';
                            printf(ADMIN_BUTTON_EDIT, site_url('duocms/menu/get/'.$m->id));
                            echo '</td>';
                            echo '</tr>';
                        }

                    }
                ?>
            </tbody>
        </table>
    </div>
    
</div>

<?php
$this->load->view('duocms/Configuration/menu');
?>
<div class="col-sm-12">
        <h3>Menadżer konfiguracji</h3>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#new_option">
         + Nowa opcja
        </button>
        <form action="<?= site_url('duocms/configuration/ajax_save_visible');?>" method="POST" id="form_visible" class="ajax-form" >
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nazwa</th>
                        <th>Klucz</th>
                        <th>Wartość</th>
                        <th>Kategoria</th>
                        <th>Kolejność</th>
                        <th>Widoczność</th>
                        <th>Utworzona</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(!empty($all_options)){
                        foreach($all_options as $option){
                            ?>
                    <tr>
                        <td><?= $option->id;?></td>
                        <td><?= $option->name;?></td>
                        <td><?= $option->key;?></td>
                        <td><?= $option->value;?></td>
                        <td><?= $option->category;?></td>
                        <td><?= $option->order;?></td>
                        <td><input type="text" name="visible[<?= $option->id;?>]" value="<?= $option->visible;?>" /></td>
                        <td><?= $option->created_at;?></td>
                        <td></td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?= form_submit("submit","Aktualizuj konfiguracje","class = 'btn btn-primary'");?>
        <?= form_close();?>
</div>

<div class="modal fade" id="new_option" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nowa opcja</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        
      </div>
        
        <form action="<?= site_url('duocms/configuration/ajax_add_option');?>" id="new_option_form" class="ajax-form">  
      <div class="modal-body">
          <div class="alert alert-warning">
            Dla bezpieczeństwa aby usunąć opcję trzeba usunąć odpowiedni rekord z bazy danych.
        </div>
              <div class="form-group">
                  <input type="text" name="name" value="" placeholder="Nazwa" class="form-control" />
              </div>
              <div class="form-group">
                  <input type="text" name="key" value="" placeholder="Klucz" class="form-control" />
              </div>
              <div class="form-group">
                  <input type="text" name="value" value="" placeholder="Wartość" class="form-control" />
              </div>
              <div class="form-group">
                  <input type="text" name="category" value="" placeholder="Kategoria" class="form-control" />
              </div>
              <div class="form-group">
                  <input type="text" name="order" value="0" placeholder="Kolejność" class="form-control" />
              </div>
              <div class="form-group">
                  <input type="number" name="visible" value="1" min="0" max="1" step="1" placeholder="Widoczny" class="form-control" />
              </div>
              <div class="form-group">
                  
              </div>
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
        <input type="submit" class="btn btn-primary" value="Zapisz"/>
      </div>
        </form>  
    </div>
  </div>
</div>
<?php
$this->load->view('duocms/Shop/menu');
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <strong>Kody rabatowe</strong>
        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#add_code_modal" id="modal_button">+ Dodaj kod</button>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nazwa</th>
                        <th>Kod</th>
                        <th>Premia</th>
<!--                        <th>Typ</th>-->
                        <th>Ważny do</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(!empty($codes)){
                        foreach($codes as $code){
                            ?>
                    <tr>
                        <td><?= $code->id;?></td>
                        <td><?= $code->name;?></td>
                        <td><?= $code->code;?></td>
                        <td><?php
                        if($code->type == 0){
                        echo $code->value*100 . '%';
                        } else {
                            echo $code->value . ' ' . $currency->get_currency($code->currency_id)->code;
                        }
                        ?></td>
<!--                        <td><?= $code->type;?></td>-->
                        <td><?= !empty($code->valid_until) ? $code->valid_until : 'bezterminowo'; ?></td>
                        <td><?= !empty($code->one_time_use) ? 'jednorazowy':'';?></td>
                        <td>
                            <a href="javascript:load_form('<?= $code->id;?>')">
                                <span class="fa fa-pencil"></span>
                              </a>
                            <?php if(!($code->id == 1)): ?>
                            <a href="<?php echo site_url('duocms/codes/delete/' . $code->id); ?>">
                                <i class="fa fa-trash" onclick="javascript:return confirm('Ta operacja jest nieodwracalna. Kontyunować?')"></i></a>
                                <?php endif; ?>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add_code_modal" tabindex="-1" role="dialog" aria-labelledby="add_code_modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Dodaj / Edytuj kod</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form method="POST" action="<?= site_url('duocms/codes/ajax_add_code');?>" class="" ">
              <input type="hidden" name="id" value="0" id="code_id" />
              <div class="form-group">
                  <p>Nazwa</p>
                  <input type="text" name="name" value="" id="name" class="form-control" />
              </div>
              <div class="form-group">
                  <p>Kod</p> 
                  <input type="text" name="code" value="" id="code" class="form-control" />
                  </div>
              <div class="form-group">
                  <p>Premia</p>
                  <input type="number" step="0.01" min="0.01" name="value" value="0.1" id="value" class="form-control" />
                  </div>
              <div class="form-group">
                  <p>Typ</p>
                  <select name="type" class="form-control" id="type">
                      <option value="0">Częściowy (0.1 = 10%)</option>
                      <option value="1">Wartościowy (1 = 1zł)</option>
                  </select>
                  </div>
              <div class="form-group" id="currency_group">
                  <p>Waluta(dotyczy tylko typu wartościowego)</p>
                  <select name="currency_id" class="form-control" id="currency">
                      <?php 
                      $currencies = $currency->get_acitve_currencies();
                      if(!empty($currencies)) :
                          foreach($currencies as $curr):
                          ?>
                      <option value="<?=$curr->id;?>"><?=$curr->code;?></option>
                              <?php
                          endforeach;
                      endif;
                      ?>
                  </select>
              </div>
              <div class="form-group">
                  <p>Ważny do(jeśli pole jest puste ważny bezterminowo)</p>
                  <input type="text" name="valid_until" class="form-control" />
              </div>
              <div class="form-group" style="display: none;">
                  <p>Min wartość zamówienia w zl</p>
                  <input type="number" step="0.01" min="0" name="min_order" value="0" id="min_order" class="form-control" />
              </div>
              <div class="form-group">
                  <input type="checkbox" name="one_time_use" value="1" /> Kod jednorazowy
              </div>
              <div class="form-group">
                  <input type="submit" name="alamaca" value="Zapisz kod"  class="btn btn-primary"/>
              </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
    jQuery.datetimepicker.setLocale('pl');
    $('input[name="valid_until"]').datetimepicker({
        format:'Y-m-d h:i:s',
        formatTime:'h:i',
        formatDate:'Y-m-d'
    });
    $('#modal_button').click(function(){
        $('#name').val('');
            $('#code').val('');
            $('#value').val('0.1');
            $('#type').val('0');
            $('#min_order').val('0');
            $('#code_id').val('0');
    });
    
    $('form').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: $('form').attr('action'),
            data: $('form').serialize(),
            dataType: 'JSON',
            method: 'POST',
            success: function(){
                location.reload();
            }
        });
    });
});    
    
function load_form(id){
    $.ajax({
        url: '<?= site_url('duocms/codes/ajax_get_code/');?>'+id,
        type: 'GET',
        dataType: 'JSON',
        success: function(res){
            console.log(res);
            $('#name').val(res['name']);
            $('#code').val(res['code']);
            $('#value').val(res['value']);
            $('#type').val(res['type']);
            $('#min_order').val(res['min_order']);
            $('#code_id').val(res['id']);
            $('input[name="valid_until"]').val(res['valid_until']);
            $('input[name="one_time_use"]').prop('checked', res['one_time_use'] == 1 );
            $('#add_code_modal').modal('toggle');
        }
    });
}
</script>
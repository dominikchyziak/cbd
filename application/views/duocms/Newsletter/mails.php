<div class="col-sm-12">
    <h2>Newsletter</h2>
    <?php $this->load->view('duocms/Newsletter/menu');?>
    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#mail_form" onclick="clear_formmail()">+ Dodaj nowy email</button>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mail_form_special">+ Dodaj mail z produktami</button>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Tytuł</th>
                    <th>Do wysłania</th>
                    <th>Już wysłano</th>
                    <th>Wysłano</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(!empty($mailings)){
                    foreach($mailings as $mailing){
                        ?>
                <tr>
                    <td><?= $mailing->id;?></td>
                    <td><?= $mailing->subject;?></td>
                    <td><?= $mailing->sended;?></td>
                    <td><?= $mailing->to_send;?></td>
                    <td><?= $mailing->created_at;?></td>
                    <td class="text-right">
                    <?php
                        if ($mailing->status != 1 && $mailing->to_send < 1) {
                        ?>
                        <button class="btn btn-danger"  data-tooltip="tooltip" 
                                title="Dodaje liste mailingową do wysłania. (każde dodanie, ponownie dodaje kolejkę)." 
                                id="button_send_<?= $mailing->id; ?>" 
                                onclick="send_mailing('<?= $mailing->id; ?>')">
                                Wyślij
                        </button>
                        <?php
                        }
                    ?>
                    <button class="btn btn-primary" data-tooltip="tooltip" title="Edycja."  
                            data-toggle="modal" data-target="#mail_form" 
                            onclick="update_edit_id('<?= $mailing->id; ?>')">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </button>
                    <a href="javascript:delete_confirm('<?= base_url("duocms/newsletter/delete_mailing/" . $mailing->id); ?>','Czy chcesz usunąć ten mailing <br>(  <?= $mailing->subject; ?>)');" 
                       class="btn btn-danger" id="delete_user" content="Czy chcesz?"  data-toggle="tooltip" title="Usuń"><i class="fa fa-trash-o" aria-hidden="true"></i>
                    </a>
                    <button data-tooltip="tooltip" title="Umożliwia wysłanie maila testowego na podany adres email." 
                            class="btn btn-info"  data-toggle="modal" data-target="#test_mail" 
                            onclick="update_test_mail_id('<?= $mailing->id; ?>')"><i class="fa fa-at" aria-hidden="true"></i>
                    </button>
                    <button  data-tooltip="tooltip" title="Podgląd mailingu." class="btn btn-success" data-toggle="modal" data-target="#show_mailing" 
                             onclick="show_mailing('<?= $mailing->id; ?>')"><i class="fa fa-eye" aria-hidden="true"></i>
                    </button>
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

<!-- modal nowego emaila lub edycji -->
<div id="mail_form" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Dodawanie / edycja mailingu</h4>
      </div>
      <div class="modal-body">
          <form method="POST" class="ajax-form" id="email_form_form">
              <input type="hidden" name="mail_id" id="mail_id" value="0"/>
              <div class="form-group">
                <p>Temat</p>
                <input type="text" name="subject" value="" id="subject" class="form-control"/>
              </div>
              <div class="form-group">
                <p>Treść emaila w html</p>
                <textarea name="content" id="content" class="form-control ckeditor" rows="15"></textarea>
              </div>
              <div class="form-group">
                  <input type="submit" value="Zapisz" class="btn btn-primary"/>
              </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
      </div>
    </div>

  </div>
</div>

<!-- modal nowego emaila lub edycji -->
<div id="mail_form_special" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Dodawanie mailingu promocyjnego</h4>
      </div>
      <div class="modal-body">
          <form method="POST" action="<?= site_url('duocms/Newsletter/ajax_add_special');?>" class="ajax-form" id="email_form_form_special">
              <input type="hidden" name="mail_id" id="mail_id" value="0"/>
              <div class="form-group">
                <p>Temat</p>
                <input type="text" name="subject" value="" id="subject" class="form-control"/>
              </div>
              <div class="form-group">
                <p>Wybierz produkty</p>
                <select class="mdb-select colorful-select dropdown-primary" multiple searchable="Szukaj..." id="products" name="products[]" style="width: 100%;">
                    <?php
                    if(!empty($products)){
                        foreach($products as $product){
                            ?>
                    <option value="<?= $product->id;?>"><?= $product->name;?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
              </div>
              <div class="form-group">
                  <input type="submit" value="Zapisz" class="btn btn-primary"/>
              </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal show mailing -->
<div id="show_mailing" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="mailing_title"></h4>
      </div>
      <div class="modal-body">
          <div class="col-sm-12" id="mailing_content">
              
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
      </div>
    </div>

  </div>
</div>
<!-- Modal maila testowego -->
<div id="test_mail" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Mail testowy</h4>
      </div>
      <div class="modal-body">
          <div class="col-sm-12">
              <form method="post" action="<?= site_url('duocms/newsletter/ajax_test_mail');?>" id="test_mail_form" class="ajax-form">
                  <input type="hidden" name="test_mail_id" value="" id="test_mail_id"/>
                  <div class="form-group">
                      <p>Adres email</p>
                      <input type="email" name="email" value="" class="form-control" />
                  </div>
                  <div class="form-group">
                      <input type="submit" value="Wyślij testowy" class="btn btn-primary" />
                  </div>
              </form>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
      </div>
    </div>

  </div>
</div>

<script>
$(document).ready(function(){
    $('#email_form_form').submit(function(e){
        e.preventDefault();
        var subject = $('#subject').val();
        var content = CKEDITOR.instances['content'].getData(); //$('#content').val();
        var mail_id = $('#mail_id').val();
        var href = '';
        if(mail_id > 0){
            href = '<?= site_url('duocms/newsletter/ajax_edit');?>';
        } else {
            href = '<?= site_url('duocms/newsletter/ajax_add');?>';
        }
        $.ajax({
            url: href,
            type: 'POST',
            dataType: 'JSON',
            data: {
                subject: subject,
                content: content,
                mail_id: mail_id
            },
            success:function(res){
                if(res[0] == '1'){
                    location.reload();
                } else {
                    toastr.error(res[1]);
                }
            }
        });
    });
    
    
}); 

function update_test_mail_id(id){
    $('#test_mail_id').val(id);
}

function clear_formmail(){
    $('#mail_id').val('');
    $('#subject').val('');
    $('#content').val('');
}

function update_edit_id(id){
    //ładuję dane do formularza edycji
    $.ajax({
        url: '<?= site_url('duocms/Newsletter/ajax_get_mailing/');?>'+id,
        dataType: 'JSON',
        success: function(res){
            $('#mail_id').val(res['id']);
            $('#subject').val(res['title']);
            CKEDITOR.instances['content'].setData(res['content']);
            //$('#content').val(res['content']);
        }
    });
    
    $('#edit_id').val(id);
}
function show_mailing(id){
    $.ajax({
        url: '<?= site_url('duocms/newsletter/ajax_get_mailing/');?>'+id,
        dataType: 'JSON',
        success: function(res){
            $('#mailing_title').html(res['title']);
            $('#mailing_content').html(res['content']);
        }
    });
}

function send_mailing(id){
    $.ajax({
        url: '<?= site_url('duocms/Newsletter/ajax_send_mailing/'); ?>'+id,
        dataType: 'JSON',
        type: 'GET',
        success: function(res){
            console.log(res);
                if(res[0] == 0){
                    toastr.error(res[1]); 
                } else if(res[0] == 1){
                    //toastr.success(res[1]);
                        $('#button_send_'+id).css('display','none');
                         
                        setTimeout(function() {
                            location.reload();
                        },500);

                } else if(res[0] == 2){
                    toastr.info(res[1]);
                }
            
        }
    });
    
}
</script>
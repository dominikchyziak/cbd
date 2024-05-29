<div class="col-sm-12">
    <h2>Lista adresów email</h2>
    <?php $this->load->view('duocms/Newsletter/menu');?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Email</th>
                    <th>Unsub</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(!empty($emails)){
                    foreach($emails as $email){
                        ?>
                <tr>
                    <td><?= $email->id;?></td>
                    <td><?= $email->email;?></td>
                    <td id='email_status_id_<?= $email->id;?>'>
                        <?= !empty($email->blocked) ? 'Tak' : '';?>
                    </td>
                    <td id="button_sub_<?= $email->id;?>">
                                <?php
                                if (empty($email->blocked)) {
                                    ?>
                                    <button data-tooltip="tooltip" title="Blokuje możliwość wysyłania maili." onclick="unsubscribe('<?= $email->id; ?>');" class="btn btn-danger">Unsub</button>
                                    <?php
                                } else {
                                    ?>
                                    <button  data-tooltip="tooltip" title="Odblokowuje możliwość wysyłania maili."  onclick="subscribe('<?= $email->id; ?>');" class="btn btn-success">Sub</button>
                                    <?php
                                }
                                ?>
                        
                    </td>
                    <td><a href="<?= site_url('duocms/newsletter/unsub/'. $email->id); ?>" class="btn btn-info">DELETE</a></td>
                </tr>
                <?php
                    }
                }
                ?>
                <tr></tr>
            </tbody>
        </table>
    </div>
</div>
<script>
//zablokowanie maili dla danego adresu
function unsubscribe(email_id){    
    $.ajax({
        url: '<?= site_url('duocms/Newsletter/ajax_unsub');?>/',
        dataType: 'JSON',
        type: 'POST',
        data: {
            email_id: email_id
        },
        success: function(res){
            if(res[0] == '1'){
                toastr.warning(res[1]);
                $('#email_status_id_'+email_id).html('<b>Tak</b>');
                $('#button_sub_'+email_id).html('<button onclick="subscribe(\''+email_id+'\');" class="btn btn-success">Sub</button>');
            } else {
                toastr.error(res[1]);
            }
            
        }
    });
}
//odblokowanie maili dla danego adresu
function subscribe(email_id){
     $.ajax({
        url: '<?= site_url('duocms/Newsletter/ajax_sub');?>',
        dataType: 'JSON',
        type: 'POST',
        data: {
            email_id: email_id
        },
        success: function(res){
            if(res[0] == '1'){
                toastr.success(res[1]);
                $('#email_status_id_'+email_id).html('');
                $('#button_sub_'+email_id).html('<button onclick="unsubscribe(\''+email_id+'\');" class="btn btn-danger">Unsub</button>');
            } else {
                toastr.error(res[1]);
            }
            
        }
    });
     
}

</script>
    
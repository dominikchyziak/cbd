<?php $this->load->view('duocms/Shop/menu'); ?>
<div class="col-sm-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            Paczki produktów(aukcje wielowartościowe) 
            <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#pack_add" id="add_pack_btn">+ Dodaj</button>
            <a href="<?=site_url('duocms/allegro/download_packs');?>" class="btn btn-primary btn-danger pull-right">Pobierz z allegro</a>
        </div>
        <div class="panel-body table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Id.</th>
                        <th >Nazwa</th>
                        <th>Ilość produktów</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(!empty($packs)):
                        foreach ($packs as $pack):
                        ?>
                    <tr>
                <td><?=$pack->id; ?></td>
                <td><?=$pack->name; ?></td>
                <td><?= $pack->count; ?></td>
                <td><a href="<?php echo site_url('duocms/ProductPacks/edit/' . $pack->id); ?>"><i class="fa fa-pencil"></i></a>
                <a href="<?php echo site_url('duocms/ProductPacks/delete/' . $pack->id); ?>"><i class="fa fa-trash" onclick="javascript:return confirm('Ta operacja jest nieodwracalna. Kontyunować?')"></i></a></td>
                    </tr>
                    <?php
                    endforeach;
                    else:
                        echo '<td>brak wyników</td>';
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="pack_add" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        <?php $this->load->view('duocms/Shop/ProductPack/mainForm');?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
      </div>
    </div>

  </div>
</div>

<script>
$(document).ready(function(){
    $('#add_pack_btn').click(function(){
        $('.form-control').val('');
        $('#add_pack_form').attr('action','<?= site_url('duocms/ProductPacks/ajax_add_pack');?>');
    });
});    
</script>
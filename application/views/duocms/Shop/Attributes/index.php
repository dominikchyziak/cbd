<?php $this->load->view('duocms/Shop/menu'); ?>
<div class="col-sm-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            Grupy atrybutów 
            <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#attr_cat_add" id="add_group_btn">+ Dodaj</button>
        </div>
        <script>
            //sortowanie
            $(function () {
                $("#sortable").sortable({
                    update: function (event, ui) {
                        $.ajax({
                            url: '<?= site_url('duocms/Products_Attributes/sort_groups');?>',
                            data: $("form").serialize(),
                            type: 'POST',
                            success: function (result)
                            {
                                console.log("Zapisano kolejność" + result);
                            }
                        });
                    }
                });
                $("#sortable").disableSelection();
            });
        </script>
        <div class="panel-body table-responsive">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>"> 
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Id.</th>
                        <th width="90%">Nazwa</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="sortable">
                    <?php
                    if(!empty($groups)){
                        foreach($groups as $group){
                            ?>
                    <tr>
                        <td><input type="hidden" name="element[]" value="<?= $group->attributes_group_id; ?>" /><?= $group->attributes_group_id;?></td>
                        <td><?= $group->name;?></td>
                        <td>
                            <a href="<?= $group->attributes_group_id;?>" class="edit_group"><i class="fa fa-pencil"></i></a>
                            <?php 
                            if(!in_array($group->attributes_group_id, [])):
                            printf(ADMIN_BUTTON_DELETE, site_url('duocms/Products_Attributes/delete_group/'.$group->attributes_group_id)); 
                            endif;
                            ?>
                        </td>
                    </tr>
                    
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="attr_cat_add" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        <?php $this->load->view('duocms/Shop/Attributes/form_group');?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
      </div>
    </div>

  </div>
</div>

<script>
$(document).ready(function(){
    $('.edit_group').click(function(e){
        e.preventDefault();
        var element = $(this);
        var id = element.attr('href');
        $.ajax({
            url: '<?= site_url('duocms/Products_Attributes/ajax_get_group');?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                group_id: id
            },
            success: function(res){
                res.forEach(function(el, index){
                    $('#name_'+el.lang).val(el.name);
                });
                $('#add_attr_group_form').attr('action','<?= site_url('duocms/Products_Attributes/ajax_edit_group/');?>'+id);
                $('#attr_cat_add').modal('toggle');
            }
        });
    });
    $('#add_group_btn').click(function(){
        $('.form-control').val('');
        $('#add_attr_group_form').attr('action','<?= site_url('duocms/Products_Attributes/ajax_add_group');?>');
    });
});    
</script>
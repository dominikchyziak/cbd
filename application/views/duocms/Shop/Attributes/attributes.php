<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script>
            //sortowanie
            $(function () {
                <?php /*
            if (!empty($attributes)) {
                foreach ($attributes as $group) {
                    ?>
                $("#sortable<?= $group['group']->id;?>").sortable({
                    update: function (event, ui) {
                        $.ajax({
                            url: '<?= site_url('duocms/Products_Attributes/sort');?>',
                            data: $("form").serialize(),
                            type: 'POST',
                            success: function (result)
                            {
                                console.log("Zapisano kolejność" + result);
                            }
                        });
                    }
                });
                $("#sortable<?= $group['group']->id;?>").disableSelection();
                <?php
                }
            }
                */ ?>
            });
        </script>
<?php $this->load->view('duocms/Shop/menu'); ?>

<div class="panel panel-default">
    <div class="panel-heading"><strong>Atrybuty</strong></div>
    <div class="panel-body">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
            <?php
            if (!empty($attributes)) {
                foreach ($attributes as $group) {
                    ?>
            <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $group['group']->id; ?>" data-grp-id="<?= $group['group']->attributes_group_id; ?>">
                                <?= $group['group']->name; ?></a>
                        </h4>
                    </div>
                    <div id="collapse<?= $group['group']->id; ?>" class="panel-collapse collapse ">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nazwa</th>
                                        <th width="50%">Opis</th>
                                        <th>Cena</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </thead>
                                <tbody id="sortable<?= $group['group']->id;?>" class="group_<?= $group['group']->attributes_group_id; ?>">

                                    <?php
                                    //if (!empty($group)) {
                                    if(false) {
                                        foreach ($group['attributes'] as $attribute) {
                                            ?>
                                            <tr class="ui-state-default">
                                                <td>
                                                    <input type="hidden" name="element[]" value="<?= $attribute->id; ?>" />
                                            <?= $attribute->id; ?>
                                                </td>
                                                <td><?= $attribute->name; ?></td>
                                                <td><?= $attribute->description; ?></td>
                                                <td><?= $attribute->value; ?></td>
                                                <td><a href="<?php echo site_url('duocms/Products_Attributes/attributes_edit/' . $attribute->id); ?>"><i class="fa fa-pencil"></i></a></td>
                                                <td><a href="<?php echo site_url('duocms/Products_Attributes/attributes_delete/' . $attribute->id); ?>"><i class="fa fa-trash" onclick="javascript:return confirm('Ta operacja jest nieodwracalna. Kontyunować?')"></i></a></td>
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
                <?php
            }
        }
        ?>
        </form>
    </div>
</div>

        
<script>
    $('a[href^="#collapse"]').click(function(e){
        var grp_id = $(this).attr('data-grp-id');
        $.ajax({
            url: "<?= site_url('duocms/Products_Attributes/ajax_get_attributes_by_group'); ?>",
            dataType: "JSON",
            method: "POST",
            data: {"group_id" : grp_id},
            success: function(res){
                $(".group_"+grp_id).empty();
                
                $(res).each(function(e) {
                    var tr = $("<tr>", {"class" : "ui-state-default"});
                    tr.append('<td><input type="hidden" name="element[]" value="'+this.id+'" />'+ this.id +'</td>');
                    tr.append('<td>'+this.name+'</td>');
                    tr.append('<td>'+this.description+'</td>');
                    tr.append('<td>'+this.value+'</td>');
                    tr.append('<td><a href="<?= site_url('duocms/Products_Attributes/attributes_edit/');?>'+this.id+'"><i class="fa fa-pencil"></i></a></td>');
                    tr.append('<td><a href="<?= site_url('duocms/Products_Attributes/attributes_delete/');?>'+this.id+'"><i class="fa fa-trash" onclick="javascript:return confirm(\'Ta operacja jest nieodwracalna. Kontyunować?\')"></i></a></td>');
                    $(".group_"+grp_id).append(tr);
                });
                
                $(".group_"+grp_id).sortable({
                    update: function (event, ui) {
                        $.ajax({
                            url: '<?= site_url('duocms/Products_Attributes/sort');?>',
                            data: $("form").serialize(),
                            type: 'POST',
                            success: function (result)
                            {
                                console.log("Zapisano kolejność" + result);
                            }
                        });
                    }
                });
                $(".group_"+grp_id).disableSelection();
            }
        });
    });
</script>
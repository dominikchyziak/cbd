<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<?php  $this->load->view('duocms/Shop/menu'); ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <strong>Lista produktów</strong>
                <a href="<?php echo site_url('duocms/products/create'); ?>" class="btn btn-primary pull-right">+ Dodaj produkt</a>
<!--                <a href="<?php echo site_url('duocms/products/availability'); ?>" class="btn btn-success pull-right" style="margin-right: 15px">Zmień dostępność</a>-->
                <!--<a href="<?= site_url('duocms/products/export_xls');?>" class="btn btn-warning pull-right">Eksportuj do XLS</a>-->
            </div>
            <div class="col-sm-12 col-md-6">
                <!--<input type="text" id="search_input" onkeyup="search()" placeholder="Wpisz szukany produkt.." class="form-control pull-right">-->
            </div>
        </div>        
    </div>
    <div class="panel-body">
         <?= $this->pagination->create_links(); ?>
           <form method="POST">
            <div class="col-sm-12 col-md-3">
                <input type="text" name="s" value="<?= $data['s']; ?>" placeholder="nazwa lub EAN" class="form-control"/>
            </div> 
            <div class="col-sm-12 col-md-3">  
                <select name="category" class="form-control" id="category_select">
                    <option value="all">Wszystkie</option>
                    <?php
                    if(!empty($categories)){
                        foreach($categories as $key => $category){
                            ?>
                    <option value="<?= $key ?>" <?= ($key == $data['category']) ? 'selected' : '' ?>><?= $category;?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-12 col-md-3">
                <select name="opt" class="form-control">
                    <option value="none" <?= ($data['opt']== 'none') ? 'selected' : '' ?>>Brak</option>
<!--                    <option value="new" <?= ($data['opt']== 'new') ? 'selected' : '' ?>>Nowości</option>-->
                    <option value="promo" <?= ($data['opt']== 'promo') ? 'selected' : '' ?>>Promocje</option>
                    <option value="bestseller" <?= ($data['opt']== 'bestseller') ? 'selected' : '' ?>>Bestseller</option>
                </select>
            </div> 
            <div class="col-sm-12 col-md-2">
                <input type="submit" value="szukaj" class="btn btn-info" />
            </div>
        </form>
        
        <script>
            //sortowanie
            $(function () {
                $("#sortable").sortable({
                    update: function (event, ui) {
                        $.ajax({
                            url: '/duocms/products',
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

        <?php if ($products): ?>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">

                <table id="main_table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <!--<th>Kod</th>-->
                            <!--<th>Kategoria</th>-->
                            <th>Nazwa produktu</th>
                            <th>Aktywny</th>
                            <th colspan="2"></th>
                        </tr>
                    </thead>
                    <tbody id="sortable">
                        <?php foreach ($products as $product): ?>
                            <tr class="ui-state-default">
                                <td><input type="hidden" name="element[]" value="<?= $product->id; ?>" /><?= $product->id; ?></td>
                                <?php /*<td><?= $product->code;?></td>> */?>
                                <?php /*<td width="30%"><?php echo $product->category_name ?: '<em>brak</em>'; ?></td> */?>
                                <td width="90%"><?php echo $product->name; ?></td>
                                <td class="activity" style="cursor: pointer;" product_id="<?= $product->id;?>"><?= empty($product->active) ? 'Tak' : 'Nie';?></td>
                                <td><a href="<?php echo site_url('duocms/products/edit/' . $product->id); ?>"><i class="fa fa-pencil"></i></a></td>
                                <td><a href="<?php echo site_url('duocms/products/delete/' . $product->id); ?>"><i class="fa fa-trash" onclick="javascript:return confirm('Ta operacja jest nieodwracalna. Kontyunować?')"></i></a></td>
                                <td><a href="<?= site_url('duocms/products/copy_product/'. $product->id);?>"><span class="glyphicon glyphicon-copy"></span></a></td>
                               <?php /* <td><a href="<?= site_url('duocms/allegro/product/'. $product->id);?>"><img src="<?= assets('duocms/img/allegro-icon.png');?>" title="allegro" style="height: 20px;"></a></td>
                           */?> </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>

        <?php else: ?>
            <p>Brak wyników.</p>
        <?php endif; ?>
    </div>

</div>  

<script>
    //zmiana statusu produktu
 $(document).ready(function(){
     $('.activity').click(function(){
         var id = $(this).attr('product_id');
         var elem = $(this);
         $.ajax({
             url: '<?= site_url('duocms/products/change_status/');?>'+id,
             dataType: 'JSON',
             type: 'GET',
             success: function(res){
                 console.log(res);
                 if(res){
                     elem.html('Tak');
                 } else {
                     elem.html('Nie');
                 }
             }
         });
     });
 });
//Szukajka
    function search() {

        var input, filter, table, tr, td, i;
        input = document.getElementById("search_input");
        filter = input.value.toUpperCase();
        table = document.getElementById("main_table");
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

</script>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<h2>Galerie</h2>

<p><a href="<?php echo site_url('duocms/gallery/create'); ?>" class="btn btn-success">+ Dodaj</a></p>
<script>
            //sortowanie
            $(function () {
                $("#sortable").sortable({
                    update: function (event, ui) {
                        $.ajax({
                            url: '/duocms/gallery/sort',
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
        
<?php if ($galleries): ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
        <div class="table-responsive">
                <table class="table table-striped table-hover">
                        <thead>
                                <tr>
                                        <th>ID</th>
                                        <th>Nazwa</th>
                                        <th>Kategoria</th>
                                        <th colspan="2"></th>
                                </tr>
                        </thead>
                        <tbody id="sortable">
                                <?php foreach ($galleries as $gallery): ?>
                                        <tr>
                                                <td><input type="hidden" name="element[]" value="<?= $gallery->id; ?>" /><?php echo $gallery->id; ?></td>
                                                <td width="80%"><?php echo $gallery->getTranslation(LANG)->name; ?></td>
                                                <td> <?php switch($gallery->category){
                                                    case -1: echo "zintegrowane";
                                                        break;
                                                    case 1: echo 'realizacje';
                                                        break;
                                                    case 0: 
                                                    default: 
                                                        echo "brak";
                                                        break;
                                                }?></td>
                                                <td><a href="<?php echo site_url('duocms/gallery/edit/'.$gallery->id); ?>"><i class="fa fa-pencil"></i></a></td>
                                                <td><a href="<?php echo site_url('duocms/gallery/delete/'.$gallery->id); ?>"><i class="fa fa-trash" onclick="javascript:return confirm('Ta operacja jest nieodwracalna. Kontyunować?')"></i></a></td>
                                        </tr>
                                <?php endforeach; ?>
                        </tbody>
                </table>
        </div>
    </form>
<?php else: ?>
        
	<p>Brak wyników.</p>
        
<?php endif; ?>


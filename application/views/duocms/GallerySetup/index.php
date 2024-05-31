<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script>
    $(function () {
        $("#sortable").sortable({
            update: function (event, ui) {
                $.ajax({
                    url: '/pl/duocms/GallerySetup/index',
                    data: $("form.kolejnosc").serialize(),
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
<h1 class="page-header">Konfigurator galerii</h1>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="kolejnosc">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nazwa</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="sortable">
                        <?php
                        if (!empty($setup)) {
                            foreach ($setup as $r) {
                                ?>
                                <tr>
                                    <td><input type="hidden" name="element[]" value="<?= $r->id; ?>" />
                                        <?= $modules[$r->module_id]->friendly_name; ?>
                                    </td>
                                    <td><?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/GallerySetup/delete/' . $r->id)); ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    <div class="col-sm-12 col-md-6">
        <?= form_open(); ?>
        <?= form_hidden("action", "add"); ?>
        <label>Moduły:</label>
        <?= form_dropdown("module_id", $modules_dropdown, null, ' class="form-control"'); ?>
        <br>
    <?=
    form_submit(array(
        "name" => "submit",
        "value" => "dodaj",
        "class" => "btn btn-success"
    ));
    ?>
    <?= form_close(); ?><br>
        <!--<p>* Autouzupełnianie to dodatkowa opcja, po wybraniu opcji z listy dane zostaną wprowadzone automatycznie i w każdej chwili możesz zmienić link lub podpis.</p>-->
    </div>
</div>

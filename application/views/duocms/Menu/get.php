<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script>
    $(function () {
        $("#sortable").sortable({
            update: function (event, ui) {
                $.ajax({
                    url: '<?= site_url('duocms/menu/get/'.$menu_id);?>',
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
<div class="row">
    <div class="col-sm-12 col-md-6">
        <p>
            <a href="<?= site_url('duocms/menu'); ?>" class="btn btn-warning">< Powrót</a>
        </p>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Rodzic</th>
                            <th>Kolejność</th>
                            <th>Nazwa</th>
                            <th>link</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="sortable">
                        <?php
                        if (!empty($menu)) {
                            foreach ($menu as $id => $r) {
                                ?>
                                <tr>
                                    <td><input type="hidden" name="element[]" value="<?= $id; ?>" /><?= $id; ?></td>
                                    <td><?= $r["parent_id"]; ?></td>
                                    <td><?= $r["order_menu"]; ?></td>
                                    <td><?= $r["name"]; ?></td>
                                    <td><?= $r["link"]; ?></td>
                                    <td><?php printf(ADMIN_BUTTON_EDIT, site_url('duocms/menu/edit/' . $id)); ?></td>
                                    <td><?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/menu/delete/' . $id . '/' . $menu_id)); ?></td>
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
        <?php $languages = get_languages(); ?>
        <?= form_hidden("action", "add"); ?>
        <label>Rodzic:</label>
        <?= form_dropdown("parent_id", $parents, null, ' class="form-control"'); ?>
        <label>Kolejność:</label><br>
        <?=
        form_input(array(
            "name" => "order_menu",
            "type" => "number",
            "class" => "form-control",
            "value" => "0"
        ));
        ?><br>
        <!-- Autousupełnianie -->
        <!--<label>Autouzupełnianie *</label>
        <select name="auto" id="auto_change" class="form-control">
            <option value="">Wybierz (opcjonalnie)</option>
            <?php
            if (!empty($links)) {
                foreach ($links as $key1 => $link_cat) {
                    if (!empty($link_cat)) {
                        foreach ($link_cat as $key => $link) {
                            echo '<option value="' . $key1 . '_' . $key . '">' . $link[0] . '</option>';
                        }
                    }
                }
            }
            ?>
        </select>-->
        <!-- koniec autouzupełaniania -->    
        <label>Nazwa pl</label>
        <?=
        form_input(array(
            "name" => "name[pl]",
            "class" => "form-control",
            "id" => "name_pl"
        ));
        ?>
        <label>Link pl</label>
        <?=
        form_input(array(
            "name" => "link[pl]",
            "class" => "form-control",
            "id" => "link_pl"
        ));
        foreach ($languages as $lang) {
            if ($lang->short != "pl") {
                ?>
                <label>Nazwa <?= $lang->short; ?></label>
                <?=
                form_input(array(
                    "name" => "name[" . $lang->short . "]",
                    "class" => "form-control",
                    "id" => "name_" . $lang->short
                ));
                ?>
                <label>Link <?= $lang->short; ?></label>
                <?=
                form_input(array(
                    "name" => "link[" . $lang->short . "]",
                    "class" => "form-control",
                    "id" => "link_" . $lang->short
                ));
                ?>
        <?php }
    } ?><br>
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
<script language='javaScript'>
    //autowybieranie
    $('#auto_change').on('change', function () {
        $.ajax({
            url: '/duocms/menu/auto_change',
            data: {"option": $("#auto_change").val()},
            type: 'POST',
            success: function (result)
            {
                var links = JSON.parse(result);
                $('#name_pl').val(links[0]);
                $('#link_pl').val(links[1]);
            }
        });
    });
</script>
<a href="<?= site_url('duocms/users/index');?>" class="btn btn-primary">Wróć</a>
<a href="<?= site_url('duocms/UserProductDiscount/createEdit'); ?>" class="btn btn-success">+ Dodaj cenę specjalną</a>
<div class="table-responsive table-striped table-hover t1 mt-2" style="margin-top: 10px">
    <table class="table" >
        <tr class="head">
            <th>Id</th>
            <!--<th>Login</th>-->
            <th>Użytkownik</th>
            <th>Produkt</th>
            <th>Cena</th>
            <th>Data</th>
            <th></th>
            <th></th>
        </tr>
        <?php
        if (!empty($discounts)) {
            foreach ($discounts as $discount) {
                    ?>
                    <tr>
                        <td><?= $discount->id; ?></td>
                        <td><?= $discount->user; ?></td>
                        <td><?= $discount->product; ?></td>
                        <td><?= $discount->price; ?></td>
                        <td><?= $discount->created_at; ?></td>
                        <td>
                            <?php printf(ADMIN_BUTTON_EDIT, site_url('duocms/UserProductDiscount/createEdit/' . $discount->id)); ?></td>
                        </td>
                        <td>
                            <?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/UserProductDiscount/delete/' . $discount->id)); ?>
                        </td>
                    </tr>
                    <?php
            }
        }
        ?>
    </table>
</div>
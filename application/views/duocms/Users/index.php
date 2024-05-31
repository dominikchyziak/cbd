<a href="<?= site_url('duocms/users/add_edit_user'); ?>" class="btn btn-success">+ Dodaj użytkownika</a>
<a href="<?= site_url('duocms/UserProductDiscount/index');?>" class="btn btn-primary">Specjalne ceny</a>
<div class="ui-tabs">
    <ul>
        <li><a href="#zwykli">Zarejestrowani użytkownicy</a></li>
        <li><a href="#administratorzy">Administratorzy</a></li>       
    </ul>
    <div id="zwykli">
        <h2>Zarejestrowani użytkownicy</h2>
        <div class="table-responsive table-striped table-hover t1">
            <table class="table" >
                <tr class="head">
                    <th>Id</th>
                    <!--<th>Login</th>-->
                    <th>Email</th>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>Telefon</th>
                    <th width="50%">Adres</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                if (!empty($users)) {
                    foreach ($users as $user) {
                        if ($user->type != "0") {
                            ?>
                            <tr>
                                <td><?= $user->id; ?></td>
                                <!--<td><?= $user->name; ?></td>-->
                                <td><?= $user->email; ?></td>
                                <td><?= $user->first_name; ?></td>
                                <td><?= $user->last_name; ?></td>
                                <td><?= $user->phone; ?></td>
                                <td><?= $user->zip_code . ' ' . $user->city . ' ' . $user->street . ' ' . $user->building_number; ?></td>
                                            <td>
                                                <?php printf(ADMIN_BUTTON_EDIT, site_url('duocms/users/add_edit_user/' . $user->id)); ?></td>
                                            </td>
                                            <td>
                                                <?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/users/index/delete_user/' . $user->id)); ?>
                                            </td> 
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
            </table>
        </div>
    </div>
    <div id="administratorzy">
        <h2>Administratorzy</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover t1">
                <tr class="head">
                    <th>Id</th>
                    <!--<th>Login</th>-->
                    <th>Email</th>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>Telefon</th>
                    <th width="50%">Adres</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                if (!empty($users)) {
                    foreach ($users as $user) {
                        if ($user->type == "0") {
                            ?>
                            <tr>
                                <td><?= $user->id; ?></td>
                                <!--<td><?= $user->name; ?></td>-->
                                <td><?= $user->email; ?></td>
                                <td><?= $user->first_name; ?></td>
                                <td><?= $user->last_name; ?></td>
                                <td><?= $user->phone; ?></td>
                                <td><?= $user->zip_code . ' ' . $user->city . ' ' . $user->street . ' ' . $user->building_number; ?></td>
                                <?php if ($user->id > 2) { ?>
                                    <td>
                                        <?php printf(ADMIN_BUTTON_EDIT, site_url('duocms/users/add_edit_user/' . $user->id)); ?></td>
                                    </td>
                                    <td>
                                        <?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/users/index/delete_user/'.$user->id)); ?>
                                    </td> 

                                <?php }else {
                            echo '<td></td><td></td>';
                        } ?>

                            </tr>
                            <?php
                        } 
                    }
                }
                ?>
            </table>
        </div>
    </div>

</div>
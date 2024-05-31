<?php $this->load->view('duocms/Shop/menu'); ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <strong>Opcje walut</strong>
        <a href="<?=site_url('duocms/currency/create'); ?>" class="btn btn-info pull-right">Dodaj walutę</a>
    </div>
    <div class="panel-body">
        <form method="POST">
            <div class="col-sm-2">
            Domyślna waluta:
            </div>
        <div class="col-sm-8">
            <select name="waluta" class="form-control">
                <?php 
                $default_curr = get_option('admin_default_currency');
                foreach ($acurrency as $ac) :
                    ?>
                <option value="<?=$ac->id; ?>" <?= ($ac->id == $default_curr) ? 'selected' : ''; ?>> <?= $ac->code;?></option>
                        <?php
                endforeach;
                ?>
            </select>
        </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-success">Zapisz </button>
            </div>
        </form>
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nazwa</th>
                    <th>3literowy kod</th>
                    <th>Opis</th>
                    <th>Widoczność</th>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tbody>

                <?php
                if (!empty($currency)) {
                    foreach ($currency as $c) {
                        ?>
                        <tr>
                            <td><?= $c->id;?></td>
                            <td><?= $c->name; ?></td>
                            <td><?= $c->code; ?></td>
                            <td><?= $c->comment; ?></td>
                            <td><?= ($c->visibility == 1) ? 'widoczna' : 'ukryta'; ?></td>
                            <td><a href="<?php echo site_url('duocms/currency/edit/' . $c->id); ?>"><i class="fa fa-pencil"></i></a></td>
                    <td><a href="<?php echo site_url('duocms/currency/delete/' . $c->id); ?>"><i class="fa fa-trash" onclick="javascript:return confirm('Ta operacja jest nieodwracalna. Kontyunować?')"></i></a></td>
                        </tr>
                        <?php
                    }
                }
                ?>

            </tbody>
        </table>
    </div>
</div>


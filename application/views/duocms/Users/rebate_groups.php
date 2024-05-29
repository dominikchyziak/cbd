
<div class="panel panel-default">
    <div class="panel-heading"><strong>Grupy rabatowe</strong></div>
    <div class="panel-body">
        <a href="<?= site_url('duocms/users');?>" class="btn btn-primary">< Powrót</a>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nazwa</th>
                        <th>Rabat</th>
                        <th>Akcja</th>
                    </tr>
                </thead>
                <tbody>
                
                    <?php
                    if(!empty($groups)){
                        foreach($groups as $group){
                            ?>
                <form method="post">
                    <tr>
                        <td><?= $group->id;?> <input type="hidden" name="id" value="<?= $group->id;?>" /></td>
                        <td><input type="text" name="name" value="<?= $group->name;?>" placeholder="nazwa" /></td>
                        <td><input type="number" step="0.01" value="<?= $group->discount;?>" name="discount" placeholder="procent" /> %</td>
                        <td><input type="submit" value="Aktualizuj" class="btn btn-primary"></td>
                    </tr>
                </form>
                 
                    <?php
                        }
                    }
                    ?>
                <form action="" method="POST">    
                    <tr>
                        <td></td>
                        <td><input type="text" name="name" value="" placeholder="nazwa" /></td>
                        <td><input type="number" step="0.01" value="" name="discount" placeholder="procent" /> %</td>
                        <td><input type="submit" value="Dodaj" class="btn btn-primary"></td>
                    </tr>
                </form>
                </tbody>
                <tr>
                    
                </tr>
                
            </table>
        </div>

    </div>
</div>

<div class="container">
    <h4 class="naglowek-podstrona" style="margin-top:15px">Linki</h4>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>LP.</th>
                    <th>ID</th>
                    <th style="width: 100px;">Obrazek</th>
                    <th>Cena</th>
                    <th>Nazwa</th>
                    <th></th>
                </tr>
            </thead>
            <?php if(!empty($all)){
                $i = 0;
               foreach($all as $r){
                   $i++;
                   ?>
            <tr>
                <td><?= $i;?></td>
                <td><?= $r['id'];?></td>
                <td><img src="<?= $r['photo'];?>" alt=""/></td>
                <td><?= $r['price'];?> zł</td>
                <td>
                    <?= $r['product_name'];?>
                    <!--<hr>
                   <?= json_encode($r);?>-->
                </td>
                <td>
                    <a href="<?= $r['link'];?>" >zobacz</a>
                </td>
            </tr>
            <?php
               } 
            }?>
        </table>
    </div>
</div>
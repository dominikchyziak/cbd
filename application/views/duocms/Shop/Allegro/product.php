<?php $this->load->view('duocms/Shop/menu'); ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12">
                <strong>Allegro</strong>
                <a href="<?php echo site_url('duocms/allegro/add_auction/'.$product_id); ?>" class="btn btn-warning pull-right">+ Dodaj Aukcję</a>
            </div>
        </div>        
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <td>Id</td>
                        <th style="width: 80%;">Id allegro</th>
<!--                        <th>Status</th>-->
                        <th>
                            Akcje
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(!empty($auctions)){
                        foreach($auctions as $auction){
                            ?>
                    <tr>
                        <td><?= $auction->id;?></td>
                        <td><?= $auction->allegro_id;?></td>
<!--                        <td><?= $auction->allegro_status;?></td>-->
                        <td><a href="<?= site_url('duocms/allegro/show/'.$auction->allegro_id);?>" class="btn btn-warning">ZOBACZ</a></td>
                    </tr>
                    <?php
                        }
                    } else {
                        ?>
                    <tr>
                        <td colspan="4" class="text-center">
                            Nie ma jeszcze zarejestrowanych aukcji dla tego produktu, dodaj jakąś!
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

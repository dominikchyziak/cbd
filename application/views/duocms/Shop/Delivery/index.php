<?php $this->load->view('duocms/Shop/menu'); ?>
<div class="panel panel-default">
    <div class="panel-heading"><strong>Opcje dostawy</strong></div>
    <div class="panel-body">
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Integracja</th>
                    <th>Nazwa</th>
                    <th>Opis</th>
                    <th>Cena</th>
                    <th>Darmowa od</th>
                    <th>Waga min</th>
                    <th>Waga max</th>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tbody>

                <?php
                if (!empty($delivery)) {
                    foreach ($delivery as $d) {
                        ?>
                        <tr>
                            <td><?= $d['id'];?></td>
                            <td><?php 
                            $cat_id = $d['category_id'];
                            switch($cat_id){
                               case '0':
                                   echo 'brak';
                                   break;
                               case '1':
                                   echo 'E-nadawca(Poczta Polska)';
                                   break;
                               case '2':
                                   echo 'Inpost';
                                   break;
                               case '3':
                                   echo 'DPD';
                                   break;
                               case '4':
                                   echo 'Bliskapaczka';
                                   break;
                               default:
                                   break;
                            }
                            ?></td>
                            <td><?= $d['translations']['pl']['name']; ?></td>
                            <td><?= $d['translations']['pl']['description']; ?></td>
                            <td><?php
                            foreach($currencies as $cur):
                                if(!empty($d['prices'][$cur->id])):
                                    echo $d['prices'][$cur->id]['price'].' '.$cur->code.'<br>';
                                endif;
                            endforeach;
                            ?></td>
                            <td><?php
                            foreach($currencies as $cur):
                                if(!empty($d['prices'][$cur->id])):
                                    echo $d['prices'][$cur->id]['max_price'].' '.$cur->code.'<br>';
                                endif;
                            endforeach;
                            ?></td>
                            <td><?= $d['weight_min'];?></td>
                            <td><?= $d['weight_max'];?></td>
                            <td><a href="<?php echo site_url('duocms/delivery/edit/' . $d['id']); ?>"><i class="fa fa-pencil"></i></a></td>
                    <td><a href="<?php echo site_url('duocms/delivery/delete/' . $d['id']); ?>"><i class="fa fa-trash" onclick="javascript:return confirm('Ta operacja jest nieodwracalna. Kontyunować?')"></i></a></td>
                        </tr>
                        <?php
                    }
                }
                ?>

            </tbody>
        </table>
    </div>
</div>


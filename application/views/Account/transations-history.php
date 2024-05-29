  <?php $this->load->view('partials/breadcrumbs'); ?><div class="szer-container">
<div class="container">      
    <h4 class="naglowek-podstrona" ><?= (new CustomElementModel('10'))->getField('Historia transakcji'); ?></h4>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <?php
            if(!empty($orders)){
                foreach($orders as $order){
                    echo '<tr>';
                    echo '<td>' . $order->created_at . '</td><td>' . $order->price . ' ' . (new CustomElementModel('16'))->getField('waluta').
                            ' </td><td><a href="' . site_url('zamowienie/summary/'.$order->id.'/'.$order->key) .'">'.(new CustomElementModel('16'))->getField('szczegoly zamowienia').'</a></td>';
                    echo '</tr>';
                    if(!empty($story[$order->id])){
                        foreach ($story[$order->id] as $story_item){
                            ?>
                            
                            <tr>
                                <td></td>
                                <td>Zmiana statusu na</td>
                                <td><?= (new CustomElementModel('14'))->getField('status '.$story_item->new_status);?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><?= $story_item->created_at;?></td>
                                <td><?= $story_item->note;?></td>
                            </tr>
                            <?php
                        }
                    }                    
                }
            } else {
                ?><tr><td>Brak zamówień.</td></tr><?php
            }
            ?>
        </table>
    </div>
</div>

  </div>
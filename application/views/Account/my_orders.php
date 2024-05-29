<div class="container">
    <div class="row">
        <div class="col-sm-12 wow fadeInLeft">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1 class="naglowek-podstrona" ><?= (new CustomElementModel('10'))->getField('moje zamowienia'); ?></h1>
        </div>
        <div class="col-sm-12 wow fadeInUp">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <?php
                    if(!empty($orders)){
                        foreach($orders as $order){
                            echo '<tr>';
                            echo '<td>' . $order->created_at . '</td><td>' . $order->price . ' ' . (new CustomElementModel('16'))->getField('waluta').
                                    ' </td><td><a href="' . site_url('zamowienie/summary/'.$order->id.'/'.$order->key) .'">'.(new CustomElementModel('16'))->getField('szczegoly zamowienia').'</a></td>';
                            echo '</tr>';
                        }
                    } else {
                        ?><tr><td><?= (new CustomElementModel('15'))->getField('brak zamowien tekst'); ?></td></tr><?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
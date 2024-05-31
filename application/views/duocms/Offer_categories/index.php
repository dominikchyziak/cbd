<?php $this->load->view('duocms/Shop/menu');?>
<div class="col-sm-12">
    <h2>Kategorie produktów</h2>

    <p><a href="<?php echo site_url('duocms/offer_categories/create'); ?>" class="btn btn-primary">+ Dodaj</a></p>

    <?php if ($offer_categories): ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
                    <thead>
                            <tr>
                                    <th width="100%">Nazwa</th>
                                    <th colspan="2"></th>
                            </tr>
                    </thead>
                    <tbody>
                            <?php
                           
                            foreach ($offer_categories as $category): ?>
                                    <?php $this->load->view('duocms/Offer_categories/index_row', ['category' => $category, 'child' => false]); ?>
                            <?php endforeach; 
                            
                            ?>
                    </tbody>
            </table>
    </div>

    <?php else: ?>
            <p>Brak wyników.</p>
    <?php endif; ?>
</div>


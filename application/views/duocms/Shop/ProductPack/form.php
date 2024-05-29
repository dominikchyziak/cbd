<?php $this->load->view('duocms/Shop/menu'); ?>

<div class="panel panel-default">
    <div class="panel-heading"><strong>Edycja paczki</strong></div>
    <div class="panel-body">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <form action="" method="post">
                            <?=form_hidden('edycja', 1);?>
                            <p>Nazwa</p>
                            <?php $data_name = array('name' => 'name', 'value' => (!empty($pack->name)) ? $pack->name : 'przykładowa paczka', 'class' => 'form-control'); ?>
                            <?= form_input($data_name); ?>
                            <p>Kategoria attrybutów 1</p>
                            <?= form_dropdown('attr_grp_1_id', $attr_groups, (!empty($pack->attr_grp_1_id)) ? $pack->attr_grp_1_id : null, array('class' => 'form-control')); ?>
                            <p>Kategoria attrybutów 2</p>
                            <?= form_dropdown('attr_grp_2_id', $attr_groups, (!empty($pack->attr_grp_2_id)) ? $pack->attr_grp_2_id : null, array('class' => 'form-control')); ?>
                            <?php  
                                $extra = array(
                                    'id' => 'pack_relations'
                                );?>
                                <p>Produkty w paczce</p>
                                <?php echo form_multiselect('relations[]', $products, $pack_products, $extra); ?>
                                <hr>
                        <p>
                            <button type="submit" class="btn btn-primary" style="width: 50%;">Zapisz</button>
                            <a href="<?php echo site_url('duocms/ProductPacks'); ?>" class="btn btn-warning" style="float: right;">Powrót</a>
                        </p>
                    </form>
                      
                </div>
            </div>
        </div>
    </div>
</div>
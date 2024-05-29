<div class="container">
    <div class="row">
        <div class="col-sm-12 wow fadeInLeft">
            <?php $this->load->view('partials/breadcrumbs'); ?> 
        </div>
        <div class="col-sm-12 wow fadeInUp">
            <div class="alert alert-success">
                <?= (new CustomElementModel('15'))->getField('rejestracja sukces'); ?>
            </div>
        </div>
    </div>
</div>
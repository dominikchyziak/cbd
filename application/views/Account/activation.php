<div class="container">
    <div class="row">
        <div class="col-sm-12 wow fadeInLeft">
            <?php $this->load->view('partials/breadcrumbs'); ?>
        </div>
        <div class="col-sm-12 wow fadeInUp">
            <div class="alert <?= $type ? "alert-success" : "alert-danger";?>">
                <p><?= $message;?></p>
            </div>
        </div>  
    </div>
</div>
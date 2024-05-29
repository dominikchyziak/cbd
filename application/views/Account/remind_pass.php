<div class="container">
    <div class="row wow fadeInLeft">
        <div class="col-sm-12">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1><?= (new CustomElementModel('10'))->getField('przypomnienie hasla'); ?></h1>
            <hr>
            <p>
                <?= (new CustomElementModel('15'))->getField('przypomnienie hasla opis'); ?>
                <a href="<?= site_url("account/login"); ?>"><?= (new CustomElementModel('11'))->getField('zaloguj sie'); ?></a>
            </p>
        </div>
    </div>

    <div class="row wow fadeInUp">
        <div class="col-sm-12 col-md-6">
            <?php if (!empty($success)): ?>
		<div class="alert alert-success"><?php echo $success; ?></div>
	<?php endif ?>
            <?php if (!empty($error_message)): ?>
		<div class="alert alert-danger"><?php echo $error_message; ?></div>
	<?php endif ?>
        <?= form_open();?>
        <div class="form-group">
        <?= form_input(array(
            "name" => "email",
            "placeholder" => (new CustomElementModel('11'))->getField('Email'),
            "class" => "form-control"
            ));?>
        </div>
            <div class="form-group">
        <?= form_submit(array(
            "name" => "send",
            "value" =>  (new CustomElementModel('15'))->getField('przypomnij haslo'), 
            "class" => "btn btn-primary"
        ));?>
            </div>
        <?= form_close();?>
    </div>
    </div>
</div>
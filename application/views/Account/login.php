<div class="container">
    <div class="row">
        <div class="col-sm-12 wow fadeInLeft">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1 class="naglowek-podstrona"><?= (new CustomElementModel('10'))->getField('logowanie');?></h1>
            <p>
                <?= (new CustomElementModel('15'))->getField('strona logowania opis'); ?>
                <strong>
                    <a href="<?= site_url("account/register"); ?>">
                    <?= (new CustomElementModel('11'))->getField('zarejestruj sie'); ?>
                    </a>
                </strong>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-6 wow fadeInUp">
            <?php if (!empty($error_message)): ?>
		<div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif ?>
            <?= form_open(); ?>
                <div class="form-group">
                <?=
                form_input(array(
                    "name" => "email",
                    "placeholder" => (new CustomElementModel('11'))->getField('Email'),
                    "class" => "form-control",
                    "required" => "TRUE"
                ));
                ?>
                </div>
            <div class="form-group">
                <?=
                form_password(array(
                    "name" => "password",
                    "placeholder" => (new CustomElementModel('11'))->getField('haslo'),
                    "class" => "form-control",
                    "required" => "TRUE"
                ));
                ?>
            </div>
            <div class="form-group">
                <?=
                form_submit(array(
                    "name" => "send",
                    "value" => (new CustomElementModel('11'))->getField('zaloguj sie'),
                    "class" => "btn button_send btn-primary"
                ));
                ?>
            </div>
            <?= form_close(); ?>

            <small>
                <?= (new CustomElementModel('15'))->getField('nie pamietasz hasla'); ?>
                <a href="<?= site_url("account/remind_pass");?>">
                    <?= (new CustomElementModel('15'))->getField('przypomnij haslo'); ?>
                </a>
            </small>
        </div>
    </div>
</div>
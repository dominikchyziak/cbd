<div class="container">
    <div class="row">
        <div class="col-sm-12 wow fadeInLeft">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1><?= (new CustomElementModel('10'))->getField('Rejestracja'); ?></h1>
            <hr>
            <?= (new CustomElementModel('15'))->getField('rejestracja opis'); ?>
        </div>
        <div class="col-sm-12 wow fadeInUp">
            <div class="row">
                <div class="col-sm-12">
                    <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                </div>
            
                <div class="col-sm-6">
                    <?= form_open(); ?>
                    <div class="form-group">
                        <?=
                        form_input(array(
                            "name" => "email",
                            "value" => !empty($_POST["email"]) ? $_POST["email"] : "",
                            "required" => "true",
                            "placeholder" => (new CustomElementModel('11'))->getField('Email')." *",
                            "class" => "form-control"
                        ));
                        ?>
                    </div>
                    <div class="form-group">
                        <?=
                        form_password(array(
                            "name" => "password",
                            "placeholder" => (new CustomElementModel('11'))->getField('haslo')." *",
                            "class" => "form-control",
                            "required" => "true"
                        ));
                        ?>
                    </div>
                    <div class="form-group">
                        <?=
                        form_password(array(
                            "name" => "password_repeat",
                            "placeholder" => (new CustomElementModel('11'))->getField('Powtorz haslo')." *",
                            "class" => "form-control",
                            "required" => "true"
                        ));
                        ?>
                    </div>
                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="<?= get_option('recaptcha_site_key'); ?>"></div>
                    </div>
                </div>


                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <?= form_input(array(
                            "name" => "first_name",
                            "placeholder" => (new CustomElementModel('11'))->getField('samo imie'),
                            "class" => "form-control",
                            "value" => !empty($_POST["first_name"]) ? $_POST["first_name"] : ""
                        ));?>
                    </div>
                    <div class="form-group">
                        <?= form_input(array(
                            "name" => "last_name",
                            "value" => !empty($_POST["last_name"]) ? $_POST["last_name"] : "",
                            "placeholder" => (new CustomElementModel('11'))->getField('samo nazwisko'),
                            "class" => "form-control"
                        ));?>
                    </div>
                    <div class="form-group">
                        <?=    form_input(array(
                            "name" => "phone",
                            "value" => !empty($_POST["phone"]) ? $_POST["phone"] : "",
                            "placeholder" => (new CustomElementModel('11'))->getField('Telefon'),
                            "class" => "form-control"
                        ));?>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="terms" value="1" required="1" style="float:left; margin-right: 15px;" />
                    </div>
                    <?= (new CustomElementModel('15'))->getField('akceptacja regulaminu'); ?>
                    <div class="form-group">
                        <?=
                            form_submit(array(
                                "name" => "send",
                                "value" => (new CustomElementModel('11'))->getField('zarejestruj sie'),
                                "class" => "button_send btn-primary btn-block"
                            ));
                        ?>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

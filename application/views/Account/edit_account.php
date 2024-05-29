<div class="container">
    <div class="row wow fadeInLeft">
        <div class="col-sm-12 title">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1><?= (new CustomElementModel('15'))->getField('edycja konta'); ?></h2>
        </div>
    </div>
    <div class="row wow fadeInUp">
        <?php if (!empty($error_message)): ?>
        <div class="col-sm-12">
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        </div>
	<?php endif ?>
        <div class="col-sm-12">
            <?= form_open(); ?>
            <?= form_hidden('email',$email);?>
            <div class="row">   
                <div class="col-sm-12">
                     <p><strong><?= (new CustomElementModel('10'))->getField('dane podstawowe naglowek'); ?></strong></p>   
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <?=
                        form_input(array(
                            "name" => "email2",
                            "value" => !empty($email) ? $email : "",
                            "required" => "true",
                            "placeholder" => (new CustomElementModel('11'))->getField('Email')." *",
                            "class" => "form-control",
                            "disabled" => "true"
                        ));
                        ?>
                    </div>
                    <div class="form-group">
                        <?=
                        form_password(array(
                            "name" => "password",
                            "placeholder" => (new CustomElementModel('11'))->getField('haslo')." ",
                            "class" => "form-control"
                        ));
                        ?>
                    </div>
                    <div class="form-group">
                        <?=
                        form_password(array(
                            "name" => "password_repeat",
                            "placeholder" => (new CustomElementModel('11'))->getField('Powtorz haslo')." ",
                            "class" => "form-control"
                        ));
                        ?>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <?= form_input(array(
                            "name" => "first_name",
                            "placeholder" => (new CustomElementModel('11'))->getField('samo imie'),
                            "class" => "form-control",
                            "value" => !empty($first_name) ? $first_name : ""
                        ));?>
                    </div>
                    <div class="form-group">
                        <?= form_input(array(
                            "name" => "last_name",
                            "value" => !empty($last_name) ? $last_name : "",
                            "placeholder" => (new CustomElementModel('11'))->getField('samo nazwisko'),
                            "class" => "form-control"
                        ));?>
                    </div>
                    <div class="form-group">
                        <?=    form_input(array(
                            "name" => "phone",
                            "value" => !empty($phone) ? $phone : "",
                            "placeholder" => (new CustomElementModel('11'))->getField('Telefon'),
                            "class" => "form-control"
                        ));?>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <p><strong><?= (new CustomElementModel('10'))->getField('dane do wysylki naglowek'); ?></strong></p>
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-8">
                            <div class="form-group">
                                <input type="text" placeholder="<?= (new CustomElementModel('11'))->getField('ulica i nr'); ?>" name="street" value="<?= !empty($street) ? $street : ''?>"  class="form-control"/>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-5 col-lg-4">
                            <div class="form-group">
                                <input type="text" placeholder="<?= (new CustomElementModel('11'))->getField('nr_mieszkania'); ?>" name="building_number" value="<?= !empty($building_number) ? $building_number : ''?>"  class="form-control"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-5 col-lg-3">
                            <div class="form-group">
                                <input type="text" name="zip_code" placeholder="<?= (new CustomElementModel('11'))->getField('kod pocztowy'); ?>" value="<?= !empty($zip_code) ? $zip_code : ''?>"  class="form-control"/>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7 col-lg-9">
                            <div class="form-group">
                                <input type="text" name="city" placeholder="<?= (new CustomElementModel('11'))->getField('Miasto'); ?>" value="<?= !empty($city) ? $city : ''?>"  class="form-control"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                     <p><strong>Newsletter</strong></p>
                    <?php if(!empty($newsletter_info)):
                        $signup_date = strtotime($newsletter_info[0]->created_at);
                        ?>
						<?php /*
                     <p>
                   Zgoda na otrzymywanie newslettera udzielona w dniu <?= date('d.m.Y', $signup_date);?> o godzinie <?= date('H:i:s', $signup_date); ?><br />
                   Jeżeli chcesz się wypisać się z newslettera, możesz to zrobić <b><a href="<?= site_url('newsletter/unsubscribe');?>">TUTAJ</a></b><br />
                     </p> */ ?>
					 <?= (new CustomElementModel('15'))->getField('newsletter zgoda udzielono'); ?>
                    <?php else: ?>
						<?= (new CustomElementModel('15'))->getField('newsletter zgoda tekst'); ?>
                     <input type="checkbox" name="newsletter_accept" value="1" /> <?= (new CustomElementModel('15'))->getField('newsletter zgoda checkbox'); ?>
                     <p>&nbsp;</p>
                    <?php endif; ?>
                    <div class="form-group">
                        <?=
                            form_submit(array(
                                "name" => "send",
                                "value" => (new CustomElementModel('15'))->getField('zaktualizuj dane'),
                                "class" => "btn btn-primary btn-block"
                            ));
                        ?>
                    </div>
                </div>
            </div>
        <?= form_close(); ?>
        </div>
    </div>
</div>
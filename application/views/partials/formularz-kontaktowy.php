<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-5">
        <h1 class="header_title wow fadeInLeft"><?= (new CustomElementModel('11'))->getField('Formularz kontaktowy'); ?></h1>
    </div>
</div>
<?php echo form_open('kontakt'); ?>
<?php if (!empty($error_message)): ?>
    <div class="alert alert-<?= $succes ? 'success' : 'danger'; ?>"><?php echo $error_message; ?></div>
<?php endif ?>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="col-sm-12 col-md-6 padding_rest_size">
            <div class="form-group wow fadeInUp" data-wow-delay="0.1s">
                <label for="name" class="control-label label-required"><?= (new CustomElementModel('11'))->getField('Imie'); ?></label>
                <?php echo form_input('name', set_value('name'), 'class="form-control" required'); ?>
            </div>
            <div class="form-group wow fadeInUp" data-wow-delay="0.2s">
                <label for="phone" class="control-label"><?= (new CustomElementModel('11'))->getField('Telefon'); ?></label>
                <?php echo form_input('phone', set_value('phone'), 'class="form-control required"'); ?>
            </div>
            <div class="form-group wow fadeInUp" data-wow-delay="0.3s">
                <label for="email" class="control-label label-required"><?= (new CustomElementModel('11'))->getField('Email'); ?></label>
                <?php echo form_input('email', set_value('email'), 'class="form-control" required'); ?>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 padding_rest_size text_area_padding">
            <div class="form-group wow fadeInUp" data-wow-delay="0.5s">
                <label for="message" class="control-label label-required"><?= (new CustomElementModel('11'))->getField('tresc wiadomosci'); ?></label>
                <?php echo form_textarea('message', set_value('message'), 'class="form-control" required'); ?>
            </div>
        </div>
    </div>
</div>
    <div class="col-sm-12 padding_rest_size">
        <?php /*<div class="form-group wow fadeInUp" data-wow-delay="0.4s">
            <input type="checkbox" name="term1" value="1" required="1" />
            <?= strip_tags((new CustomElementModel('19'))->getField('fiszka1')->value, '<a><span><strong><b><i><u>'); ?>
        </div> */?>
        <div class="checkbox wow fadeInUp" data-wow-delay="0.4s">
            <label>
                <input type="checkbox" name="term1" value="1" required="1" style="float:left;"/>
         
                <?= (new CustomElementModel('19'))->getField('fiszka1'); ?>
                <?php /* <?= strip_tags((new CustomElementModel('19'))->getField('fiszka1')->value, '<a><span><strong><b><i><u>'); ?>*/?>
            </label>
        </div>
        <?php /*<div class="form-group wow fadeInUp" data-wow-delay="0.5s">
            <input type="checkbox" name="term2" value="1" required="1" />
            <?= strip_tags((new CustomElementModel('19'))->getField('fiszka2')->value, '<a><span><strong><b><i><u>'); ?>
        </div> */?>
        <div class="checkbox wow fadeInUp" data-wow-delay="0.4s">
            <label>
                <input type="checkbox" name="term2" value="1" required="1" style="float:left;" />
                
                <?= (new CustomElementModel('19'))->getField('fiszka2'); ?>
                <?php /* <?= strip_tags((new CustomElementModel('19'))->getField('fiszka2')->value, '<a><span><strong><b><i><u>'); ?> */?>
            </label>
        </div>
        <div class="privacy_policy wow fadeInUp" data-wow-delay="0.6s">
            <?php /*strip_tags((new CustomElementModel('19'))->getField('linki polityka i rodo')->value, '<a><span><strong><b><i><u>'); */?>
            <a href="<?= site_url('/polityka-prywatnosci'); ?>" class="privacy_policy_button"><?= (new CustomElementModel('19'))->getField('polityka_prywatności'); ?></a>
        </div>
    </div>
    <div class="col-sm-6 col-md-6 padding_rest_size">
        <div class="form-group wow fadeInUp" data-wow-delay="0.7ss">
            <div class="g-recaptcha" data-sitekey="<?= get_option('recaptcha_site_key'); ?>"></div>
        </div>
    </div>   
    <div class="col-sm-6 col-md-6 padding_rest_size btn_send_padding">
        <div class="form-group wow fadeInUp" data-wow-delay="0.8s">
            <button type="submit" class="btn btn-primary btn-block" name="send" value="1"><?= (new CustomElementModel('11'))->getField('przeslij formularz'); ?></button>
        </div>
    </div>  
<?php echo form_close(); ?>
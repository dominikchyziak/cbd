<div class="container">
    <div class="row">
        <div class="col-sm-12 wow fadeInLeft">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1><?= (new CustomElementModel('15'))->getField('Witaj'); ?> <?= !empty($this->session->login["user"]["first_name"]) ? $this->session->login["user"]["first_name"] : $this->session->login["user"]["email"] ;?></h1>
            <hr>
            <p>
               <?= (new CustomElementModel('15'))->getField('Powitanie'); ?><br>
            </p>
            <p>
                <a href="<?= site_url('account/edit_account');?>"><?= (new CustomElementModel('15'))->getField('edycja konta'); ?></a><br>
                <a href="<?= site_url("account/my_orders");?>"><?= (new CustomElementModel('15'))->getField('Moje zamówienia'); ?></a><br>
                <a href="<?= site_url("account/logout"); ?>"><?= (new CustomElementModel('15'))->getField('Wyloguj'); ?></a>
            </p>
        </div>
    </div>
</div>









<?php /*

<?php $this->load->view('partials/breadcrumbs'); ?>
 <div class="szer-container">
<div class="container zawartosc-podstrony">
      
        <h1><?= (new CustomElementModel('15'))->getField('Witaj'); ?> <?= !empty($this->session->login["user"]["first_name"]) ? $this->session->login["user"]["first_name"] : $this->session->login["user"]["email"] ;?></h1>
        <hr>
        <p>
           <?= (new CustomElementModel('15'))->getField('Powitanie'); ?><br>
        </p>
        <p>
            <a href="<?= site_url('account/edit_account');?>"><?= (new CustomElementModel('15'))->getField('edycja konta'); ?></a><br>
            <a href="<?= site_url("account/my_orders");?>"><?= (new CustomElementModel('15'))->getField('Moje zamówienia'); ?></a><br>
            <a href="<?= site_url("account/logout"); ?>"><?= (new CustomElementModel('15'))->getField('Wyloguj'); ?></a>
        </p>
</div>

 </div> */ ?>
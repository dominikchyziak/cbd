<?php
$this->load->model('PartnerModel');
$partnerzy = (new PartnerModel())->findAll();
?>
<?php if ($partnerzy): ?>
    <?php foreach ($partnerzy as $partner):  ?>
        <a href="<?= $partner->url;?>">
            <img src="<?= $partner->getUrl(); ?>" title=""  alt="">
        </a>
     <?php endforeach; ?> 
<?php endif; ?>

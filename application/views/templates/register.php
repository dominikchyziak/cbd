<?= (new CustomElementModel('14'))->getField('naglowek maila'); ?>
<?= (new CustomElementModel('15'))->getField('mail rejestracja'); ?>
<a href="<?= site_url("account/activation/".$code);?>">
    <?= site_url("account/activation/".$code);?>
</a>
<?= (new CustomElementModel('14'))->getField('stopka maila'); ?>
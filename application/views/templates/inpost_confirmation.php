<?= (new CustomElementModel('14'))->getField('naglowek maila'); ?>

<?= (new CustomElementModel('16'))->getField('mail z nr listu przewozowego inpost'); ?>
<br />
<a href="https://inpost.pl/sledzenie-przesylek?number=<?= $package_id; ?>">Śledź przesyłkę</a>

<?= (new CustomElementModel('14'))->getField('stopka maila'); ?>

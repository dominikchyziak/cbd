<?= (new CustomElementModel('14'))->getField('naglowek maila'); ?>
Twoje zamówienie o id: <?= $order->id;?> zmieniło status na: zrealizowane.
<hr>
<?= !empty($note) ? $note : '';?>
<hr>
<?= (new CustomElementModel('14'))->getField('status opis 15'); ?>
<?= (new CustomElementModel('14'))->getField('w kazdej chwili mozesz'); ?>
<a href="<?= site_url('zamowienie/summary/'.$order->id.'/'.$order->key);?>">
    <?= (new CustomElementModel('14'))->getField('szczegoly zamowienia'); ?>
</a>
<?= (new CustomElementModel('14'))->getField('stopka maila'); ?>
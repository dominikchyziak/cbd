<?= (new CustomElementModel('14'))->getField('naglowek maila'); ?>
Twoje zamówienie o id: <?= $order->id;?> zmieniło status na: <?= (new CustomElementModel('14'))->getField('status 2'); ?>.
<hr>
<?= (new CustomElementModel('14'))->getField('status opis 2'); ?></br>
<?= !empty($note) ? $note : '';?>
<hr>

<?= (new CustomElementModel('14'))->getField('w kazdej chwili mozesz'); ?>
<a href="<?= site_url('zamowienie/summary/'.$order->id.'/'.$order->key);?>">
    <?= (new CustomElementModel('14'))->getField('szczegoly zamowienia'); ?>
</a>

<?= (new CustomElementModel('14'))->getField('stopka maila'); ?>

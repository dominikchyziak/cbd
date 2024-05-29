<h1 class="page-header">Konfigurator galerii - css</h1>

<?php if(!empty($options)): ?>
<form method="POST">
<?php  $i=0; foreach($options as $opt): ?>
<p><?= $opt->friendly_name;?></p>
<input type="hidden" name="id[<?=$i;?>]" value="<?= $opt->id;?>" />
<input type="text" name="value[<?= $i;?>]" value="<?= $opt->value; ?>" class="form-control" />
<?php $i++; endforeach;?>
<p>&nbsp;</p>
<input type="submit" value="Zapisz" class="form-control">
</form>
<?php endif;?> 
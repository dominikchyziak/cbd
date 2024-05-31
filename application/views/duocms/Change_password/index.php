<div class="header">Zmień hasło:</div>

<?php if ($errors = validation_errors()): ?>
	<div id="errors"><?php echo $errors; ?></div>
<?php endif ?>

<?php echo form_open('duocms/change_password/submit'); ?>
<div class="col-sm-12 col-md-4">
	<table>
		<tr>
			<td>Dla użytkownika:</td>
			<td><?php echo form_dropdown('user_id', $user_list, null, 'class="form-control"'); ?></td>
		</tr>
		<tr>
			<td>Podaj nowe hasło:</td>
			<td><?php echo form_password(array(
				'name' => 'haslo',
				'required' => TRUE,
                            'class' => 'form-control'
			)); ?></td>
		</tr>
		<tr>
			<td>Powtórz hasło:</td>
			<td><?php echo form_password(array(
				'name' => 'haslo2',
				'required' => TRUE,
                            'class' => 'form-control'
			)); ?></td>
		</tr>
		<tr>
			<td></td>
			<td><?php echo form_submit(array(
				'name' => 'submit',
				'value' => 'Zmień',
				'class' => 'button form-control'
			)); ?></td>
		</tr>
	</table>
</div>
<?php form_close(); ?>
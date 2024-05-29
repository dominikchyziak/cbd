<h3>Formularz kontaktowy</h3>

<p>Imię i nazwisko: <b><?php echo $name; ?></b></p>

<?php if (!empty($phone)): ?>
	<p>Numer telefonu: <b><?php echo $phone; ?></b></p>
<?php endif; ?>

<p>Adres e-mail: <b><?php echo $email; ?></b></p>

<p>Treść wiadomości: <b><?php echo $message; ?></b></p>
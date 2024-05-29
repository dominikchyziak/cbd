<h3>Formularz zamówienia</h3> 

<p>Imię i nazwisko: <b><?php echo $name; ?></b></p>

<?php if (!empty($phone)): ?>
	<p>Numer telefonu <b><?php echo $phone; ?></b></p>
<?php endif; ?>
<p>Adres e-mail: <b><?php echo $email; ?></b></p>

<?php if (!empty($product)): ?>
	<p>Produkt <b><?php echo $product; ?></b></p>
<?php endif; ?>
        <?php if (!empty($delivery)): ?>
	<p>Sposób dostawy <b><?php echo $delivery; ?></b></p>
<?php endif; ?>
<?php if (!empty($address)): ?>
	<p>Adres <b><?php echo $address; ?></b></p>
<?php endif; ?>
   <?php if (!empty($marketing)): ?>
	<p>Marketing <b><?php echo $marketing; ?></b></p>
<?php endif; ?>     
<p>Dodatkowe informacje: <b><?php echo $message; ?></b></p>
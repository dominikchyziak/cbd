<h3>Edycja agenta</h3>
<p>
    <a href="../index" class="button">Powrót</a>
</p>
<form method="POST">
    <input type="text" name="city" value="<?= $agent->city; ?>" placeholder="Wpisz miejscowość" required="true"/>
    <input type="text" name="name" value="<?= $agent->name; ?>" placeholder="Wpisz nazwę" required="true"/>
    <input type="text" name="address" value="<?= $agent->address; ?>" placeholder="Wpisz adres" required="true"/>
    <input type="text" name="tel" value="<?= $agent->tel; ?>" placeholder="Telefon kontaktowy" required="true"/>
    <input type="text" name="email" value="<?= $agent->email; ?>" placeholder="Adres E-mail" required="true"/>
    <input type="submit" value="Aktualizuj dane agenta" />
</form>
<h3>Nowa kategoria elementów</h3>
<div class="alert alert-info">
    Kategoria elementów to odrębny dział/sekcja strony, w której znajdują się elementy tak jak np w swopce znajduje się wiele informacji kontaktowych i innych.
    Później w każdej z kategorii można dodawać pola, których zawartość wyświetlana jest na stronie.<br>
</div>
<div class="alert alert-warning">
    <b>Uwaga: oprócz dodania pola niezbędna jest późniejsza jego implementacja w kodzie źródłowym strony.</b>
</div>    

<p>
<a href="<?= base_url('duocms/custom_elements');?>" class="btn btn-primary">< Powrót</a><br>
</p>
<form method="POST">
    <input type="hidden" name="category_id" value="<?= !empty($category_id) ? $category_id : '';?>" />
    <input type="text" name="name" value="<?= !empty($name) ? $name : '';?>" placeholder="Wpisz nazwę kategorii" required="true" class="form-control" /><br>
    <input type="submit" value="<?= !empty($name) ? 'Aktualizuj' : 'Dodaj';?> kategorię" class="btn btn-primary"/>
</form>
<h3>Dodawanie elementu</h3>
<p>
    W tym miejscu możesz dodać element na strone, pamiętaj jednak że po dodaniu trzeba go jeszcze zaprogramować w kodzie źródłowym umieszczając w miejscu gdzie ma się wyświetlać.
</p>
<p>
    <a href="<?= base_url('duocms/custom_elements/edit/'. $category_id);?>" class="button">< Powrót</a><br> 
</p>
 
<form method="POST">
    <input type="text" name="title" placeholder="nazwa pola" class="form-control" required="true"/><br>
    <select name="type" required="true" class="form-control">
        <option value="text">Tekstowe</option>
        <option value="ckeditor">Ck Edytor</option>
        <option value="image">Obrazek</option>
        <option value="file">Plik</option>
    </select><br>
    <textarea name="content" placeholder="Zawartość pola (możnna później edytować/dodać wersje językowe" class="form-control"></textarea><br>
    <input type="submit" value="Dodaj element" class="button" /><br>
</form>
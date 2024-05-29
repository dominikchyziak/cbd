<div class="col-sm-12">
    <h2>Edycja opcji</h2>

    <form method="POST">
        <div class="form-group">
            <label for="name" class="label">Nazwa <br><small>(Widoczna na liście wyboru opcji danego produktu)</small></label>
            <input type="text" name="name" value="<?= $option['name']; ?>" class="form-control" required="true" />
        </div>
        <div class="form-group">
            <label for="description" class="label">Klasa <br><small></small></label>
            <input type="text" name="description" value="<?= $option['description']; ?>" class="form-control" required="true" />
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <label for="price_change" class="label">Zmiana ceny <br>
                        <small>(Wartość, o którą zostanie zmieniona<br> cena bazowa po wyborze danej opcji)</small></label>
                    <input type="number" step="0.01" name="price_change" value="<?= $option['price_change']; ?>" class="form-control" required="true" />
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <label for="quantity" class="label">Ilość <br>
                        <small>(Ilość danej opcji.<br>Jeśli nieograniczona -1)</small></label>
                    <input type="number" step="1" name="quantity" value="<?= $option['quantity']; ?>" class="form-control" required="true" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <label for="visibility">Widoczna na stronie</label>
                    <select name="visibility">
                        <option value="<?= $option['visibility']; ?>"><?= !empty($option['visibility']) ? 'Tak' : 'Nie'; ?></option>
                        <option value="<?= !$option['visibility']; ?>"><?= empty($option['visibility']) ? 'Tak' : 'Nie'; ?></option>
                    </select>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <input type="submit" name="add_option" value="Aktualizuj opcję" class="button" />
                </div>
            </div>
        </div>
    </form>
</div>
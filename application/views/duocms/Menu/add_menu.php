<div class="col-sm-12">
    <h3>Dodaj nowe menu</h3>
    <div class="alert alert-info">
        Tutaj możesz dodać nowe menu (na przykład menu w stopce)
    </div>
    <p>
        <a href="<?= site_url('duocms/menu/');?>" class="btn btn-warning">Powrót</a>
    </p>
    <form method="POST">
        <input type="text" name="name" value="" required="true" placeholder="Nazwa menu" class="form-control"/><br>
        <input type="submit" value="Dodaj nowe menu" class="btn btn-success"/>
    </form>
</div>

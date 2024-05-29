<div class="col-sm-12">
    <h2>Edycja dodatku</h2>

    <form method="POST">
        <input type="hidden" name="id" value="<?=$detail->id; ?>" />
        <div class="form-group">
            <label for="name" class="label">Nazwa</label>
            <input type="text" name="name" value="<?= $detail->name; ?>" class="form-control" required="true" />
        </div>
        <div class="form-group">
            <label for="description" class="label">Nazwa wewn.</label>
            <input type="text" name="val" value="<?= $detail->val; ?>" class="form-control" required="true" />
        </div>

                <div class="form-group">
                    <label for="price_change" class="label">Cena</label>
                    <input type="number" step="0.01" name="price" value="<?= $detail->price; ?>" class="form-control" required="true" />
                </div>
        
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <input type="submit" name="update_detail" value="Aktualizuj dodatek" class="button" />
                </div>
            </div>
             <div class="col-sm-12 col-md-6">
                <div class="pull-right">
                    <a href="<?= site_url('duocms/products/edit/'.$product->id);?>" >Powrót</a>
                </div>
            </div>
        </div>
    </form>
</div>
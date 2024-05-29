<div class="col-sm-12">
    <h2>Edycja dodatku</h2>

    <form method="POST">
        <input type="hidden" name="id" value="<?=$group->id; ?>" />
        <div class="form-group">
            <label for="name" class="label">Nazwa</label>
            <input type="text" name="name" value="<?= $group->name; ?>" class="form-control" required="true" />
        </div>
       
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <input type="submit" name="update_detail" value="Aktualizuj grupę" class="button" />
                </div>
            </div>
             <div class="col-sm-12 col-md-6">
                <div class="pull-right">
                    <a href="<?= site_url('duocms/products/edit/'.$group->product_id);?>" >Powrót</a>
                </div>
            </div>
        </div>
    </form>
</div>
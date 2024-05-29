<?php $this->load->view('duocms/Shop/menu'); ?>

<div class="panel panel-default">
    <div class="panel-heading"><strong><?= !empty($currency) ? 'Edycja' : 'Dodawanie';?> waluty</strong></div>
    <div class="panel-body">
        <form method="POST">
            <div class="tab-content">
                <div id="pl" class="tab-pane fade in active">

                    <div class="form-group">
                        <p>Nazwa</p>
                        <input type="text" name="name" value="<?= !empty($currency->name) ? $currency->name : '';?>" class="form-control" />
                    </div>
                    <div class="form-group">
                        <p>3literowy kod waluty</p>
                       <input type="text" name="code" value="<?= !empty($currency->code) ? $currency->code : '';?>" class="form-control" />
                    </div>
                    <div class="form-group">
                        <p>Komentarz</p>
                        <input type="text" name="comment" value="<?= !empty($currency->comment) ? $currency->comment : '';?>" class="form-control" />
                    </div>

                    <div class="form-groip">
                        <p>Widoczność na stronie</p>
                        <select name="visibility" class="form-control">
                            <option value="1" <?= (!empty($currency->visibility) && $currency->visibility == 1)? 'selected' : ''; ?>>Widoczna</option>
                            <option value="2" <?= (!empty($currency->visibility) && $currency->visibility == 2)? 'selected' : ''; ?>>Ukryta</option>
                        </select>
                    </div>
                </div>
                    <br>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <input type="submit" value="Zapisz" class="button" />
                 </div>
            </div>
        </form>
    </div>
</div>

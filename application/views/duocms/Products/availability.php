<?php $this->load->view('duocms/Shop/menu'); ?>
<div class="panel panel-default">
    <div class="panel-body">
            <form method="post">
            <?php if(!empty($producents)){?>
                <select name="producent" class="form-control" style="width: 50%;margin-bottom:15px;">
                    <?php foreach($producents as $producent){ ?>
                    <option value="<?= $producent->producent ?>" ><?= $producent->producent ?></option>
                    <?php } ?>
                </select>
            <?php } ?>
                
            <input type="text" name="availability" placeholder="Dostępność" class="form-control" style="width: 50%;margin-bottom:15px;"/>
            <input type="submit" class="btn btn-primary" value="Zmień" />
            </form>
    </div>
</div>

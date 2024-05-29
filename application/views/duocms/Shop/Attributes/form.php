<?php $this->load->view('duocms/Shop/menu'); ?>

<div class="panel panel-default">
    <div class="panel-heading"><strong><?= !empty($attribute) ? 'Edycja' : 'Dodawanie';?> atrybutu</strong></div>
    <div class="panel-body">
        <form method="POST">
            <?php $languages = get_languages();?>
            <ul class="nav nav-tabs">
                <li><a href="#pl" data-toggle="tab">Polski</a></li>
			<?php
                        foreach($languages as $lang){
                            if($lang->short != "pl"){
                                echo '<li><a href="#' . $lang->short . '" data-toggle="tab">' . $lang->name . '</a></li>';
                            }
                        }
                        ?>
            </ul>

            <div class="tab-content">
                <div id="pl" class="tab-pane fade in active">
                    <div class="form-group">
                        <p>Grupa</p>
                        <select name="attributes_group" class="form-control">
                            <?php
                            if(!empty($groups)){
                                foreach($groups as $group){
                                    ?>
                            <option value="<?= $group->attributes_group_id;?>" <?php 
                            if(!empty($attribute['attributes_group_id']) && $attribute['attributes_group_id'] == $group->attributes_group_id){ 
                                echo 'selected';
                            }?>><?= $group->name;?> </option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                   <div class="form-group">
                        <p>Cena</p>
                   
  <input type="number" name="value" value="<?= !empty($attribute['value']) ? $attribute['value'] : '';?>" step="0.01" min="-100" class="form-control" />
   </div>
                    <div class="form-group">
                        <p>Nazwa</p>
                        <input type="text" name="pl[name]" value="<?= !empty($attribute['translations']['pl']['name']) ? $attribute['translations']['pl']['name'] : '';?>" class="form-control" /> 
                    </div>
                    <div class="form-group">
                        <p>Opis</p>
                        <textarea name="pl[description]" class="ckeditor" ><?= !empty($attribute['translations']['pl']['description']) ? $attribute['translations']['pl']['description'] : '';?></textarea>
                    </div>
                    <br>
                    
                </div>
                <?php
                        foreach($languages as $lang){
                            if($lang->short != "pl"){
                ?>
                <div id="<?= $lang->short;?>" class="tab-pane fade">
                    <div class="form-group">
                        <p>Nazwa:</p>
                        <input type="text" name="<?= $lang->short;?>[name]" value="<?= !empty($attribute['translations'][$lang->short]['name']) ? $attribute['translations'][$lang->short]['name'] : '';?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <p>Opis:</p>
			<textarea name="<?= $lang->short;?>[description]" class="ckeditor"><?= !empty($attribute['translations'][$lang->short]['description']) ? $attribute['translations'][$lang->short]['description'] : '';?></textarea>
                    </div>
		</div>
                <?php
                            }
                        }
                 ?>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <input type="submit" value="Zapisz" class="button" />
                 </div>
            </div>
        </form>
    </div>
</div>
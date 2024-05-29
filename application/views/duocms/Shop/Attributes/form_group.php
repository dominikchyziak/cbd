<div class="panel panel-default">
    <div class="panel-heading">
        <form action="<?= site_url('duocms/Products_Attributes/ajax_add_group');?>" class="ajax-form" method="post" id='add_attr_group_form' enctype="multipart/form-data">
            <?php $languages = get_languages();?>
                <div class="ui-tabs">
                        <ul>
                                <li><a href="#pl">Polski</a></li>
                                <?php
                                foreach($languages as $lang){
                                    if($lang->short != "pl"){
                                        echo '<li><a href="#' . $lang->short . '">' . $lang->name . '</a></li>';
                                    }
                                }
                                ?>
                        </ul>
                        <div id="pl">
                            <p>Nazwa grupy</p>
                            <input type="text" name="name_pl" id="name_pl"  value=""  class="form-control"/>
                        </div>
                        <?php
                                foreach($languages as $lang){
                                    if($lang->short != "pl"){
                        ?>
                        <div id="<?= $lang->short;?>">
                           <p>Nazwa grupy</p>
                           <input type="text" name="name_<?= $lang->short;?>" id="name_<?= $lang->short;?>" value="" class="form-control"/>
                        </div>
                        <?php
                                    }
                                }
                         ?>
                </div>
                <p>
                        <button type="submit" class="btn btn-primary" style="width: 50%;">Zapisz</button>
                </p>
        </form>
    </div>
    
</div>

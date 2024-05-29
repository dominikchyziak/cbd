<?php echo form_open_multipart('praca'); ?>
<?php if (!empty($error_message)): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif ?>
<div class="col-sm-12 col-md-6">
    <div class="form-group">
        <label for="userfile" class="control-label">Załącz CV oraz LM <small>(gif,png,jpg,pdf,doc)</small></label>
        <input type="file" name="userfile" size="2000" required="true"/>
        <input type="file" name="lm" size="2000" />
    </div>
    <div class="form-group">
        <label for="name" class="control-label label-required">Stanowisko</label>
        <select name="position" class="form-control">
            <option value="">Wybierz</option>
            <?php
            if(!empty($positions)){
                foreach($positions as $position){
                    ?>
            <option value="<?= $position->id;?>"><?= $position->name;?></option>
                    <?php
                }
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="name" class="control-label label-required"><?php echo lang('contact_form_name'); ?></label>
        <?php echo form_input('name', set_value('name'), 'class="form-control" required'); ?>
    </div>
    <div class="form-group">
        <label for="phone" class="control-label"><?php echo lang('contact_form_phone'); ?></label>
        <?php echo form_input('phone', set_value('phone'), 'class="form-control required"'); ?>
    </div>
    <div class="form-group">
        <label for="email" class="control-label label-required"><?php echo lang('contact_form_email'); ?></label>
        <?php echo form_input('email', set_value('email'), 'class="form-control" required'); ?>
    </div>
    
    <div class="form-group">

        <div class="g-recaptcha" data-sitekey="<?= get_option('recaptcha_site_key'); ?>"></div>
    </div>
</div>
<div class="col-sm-12 col-md-6">
    <div class="form-group">
        <label for="message" class="control-label label-required"><?php echo lang('contact_form_message'); ?></label>
        <?php echo form_textarea('message', set_value('message'), 'class="form-control" required'); ?>
    </div>


    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-block" name="send" value="1"><?php echo lang('contact_form_send'); ?></button>
    </div>
</div>   
<?php echo form_close(); ?>
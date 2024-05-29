<h3 class="page-header"><?php echo lang('order_head_form'); ?></h3>
<?php echo form_open('zamowienie'); ?>
	<?php if (!empty($error_message)): ?>
		<div class="alert alert-danger"><?php echo $error_message; ?></div>
	<?php endif ?>
	<div class="form-group">
		<label for="name" class="control-label label-required"><?php echo lang('contact_form_name'); ?></label>
		<?php echo form_input('name', set_value('name'), 'class="form-control" required'); ?>
	</div>
	<div class="form-group">
		<label for="phone" class="control-label"><?php echo lang('contact_form_phone'); ?></label>
		<?php echo form_input('phone', set_value('phone'), 'class="form-control"'); ?>
	</div>
	<div class="form-group">
		<label for="email" class="control-label label-required"><?php echo lang('contact_form_email'); ?></label>
		<?php echo form_input('email', set_value('email'), 'class="form-control" required'); ?>
	</div>
        <div class="form-group">
		<label for="product" class="control-label label-required"><?php echo lang('check_product'); ?></label>
		<?php echo form_dropdown('product',$products, !empty($_POST["product"]) ?  $_POST["product"] : "", 'class="form-control" required'); ?>
	</div>    
        <div class="form-group">
		<label for="delivery_form" class="control-label label-required"><?php echo lang('delivery_form'); ?></label>
		<?php echo form_dropdown('delivery', $delivery, !empty($_POST["delivery"]) ? $_POST["delivery"] : "", 'class="form-control" required'); ?>
	</div> 
        <div class="form-group">
		<label for="address" class="control-label label-required"><?php echo lang('order_address'); ?></label>
		<?php echo form_textarea('address', set_value('address'), 'class="form-control" required'); ?>
	</div>
	<div class="form-group">
		<label for="message" class="control-label label-required"><?php echo lang('order_content'); ?></label>
		<?php echo form_textarea('message', set_value('message'), 'class="form-control"'); ?>
	</div>
	<?php /*<div class="form-group">
		<label for="captcha" class="control-label label-required"><?php echo lang('contact_form_captcha'); ?></label>
		<p><?php echo $captcha['image']; ?></p>
		<?php echo form_input('captcha', set_value('captcha'), 'class="form-control" required'); ?>
	</div> */?>
        <div class="form-group">
            <div class="g-recaptcha" data-sitekey="6Le6pa8UAAAAAPQ6GpA2wlHw-GY3d0-lkgiCEHQa"></div>
        </div>
        <div class="form-group">
            <input type="checkbox" name="accept_term" required="true" /> <a href="<?= site_url("regulamin");?>">
                <?= lang("accept_term");?>
            </a>
        </div>
        <div class="form-group">
            <input type="checkbox" name="accept_term2" required="true" />
                <?= lang("accept_term2");?>
        </div>
        <div class="form-group">
            <input type="checkbox" name="accept_term3" value="1"/>
                <?= lang("accept_term3");?>
        </div>
	<div class="form-group">
		<button type="submit" class="btn btn-primary btn-block" name="send" value="1"><?php echo lang('order_form_send'); ?></button>
	</div>
<?php echo form_close(); ?>
<?php if ($this->msg_error): ?>
	<div class="message-fail"><?php echo $this->msg_error; ?></div>
<?php endif ?>

<?php if ($this->msg_okay): ?>
	<div class="message-ok"><?php echo $this->msg_okay; ?></div>
<?php endif ?>
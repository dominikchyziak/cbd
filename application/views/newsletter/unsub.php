<div class="container subpage">
	<div class="row">
		<div class="col-sm-12">
		<h1 class="naglowek-strona naglowek-strona-czarny naglowek-strona-center wow fadeInRight"><?= (new CustomElementModel('10'))->getField('newsletter wypis sie'); ?></h1>
		<?php if(!empty($msg)): ?>
		<div class="alert alert-info"><?= $msg; ?></div>
		<?php endif; ?>
		</div>
		<div class="col-sm-12 col-md-6 site-body">  
			<form method="POST">
				<input type="email" name="email" value="" placeholder="<?= (new CustomElementModel('2'))->getField('newsletter placeholder'); ?>"  class="form-control" /><br/>
				<input type="submit" name="send" value="OK" class="btn btn-primary btn-block"/>
			</form>
		</div>
	</div>
</div>
<div class="subpage">
	<h1><?php echo lang('certyfikaty_header'); ?></h1>
	<?php if ($certyfikaty): ?>
		<div class="row">
			<div class="galeria animatedParent animateOnce" data-sequence="100">
				<?php foreach ($certyfikaty as $l => $certyfikat): ?>
					<div class="item">
						<div class="image animated bounceInLeft" data-id="<?php echo ++$l; ?>">
							<a href="<?php echo $certyfikat->getUrl(); ?>" data-lightbox="certyfikaty">
								<img src="<?php echo $certyfikat->getUrl('mini'); ?>" alt="<?php echo lang('certyfikaty_header'); ?>">
							</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php else: ?>
		<p><?php echo lang('certyfikaty_empty'); ?></p>
	<?php endif; ?>
</div>

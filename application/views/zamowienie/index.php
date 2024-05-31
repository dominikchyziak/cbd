<?php $translation = $page->getTranslation(LANG); ?>
<div class="subpage">
	<div class="">
		<div class="col-lg-7 col-md-7 col-sm-6">
                    <h1 class="page-header"><?php echo $translation->title; ?></h1>
			<?php echo $translation->body; ?>
		</div>
		<div class="col-lg-5 col-md-5 col-sm-6">
			<?php $this->load->view('partials/formularz-zamowienia'); ?>
		</div>
	</div>
</div>

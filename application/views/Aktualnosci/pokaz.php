<div class="container">
    <div class="row wow fadeInLeft">
        <div class="col-sm-12">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <div class="news-time-box">
                <span><?=(new DateTime($news->started_at))->format('d').' '.$news->getMonthName((new DateTime($news->started_at))->format('m'), LANG).'</span> '.(new DateTime($news->started_at))->format('Y'); ?>
            </div>
            <h1 class="naglowek-podstrona"><?php echo stolarczyk_title($translation->title); ?></h1>
        </div>
    </div>
    <div class="row wow fadeInUp">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="description wow fadeInUp">
                <?php /* if(!empty($translation->image)): ?>
                    <img src="<?= $translation->image;?>" style="float:right; max-width: 100%; margin-bottom: 25px;" />
                <?php endif; */ ?>
                <?php echo $translation->body; ?>
            </div>
        </div>
    </div>
</div>
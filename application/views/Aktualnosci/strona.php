<div class="container">
    <div class="row wow fadeInLeft">
        <div class="col-sm-12">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1 class="naglowek-podstrona wow fadeInLeft"><?= (new CustomElementModel('10'))->getField('aktualnosci naglowek'); ?></h1>
        </div>
    </div>
    
    <div class="row wow fadeInUp">
        <?php
        foreach($news as $i => $n) :
                $this->load->view('Aktualnosci/partials/single_news_view',[ 'news' => $n ]);
        endforeach;
        ?> 
    </div> 
    <div class="row wow fadeInUp">
        <div class="col-12">
            <?php echo $this->pagination->create_links(); ?>
        </div>   
    </div>
</div>
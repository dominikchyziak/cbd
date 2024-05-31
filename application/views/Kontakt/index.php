<?php $translation = $page->getTranslation(LANG); ?>
<div class="container">
    <div class="row">
        <div class="col-12 wow fadeInLeft">
            <?php $this->load->view('partials/breadcrumbs'); ?>
            <h1 class="naglowek-podstrona"><?php echo stolarczyk_title($translation->title); ?></h1>
        </div>
        <div class="col-md-4 col-sm-12 my-auto body_contact wow fadeInUp kontakt">
            <?php echo $translation->body; ?>
        </div>
       
        <div class="col-md-8 col-sm-12 wow fadeInDown">  
           <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2560.0706914487137!2d20.84565731593445!3d50.08496332151291!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x473d80550342f5c7%3A0x57d3b0d519965a6!2zUG9sZWfFgnljaCAzQywgMzMtMTMwIFJhZMWCw7N3!5e0!3m2!1spl!2spl!4v1611834285818!5m2!1spl!2spl" width="100%" height="480" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
        </div>  
        
        <?php /*
        <div class="col-sm-12 col-md-12 formularz-kontaktowy">
            <?php $this->load->view('partials/formularz-kontaktowy',['succes'=>$succes]); ?>
        </div> */ ?>
    </div>
</div>
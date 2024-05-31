<section class="wow fadeInDown sekcja-o-firmie">
    <div class="sekcja-o-firmie-zaw">          
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-1"></div>
                <div class="col-12 col-lg-5">                            
                    <h1 class="sekcja-o-firmie-naglowek"><?= (new CustomElementModel('2'))->getField('o firmie naglowek'); ?></h1>                                
                    <?= (new CustomElementModel('2'))->getField('o firmie paragraf'); ?>
                    <picture>
                         <img src="<?= (new CustomElementModel('2'))->getField('o firmie obrazek dolny'); ?>" alt="" title="" class="sekcja-o-firmie-ob-jeden">
                    </picture>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="sekcja-o-firmie-ob-dwa">                            
                        <picture>
                            <img src="<?= (new CustomElementModel('2'))->getField('o firmie obrazek boczny'); ?>" alt="" title="">
                        </picture>
                    </div>
                </div>                             
            </div>                                                                           
        </div>
    </div>           
</section>

<?php if(!empty($products)): ?>
<section class="wow fadeInLeft sekcja-produkty">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-12">
                <h2 class="sekcja-produkty-naglowek"><?= (new CustomElementModel('2'))->getField('produkty naglowek'); ?></h2>  
            </div>
        </div>
    </div>    
    <div class="sekcja-produkty-zaw">                          
        <div class="container">
            <div class="row">      
                <?php foreach($products as $p):
                     $this->load->view('Oferta/partials/product_view', [ 'product' => $p ]); ?>
                <?php endforeach; ?>                                                                                 
            </div>                                                                           
        </div>
    </div>           
</section> 
<?php endif; ?>

<section class="sekcja-punkty wow fadeInRight">
    <div class="sekcja-punkty-zaw" style="background:#064e37 url('<?= (new CustomElementModel('2'))->getField('punkty kontener tlo'); ?>') no-repeat top center; background-size:cover;">          
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12">                            
                    <h3 class="sekcja-punkty-naglowek"><?= (new CustomElementModel('2'))->getField('punkty naglowek'); ?></h3>
                    <div class="sekcja-punkty-zaw-info">
                        <a href="<?= (new CustomElementModel('2'))->getField('punkty pozycja 1 odnosnik'); ?>">
                            <picture>
                                <img src="<?= (new CustomElementModel('2'))->getField('punkty pozycja 1 obrazek'); ?>" alt="" title="">
                            </picture>
                            <h4 class="sekcja-punkty-zaw-info-naglowek"><?= (new CustomElementModel('2'))->getField('punkty pozycja 1 etykieta'); ?></h4>                                        
                        </a>
                        <a href="<?= (new CustomElementModel('2'))->getField('punkty pozycja 2 odnosnik'); ?>">
                            <picture>
                                <img src="<?= (new CustomElementModel('2'))->getField('punkty pozycja 2 obrazek'); ?>" alt="" title="">
                            </picture>
                            <h4 class="sekcja-punkty-zaw-info-naglowek"><?= (new CustomElementModel('2'))->getField('punkty pozycja 2 etykieta'); ?></h4>                                        
                        </a>
                        <a href="<?= (new CustomElementModel('2'))->getField('punkty pozycja 3 odnosnik'); ?>">
                            <picture>
                                <img src="<?= (new CustomElementModel('2'))->getField('punkty pozycja 3 obrazek'); ?>" alt="" title="">
                            </picture>
                            <h4 class="sekcja-punkty-zaw-info-naglowek"><?= (new CustomElementModel('2'))->getField('punkty pozycja 3 etykieta'); ?></h4>                                        
                        </a>                                                                        
                    </div> 
                </div>                                                        
            </div>                                                                                                   
        </div>
    </div>           
</section>   

<section class="sekcja-newsletter wow fadeInLeft">
    <div class="sekcja-newsletter-zaw" style="background:url('<?= (new CustomElementModel('2'))->getField('newsletter kontener tlo'); ?>') no-repeat top center; background-size:cover;">          
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6 col-xl-7 p-1-xl"></div>
                <div class="col-12 col-md-6 col-lg-6 col-xl-5 p-1-xl">                            
                    <h3 class="sekcja-newsletter-naglowek"><?= (new CustomElementModel('2'))->getField('newsletter naglowek 1'); ?><span> <?= (new CustomElementModel('2'))->getField('newsletter naglowek 2'); ?></span></h3>
                    <div class="sekcja-newsletter-form">
                        <form method="POST" id="newsletter_form">
                            <input type="text" name="mail" id="newsletter_email" placeholder="Podaj adres email">
                            <button type="submit" name="submit"><?= (new CustomElementModel('9'))->getField('zapisz sie'); ?></button>
                            <div class="kasuj"></div>
                            <div class="sekcja-newsletter-form-check">
                                <input type="checkbox" name="rodo" id="rodo">
                                <label for="rodo"><?= (new CustomElementModel('19'))->getField('fiszka newsletter'); ?></label>
                            </div>
                        </form>                                                                 
                    </div> 
                </div>                                                        
            </div>                                                                                                   
        </div>
    </div>           
</section>

<?php if(!empty($news)): ?>
    <section class="sekcja-aktualnosci wow fadeInRight">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12">
                    <h3 class="sekcja-aktualnosci-naglowek"><?= (new CustomElementModel('2'))->getField('aktualnosci naglowek'); ?></h3>  
                </div>
            </div>
        </div>    
        <div class="sekcja-aktualnosci-zaw">                          
            <div class="container">
                <div class="row">
                    <?php foreach ($news as $index =>$n): $translation = $n->getTranslation(LANG);   
                        $this->load->view('Aktualnosci/partials/single_news_view', [ 'news' => $n ]);
                    endforeach; ?>  
                    <div class="col-12 col-lg-12">
                        <div class="sekcja-aktualnosci-zaw-przycisk">
                            <a href="<?=site_url('aktualnosci'); ?>" class="przycisk"><?= (new CustomElementModel('9'))->getField('pokaz wszystkie'); ?></a>
                        </div> 
                    </div>
                </div>                                                                           
            </div>
        </div>           
    </section>
<?php endif; ?>

<aside class="sekcja-zalety wow fadeInUp">       
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-5">                          
                <picture>
                    <img src="<?= (new CustomElementModel('2'))->getField('zalety obrazek 1'); ?>" alt="" title="" class="sekcja-zalety-ob-jeden">
                </picture>
            </div>
            <div class="col-12 col-lg-7">                          
                <picture>
                    <img src="<?= (new CustomElementModel('2'))->getField('zalety obrazek 2'); ?>" alt="" title="" class="sekcja-zalety-ob-dwa">
                </picture>
            </div>
            <div class="col-12 col-lg-12">                          
                <h3 class="sekcja-zalety-przycisk">
                    <a href="<?=site_url((new CustomElementModel('2'))->getField('zalety przycisk odnosnik')); ?>" class="przycisk"><?= (new CustomElementModel('9'))->getField('dowiedz sie wiecej'); ?></a>
                </h3>
            </div>                                                                          
        </div>                                                        
    </div>                                                                                                            
</aside> 
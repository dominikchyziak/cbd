<!DOCTYPE html>
<html lang="<?php echo LANG; ?>">

<head>


  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta name="Description" content="<?php echo $this->meta_desc; ?>" />
  <meta name="Keywords" content="<?php echo $this->keywords; ?>" />
  <meta name="Author" content="Duonet sp. z o.o. (www.duonet.eu)" />
  <?php if(!empty($this->canon_link)) : ?>
  <link rel="canonical" href="<?= $this->canon_link; ?>" />
  <?php endif; ?>
  <title><?php echo $this->meta_title; ?></title>

  <?php /*<link href="https://fonts.googleapis.com/css?family=Lora:400,700%7COpen+Sans:300,400,400i,600,700,800&amp;subset=latin-ext" rel="stylesheet"> */?>
  <link href="<?php echo assets('img/favicon.ico'); ?>" type="image/x-icon" rel="icon" />
  <link rel="stylesheet" href="<?php echo assets('css/bootstrap.min.css'); ?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo assets('plugins/toastr/toastr.min.css'); ?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo assets('css/jquery.bxslider.min.css'); ?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo assets('css/hamburgers.css'); ?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo assets('plugins/lightbox2-master/dist/css/lightbox.min.css'); ?>"
    type="text/css" />
  <link rel="stylesheet" href="<?php echo assets('plugins/cookie-policy/css/cookie-policy.min.css'); ?>"
    type="text/css" />
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css">
  <link rel="stylesheet" type="text/css" href="<?= assets('plugins/slick/slick.css'); ?>" />
  <link rel="stylesheet" type="text/css" href="<?= assets('plugins/slick/slick-theme.css'); ?>" />
  <!--<link rel="stylesheet" href="<?php echo assets('css/swiper.min.css'); ?>" type="text/css" />-->
  <link rel="stylesheet" href="<?php echo assets('plugins/font-awesome-4.3.0/css/font-awesome.css'); ?>"
    type="text/css" />
  <link rel="stylesheet" type="text/css"
    href="<?= assets('plugins/EasyAutocomplete-1.3.5/easy-autocomplete.min.css'); ?>" />
  <link rel="stylesheet" type="text/css"
    href="<?= assets('plugins/EasyAutocomplete-1.3.5/easy-autocomplete.themes.min.css'); ?>" />
  <link rel="stylesheet" href="<?php echo assets('css/slick.css'); ?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo assets('css/default.css'); ?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo assets('css/animate.css'); ?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo assets('css/style.css'); ?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo assets('css/style-m.css'); ?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo site_url('css'); ?>" type="text/css" />




  <script async src="<?php echo assets('js/html5shiv.js'); ?>"></script>
  <script src="<?php echo assets('js/jquery-3.2.0.min.js'); ?>"></script>
  <script src="<?= assets('js/jquery-ui.min.js'); ?>"></script>
  <script src="https://malsup.github.io/jquery.cycle2.js"></script>
  <script src="https://malsup.github.io/jquery.cycle2.swipe.js"></script>
  <script src="https://malsup.github.io/ios6fix.js"></script>
  <script async src="<?php echo assets('plugins/toastr/toastr.min.js'); ?>"></script>
  <script src="<?= assets('js/wow.min.js'); ?>"></script>
  <script src="<?= assets('js/slick.min.js'); ?>"></script>
  <!--<script src="<?= assets('js/swiper.min.js'); ?>"></script>-->
  <script async src='https://www.google.com/recaptcha/api.js'></script>

  <!-- Google Tag Manager -->
  <script>
  (function(w, d, s, l, i) {
    w[l] = w[l] || [];
    w[l].push({
      'gtm.start': new Date().getTime(),
      event: 'gtm.js'
    });
    var f = d.getElementsByTagName(s)[0],
      j = d.createElement(s),
      dl = l != 'dataLayer' ? '&l=' + l : '';
    j.async = true;
    j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
    f.parentNode.insertBefore(j, f);
  })(window, document, 'script', 'dataLayer', 'GTM-MJ4MF9M');
  </script> <!-- End Google Tag Manager -->



<body>:
  <!-- Google Tag Manager (noscript) --> <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MJ4MF9M"
      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->


  <!-- Facebook Pixel Code -->
  <script>
  ! function(f, b, e, v, n, t, s) {
    if (f.fbq) return;
    n = f.fbq = function() {
      n.callMethod ?
        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
    };
    if (!f._fbq) f._fbq = n;
    n.push = n;
    n.loaded = !0;
    n.version = '2.0';
    n.queue = [];
    t = b.createElement(e);
    t.async = !0;
    t.src = v;
    s = b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t, s)
  }(window, document, 'script',
    'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '1959314300874177');
  fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=1959314300874177&ev=PageView&noscript=1" /></noscript>
  <!-- End Facebook Pixel Code -->

  <?php foreach ($this->styles as $css): ?>
  <link rel="stylesheet" href="<?php echo $css; ?>">
  <?php endforeach; ?>
  </head>

  <body>
    <script>
    var domReadyQueue = [];
    </script>

    <header class="naglowek-calosc no-webp">
      <div class="naglowek">
        <div class="naglowek-gora">
          <div class="naglowek-gora-tlo"></div>
          <div class="container">
            <div class="naglowek-gora-zaw">
			<div class="naglowek-jezyki-kontener">
				<a href="<?=base_url((LANG === "en") ? 'pl' : 'en'); ?>">
					<i class="<?=(LANG === "en") ? 'pol' : 'eng'; ?>"></i>
				</a>
			</div>
              <?php if(empty($this->session->userdata('login'))): ?>
              <a href="<?=site_url('rejestracja'); ?>"
                class="naglowek-gora-zaw-rej"><?= (new CustomElementModel('1'))->getField('rejestracja etykieta'); ?></a>
              <a href="<?=site_url('moje-konto'); ?>"
                class="naglowek-gora-zaw-konto"><?= (new CustomElementModel('1'))->getField('konto etykieta'); ?></a>
              <?php else: ?>
              <a href="<?=site_url('moje-konto'); ?>"
                class="naglowek-gora-zaw-konto"><?=(($this->session->userdata('login')['user']['first_name']) == "") ? $this->session->userdata('login')['user']['email'] : $this->session->userdata('login')['user']['first_name']; ?></a>
              <a href="<?=site_url('account/logout'); ?>"
                class="naglowek-gora-zaw-konto przycisk-wyloguj-sie"><?= (new CustomElementModel('1'))->getField('wyloguj sie etykieta'); ?></a>
              <?php endif; ?>
              <?php if (basket('price')>0): ?>
              <div class="naglowek-gora-zaw-cena"><a
                  href="<?= site_url('koszyk'); ?>"><span><?= (new CustomElementModel('1'))->getField('koszyk etykieta'); ?></span>
                  <?=number_format(basket('price'), 2, ',', ' ').' zł.'; ?></a></div>
              <?php else: ?>
              <div class="naglowek-gora-zaw-cena"><a
                  href="<?= site_url('koszyk'); ?>"><span><?= (new CustomElementModel('1'))->getField('koszyk etykieta'); ?></span>
                  <?= (new CustomElementModel('1'))->getField('koszyk pusty'); ?></a></div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="container">
          <div class="naglowek-zaw">
            <a href="<?= site_url((new CustomElementModel('1'))->getField('logo odnosnik')); ?>">
              <picture>
                <img class="naglowek-zaw-logo" src="<?= (new CustomElementModel('1'))->getField('logo obrazek'); ?>"
                  alt="" title="">
              </picture>
            </a>
            <div class="naglowek-zaw-slogan">
              <div class="naglowek-zaw-slogan-tekst">
                <?= (new CustomElementModel('1'))->getField('slogan tekst'); ?>
              </div>
            </div>
            <div class="naglowek-zaw-info">
              <div class="naglowek-zaw-info-socialmedia">
                <a href="<?= (new CustomElementModel('1'))->getField('facebook odnosnik'); ?>">
                  <picture>
                    <img src="<?= (new CustomElementModel('1'))->getField('facebook ikona'); ?>" alt=""
                      title="Facebook">
                  </picture>
                </a>
                <a href="<?= (new CustomElementModel('1'))->getField('instagram odnosnik'); ?>">
                  <picture>
                    <img src="<?= (new CustomElementModel('1'))->getField('instagram ikona'); ?>" alt=""
                      title="Instagram">
                  </picture>
                </a>
              </div>
              <div class="naglowek-zaw-infolinnia">
                <picture>
                  <img src="<?= (new CustomElementModel('1'))->getField('infolinia ikona'); ?>" alt="" title="">
                </picture>
                <div class="naglowek-zaw-infolinnia-tekst-tel">
                  <span><?= (new CustomElementModel('1'))->getField('infolinia etykieta'); ?></span> <a
                    href="tel:<?=preg_replace('/\D/', '', (new CustomElementModel('1'))->getField('infolinia numer')); ?>"><?= (new CustomElementModel('1'))->getField('infolinia numer'); ?></a>
                  <!--todo: poprawić formatowanie numeru telefonu -->
                </div>
                <div class="naglowek-zaw-infolinnia-tekst-mail">
                  <a
                    href="mailto:<?= (new CustomElementModel('1'))->getField('adres email'); ?>"><?= (new CustomElementModel('1'))->getField('adres email'); ?></a>
                </div>
              </div>
            </div>
            <div class="menu-strony-przycisk-mobilny"></div>
          </div>
        </div>
        <div class="naglowek-menu">
          <div class="naglowek-menu-tlo"></div>
          <div class="container">
            <div class="naglowek-menu-zaw">
              <div class="naglowek-menu-zaw-info">
                <div class="naglowek-menu-zaw-info-p">
                  <?= (new CustomElementModel('26'))->getField('przycisk etykieta'); ?>
                </div>
              </div>
              <nav class="menu-strony-nav">
                <div class="menu-strony-zam"><span></span></div>
                <?=get_menu(empty($is_home), 1, 'menu-strony'); ?>
              </nav>
            </div>
          </div>
          <ul class="naglowek-menu-zaw-info-p-pod" id="menuwysuw">
            <?php for ($i=0; $i<9; $i++):
                    if (((new CustomElementModel('26'))->getField("pozycja $i odnosnik")) != ""): ?>
            <li>
              <a href="<?= (new CustomElementModel('26'))->getField("pozycja $i odnosnik"); ?>">
                <picture>
                  <img src="<?= (new CustomElementModel('26'))->getField("pozycja $i ikona"); ?>" alt="" title="">
                </picture>
                <span><?= (new CustomElementModel('26'))->getField("pozycja $i etykieta"); ?></span>
              </a>
            </li>
            <?php endif; endfor; ?>
            <li class="przycisk-zwin" id="zwin">
              <a href="#">
                <picture>
                  <img src="<?=assets('img/ext_menu_arrow.png'); ?>" author="Freepik">
                </picture>
                <span><?= (new CustomElementModel('26'))->getField("zwin etykieta"); ?></span>
              </a>
            </li>
          </ul>
        </div>
      </div>
      <?php if (!empty($is_home)):
            $this->load->view('partials/wizerunek');
        endif; ?>
    </header>


    <main
      class="<?= empty($is_home) ? 'not-homepage' : '';?> <?= isset($is_product) && $is_product ? 'product-page': ''; ?> no-webp">
      <?php $this->load->view($this->view_file); ?>
    </main>

    <footer
      class="stopka no-webp <?= empty($is_home) ? 'not-homepage' : '';?> <?= isset($is_product) && $is_product ? 'product-page': ''; ?>">
      <div class="stopka-gora">
        <div class="stopka-gora-tlo"></div>
        <div class="container">
          <div class="row">
            <div class="col-12 col-lg-12">
              <div class="stopka-gora-zaw">
                <div class="stopka-gora-info">
                  <span><?= (new CustomElementModel('3'))->getField('infolinia etykieta'); ?> </span> <a
                    href="tel:<?=preg_replace('/\D/', '',  (new CustomElementModel('3'))->getField('infolinia numer 1')); ?>"><?=  (new CustomElementModel('3'))->getField('infolinia numer 1'); ?></a></br>
					<a
                    href="tel:<?=preg_replace('/\D/', '',  (new CustomElementModel('3'))->getField('infolinia numer 2')); ?>"><?=  (new CustomElementModel('3'))->getField('infolinia numer 2'); ?></a>
                </div>
                <div class="stopka-gora-poz">
                  <?= get_menu(!empty($is_home), 2, '', ''); ?>
                </div>
                <div class="stopka-gora-poz">
                  <?= get_menu(!empty($is_home), 3, '', ''); ?>
                </div>
                <div class="stopka-gora-poz">
                  <?= get_menu(!empty($is_home), 4, '', ''); ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="stopka-wyk">
        <div class="container">
          <div class="row">
            <div class="col-12 col-lg-12">
              Projekt i wykonanie strony www: <a href="https://www.duonet.eu" target="_blank">DUONET</a>
            </div>
          </div>
        </div>
      </div>
    </footer>

    <script type="text/javascript">
    document.getElementById("zwin").onclick = function() {
      document.getElementById("menuwysuw").style.display = "none";
    };
    </script>

    <script src="<?php echo assets('js/jquery.bxslider.min.js'); ?>"></script>
    <script src="<?php echo assets('js/chosen.jquery.js'); ?>"></script>
    <script src="<?php echo assets('js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo assets('js/placeholders.min.js'); ?>"></script>
    <script src="<?php echo assets('js/jquery.hoverIntent.js'); ?>"></script>
    <script src="<?php echo assets('js/jquery.matchHeight.js'); ?>"></script>
    <script src="<?php echo assets('plugins/lightbox2-master/dist/js/lightbox.min.js'); ?>"></script>
    <script>
    var polityka_komunikat =
      '<?= trim(nl2br((new CustomElementModel('19'))->getField('polityka komunikat')->value)); ?>';
    </script>
    <script src="<?php echo assets('plugins/cookie-policy/js/cookie-policy.js'); ?>"></script>
    <script src="<?php echo assets('plugins/EasyAutocomplete-1.3.5/jquery.easy-autocomplete.min.js'); ?>"></script>
    <script src="<?php echo assets('plugins/slick/slick.min.js'); ?>"></script>
    <script>
    <?php
         getAlerts();
        ?>
    //przekaz urli
    var base_url = '<?= site_url();?>';
    var is_product = <?= isset($is_product) && $is_product ? 'true' : 'false'; ?>
    </script>
    <script src="<?php echo assets('js/script_dev.js'); ?>"></script>
    <script src="<?php echo assets('js/script.js'); ?>"></script>
    <script src="<?php echo assets('js/modernizr.js'); ?>"></script>
    <script>
    $(document).ready(function() {

    });
    </script>
  </body>

</html>
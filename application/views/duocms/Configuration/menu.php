<div class="col-sm-12">
    <!-- Static navbar -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="<?= base_url('duocms/configuration');?>">Główne konfiguracje</a></li>
              <?php /*if(get_option('admin_shop')): ?>
              <li ><a href="<?= base_url('duocms/configuration/main_shop');?>">Konfiguracje sklepu</a></li>
              <?php endif; */
              if(ENVIRONMENT == 'development'){
                  ?>
              <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Zarządzanie konfiguracjami <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                      <li><a href="<?= base_url('duocms/configuration/manager');?>">Dostępne opcje</a></li>
                      <li><a href="<?= base_url('duocms/configuration/admin_modules');?>">Moduły admina</a></li>
              </ul>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Moduły <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="<?= base_url('duocms/configuration/module_config/slider');?>" class=" ">Wizerunki</a></li>
                  <li><a href="<?= base_url('duocms/configuration/module_config/news');?>" class=" ">Aktualności</a></li>
                  <li><a href="<?= base_url('duocms/configuration/module_config/product_category');?>" class=" ">Kategorie produktów</a></li>
                  <li><a href="<?= base_url('duocms/configuration/module_config/product');?>" class=" ">Produkty</a></li>
                  <li><a href="" class=" ">Tłumaczenia</a></li>
                </ul>
              </li>
             
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pozostałe <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="<?= base_url('duocms/configuration/languages');?>" class=" ">Języki</a></li>
                  <li><a href="<?= base_url('duocms/configuration/translations');?>" class=" ">Tłumaczenia</a></li>
                  <li><a href="<?= base_url('duocms/configuration/module_config/payu');?>" class=" ">PayU</a></li>
                  <li><a href="<?= base_url('duocms/configuration/module_config/p24');?>" class=" ">Przelewy24</a></li>
                  <li><a href="<?= base_url('duocms/configuration/module_config/paypal');?>" class=" ">PayPal</a></li>
                  <li><a href="<?= base_url('duocms/configuration/module_config/inpost');?>" class=" ">Inpost</a></li>
                  <li><a href="<?= base_url('duocms/configuration/module_config/allegro');?>" class=" ">Allegro</a></li>
                  <li><a href="<?= base_url('duocms/configuration/module_config/facebook');?>" class=" ">Facebook</a></li>
                  <li><a href="<?= base_url('duocms/configuration/module_config/ceneo');?>" class=" ">Ceneo</a></li>
                  <li><a href="<?= base_url('duocms/configuration/module_config/enadawca');?>" class=" ">Poczta Polska</a></li>
                  <li><a href="<?= base_url('duocms/configuration/module_config/sendit');?>" class=" ">SendIt</a></li>
                  <li><a href="<?= base_url('duocms/configuration/module_config/bliskapaczka');?>" class=" ">Bliskapaczka</a></li>
                </ul>
              </li>
              <?php
              }
              ?>
               <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Konfigurator galerii <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="<?= base_url('duocms/GallerySetup');?>" class=" ">Rozkład galerii</a></li>
                  <li><a href="<?= base_url('duocms/GallerySetup/css');?>" class=" ">CSS galerii</a></li>
                 
                </ul>
              </li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
</div>


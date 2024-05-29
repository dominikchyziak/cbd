<?php
if(!empty($this->menu_item)){
    $menu_item = $this->menu_item;
} else {
    $menu_item = null;
}

function active_menu($menu_item, $first = '', $second = ''){

    if(!empty($menu_item[0]) && $first > '' && $second == '' && $menu_item[0] == $first) {
        echo 'active';
    }
    if(!empty($menu_item[1]) && $second > '' && $menu_item[1] == $second) {
        echo 'active';
    }
}
?>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <ul class="nav navbar-nav">
      <?php if (get_option('admin_offer_categories')): ?>      
      <li class="<?php active_menu($menu_item, 'product');?>"><a href="<?= site_url('duocms/products');?>"><span class="glyphicon glyphicon-th-list"></span>&nbsp;&nbsp;  Lista produktów</a></li>
       <?php endif; ?>
          <?php if (get_option('admin_product_packs')): ?>    
      <li class="<?php active_menu($menu_item, 'productpacks','');?>">
          <a href="<?= site_url('duocms/ProductPacks/index');?>">
              <span class="glyphicon glyphicon-piggy-bank"></span>&nbsp;&nbsp; Paki
          </a>
      </li>
      <?php endif; ?>
      <?php if (get_option('admin_offer_categories')): ?>    
      <li class="<?php active_menu($menu_item, 'category');?> dropdown">
          <a href="" class="dropdown-toggle" type="button" data-toggle="dropdown">
              <span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;  Kategorie <span class="caret"></span></a>
          <ul class="dropdown-menu">
              <li class="<?php active_menu($menu_item, 'category','category_list');?>"><a href="<?= site_url('duocms/offer_categories');?>">Lista kategorii</a></li>
            <li class="<?php active_menu($menu_item, 'category','category_create');?>"><a href="<?= site_url('duocms/offer_categories/create');?>">Dodaj nową</a></li>
          </ul>
      </li>
      <?php endif; ?>
      <?php if (get_option('admin_modules_product_attributes')): ?>    
      <li class="<?php active_menu($menu_item, 'attributes','');?>">
          <a href="" class="dropdown-toggle"  data-toggle="dropdown"><span class="glyphicon glyphicon-wrench"></span>&nbsp;&nbsp;  Atrybuty <span class="caret"></span></a>
          <ul class="dropdown-menu">
             <li class="<?php active_menu($menu_item, 'attributes','attributes_groups');?>"><a href="<?= site_url('duocms/Products_Attributes/groups');?>">Grupy atrybutów</a></li>
            <li class="<?php active_menu($menu_item, 'attributes','attributes_list');?>"><a href="<?= site_url('duocms/Products_Attributes/attributes');?>">Lista atrybutów</a></li>
            <li class="<?php active_menu($menu_item, 'attributes','attributes_create');?>"><a href="<?= site_url('duocms/Products_Attributes/attributes_create');?>">Dodaj nowy</a></li>
          </ul>
      </li>
      <?php endif; ?>
    <?php if (get_option('admin_delivery')): ?>   
      <li class="<?php active_menu($menu_item, 'delivery','');?>">
          <a class="dropdown-toggle"  data-toggle="dropdown" href="<?= site_url('duocms/delivery');?>"><span class="glyphicon glyphicon-shopping-cart"></span>&nbsp;&nbsp; Dostawa <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li class="<?php active_menu($menu_item, 'delivery','delivery_list');?>"><a href="<?= site_url('duocms/delivery');?>">Opcje dostawy</a></li>
            <li class="<?php active_menu($menu_item, 'delivery','delivery_create');?>"><a href="<?= site_url('duocms/delivery/create');?>">Dodaj nową</a></li>
            <!--<li class="<?php active_menu($menu_item, 'delivery','delivery_inpost');?>"><a href="<?= site_url('duocms/delivery/inpost_list');?>">Przesyłki inpost</a></li>-->
          </ul>
      </li>
      <?php endif; ?>
       <?php if (get_option('admin_discount_codes')): ?> 
      <li class="<?php active_menu($menu_item, 'codes','');?>">
          <a class="dropdown-toggle"  data-toggle="dropdown" href="<?= site_url('duocms/codes');?>"><span class="glyphicon glyphicon-gift"></span>&nbsp;&nbsp; Kody rabatowe <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li class="<?php active_menu($menu_item, 'codes','codes_list');?>"><a href="<?= site_url('duocms/codes');?>">Lista kodów</a></li>
           <!-- <li class="<?php active_menu($menu_item, 'codes','codes_create');?>"><a href="<?= site_url('duocms/codes/create');?>">Dodaj nową</a></li>-->
          </ul>
      </li>
      <?php endif; ?>
      <?php if (get_option('admin_orders')) { ?>
          <li class="<?php echo $activePage == 'orders' ? 'active-link' : null; ?>">
              <a href="<?php echo site_url('duocms/orders'); ?>">
                  <i class="fa fa-shopping-cart" aria-hidden="true"></i> Zamówienia
              </a>
          </li>
      <?php }  ?>  
           <?php if (get_option('admin_modules_allegro_active')) { ?>
     <li class="<?php active_menu($menu_item, 'allegro');?> dropdown">
          <a href="" class="dropdown-toggle" type="button" data-toggle="dropdown">
              <span class="glyphicon glyphicon-font"></span>&nbsp;&nbsp;  Allegro <span class="caret"></span></a>
          <ul class="dropdown-menu">
              <li class="<?php active_menu($menu_item, 'category','allegro_list');?>"><a href="<?= site_url('duocms/allegro/allegro_list');?>">Lista aukcji</a></li>
            <li class="<?php active_menu($menu_item, 'category','allegro_delivery');?>"><a href="<?= site_url('duocms/allegro/delivery_options');?>">Opcje dostawy</a></li>
          </ul>
      </li>
        <?php }  ?>  
            <?php if (get_option('admin_currencies')) { ?>
          <li class="<?php echo $activePage == 'currency' ? 'active-link' : null; ?>">
              <a href="<?php echo site_url('duocms/currency'); ?>">
                  <i class="fa fa-dollar" aria-hidden="true"></i> Waluty
              </a>
          </li>
      <?php }  ?>  
 <!--           <li class="<?php echo $activePage == 'clients' ? 'active-link' : null; ?>">
              <a class="dropdown-toggle"  data-toggle="dropdown" href="">
                  <i class="fa fa-user" aria-hidden="true"></i> Pozostałe <span class="caret"></span>
              </a>
         <ul class="dropdown-menu">
            <li class="<?php active_menu($menu_item, 'other','clients');?>"><a href="<?php echo site_url('duocms/orders/clients'); ?>">Klienci</a></li>
            <li class="<?php active_menu($menu_item, 'other','allegro');?>"><a href="<?= site_url('duocms/allegro/allegro_list');?>">Allegro</a></li>
          </ul>
          </li>-->
          
    </ul>
  </div>
</nav>

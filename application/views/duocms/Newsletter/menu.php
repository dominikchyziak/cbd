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
      <li class="<?php active_menu($menu_item, 'mailings');?>"><a href="<?= site_url('duocms/Newsletter/index');?>"><span class="glyphicon glyphicon-envelope"></span>&nbsp;&nbsp;  Lista mailingów</a></li>
      <li class="<?php active_menu($menu_item, 'addresses');?>"><a href="<?= site_url('duocms/Newsletter/emails');?>"><span class="glyphicon glyphicon-th-list"></span>&nbsp;&nbsp;  Lista Adresów</a></li>
      <li class="<?php active_menu($menu_item, 'config');?>"><a href="<?= site_url('duocms/Newsletter/config');?>"><span class="glyphicon glyphicon-wrench"></span>&nbsp;&nbsp;  Konfiguracja</a></li>
      <li class=""><a href="<?= site_url('duocms/bilans/newsletter_emails'); ?>"><span class="glyphicon glyphicon-copy"></span>&nbsp;&nbsp; Lista adresów w xml</a></li>
    </ul>
  </div>
</nav>
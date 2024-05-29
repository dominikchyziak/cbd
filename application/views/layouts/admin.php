<!doctype html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="<?php echo $this->meta_title; ?>">
        <meta name="author" content="Duonet sp. z o.o. (www.duonet.eu)">
        <title><?php echo $this->meta_title; ?></title>

        <?php foreach ($this->styles as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach ?>
            <script    type="text/javascript" src="<?php echo assets('js/jquery-3.2.0.min.js'); ?>"></script>
            <link href="<?php echo assets('img/favicon.ico'); ?>" type="image/x-icon" rel="icon" />
        <link href="https://fonts.googleapis.com/css?family=Merriweather:300,300i,400,400i,700,700i,900,900i" rel="stylesheet">
        <script>
            var domReadyQueue = [];
        </script>
    </head>
    <body>
        <div id="wrapper">
            <div class="navbar navbar-inverse navbar-fixed-top">
                <div class="adjust-nav">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="<?= site_url('duocms'); ?>">
                            <img src="<?php echo assets('duocms/img/logo_duonet.png'); ?>" alt="Duonet Agencja Interaktywna" title="Duonet Agencja Interaktywna" />
                        </a>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <h1><?= ADMIN_COMPANY_NAME ?></h1>
                    </div>
                    <span class="logout-spn text-right" >
                        <small>Zalogowany jako: <?= Admin_Model::loggedin()->name; ?><br></small>
                        <a href="<?php echo site_url('duocms/logout'); ?>" style="color:#fff;">
                            Wyloguj <i class="fa fa-sign-out" aria-hidden="true"></i>
                        </a>  

                    </span>
                </div>
            </div>          
            <?php $this->load->view('partials/admin/left-col'); ?>
            <!-- /. NAV SIDE  -->
            <div id="page-wrapper" >
                <div id="page-inner">
                    <div class="row">
                        <div class="col-md-12">
                            <?php $this->load->view('partials/admin/errors'); ?>
                            <?php $this->load->view($this->view_file); ?>  
                        </div>
                    </div>              
                    <!-- /. ROW  -->
                    <hr />
                    <!-- /. ROW  -->           
                </div>
                <!-- /. PAGE INNER  -->
            </div>
            <!-- /. PAGE WRAPPER  -->
        </div>
        <div class="footer">
            <div class="row">
                <div class="col-lg-12" >
                    &copy;  2018 duonet.eu 
                </div>
            </div>
        </div>
        
        <?php foreach ($this->scripts as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach ?>
        <script>
            function openKCFinderImage(field, type) {
                var self = $(this);

                window.KCFinder = {
                    callBack: function (url) {
                        field.value = url;
                        window.KCFinder = null;
                    }
                };

                window.open('<?php echo assets('plugins/ckeditor/kcfinder/browse.php?type='); ?>' + type, 'kcfinder_textbox', 'status=0, toolbar=0, location=0, menubar=0, directories=0, resizable=1, scrollbars=0, width=800, height=600');
            }
        $(function () {
            while (domReadyQueue.length) {
                domReadyQueue.shift()($);
            }
        });
        
        <?php
        getAlerts();
        ?>
        </script>
    </body>
</html>
<!doctype html>
<html lang="<?php echo LANG; ?>">
	<head>
		<meta charset="UTF-8">
		<title>DuoCMS v4.2</title>
                <link rel="stylesheet" href="<?php echo assets('css/bootstrap.min.css'); ?>" />
		<link rel="stylesheet" href="<?php echo assets('duocms/css/style.css'); ?>" />
                <style>
                    body, html {
                        height: 100%;
                        background-repeat: no-repeat;
                        background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));
                    }

                    .card-container.card {
                        max-width: 382px;
                        padding: 40px 40px;
                        text-align: center;
                    }

                    .btn {
                        font-weight: 700;
                        height: 36px;
                        -moz-user-select: none;
                        -webkit-user-select: none;
                        user-select: none;
                        cursor: default;
                    }

                    /*
                     * Card component
                     */
                    .card {
                        background-color: #000;
                        color: white;
                        /* just in case there no content*/
                        padding: 20px 25px 30px;
                        margin: 0 auto 25px;
                        margin-top: 50px;
                        /* shadows and rounded borders */
                        -moz-border-radius: 10px;
                        -webkit-border-radius: 10px;
                        border-radius: 10px;
                        -moz-box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3);
                        -webkit-box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3);
                        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3);
                    }
                    /*
                     * Form styles
                     */

                    .form-signin #inputEmail,
                    .form-signin #inputPassword {
                        direction: ltr;
                        height: 44px;
                        font-size: 16px;
                    }

                    .form-signin input[type=email],
                    .form-signin input[type=password],
                    .form-signin input[type=text],
                    .form-signin button {
                        width: 100%;
                        display: block;
                        margin-bottom: 10px;
                        z-index: 1;
                        position: relative;
                        -moz-box-sizing: border-box;
                        -webkit-box-sizing: border-box;
                        box-sizing: border-box;
                    }

                    .form-signin .form-control:focus {
                        border-color: rgb(104, 145, 162);
                        outline: 0;
                        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
                        box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
                    }

                    .btn.btn-signin {
                        /*background-color: #4d90fe; */
                        background-color: rgb(234, 60, 147);
                        /* background-color: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));*/
                        padding: 0px;
                        font-weight: 700;
                        font-size: 14px;
                        height: 36px;
                        -moz-border-radius: 3px;
                        -webkit-border-radius: 3px;
                        border-radius: 3px;
                        border: none;
                        -o-transition: all 0.218s;
                        -moz-transition: all 0.218s;
                        -webkit-transition: all 0.218s;
                        transition: all 0.218s;
                    }

                    .btn.btn-signin:hover,
                    .btn.btn-signin:active,
                    .btn.btn-signin:focus {
                        background-color: rgb(234, 100, 167);
                    }

                    
                    
                </style>
                <script src='https://www.google.com/recaptcha/api.js'></script>
	</head>
        <body class="login_body">
            <div class="container">
                <div class="card card-container">
                    <img src="<?= assets('duocms/img/logo_duonet.png');?>" />
                    <p>
                        <br>
                    </p>
                    <p id="profile-name" class="profile-name-card"></p>
                    <?php if ($errors = validation_errors()){ ?>
                        <div id="errors" class="alert alert-danger"><?php echo $errors; ?></div>
                    <?php } ?>
                    <?php echo form_open('duocms/login', array("class" => 'form-signin')); ?>
                        <div class="form-group">
                        <?php
                        echo form_input(array(
                            'name' => 'email',
                            'class' => 'form-control',
                            'placeholder' => 'Login',
                            'required' => TRUE,
                            'autofocus' => TRUE));
                        ?>
                        </div>
                        <div class="form-group">
                            <?php
                            echo form_password(array(
                                'name' => 'haslo',
                                'class' => 'form-control',
                                'placeholder' => 'Hasło',
                                'required' => TRUE
                            ));
                            ?>
                        </div>
                        <div class="form-group">
                            <div class="g-recaptcha" data-sitekey="<?= get_option('recaptcha_site_key'); ?>"></div>
                        </div>
                        <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Zaloguj</button>
                    <?php echo form_close(); ?><!-- /form -->
 
                    <div class="footer">DuoCMS v4.2 powered by <a href="https://duonet.eu">Duonet</a></div>
                </div><!-- /card-container -->
            </div><!-- /container -->
	</body>
</html>

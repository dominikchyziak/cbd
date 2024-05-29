<?php

    $payu_config = array(
        'sandbox' => get_option('sandbox'),
        'pos_id' => get_option('merchant_pos_id'),
        'sign_key' => get_option('payu_signature_key'),
        'payu_client_id' => get_option('payu_client_id'),
        'payu_client_secret' => get_option('payu_client_secret')
    );

    //set Sandbox Environment
    OpenPayU_Configuration::setEnvironment($payu_config['sandbox']);

    //set POS ID and Second MD5 Key (from merchant admin panel)
    OpenPayU_Configuration::setMerchantPosId($payu_config['pos_id']);
    OpenPayU_Configuration::setSignatureKey($payu_config['sign_key']);
    
    //set Oauth Client Id and Oauth Client Secret (from merchant admin panel)
    OpenPayU_Configuration::setOauthClientId($payu_config['payu_client_id']);
    OpenPayU_Configuration::setOauthClientSecret($payu_config['payu_client_secret']); 

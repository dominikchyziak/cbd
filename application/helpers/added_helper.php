<?php
/**
 * 
 * @param array $args pobiera tablice [ typ_alertu , wiadomosc , funkcja_js(opcjonalnie), argumenty funkcji]
 */
function ajax_res($args){
    echo json_encode($args);
    die();
}

function setAlert($type = 'success', $message = ''){
    $ci = &get_instance();

    if(empty($ci->sesssion->alerts)){
        $alerts = array();
    } else {
        $alerts = $ci->sesssion->alerts;
    }
    $alerts[] = array($type,$message);
    $ci->session->set_userdata('alerts', $alerts);
    
}

function getAlerts(){
    $ci = &get_instance();
    if(empty($ci->session->alerts)){
        $alerts = array();
    } else {
        $alerts = $ci->session->alerts;
        foreach($alerts as $alert){
            ?>
            toastr.<?= $alert[0];?>('<?= $alert[1];?>');
            <?php
        }
    }
    $alerts = array();
    $ci->session->set_userdata('alerts', $alerts);
}
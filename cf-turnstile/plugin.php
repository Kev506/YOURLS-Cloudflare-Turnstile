<?php
/*
Plugin Name: Cloudflare Turnstile for YOURLS Admin
Plugin URI: https://github.com/Kev506/YOURLS-Cloudflare-Turnstile
Description: Adds Cloudflare Turnstile to the YOURLS Admin login.
Version: 2.0
Author: Kevin Andrews
Author URI: https://github.com/Kev506/
*/

if( !defined( 'YOURLS_ABSPATH' ) ) die();

// Cloudflare Turnstile script to the head section of the HTML file
yourls_add_action('html_head', 'cf_turnstile_html_head');
function cf_turnstile_html_head() {
    echo("<script src='https://challenges.cloudflare.com/turnstile/v0/api.js' defer></script>");
}

// Cloudflare Turnstile widget to the YOURLS admin login form
yourls_add_action('login_form_bottom', 'cf_turnstile_login_form');
function cf_turnstile_login_form() {
	$pubkey = CLOUDFLARE_SITE_KEY;
    echo("<div id='turnstyle' class='cf-turnstile' data-sitekey='" . $pubkey . "'></div>");
}

yourls_add_action( 'pre_login_username_password', 'captcha_validateTurnstile' );
function captcha_validateTurnstile() {
    
    if(isset($_POST['cf-turnstile-response']) && !empty($_POST['cf-turnstile-response'])) {
        $secret = CLOUDFLARE_SEC_KEY;
        $remote_ip = $_SERVER['REMOTE_ADDR'];
        $recaptcha = $_POST['cf-turnstile-response'];
       
        $url_path = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
        $data = array('secret' => $secret, 'response' => $recaptcha, 'remoteip' => $remote_ip);
	
        $options = array(
	    'http' => array(
	    'method' => 'POST',
	    'content' => http_build_query($data))
	    );
	
       $stream = stream_context_create($options);
	
	   $result = file_get_contents(
	   $url_path, false, $stream);
	
	   $response =  $result;
   
       $responseData = json_decode($response,true);
             
       if(intval($responseData["success"])) {
            return true;
        } else {
           responseError();
           yourls_do_action( 'login_failed' );
           return false;
        }       
    } else {
        captchaError();
        yourls_do_action( 'login_failed' );
        return false;
    }

}

function captchaError() {
    yourls_login_screen( $error_msg = 'Captcha verification failed, please try again.' );
}

function responseError() {
    yourls_login_screen( $error_msg = 'Verification failed, please try again.' );
}

?>

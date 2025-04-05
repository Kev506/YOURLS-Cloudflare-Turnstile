# YOURLS-Cloudflare-Turnstile
Cloudflare Turnstile for YOURLS admin login.

# Installation
* Register on [Cloudflare](https://dash.cloudflare.com/?to=/:account/turnstile) to use the Turnstile and obtain your site and secret key.
* Download the cf-turnstile folder complete with plugin.php
* Add the folder to the users/plugin directory.
* In the users directory in config.php add the following.
  *  define('CLOUDFLARE_SEC_KEY', 'your_cloudflare_secret_key')
  *  define('CLOUDFLARE_SITE_KEY', 'your_cloudflare_site_key');
* Go to the Plugins administration page and activate the plugin.
* Tested successfully on YOURLS latest v1.10.0.

That's it, now logout refresh the page and the cloudflare turnstile is enabled.

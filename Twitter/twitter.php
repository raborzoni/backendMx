<?php
header("Access-Control-Allow-Origin: *");

require __DIR__ . '/vendor/autoload.php';
// Cliente Id = d3YyR085YWJBVkJGUlpSd05lYUk6MTpjaQ
// Secret = iv-REKq23i61-6tyYDOWBai43bs9r0C0-5XFu8F5uR6DoXZAMG
// Client secret = iv-REKq23i61-6tyYDOWBai43bs9r0C0-5XFu8F5uR6DoXZAMG

define ("CONSUMER_KEY", "d3YyR085YWJBVkJGUlpSd05lYUk6MTpjaQ");
define ("CONSUMER_SECRET", "iv-REKq23i61-6tyYDOWBai43bs9r0C0-5XFu8F5uR6DoXZAMG");
define ("CALLBACK", "http://localhost:8000/callback");

use Abraham\TwitterOAuth\TwitterOAuth;

$tt = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

$request_token = $tt->oauth(
    'oauth/request_token', [
        'oauth_callback' => CALLBACK
    ]
);

var_dump($tt);
//$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token, $access_token_secret);
//$content = $connection->get("account/verify_credentials");

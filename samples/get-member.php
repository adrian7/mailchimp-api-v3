<?php
/**
 * MailChimp API v3 Client - Get Member example
 * @author adrian7
 * @version 1.0
 */

require_once ('../vendor/autoload.php');

define('MC_API_KEY', 'YOUR_MC_API_KEY_HERE');

$listId = 'YOUR_MC_LIST_ID_HERE';
$email  = 'you@example.com';

$mc       = new \MailChimp\MailChimpAPI(MC_API_KEY);
$memberId = md5( $email );

$result = $mc->get("/lists/{$listId}/members/{$memberId}");

var_dump($result);
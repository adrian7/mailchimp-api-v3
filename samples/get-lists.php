<?php
/**
 * mailchimp-api-v3 - get lists sample
 * @author adrian7 (adrian@studentmoneysaver.co.uk)
 * @version 1.0
 */

require_once ('../vendor/autoload.php');

define('MC_API_KEY', 'ea400f0d078e0ddddf638e95e69f9b0f-us10');

$mc     = new \MailChimp\MailChimpAPI(MC_API_KEY);
$result = $mc->get('lists', [
    'fields' => 'lists.id,lists.name,lists.stats.member_count',
    'count'  => 10
]);

var_dump($result);
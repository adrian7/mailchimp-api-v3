<?php
/**
 * MailChimp API v3 Client - get lists sample
 * @author adrian7
 * @version 1.0
 */

require_once ('../vendor/autoload.php');

define('MC_API_KEY', 'YOUR_MC_API_KEY_HERE');

$mc     = new \MailChimp\MailChimpAPI(MC_API_KEY);
$result = $mc->get('lists', [
    'fields' => 'lists.id,lists.name,lists.stats.member_count',
    'count'  => 10
]);

var_dump($result);
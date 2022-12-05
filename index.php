<?php


if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
include 'madeline.php';

$settings['peer']['full_fetch'] = TRUE;
$settings['peer']['cache_all_peers_on_startup'] = TRUE;

$MadelineProto = new \danog\MadelineProto\API('session.madeline', $settings);
$MadelineProto->settings = $settings;
#$MadelineProto->start();

include "funcs.php";

?>
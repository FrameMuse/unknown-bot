<?
/*
if ($_GET['key'] != "jopa") die("Invalid jopa");

$user = $_GET['user'];
$msg = $_GET['msg'];
*/
#if (!file_exists('madeline.php')) {
#    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
#}
#include 'madeline.php';

$MadelineProto = new \danog\MadelineProto\API('bot.madeline');
$MadelineProto->start();

    try {
        $MadelineProto->messages->sendMessage(['peer' => $user, 'message' => $msg, 'parse_mode' => 'HTML', ]);
    } catch (\danog\MadelineProto\RPCErrorException $e) {
        $MadelineProto->messages->sendMessage(['peer' => "565324826", 'message' => $e->getCode().': '.$e->getMessage().PHP_EOL.$e->getTraceAsString()]);
    }

?>
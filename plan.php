<?php

date_default_timezone_set('Europe/Moscow');

require '/bot/madeline.phar';
require '/bot/funcs.php'; 

$MP_BOT = new \danog\MadelineProto\API('/bot/bot.madeline');
#$MP_USER = new \danog\MadelineProto\API('/bot/session.madeline');

$hn = "jora13y6.beget.tech";
$un = "jora13y6_eup";
$pw = "3EuvV*nS";
$bn = "jora13y6_eup";

$conn = new mysqli($hn, $un, $pw, $bn);

$channel = "https://t.me/chanelvaleri";

#$info = $MP_USER->channels->getAdminedPublicChannels();

#print_r($info);

$date['now'] = NULL;
$date['that'] = NULL;

$i = 0;

while (true) try {
    
    $date['that'] = date("H-i");
    
    echo "realizing...\r\n\r\n";
    
    if ($conn == NULL) $conn = new mysqli($hn, $un, $pw, $bn);
    
    $msgs = $conn->query("SELECT * FROM pl_msgs WHERE date = '{$date['that']}'");
    if ($msgs->num_rows != 0 and $date['now'] != $date['that']) while ($msg = $msgs->fetch_assoc()) {
        
        if (!empty($msg['button'])) {
            $data = explode('[JOPA]', $msg['button']);
            
            for ($i = 1; $i < count($data); $i = $i+2) {
                $buttons[$i] = [
                    '_' => 'keyboardButtonUrl',
                    'text' => $data[$i-1],
                    'url' => $data[$i],
                ];
            }
            
            print_r($data);
        } else $buttons = NULL;
        
        send_message($MP_BOT, $channel, $msg['message'], "HTML", $buttons);
        echo "Message has been sent\r\n\r\n";
    } else echo "It's been nothing\r\n\r\n";
    
} catch(Exception $e) {
    
    print $e;
    
} finally {
    
    try {
        if ($i % 5 == 0) {
            $conn->close();
            $conn = NULL;
        }
    } catch(Exception $e) {
    
        print $e;
    
    } finally {
        echo $i; $i++."\r\n\r\n";
        print_r($date);

        $date['now'] = date("H-i");

        sleep(10);
    }
    
}

?>
<?php

ini_set('memory_limit', '80M');

require '/bot/madeline.phar';
require '/bot/funcs.php'; 

$MP_BOT = new \danog\MadelineProto\API('/bot/bot.madeline');
$MP_USER = new \danog\MadelineProto\API('/bot/session.madeline');

$hn = "jora13y6.beget.tech";
$un = "jora13y6_eup";
$pw = "3EuvV*nS";
$bn = "jora13y6_eup";

$conn = new mysqli($hn, $un, $pw, $bn);

$msg['user']['successful'] = "Здравствуйте,<br>Рады видеть Вас в нашем чате по спорту или киберспорту.<br>Вот тут найдете обучение по ставкам и сайты, где нужно ставить на спорт и киберспорт https://tgdo.me/eupmoneyschool<br>С уважением EasyUpMoney!";
$msg['bot']['successful'] = "Здравствуйте,<br>Вы были успешно добавлены в чат!<br>Список команд /start<br><br>Если возникнут вопросы обращайтесь к боту.<br>С уважением EasyUpMoney!";

$msg['bot']['unsuccessful'] = "Ошибка добавления: <br><br>";
$msg['user']['unsuccessful'] = "Ошибка добавления: <br><br>";

$i = 0;

while (true) try {
    
    print "Processing... ";
    
    if ($conn == NULL) $conn = new mysqli($hn, $un, $pw, $bn);
    
    $query = $conn->query("SELECT * FROM for_add WHERE status LIKE '60%'");
    
    if ($query->num_rows != 0) while ($row = $query->fetch_assoc()) {
        
        switch ($row['status']) {
            case 600: 
                
                $id = $row['id'];
                $user_id = $row['user_id'];
                $phone = $row['phone'];
                $date = calendar($row['ends']);
                $chnl = "channel#".$row['channel'];

                if (!empty($user_id)) {
                    $bot = TRUE;
                    foreach ($MP_BOT->API->chats as $bot_api_id => $chat);
                    print $user = $user_id;
                } else {
                    $bot = FALSE;
                    print $user = import_contact($MP_USER, $id, $phone, $date['day'], $date['month']);
                    $conn->query("UPDATE for_add SET user_id = '$user' WHERE id = $id");
                }

                print $iu = invite_user($MP_USER, $chnl, $user);
                
                if (empty($iu)) {
                    print "User#$user has Successfull been added";
                    $conn->query("UPDATE for_add SET status = '700' WHERE id = $id");
                    if ($bot == TRUE) {
                        send_message($MP_BOT, $user, $msg['bot']['successful'], "HTML");
                    } else {
                        send_message($MP_USER, $user, $msg['user']['successful'], "HTML");
                    }
                } else {
                    print "User#$user hasn't been added";
                    $conn->query("UPDATE for_add SET status = '610' WHERE id = $id");
                    if ($bot == TRUE) {
                        send_message($MP_BOT, 565324826, $msg['user']['unsuccessful'].$iu."user#".$id, "HTML");
                        send_message($MP_BOT, $user, $msg['bot']['unsuccessful'].$iu, "HTML");
                    } else {
                        send_message($MP_BOT, 565324826, $msg['user']['unsuccessful'].$iu."user#".$id, "HTML");
                        send_message($MP_USER, $user, $msg['user']['unsuccessful'].$iu, "HTML");
                    }
                }
                
            break;
            
            case 610: 
                
                
                
            break;
                
        }
        
    } else print "Found nothing\r\n\r\n";
    
    
} catch (Exception $e) {
    
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
        
        print "Cycle ".$i.":\r\n\r\n"; $i++;

        sleep(10);
        
    }
    
}
?>
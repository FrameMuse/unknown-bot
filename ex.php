<?

//Подключение к БД
$hn = "jora13y6.beget.tech";
$un = "jora13y6_eup";
$pw = "3EuvV*nS";
$bn = $un;

$i = 0;
$mex = 0;
$users = array();
$choice = array();


require '/bot/madeline.phar';
require '/bot/funcs.php'; //functions to easy call commands
$MP_BOT = new \danog\MadelineProto\API('bot.madeline');
$MP_USER = new \danog\MadelineProto\API('/bot/session.madeline');
echo "start checking up...\n";

while (true) {
    
    try {
    
        $conn = new mysqli($hn, $un, $pw, $bn);

        $result = $conn->query("SELECT * FROM for_add WHERE status = '700'"); 

        while ( $row = $result->fetch_assoc() ) {
            
            $msg = NULL;
            $date1 = new DateTime(date("Y-m-d"));
            $date2 = new DateTime($row['ends']);
            $chnl = "channel#".$row['channel'];
            
            if ($date1 >= $date2) {
                $conn->query("UPDATE payments SET test = '5' WHERE OrderId = '{$row['order_id']}'");
                
                echo $date1->format('Y-m-d');
                echo $date2->format('Y-m-d');
                
                if ($row['user_id'] != 0) {
                    echo "It's been run by bot\n";
                    if ($row['user_id'] == 593880447) echo "Can't kick this user!"; else $user = $row['user_id'];
                } else {
                    echo "It's been run by website\n";
                    if ($row['phone'] == 79523092011) echo "Can't kick this user!"; else {
                        $user = import_contact($MP_USER, $row['id'], $row['phone']);
                    }
                }
                
                $kas = kick_user($MP_USER, $chnl, $user);
                    print_r($kas);
                
                if (empty($kas)) {
                    echo "User has been removed\n";
                    $msg = "Привет,<br>Твоя подписка закончилась, нам придётся тебя исклчить из чата <i>$choice</i>!<br>Список команд /start<br><br>Если возникнут вопросы обращайся к боту.<br>С уважением EasyUpMoney!";
                    send_message($MP_BOT, $user, $msg, "HTML");
                    $conn->query("UPDATE for_add SET status = '800' WHERE id = '{$row['id']}'");
                    $mex++;
                } else {
                    $conn->query("UPDATE for_add SET status = '710' WHERE id = '{$row['id']}'");
                }
                
            }
            $hex = $result->num_rows;
        }
    } catch (Exception $e) {
        
        print $e;
        
    } finally {
        echo "Cycle $i\r\n\r\n";
        echo "Occurrences:$hex\r\n\r\n";
        echo "Deleted people:$mex\r\n\r\n";
        $result->close();
        $conn->close();
        $i++;
        sleep(300);
    }
    
}

?>
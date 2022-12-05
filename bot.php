<?

require '/bot/madeline.php';
require '/bot/funcs.php'; 


$MP = new \danog\MadelineProto\API('/bot/bot.madeline');
#$MP_USER = new \danog\MadelineProto\API('/bot/session.madeline');

$hn = "localhost";
$un = "root";
$pw = "666";
$bn = "db";

$conn = new mysqli($hn, $un, $pw, $bn);

$bot = NULL;
$offset = NULL;
$msg = NULL;
$i = 0;
while (true) {
    
    if ($conn == NULL) $conn = new mysqli($hn, $un, $pw, $bn);
    
    $updates = $MP->get_updates(['offset' => $offset]);
    
    \danog\MadelineProto\Logger::log($updates);
    
    foreach ($updates as $update) {
        $update_id = $update['update_id'];
        $offset = $update_id + 1;
        switch ($update['update']['_']) {
            case 'updateNewMessage':
            #case 'updateNewChannelMessage':
                try {
            if (isset($update['update']['message']['out']) && $update['update']['message']['out']) {
                continue;
            }
                    
                    $user = $update["update"]["message"]["from_id"];
                    $message = $update["update"]["message"]["message"];
                    $bot = wu($conn, $user, "check");
            
            if ($bot != NULL) {
                    $msg = "Неверная команда!";
                
                if ($message == "/cancel") {
                    wu($conn, $user, "end");
                    $msg = "Действие {$bot['command']} было отмененно! <br><br>   /start - Чтобы показать это окно<br><br>   /buy - Купить подписку<br><br>   /info - Получить информацию<br><br>   /check - Проверить подписки<br><br>   /faq - Вопросы и Ответы<br><br>   /cancel - Отменить текущие действие";
                } else switch ($bot['command']) {
                    case "/faq":
                        $msg = "Чтобы отменить действие /faq - /cancel<br><br>";
                        switch ($message) {
                            case "/faq": $msg = "Вопросы и Ответы:<br><b>1.</b>Чем Вы занимаетесь и кто такие?<br><b>2.</b>На какое время лучше покупать подписку?<br><b>3.</b>Сколько денег нужно для ставок?<br><b>4.</b>На каких сайтах Вы делаете ставки?<br><b>5.</b>Если мне нет 18+ лет, то я могу делать ставки?<br><b>6.</b>На что лучше покупать подписку?<br><b>7.</b>Сколько прогнозов в день вы даете?<br><b>8.</b>Если я не окуплю подписку, что будет?<br><b>9.</b>Я новичок и не умею ставить, что делать?<br><b>10.</b>Как купить подписку и начать зарабатывать?<br><b>11.</b>У Вас есть бесплатный чат?<br><b>12.</b>Какие у Вас контакты?<br><b>13.</b>Куда я могу задавать свой вопрос?<br>"; break;
                            // <----- Можешь трогать
                                case "1": $msg .= "Мы занимаемся прогнозами на спорт и киберспорт, а именно помогаем зарабатывать деньги нашим клиентам, у нас работают лучшие аналитики, которые дают самые проходимые прогнозы"; break;
                                case "2": $msg .= "На ставках невозможно подняться за 1 ставку и чтобы поднимать неплохие деньги нужно ставить как минимум неделю, ну а если Вы хотите серьезно зарабатывать на ставках и большие деньги, то конечно же на месяц"; break;
                                case "3": $msg .= "Мы рекомендуем нашим клиентам покупать как минимум недельную подписку и оставлять на ставки 300-500 рублей, затем Вы ставите по нашим прогнозам из чатов по 5-10% от вашего банка и поднимаете себе деньги, уже за неделю получиться очень крутой результат"; break;
                                case "4": $msg .= "На спорт мы ставим через париматч - https://goo.gl/JHqSG8, на киберспорт через бетсксго - https://betscsgo.net"; break;
                                case "5": $msg .= "По ставкам на киберспорт проблем вообще никаких нет, но для ставок на спор нужно указывать паспорт, к примеру ютубер Сней указал паспорт мамы и ставит каждый день, все хорошо"; break;
                                case "6": $msg .= "Если честно без разницы, иногда матчи лучше по спорту, иногда по киберспорту, ну а вообще можете купить сразу два чата"; break;
                                case "7": $msg .= "По спорту мы даем от 5 до 20 прогнозов в день, а по киберспорту примерно так же"; break;
                                case "8": $msg .= "Мы продлим Вам подписку если Вы отправите скриншоты всех Ваших ставок, это является нашей фишкой"; break;
                                case "9": $msg .= "Если Вы покупаете у нас подписку и не понимаете, как ставить по прогнозам, то мы бесплатно обучим Вас за пару минут, если Вы напишите к нам в группу в VK, все очень просто"; break;
                                case "10": $msg .= "Чтобы купить подписку на прогнозы, напишите команду /start и выполняйте дальнейшие действия"; break;
                                case "11": $msg .= "Да, у нас есть бесплатный чат, где Вы бесплатно можете заработать деньги - https://tgdo.me/eupmoney"; break;
                                case "12": $msg .= "Наши контакты: Группа VK - https://vk.com/easyupmoney, Сайт - https://www.EasyUpMoney.ru"; break;
                                case "13": $msg .= "Онлайн поддержка находится в группе VK, задавайте свои вопросы - https://vk.com/easyupmoney"; break;
                                // ----->
                            default: $msg = "Введите число соответствующие вопросу"; break;
                        }
                    break;
                    case "/check": 
                        if ($message == "/fix") {
                            exec('curl --data "oid='.$bot['data'].'&sign=GrwraFHs6db1Pp4pi8b8" https://easyupmoney.ru/hd.php > /dev/null 2>/dev/null &');
                            send_message($MP, $user, "В течении одной минуты вы будете добавлены в чат, если же нет то напишите нам об этом!", "HTML");
                            wu($conn, $user, "end");
                            $msg = NULL;
                        }
                    break;
                }
                    $step = NULL;
                    $step['step'] = NULL;
                    $step['data'] = NULL;
                
                    if ($msg != NULL) send_message($MP, $user, $msg, "HTML");
            } else {
                switch ($message) {
                    case "/start": 
                        $Chat = $MP->get_info($user);
                    
                        $msg = "Привет <b>{$Chat["User"]["first_name"]}</b>, Это <b>Бот EasyUpMoney</b>,<br><br>Ты можешь использовать эти команды: <br><br>   /start - Чтобы показать это окно<br><br>   /buy - Купить подписку<br><br>   /info - Получить информацию<br><br>   /check - Проверить подписки<br><br>   /faq - Вопросы и Ответы<br><br>   /cancel - Отменить текущие действие";
                    break;
                        
                    case "/info": 
                        
                        $Chat = $MP->get_info($user);
                        $msg = "Привет <b>{$Chat["User"]["first_name"]}</b>,<br>";
                        $sub_count = 0;
                        $hn1 = "jora13y6.beget.tech";
                        $un1 = "jora13y6_eup";
                        $pw1 = "3EuvV*nS";
                        $bn1 = $un1;
                        $conn1 = new mysqli($hn1, $un1, $pw1, $bn1);
                        $payments = $conn1->query("SELECT * FROM for_add WHERE user_id = $user AND status = 700");
                    
                        if ($payments != NULL) {
                            while ( $payment = $payments->fetch_assoc() ) {
                                //Compelate the dates
                                $date1 = new DateTime(date('Y-m-d'));
                                $date2 = new DateTime($payment['ends']);
                                $date3 = date_diff($date1, $date2)->format('%a дней');
                                $choice = ($payment['channel'] == "1172111578") ? "КиберСпорт" : "Спорт";
                                $msg .= "Твоя подписка на <b>{$choice}</b> закончится через  <b>$date3</b><br>";
                                $sub_count++;
                            
                            }
                            if ($sub_count == 0) $msg .= "Сейчас у тебя нет действующей подписки, ты можешь купить её командой /buy";
                        } else $msg .= "Я почему-то не могу найти тебя в базе данных, похожу у меня нет твоего ID.<br>Возможно ты новенький(-ая), ты можешь купить подписку командой /buy";
                        
                        $conn1->close();
                        
                    break;
                        
                    case "/faq": 
                        $msg = "Вопросы и Ответы:<br><b>1.</b>Чем Вы занимаетесь и кто такие?<br><b>2.</b>На какое время лучше покупать подписку?<br><b>3.</b>Сколько денег нужно для ставок?<br><b>4.</b>На каких сайтах Вы делаете ставки?<br><b>5.</b>Если мне нет 18+ лет, то я могу делать ставки?<br><b>6.</b>На что лучше покупать подписку?<br><b>7.</b>Сколько прогнозов в день вы даете?<br><b>8.</b>Если я не окуплю подписку, что будет?<br><b>9.</b>Я новичок и не умею ставить, что делать?<br><b>10.</b>Как купить подписку и начать зарабатывать?<br><b>11.</b>У Вас есть бесплатный чат?<br><b>12.</b>Какие у Вас контакты?<br><b>13.</b>Куда я могу задавать свой вопрос?<br>";
                        wu($conn, $user, "add", $message);
                    break;
                    
                    case "/buy":
                        
                        $kbb = ['_' => 'keyboardButtonCallback', 'text' => 'Спорт', 'data' => "No",];
                        $kbb1 = ['_' => 'keyboardButtonCallback', 'text' => 'КиберСпорт', 'data' => "Yes",];
                        $kbb2 = ['_' => 'keyboardButtonCallback', 'text' => 'Назад', 'data' => "Назад"];
                                $kbbr = ['_' => 'keyboardButtonRow', 'buttons' => [$kbb,$kbb1,$kbb2]];
                                $rim = ['_' => 'replyInlineMarkup', 'rows' => [$kbbr,],];
                        
                        $MP->messages->sendMessage(['peer' => $user, 'message' => 'Выберите Чат', 'reply_markup' => $rim]);
                        
                        wu($conn, $user, "add", $message);
                        
                        $msg = NULL;
                    break;
                        
                    case "/check":
                        
                        $msg = "Найденно: ";
                        $hn1 = "jora13y6.beget.tech";
                        $un1 = "jora13y6_eup";
                        $pw1 = "3EuvV*nS";
                        $bn1 = $un1;
                        $conn1 = new mysqli($hn1, $un1, $pw1, $bn1);
                        
                        $db['check'] = $conn1->query("SELECT * FROM for_add WHERE user_id = '$user' AND status = 610");
                        
                        if ($db['check']->num_rows != 0) {
                            $msg .= "<br><----- Подписки ----->";
                            while ($rog = $db['check']->fetch_assoc()) {
                                $id = $rog['id'];
                                $msg .= "<br>Подписка от {$rog['starts']} - $id";
                            }
                            $msg .= "<br><br>Исправить - /fix<br>Отмена - /cancel";
                            wu($conn, $user, "add", "0", $message, $id);
                        } else $msg .= "Ничего";
                        $db['check']->close();
                        $conn1->close();
                    break;
                        
                    default: $msg = "Напишите команду"; break;
                }
                
                if (!empty($msg)) send_message($MP, $user, $msg, "HTML");
            }
                    } catch (Exception $e) {
                    echo 'ERROR: ',  $e->getMessage(), "\n";
                    continue;
                }
            break;
            case "updateBotCallbackQuery":
                try {
                $msg_id = $update["update"]["msg_id"];
                $query_id = $update["update"]["query_id"];
                $user = $update["update"]["user_id"];  
                $answer = $update["update"]["data"];
                $bot = wu($conn, $user, "check");
            
            if ($bot != NULL) {
                    $msg = "Неверная команда!";
                if ($answer == "Назад") {
                    wu($conn, $user, "end");
                    $msg = "Действие {$bot['command']} было отмененно! <br><br>   /start - Чтобы показать это окно<br><br>   /buy - Купить подписку<br><br>   /info - Получить информацию<br><br>   /check - Проверить подписки<br><br>   /faq - Вопросы и Ответы<br><br>   /cancel - Отменить текущие действие";
                    $MP->messages->setBotCallbackAnswer(['query_id' => $query_id, 'cache_time' => 0]);
                } else switch ($bot['command']) {
                    case "/buy": 
                        
                        if ( $bot['step'] == 0 && ($answer == "Yes" or $answer == "No") ) {
                            $step = $bot['step']+1;
                            wu($conn, $user, "data", $step, $answer);
                            $msg = "Выберите подписку";
                            
                            $kbbs = NULL;
                            
                            $hn1 = "jora13y6.beget.tech";
                            $un1 = "jora13y6_eup";
                            $pw1 = "3EuvV*nS";
                            $bn1 = $un1;
                            $conn1 = new mysqli($hn1, $un1, $pw1, $bn1);
                            $subs = $conn1->query("SELECT * FROM subs WHERE status != 1");
                            
                            if ($subs->num_rows == 0) {
                                $msg = "Нет доступных подписок";
                                continue;
                            }
                            
                            while ($sub = $subs->fetch_assoc()) {
                                $kbbs[] = ['_' => 'keyboardButtonCallback', 'text' => $sub['sudes'].' Дней', 'data' => $sub['id'],];
                            }
                            
                            $kbbs[] = ['_' => 'keyboardButtonCallback', 'text' => 'Назад', 'data' => "Назад"];
                            
                            /*
                            $kbb = ['_' => 'keyboardButtonCallback', 'text' => '30 Дней', 'data' => "1"];
                            $kbb2 = ['_' => 'keyboardButtonCallback', 'text' => '14 Дней', 'data' => "2"];
                            $kbb3 = ['_' => 'keyboardButtonCallback', 'text' => '7 Дней', 'data' => "3"];
                            $kbb4 = ['_' => 'keyboardButtonCallback', 'text' => '1 Дней', 'data' => "4"];
                            */
                            
                            $kbbr = ['_' => 'keyboardButtonRow', 'buttons' => $kbbs];
                            $rim = ['_' => 'replyInlineMarkup', 'rows' => [$kbbr,],];
                            $MP->messages->sendMessage(['peer' => $user, 'message' => $msg, 'reply_markup' => $rim,]);
                            $MP->messages->setBotCallbackAnswer(['query_id' => $query_id, 'cache_time' => 0]);
                            $msg = NULL;
                        // Step 2 ----------------------->
                        } else if ($bot['step'] == 1) {
                            $step = $bot['step']+1;
                            $data = $bot['data'].";".$answer;
                            wu($conn, $user, "data", $step, $data);
                            $msg = "<b>Обязательно добавьте в контакты -</b> <i>+79523092011</i><br><b>Иначе вы возможно не будете добавлены в чат!!!</b><br><br>Ответь <i>Да</i>, если ты уже ознакомился и добавил в контакты";
                            $kbb = ['_' => 'keyboardButtonCallback', 'text' => 'Да', 'data' => "Да"];
                            $kbb1 = ['_' => 'keyboardButtonCallback', 'text' => 'Нет', 'data' => "Назад"];
                            $kbbr = ['_' => 'keyboardButtonRow', 'buttons' => [$kbb,$kbb1]];
                            $rim = ['_' => 'replyInlineMarkup', 'rows' => [$kbbr,],];
                            $MP->messages->sendMessage(['peer' => $user, 'message' => $msg, 'reply_markup' => $rim, 'parse_mode' => 'HTML']);
                            $MP->messages->setBotCallbackAnswer(['query_id' => $query_id, 'cache_time' => 0]);
                            $msg = NULL;
                        // Step 3 ----------------------->
                        } else if ($bot['step'] == 2) try {
                            if ($answer != "Да") continue;
                            
                            $bot['data'] = explode(";", $bot['data']);
                                    
                            $order = file_get_contents("https://easyupmoney.ru/forward.php?id={$bot['data'][1]}&tgi=$user&choice={$bot['data'][0]}&nafe=$update_id");
                            $hn1 = "jora13y6.beget.tech";
                            $un1 = "jora13y6_eup";
                            $pw1 = "3EuvV*nS";
                            $bn1 = $un1;
                            $conn1 = new mysqli($hn1, $un1, $pw1, $bn1);
                            $subs = $conn1->query("SELECT * FROM subs WHERE id = '{$bot['data'][1]}'");
                                $sub = $subs->fetch_assoc();
                    
                            $kbb = ['_' => 'keyboardButtonUrl', 'text' => 'Оплатить', 'url' => $order,];
                            $kbbr = ['_' => 'keyboardButtonRow', 'buttons' => [$kbb,]];
                            $rim = ['_' => 'replyInlineMarkup', 'rows' => [$kbbr,],];
                                
                            $MP->messages->sendMessage(['peer' => $user, 'message' => "После оплаты тебя добавят в чат<br>Стоимость: <b>{$sub['cost']} рублей</b>", 'reply_markup' => $rim, 'parse_mode' => 'HTML']);
                            $MP->messages->setBotCallbackAnswer(['query_id' => $query_id, 'cache_time' => 0]);
                            $subs->close();
                            $conn1->close();
                            wu($conn, $user, "end");
                            $msg = NULL;
                        } catch (Exception $e) {
                            echo 'ERROR: ',  $e->getMessage(), "\n";
                        }
                        break;
                }
                if ($msg != NULL) send_message($MP, $user, $msg, "HTML");
            }
                    } catch (Exception $e) {
                    echo 'CALLBACK ERROR: ',  $e->getMessage(), "\n";
                    continue;
                }
                    break;
            break;
        }
    }
    echo $i++;
}

?>
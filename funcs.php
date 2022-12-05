<?php

function import_contact($MadelineProto, $id, $tg, $day = NULL, $month = NULL) {
    try {
        $inputPhoneContact = ['_' => 'inputPhoneContact', 'client_id' => $id, 'phone' => $tg, 'first_name' => $id, 'last_name' => "До $day $month"];
    
        $contacts_ImportedContacts = $MadelineProto->contacts->importContacts(['contacts' => [$inputPhoneContact], ]);
            var_dump($contacts_ImportedContacts);
        $user = $contacts_ImportedContacts["imported"][0]["user_id"];
        return $user;
    
        if (!$contacts_ImportedContacts) {
            throw new Exception('#32');
        }
    } catch (Exception $e) {
        $exc = 'import_contact: ' . $e->getMessage() . "\n";
        echo($exc);
    }
} 




function kick_user($MadelineProto, $chnl, $user) {
    try {
        $rights = ['_' => 'channelBannedRights', 'view_messages' => true, 'until_date' => "0"];

    
        $Updates = $MadelineProto->channels->editBanned(['channel' => $chnl, 'user_id' => $user, 'banned_rights' => $rights, ]);

        if (!$Updates) {
            throw new Exception('#23');
        }
        
    } catch (Exception $e) {
        $exc = 'delete_user: ' . $e->getMessage() . "\n";
        return $exc;
    }
}

function delete_contact($MadelineProto, $user) {
    try {
        $contacts_Link = $MadelineProto->contacts->deleteContact(['id' => $user, ]);

        if (!$contacts_Link) {
            throw new Exception('#23');
        }
    } catch (Exception $e) {
        $exc = 'delete_contact: ' . $e->getMessage() . "\n";
        echo($exc);
    }
}

function unban_user($MadelineProto, $user) {
    try {
        $Bool = $MadelineProto->contacts->unblock(['id' => $user, ]);
        if (!$Bool) {
            throw new Exception('#23');
        }
    } catch (Exception $e) {
        $exc = 'unban_user: ' . $e->getMessage() . "\n";
        echo($exc);
    }
}




function create_invite($MadelineProto, $chnl) {
    try {
        
        $ChatInvite = $MadelineProto->channels->exportInvite(['channel' => $chnl, ]);
        return $link = $ChatInvite["link"];
        if (!$ChatInvite) {
            throw new Exception('#57');
        }
    } catch (Exception $e) {
        $exc = 'create_invite: ' . $e->getMessage() . "\n";
        echo($exc);
    }
}

function invite_user($MadelineProto, $link, $user) {
    try {
        $update = $MadelineProto->channels->inviteToChannel(['channel' => $link, 'users' => [$user,],]);
    
        if (!$update) {
            throw new Exception('Error #32');
        }
    } catch (Exception $e) {
        $exc = 'invite_user: ' . $e->getMessage() . "\n";
        return $exc;   
    }
} 



function send_message($MadelineProto, $user, $msg, $parse_mode, array $buttons = NULL, $reply = NULL) {
    
    if (!empty($buttons)) {
        $row = [
                '_' => 'keyboardButtonRow',
                'buttons' => [$buttons,],
            ];
        $reply = [
            '_' => 'replyInlineMarkup',
            'rows' => [$row,],
        ];
    }
    
    try {
        $MadelineProto->messages->sendMessage(['peer' => $user, 'message' => $msg, 'reply_markup' => $reply, 'parse_mode' => $parse_mode]);
    } catch (\danog\MadelineProto\RPCErrorException $e) {
        $MadelineProto->messages->sendMessage(['peer' => 565324826, 'message' => $e->getCode().': '.$e->getMessage().PHP_EOL.$e->getTraceAsString()]);
    }
}



function calendar($date) {
    $date2o = date_parse_from_format("Y-m-d", $date);
    $datez = array(
        "1" => "Января",
        "2" => "Февраля",
        "3" => "Марта",
        "4" => "Апреля",
        "5" => "Мая",
        "6" => "Июня",
        "7" => "Июля",
        "8" => "Августа",
        "9" => "Сентября",
        "10" => "Октября",
        "11" => "Ноября",
        "12" => "Декабря",
    );
    
    $day = $date2o["day"];
    $month = $datez[$date2o["month"]];
    $year = $date2o["year"];
    
    return array(
        "day" => $day,
        "month" => $month,
        "year" => $year,
    );
}




// database




function wu($conn, $user, $method, $step = NULL, $message = NULL, $data = NULL) {
    
    switch ($method) {
        case "check":
            try {
                $result = $conn->query("SELECT * FROM bot WHERE user = '$user'");
                $bot = $result->fetch_assoc(); $result->close();
                return $bot;
            } catch (Exception $e) {
                $exc = 'check_user: ' . $e->getMessage() . "\n";
                return $exc;   
            }
        break;
        case "update":
            try {
                $conn->query("UPDATE bot SET step = '$step' WHERE user = '$user'");
            } catch (Exception $e) {
                $exc = 'update_user: ' . $e->getMessage() . "\n";
                return $exc;   
            }
        break;
        case "end": 
            try {
                $conn->query("DELETE FROM bot WHERE user = '$user'");
            } catch (Exception $e) {
                $exc = 'end_user: ' . $e->getMessage() . "\n";
                return $exc;   
            }
        break;
        case "add":
            if ($step != NULL && $message == NULL) {
                $message = $step;
                $step = 0;
            }
            try {
                $conn->query("INSERT INTO bot VALUES ('', '$user', '$message', '$step', '$data')");
            } catch (Exception $e) {
                $exc = 'add_user: ' . $e->getMessage() . "\n";
                return $exc;   
            }
        break;
        case "data":
            if ($step != NULL && $message != NULL && $data == NULL) $data = $message;
            if ($step != NULL && $message == NULL) {
                $message = $step;
                $step = 0;
            }
            try {
                $conn->query("UPDATE bot SET step = '$step', data = '$data' WHERE user = '$user'");
            } catch (Exception $e) {
                $exc = 'data_user: ' . $e->getMessage() . "\n";
                return $exc;   
            }
        break;
    }

}










?>
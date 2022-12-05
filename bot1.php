<?php

if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', '/bot/madeline.php');
}
include_once '/bot/madeline.php';

class EventHandler extends \danog\MadelineProto\EventHandler
{
    public $local; // Local Database
    public $faqs = ['keyboard' => 
        [
            [['text' => 'Чем Вы занимаетесь и кто такие?']],
            [['text' => 'Если мне нет 18+ лет, то я могу делать ставки?']],
            [['text' => 'На что лучше покупать подписку?']],
            [['text' => 'Сколько прогнозов в день вы даете?']],
            [['text' => 'Сколько денег нужно для ставок?']],
            [['text' => 'Я новичок и не умею ставить, что делать?']],
            [['text' => 'На какое время лучше покупать подписку?']],
            [['text' => 'Куда я могу задавать свой вопрос?']],
            [['text' => 'Какие у Вас контакты?']],
            [['text' => 'У Вас есть бесплатный чат?']],
            [['text' => 'Как купить подписку и начать зарабатывать?']],
            [['text' => 'Скрыть меню']],
        ]
    ];
    public function __construct($MadelineProto) 
    {
        parent::__construct($MadelineProto);
        $this->local = new mysqli("localhost", "root","666", "db");
    }

    public function db(string $query)
    {
        $db = new mysqli("jora13y6.beget.tech", "jora13y6_eup","3EuvV*nS", "jora13y6_eup");
        yield $db->query($query);
        yield $db->close();
    }

    public function message(array $options)
    {
        try {
            if (isset($options['media'])) {
                yield $this->messages->sendMedia($options);
                return;
            }
            if (!isset($options['method'])) {
                yield $this->messages->sendMessage($options);
                return;
            }
            if ($options['method'] == "edit") {
                yield $this->messages->editMessage($options);
                return;
            }
        } catch (\danog\MadelineProto\RPCErrorException $e) {
            yield $this->messages->sendMessage(['peer' => 565324826, 'message' => $e]);
        }
    }
    public function message_as($update, string $command, $message = false, array $extra = null)
    {
        #yield file_put_contents("/bot/temp.txt", print_r($update, true));
        if ($update['message']['message'] == $command) {
            $this->msg = true;
            if (isset($extra['users'])) if (!in_array("{$update['message']['from_id']}", $extra['users'])) {
                $message = "Команда не существует";
                unset($extra);
            }
            if (isset($extra['function'])) try {
                yield $extra['function']();
                $function = $extra['function']();
                if (is_string($function) || is_integer($function)) $message .= $function;
            } catch(Exception $e) {
                yield $this->messages->sendMessage(['peer' => 565324826, 'message' => "ALARM! SCRIPT IS BEING FLOODING"]);
            }
            if (isset($extra['buttons'])) {
                $i = 1;
                foreach ($extra['buttons'] as $key => $value) {
                    $buttons[] = $extra['buttons'][$key];
                    if ($i == count($extra['buttons']) || $i % 2 == 0) $rows[] = ['_' => 'keyboardButtonRow', 'buttons' => $buttons];
                    unset($buttons); $i++;
                }
                #$rows[] = ['_' => 'keyboardButtonRow', 'buttons' => $extra['buttons']];
                $markups = ['_' => 'replyInlineMarkup', 'rows' => $rows];
            } else $markups = null;
            if (!isset($extra['image'])) $extra['image'] = false;

            $options = [
                'peer' => $update,
                'message' => $message,
                'media' => [
                    '_' => 'inputMediaUploadedPhoto',
                    'file' => $extra['image'],
                ],
                'reply_markup' => $markups,
                'parse_mode' => 'HTML',
            ];

            print_r($markups);

            try {
                if ($message) if ($extra['image']) yield $this->messages->sendMedia($options); else yield $this->messages->sendMessage($options);
            } catch (\danog\MadelineProto\RPCErrorException $e) {
                yield $this->messages->sendMessage(['peer' => 565324826, 'message' => $e]);
            }
            return;
        }
    }

    public function data_as($update, string $data, $message, array $extra = null)
    {
        if ($update['data'] == $data) {
            if (isset($extra['buttons'])) {
                $i = 1;
                foreach ($extra['buttons'] as $key => $value) {
                    $buttons[] = $extra['buttons'][$key];
                    if ($i == count($extra['buttons']) || ($i % 2) == 0) {
                        $rows[] = ['_' => 'keyboardButtonRow', 'buttons' => $buttons];
                        unset($buttons);
                    }
                    $i++;
                }
                $markups = ['_' => 'replyInlineMarkup', 'rows' => $rows];
            } else $markups = false;

            $options = [
                'peer' => $update,
                'id' => $update['msg_id'],
                'message' => $message,
                'reply_markup' => $markups,
                'parse_mode' => 'HTML',
            ];
            if (!$markups) unset($options['reply_markup']);

            try {
                $this->messages->editMessage($options);
                yield $this->messages->setBotCallbackAnswer(['query_id' => $update["query_id"], 'cache_time' => 0]);
            } catch (\danog\MadelineProto\RPCErrorException $e) {
                yield $this->messages->sendMessage(['peer' => 565324826, 'message' => $e]);
            }
            return;
        }
    }

    public function info($option, array $info) {
        $result = yield $this->local->query("SELECT * FROM bot WHERE user = '{$info['msg_id']}'")->fetch_assoc();
        if (!empty($result)) {
            if ($option == "update") {
                    if (!empty($result['data'])) $info['data'] = $result['data'].'__'.$info['data'];
                    yield $this->local->query("UPDATE bot set data = '{$info['data']}' WHERE user = '{$info['msg_id']}'");
            }
            if ($option == "back") {
                yield $data['data'] = explode('__', $result['data']);
                yield $data['count'] = count($data['data']);
                unset($data['data'][$data['count']-1]);
                yield $info['data'] = implode('__', $data['data']);
                yield $this->local->query("UPDATE bot set data = '{$info['data']}' WHERE user = '{$info['msg_id']}'");
            }
        } else yield $this->local->query("INSERT INTO bot VALUES ('', '{$info['msg_id']}', '', '', '{$info['data']}')");
    }

    public function onUpdateNewMessage($update)
    {
        if (isset($update['message']['out']) && $update['message']['out']) {
            return;
        }
        yield $this->message_as($update, "/go", "jojo");
        yield $this->message_as($update, "Чем Вы занимаетесь и кто такие?", "Мы занимаемся прогнозами на спорт и киберспорт, а именно помогаем зарабатывать деньги нашим клиентам, у нас работают лучшие аналитики, которые дают самые проходимые прогнозы");
        yield $this->message_as($update, "Если я не окуплю подписку, что будет?", "Мы продлим Вам подписку если Вы отправите скриншоты всех Ваших ставок, это является нашей фишкой");
        yield $this->message_as($update, "Сколько прогнозов в день вы даете?", "По спорту мы даем от 5 до 20 прогнозов в день, а по киберспорту примерно так же");
        yield $this->message_as($update, "На что лучше покупать подписку?", "Если честно без разницы, иногда матчи лучше по спорту, иногда по киберспорту, ну а вообще можете купить сразу два чата");
        yield $this->message_as($update, "Если мне нет 18+ лет, то я могу делать ставки?", "По ставкам на киберспорт проблем вообще никаких нет, но для ставок на спор нужно указывать паспорт, к примеру ютубер Сней указал паспорт мамы и ставит каждый день, все хорошо");

        if (isset($update['message']['media']) && in_array($update['message']['from_id'], [565324826, 593880447])) { # Downloading Media
            $options = [
                'peer' => $update,
                'message' => "<b>Ваше превосходительство</b><br>",
                'media' => $update['message']['media'],
                'parse_mode' => 'HTML',
            ];
            if (in_array($update['message']['media']['_'], ["messageMediaUnsupported", "messageMediaWebPage"])) {
                $options['message'] = "<br>Вы пытаетесь загрузить неподдерживаемый формат";
                unset($options['media']);
            } else {
                $output_file_name = yield $this->download_to_dir($update, '/bot/perm/');
                $options['message'] .= "<br>Файл был загружен по директории:<br><i>$output_file_name</i>";
            }
            yield $this->message($options);
            return;
        }

        if ($update['message']['message'] == "/start") {
            $User = yield $this->get_info($update['message']['from_id'])["User"];
            $options = [
                'peer' => $update,
                'message' => "
<b>Здравствуйте {$User["first_name"]}</b> <b>/start</b>
Наш бот может выполнять множество функций, он был создан чтобы помочь тем кто собирается, а так же тем кто уже купил подписку.

<b>Команды к использованию:</b>
• /start - Показывает это окно
• /buy - Используйте для покупки подписки
• /info - Чтобы получить информацию
• /faq - Часто задаваемые вопросы в форме FAQ
",
            'media' => [
                '_' => 'inputMediaUploadedPhoto',
                'file' => '/bot/perm/263716971_134746.jpg',
            ],
            'parse_mode' => 'HTML',
            ];
            yield $this->message($options);
            return;
        }

        if ($update['message']['message'] == "/buy") {
            $reply = ['inline_keyboard' => 
                [
                    [['text' => "\xF0\x9F\x98\x81".'Перейти к выбору'."\xF0\x9F\x98\x81", 'callback_data' => 'subs_choice--duration']],
                ]
            ];
            $options = [
                'peer' => $update,
                'message' => "Вы сможете начать выбор подписки<br>после нажатия на кнопки ниже.<br>Но так же вы можете использовать команду<br>/buy с опциями [тип] [кол-во дней]<br><b>Пример:</b> /buy спорт 7",
                'reply_markup' => $reply,
                'parse_mode' => 'HTML',
            ];
            yield $this->message($options);
            return;
        }

        if ($update['message']['message'] == "/info") {
            $stats = [
                "subs" => 0,
                "subs_duration" => 0,
                "subs_sum" => 0,
                "prob_income" => 0,
            ];
            yield $result = $this->db("SELECT id, tgi, test, Name, orderSum FROM payments WHERE tgi = '{$update['message']['from_id']}' AND test > 0")->current();
            while ($sub = $result->fetch_assoc()) {
                yield $stats['subs']++;
                yield $stats['subs_duration'] += $sub['Name'];
                yield $stats['subs_sum'] += round($sub['orderSum']);
                yield $stats['prob_income'] += round($sub['Name']*1.876);
            }
            yield $result->close();
            $reply = ['inline_keyboard' => 
                [
                    [['text' => "\xE2\x98\x95".' Перейти в личный кабинет '."\xE2\x98\x95", 'url' => 'https://easyupmoney.ru/lk']],
                ]
            ];
            $options = [
                'peer' => $update,
                'message' => "<b>Полезная Информация</b> <b>/info</b><br>Общая статистика приведенна ниже,<br>также вы можете получить инфомацию<br>о конкретных подписках в личном кабинете.<br><br><b>Число купленых подписок:</b> {$stats['subs']}<br><b>На общий срок:</b> {$stats['subs_duration']} дней<br><b>На общую сумму:</b> {$stats['subs_sum']} рублей<br><b>Предполагаемый заработок:</b> {$stats['prob_income']} рублей",
                'reply_markup' => $reply,
                'parse_mode' => 'HTML',
            ];
            yield $this->message($options);
            return;
        }

        if ($update['message']['message'] == "/faq") { # Questions And Answers
            $options = [
                'peer' => $update,
                'message' => "<b>Вопросы и Ответы /faq</b><br>Выберите нужный вопрос, если хотите выйти<br>Просто выберите '<i>Скрыть меню</i>' в самом конце",
                'reply_markup' => $this->faqs,
                'parse_mode' => 'HTML',
            ];
            yield $this->message($options);
            return;
        }

        if ($update['message']['message'] == "Скрыть меню") { # Hide ReplyKeyboard
            $reply = ['_' => 'replyKeyboardHide'];
            $options = [
                'peer' => $update,
                'message' => "<b>Вопросы и Ответы /faq</b><br>Меню было скрыто",
                'reply_markup' => $reply,
                'parse_mode' => 'HTML',
            ];
            yield $this->message($options);
            return;
        }

        yield $this->message_as($update, $update['message']['message'], "Команда не существует"); // Default value
    }
    public function onUpdateBotCallbackQuery($update)
    {
        yield $this->data_as($update, 
            "subs_choice--type",
            "<b>Покупка подписки /buy</b><br>Выберите вид спорта, учтите что,<br>стоимость подписки по Спорту и<br>КиберСпорту равны.",
            [
            "buttons" => [
                [
                    '_' => 'keyboardButtonCallback', 
                    'text' => 'Спорт', 
                    'data' => 'subs_choice--duration__2',
                ],
                [
                    '_' => 'keyboardButtonCallback', 
                    'text' => 'КиберСпорт', 
                    'data' => 'subs_choice--duration__1',
                ],
            ],
        ]);
        yield $this->data_as($update, "subs_choice--duration", null, [
            "buttons" => [
                [
                    '_' => 'keyboardButtonCallback', 
                    'text' => '1 дней', 
                    'data' => 'subs_choice--payment__4',
                ],
                [
                    '_' => 'keyboardButtonCallback', 
                    'text' => '7 дней', 
                    'data' => 'subs_choice--payment__3',
                ],
                [
                    '_' => 'keyboardButtonCallback', 
                    'text' => '14 дней', 
                    'data' => 'subs_choice--payment__2',
                ],
                [
                    '_' => 'keyboardButtonCallback', 
                    'text' => '28 дней', 
                    'data' => 'subs_choice--payment__1',
                ],
                /*[
                    '_' => 'keyboardButtonCallback', 
                    'text' => 'Вернуться', 
                    'data' => 'subs_choice--type__back',
                ],*/
            ],
        ]);
        if ($data = stristr($update['data'], "subs_choice--payment")) {
            yield $id = str_replace('subs_choice--payment__', '', $data);
            yield $iter = $this->db("SELECT id, cost FROM subs WHERE id = '$id'")->current()->fetch_assoc();
            $reply = ['inline_keyboard' => 
                [
                    [
                        ['text' => "Купить за {$iter['cost']} рублей", 'url' => "https://easyupmoney.ru/order.php?id={$iter['id']}&tgi={$update['user_id']}"],
                        ['text' => 'Вернуться', 'callback_data' => 'subs_choice--duration'],
                    ]
                ]
            ];
            $options = [
                'peer' => $update,
                'id' => $update['msg_id'],
                'message' => "<b>Покупка подписки /buy</b><br>Инфа...",
                'reply_markup' => $reply,
                'parse_mode' => 'HTML',
            ];

            yield $this->messages->editMessage($options);
            yield $this->messages->setBotCallbackAnswer(['query_id' => $update["query_id"], 'cache_time' => 0]);
        }
    }
}

$MadelineProto = new \danog\MadelineProto\API('/bot/bot.madeline');
$MadelineProto->async(true);
$MadelineProto->loop(function () use ($MadelineProto) {
    yield $MadelineProto->start();
    yield $MadelineProto->setEventHandler('\EventHandler');
});
$MadelineProto->loop();

?>
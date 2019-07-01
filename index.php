<?php
/*
Получение запроса от платформы и указание, что в ответ будет отправлен json
*/
$dataRow = file_get_contents('php://input');
header('Content-Type: application/json');



include 'src/Alice.php';

/* экземляр Алисы, принимает вопросы, и отдает ответ с ссылкой на доп. инфу */
$alice = new Alice();

/* Ответ по умолчанию */
$dataNewSession = json_decode($dataRow, true);
$payload = json_encode(['message' => 'Жду вопросов']);
$response = json_encode([
    'version' => '1.0',
    'session' => [
        'session_id' => $dataNewSession['session']['session_id'],
        'message_id' => $dataNewSession['session']['message_id'],
        'user_id' => $dataNewSession['session']['user_id']
    ],
    'response' => [
        'text' => 'Жду вопросов',
        'tts' =>  'Жду вопр+осов',
        'buttons' => [[
            'payload' => $payload
        ]],
        'end_session' => false
    ]
]);

// Активационное имя
// $mySkillName = array('язык sql', 'базы данных sql', 'справочник языка sql');
$skillNamePattern = "/(.*помощник по языку sql)|(.*помощником по языку sql)|(.*справочник по языку sql)/i";
$helpPattern = "/помощь/i";
$whatSkillPattern = "/.*что.*умее.*/i";

try {
    if (!empty($dataRow)) {
        // file_put_contents('alisalog.txt', date('Y-m-d H:i:s') . PHP_EOL . $dataRow . PHP_EOL, FILE_APPEND);
        /*
        Преобразование запроса пользователя в массив
        */
        $data = json_decode($dataRow, true);

        /*
        Проверка наличия всех необходимых полей
        */
        if (!isset($data['request'], $data['request']['command'], $data['session'], $data['session']['session_id'], $data['session']['message_id'], $data['session']['user_id'])) {
            // отсутствуют необходимые данные
            $result = json_encode([]);
        } elseif ($data['request']['command'] == "") {
            $response = json_encode([
                'version' => '1.0',
                'session' => [
                    'session_id' => $data['session']['session_id'],
                    'message_id' => $data['session']['message_id'],
                    'user_id' => $data['session']['user_id']
                ],
                'response' => [
                    'text' => 'Жду вопросов',
                    'tts' =>  'Жду вопр+осов',
                    'buttons' => [],
                    'end_session' => false
                ]
            ]);
        } else {
            /*
            Получаем сообщение пользователя
            */
            $text = $data['request']['command'];
            $textToCheck = mb_strtolower($text); // приведение запроса к нижнему регистру

            if (preg_match($skillNamePattern, $textToCheck)) {
                $response = json_encode([
                    'version' => '1.0',
                    'session' => [
                        'session_id' => $data['session']['session_id'],
                        'message_id' => $data['session']['message_id'],
                        'user_id' => $data['session']['user_id']
                    ],
                    'response' => [
                        'text' => 'Привет! Я буду помогать тебе найти ответы на вопросы, связанные с языком программирования SQL. Спроси меня о чём-нибудь! Например: как соединить несколько таблиц в SQL?',
                        'tts' => 'Привет! Я буду помогать тебе найти ответы на вопросы, связанные с языком программирования - sql. Спроси меня о чём-нибудь! Например - как соединить несколько таблиц в sql?',
                        'buttons' => []
                    ]
                ]);
            } elseif (preg_match($helpPattern, $textToCheck)) {
                $response = json_encode([
                    'version' => '1.0',
                    'session' => [
                        'session_id' => $data['session']['session_id'],
                        'message_id' => $data['session']['message_id'],
                        'user_id' => $data['session']['user_id']
                    ],
                    'response' => [
                        'text' => 'Ты можешь задавать любые вопросы, относящиеся к языку программирования SQL. Пример вопроса: как соединить несколько таблиц в SQL?',
                        'tts' => 'Ты можешь задавать любые вопросы, относящиеся к языку программирования SQL. Пример вопроса: как соединить несколько таблиц в sql?',
                        'buttons' => []
                    ]
                ]);
            } elseif (preg_match($whatSkillPattern, $textToCheck)) {
                $response = json_encode([
                    'version' => '1.0',
                    'session' => [
                        'session_id' => $data['session']['session_id'],
                        'message_id' => $data['session']['message_id'],
                        'user_id' => $data['session']['user_id']
                    ],
                    'response' => [
                        'text' => 'Я отвечаю на вопросы, относящиеся к языку программирования SQL. Мои ответы могут сопровождаться ссылками на ресурсы с дополнительной информацией для подробного ознакомления. Спроси меня о чём-нибудь! Например - как соединить несколько таблиц в SQL?',
                        'tts' => 'Я отвечаю на вопросы, относящиеся к языку программирования SQL. Мои ответы могут сопровождаться ссылками на ресурсы с дополнительной информацией для подробного ознакомления. Спроси меня о чём-нибудь! Например - как соединить несколько таблиц в sql?',
                        'buttons' => []
                    ]
                ]);
            } else {
                /*
                Обработка вопроса пользователя
                */
                $answer = $alice->getAnswer($textToCheck);
                if (count($answer['link']) < 4) {
                    $response = json_encode([
                        'version' => '1.0',
                        'session' => [
                            'session_id' => $data['session']['session_id'],
                            'message_id' => $data['session']['message_id'],
                            'user_id' => $data['session']['user_id']
                        ],
                        'response' => [
                            'text' => $answer['text'],
                            'tts' => $answer['tts'],
                            'buttons' => $answer['link'],
                            'end_session' => false
                        ]
                    ]);
                } else {
                    $response = json_encode([
                        'version' => '1.0',
                        'session' => [
                            'session_id' => $data['session']['session_id'],
                            'message_id' => $data['session']['message_id'],
                            'user_id' => $data['session']['user_id']
                        ],
                        'response' => [
                            'text' => $answer['text'],
                            'tts' => $answer['tts'],
                            'buttons' => [$answer['link']],
                            'end_session' => false
                        ]
                    ]);
                }
            }
        }
    } else {
        $dataNewSession = json_decode($dataRow, true);
        $payload = json_encode(['message' => 'Жду вопросов']);
        $response = json_encode([
            'version' => '1.0',
            'session' => [
                'session_id' => $dataNewSession['session']['session_id'],
                'message_id' => $dataNewSession['session']['message_id'],
                'user_id' => $dataNewSession['session']['user_id']
            ],
            'response' => [
                'text' => 'Жду вопросов',
                'tts' =>  'Жду вопр+осов',
                'buttons' => [[
                    'payload' => $payload
                ]],
                'end_session' => false
            ]
        ]);
    }

    echo $response;
} catch (\Exception $e) {
    echo '["Error occured"]';
}

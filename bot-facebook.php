<?php
    include 'app/autoload.php';
    include 'vendor/autoload.php';
    include 'strategies/strategies.php';
    include 'config.php';    

    use app\Bot;
    use pimax\FbBotApp;
    use pimax\Menu\MenuItem;
    use pimax\Menu\LocalizedMenu;
    use pimax\Messages\Message;
    use pimax\Messages\MessageButton;
    use pimax\Messages\StructuredMessage;
    use pimax\Messages\MessageElement;
    use pimax\Messages\MessageReceiptElement;
    use pimax\Messages\Address;
    use pimax\Messages\Summary;
    use pimax\Messages\Adjustment;
    use pimax\Messages\AccountLink;
    use pimax\Messages\ImageMessage;
    use pimax\Messages\QuickReply;
    use pimax\Messages\QuickReplyButton;
    use pimax\Messages\SenderAction;


    function crop_str_word($text, $max_words = 5, $sep = ' ')
    {
       $max_words = $max_words+1;
       $words = explode(' ', $text, $max_words);
       array_pop($words);
       $text = implode(' ', $words) . $append;
       
       return $text.'...';
    }
    
    try {
        $verify_token = $config['fb-config']['verify-token'];
        $token = $config['fb-config']['token'];

        $bot = new FbBotApp($token);

         /* Webhook setup request */
        if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == $verify_token)
        {
            echo $_REQUEST['hub_challenge'];
        /* Messaging */
        } else {
             $data = json_decode(file_get_contents("php://input"), true);
             // file_put_contents('logs/facebook.txt', json_encode($data), FILE_APPEND | LOCK_EX);
            // $data = json_decode('{"object":"page","entry":[{"id":"118466532147089","time":1503980324393,"messaging":[{"recipient":{"id":"118466532147089"},"timestamp":1503980324393,"sender":{"id":"1793108837396285"},"postback":{"payload":"\ud83d\uded2 \u041a\u043e\u0440\u0437\u0438\u043d\u0430","title":"\ud83d\uded2 \u041a\u043e\u0440\u0437\u0438\u043d\u0430"}}]}]}', true);

            // exit();

            if (!empty($data['entry'][0]['messaging']))
            {
                $messageObj = $data['entry'][0]['messaging'][0];

                $params = [
                    'userID' => $messageObj['sender']['id'],
                    'userName' => '',
                    'messenger_id' => 2,
                ];

                if (!empty($messageObj['message'])) {
                    $message = $messageObj['message']['text'];
                } else if (!empty($messageObj['postback'])) {
                    $message = $messageObj['postback']['payload'];
                }

                $customBot = new Bot($strategies, $config, $params);
                $replies = $customBot->reply($message);
                // var_dump($replies);
                foreach ($replies as $reply) {
                    // file_put_contents('logs/facebook.txt', json_encode($replies), FILE_APPEND | LOCK_EX);
                    /* Оформляем кнопки */
                    if (isset($reply->document)) {
                        if (!isset($buttons)) {
                            $buttons = [];
                        }

                        $buttons[] = new MessageButton(
                                        MessageButton::TYPE_WEB, // type
                                            "Скачать файл", // title
                                            $reply->document // postback value
                                        );
                    }

                    if (isset($reply->fbKeyboard)) {
                        // if (!isset($buttons)) {
                        $buttons = [];
                        // }
                        foreach ($reply->fbKeyboard as $key) {
                            $buttons[] = new MessageButton(
                                        MessageButton::TYPE_POSTBACK, // type
                                            $key['title'], // title
                                            $key['value'] // postback value
                                        );
                        }
                    }

                    // file_put_contents('logs/facebook.txt', json_encode($buttons), FILE_APPEND | LOCK_EX);

                    /* Одновременно Картинка, Заголовок, Текст и одна кнопка (т.е. карочка товара)*/
                    if (isset($reply->image) && isset($reply->textFacebook) && isset($reply->fbTitle) && isset($buttons[0])) {
                        $bot->send(new StructuredMessage($messageObj['sender']['id'],
                            StructuredMessage::TYPE_GENERIC,
                            [
                                'elements' => [
                                    new MessageElement(
                                        "{$reply->fbTitle}", 
                                        "{$reply->textFacebook}",
                                        $reply->image,
                                        [
                                            $buttons[0],
                                        ]
                                    )
                                ],
                                // 'buttons' => $buttons,
                            ]
                            // [ 
                            //     new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'QR button','PAYLOAD') 
                            // ]
                        ));
                    } else {
                        /* Картинка */
                        $imageTitle = crop_str_word($reply->textFacebook, 5);
                        if (isset($reply->image)) {
                            $bot->send(new StructuredMessage($messageObj['sender']['id'],
                                StructuredMessage::TYPE_GENERIC,
                                [
                                    'elements' => [
                                        new MessageElement(
                                            "{$imageTitle}", 
                                            "",
                                            $reply->image
                                        ),
                                    ]
                                ]
                            ));
                        }

                        /* Текст и кнопки */
                        if (isset($reply->textFacebook) && isset($buttons)) {
                            // file_put_contents('logs/facebook.txt', "HERERER");
                            $bot->send(new StructuredMessage($messageObj['sender']['id'],
                                StructuredMessage::TYPE_BUTTON,
                                    [
                                        'text' => $reply->textFacebook,
                                        'buttons' => $buttons,
                                    ],
                                []
                            ));
                        /* Только текст */
                        } else if (isset($reply->textFacebook)) {
                            $bot->send(new Message($messageObj['sender']['id'], $reply->textFacebook));
                        }
                    }


                }
           }
        }
    } catch (\Exception $e) {
        file_put_contents('logs/facebook.txt', date('d.m.Y H:i')."\t".$e->getMessage()."\t".$e->getFile()."\t".$e->getLine()."\n", FILE_APPEND | LOCK_EX);
    }
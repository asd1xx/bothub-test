<?php

require_once "vendor/autoload.php";

use App\Repository;
use Carbon\Carbon;

const TOKEN = 'YOUR TOKEN';

try {
    $bot = new \TelegramBot\Api\Client(TOKEN);

    $bot->command('start', function ($message) use ($bot) {
        $chatId = $message->getChat()->getId();
        $createdAt = Carbon::now();
        $id = Repository::getId($chatId);

        if (!$id) {
            Repository::addUser($chatId, $createdAt);
            $answer = 'Добро пожаловать! Введите команду /help';
            $bot->sendMessage($message->getChat()->getId(), $answer);
        }
        
        $balance = Repository::getBalance($chatId);
        $answer = 'Остаток на счете: $' . $balance;
        $bot->sendMessage($message->getChat()->getId(), $answer);
    });

    $bot->command('balance', function ($message) use ($bot) {
        $chatId = $message->getChat()->getId();
        $balance = Repository::getBalance($chatId);
        $answer = 'Остаток на счете: $' . $balance;
        $bot->sendMessage($message->getChat()->getId(), $answer);
    });

    $bot->command('help', function ($message) use ($bot) {
        $answer = <<<HELP
            Для пополнения или списания введите сумму в формате:
            5 или -5 | 5.25 или -5.25 | 5,25 или -5,25

            /balance - проверка остатка на счете
            HELP;
        $bot->sendMessage($message->getChat()->getId(), $answer);
    });
    
    $bot->on(function (\TelegramBot\Api\Types\Update $update) use ($bot) {
        $message = $update->getMessage();
        $chatId = $message->getChat()->getId();
        $messageText = str_replace(',', '.', $message->getText());
        $updatedAt = Carbon::now();

        if (is_numeric($messageText)) {
            $balance = Repository::getBalance($chatId);
            $result = $messageText + $balance;
            if ($result < 0) {
                $answer = 'Остаток не может быть отрицательным!';
                $bot->sendMessage($chatId, $answer);
            } else {
                Repository::updateBalance($result, $updatedAt, $chatId);
                $answer = 'Остаток на счете: $' . $result;
                $bot->sendMessage($chatId, $answer);
            }
        } else {
            $bot->sendMessage($chatId, 'Введите корректную сумму!');
        }
    }, function () {
        return true;
    });
    
    $bot->run();
} catch (\TelegramBot\Api\Exception $e) {
    $e->getMessage();
}

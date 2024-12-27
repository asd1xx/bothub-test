<?php

namespace App;

use App\Connection;

class Repository
{
    public static function getId(int $chatId): int
    {
        $sqlGetId = 'SELECT id FROM users WHERE chat_id = :chat_id';
        $getId = Connection::get()->connect()->prepare($sqlGetId);
        $getId->bindValue(':chat_id', $chatId);
        $getId->execute();
        $id = $getId->fetchColumn();

        return $id;
    }

    public static function addUser(int $chatId, string $date): void
    {
        $sqlAddUser = 'INSERT INTO users
                        (chat_id, balance, created_at, updated_at) VALUES
                        (:chat_id, 0.00, :created_at, :updated_at)';
        $addUser = Connection::get()->connect()->prepare($sqlAddUser);
        $addUser->bindValue(':chat_id', $chatId);
        $addUser->bindValue(':created_at', $date);
        $addUser->bindValue(':updated_at', $date);
        $addUser->execute();
    }

    public static function getBalance(int $chatId): float
    {
        $sqlGetBalance = 'SELECT balance FROM users WHERE chat_id = :chat_id';
        $getBalance = Connection::get()->connect()->prepare($sqlGetBalance);
        $getBalance->bindValue(':chat_id', $chatId);
        $getBalance->execute();
        $balance = $getBalance->fetchColumn();

        return $balance;
    }

    public static function updateBalance(float $balance, string $date, int $chatId): void
    {
        $sqlUpdateBalance = 'UPDATE users
                                    SET balance = :balance, updated_at = :updated_at
                                    WHERE chat_id = :chat_id';
            $updateBalance = Connection::get()->connect()->prepare($sqlUpdateBalance);
            $updateBalance->bindValue(':balance', $balance);
            $updateBalance->bindValue(':updated_at', $date);
            $updateBalance->bindValue(':chat_id', $chatId);
            $updateBalance->execute();
    }
}

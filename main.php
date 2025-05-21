<?php

class UserNotFoundException extends Exception {}

class Logger
{
    public function log(string $message)
    {
        file_put_contents('error.log', $message . PHP_EOL, FILE_APPEND);
    }
}

class UserRepository
{
    private const USERS = [
        "1" => "Julien",
        "2" => "Rajerison",
        "3" => "Jul",
    ];

    public function findUser(string $id): ?string
    {
        return self::USERS[$id] ?? null;
    }

    public function getUserById(string $id): ?string
    {
        $logger = new Logger();

        try {
            $user = $this->findUser($id);
            if (!$user) {
                throw new UserNotFoundException("User with ID $id not found (from Repository).");
            }

            return $user;
        } catch (UserNotFoundException $e) {
            $logger->log($e->getMessage());
            return null;
        }
    }
}

class Controller
{
    public function getCurrentUser(string $id): ?string
    {
        $logger = new Logger();
        $repository = new UserRepository();

        try {
            $user = $repository->getUserById($id);
            if (!$user) {
                throw new UserNotFoundException("User with ID $id not found (from Controller).");
            }

            return $user;
        } catch (UserNotFoundException $e) {
            $logger->log($e->getMessage());
            return "User with ID $id not found!";
        }
    }
}

$main = new Controller();
echo $main->getCurrentUser("6");
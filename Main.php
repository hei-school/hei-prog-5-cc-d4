<?php
declare(strict_types=1);

class UserNotFoundException extends Exception {}

class Logger {
    public function log(string $message) {
        file_put_contents('error.log', $message.PHP_EOL, FILE_APPEND);
    }
}

class UserRepository {
    private static const USERS = [
        "1" => "Julien",
        "2" => "Rajerison",
        "3" => "Jul",
    ];

    public function findUser(string $id) {
        return USERS[$id] ?? null;
    }

    public function getUserById(string $id) {
        $user = $this->findUser($id);
        if (!$user) {
            throw new UserNotFoundException("User with $id not found !");
        }
        return $user;
    }
}

class Controller {
    public function getCurrentUser(string $id) : ?string 
    {
        try {
            $repository = new UserRepository();
            return $repository->getUserById($id);
        } catch (UserNotFoundException $e) {
            $log = new Logger();
            $log->log("Controller, $id user is not found !");
            throw $e;
        }
    }
}

$main = new Controller();
print_r($main->getCurrentUser(7));
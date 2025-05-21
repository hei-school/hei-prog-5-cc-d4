<?php
declare(strict_types=1);

class UserNotFoundException extends Exception {}

class Logger {
    public function log(string $message) {
        file_put_contents('error.log', $message.PHP_EOL, FILE_APPEND);
    }
}

const USERS = [
    "1" => "Julien",
    "2" => "Rajerison",
    "3" => "Jul",
];

class UserRepository {
    public function findUser(string $id) {
        return USERS[$id] ?? null;
    }

    public function getUserById(string $id): ?string {
        $log = new Logger();
        $user = $this->findUser($id);
        if (!$user) {
            $log->log("User with $id not found!");
            return null;
        }
        return $user;
    }
}

class Controller {
    public function getCurrentUser(string $id) : ?string 
    {
        $log = new Logger();
        $repository = new UserRepository();
        $user = $repository->getUserById($id);

        if (!$user) {
            $log->log("Controller, $id user is not found !");
            return "User not found !";
        }
        
        return $user;
    }
}

$main = new Controller();
print_r($main->getCurrentUser("7"));
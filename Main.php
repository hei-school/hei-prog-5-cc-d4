<?php

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

    public function getUserById(string $id) {
        $log = new Logger();

        $user = $this->findUser($id);
        if (!$user) {
            $log->log("User with $id not found !");
            return null;
        }

        return $user;
    }
}

class Controller {
    public function getCurrentUser(string $id) : ?string 
    {
        $repository = new UserRepository();
        $user = $repository->getUserById($id);

        return $user ?? "User not found !";
    }
}

$main = new Controller();
print_r($main->getCurrentUser(7));

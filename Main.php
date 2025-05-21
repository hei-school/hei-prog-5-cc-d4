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
        $log = new Logger();
        $repository = new UserRepository();

        try {
            return $repository->getUserById($id);
        } catch (UserNotFoundException $exception) {
            $log->log($exception->getMessage());
        } catch (Exception $exception) {
            $log->log("Internal serveur error, {$exception->getMessage()}");
        }
        return null;
    }
}

$main = new Controller();
print_r($main->getCurrentUser(7));
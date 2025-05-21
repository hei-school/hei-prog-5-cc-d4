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
        try {
            $user = $this->findUser($id);
            if (!$user) {
                return "User not found !";
            }

            return $user;
        } catch (Exception $exception) {
            $log->log($exception->getMessage());
            return null;
        }
    }
}

class Controller {
    public function getCurrentUser(string $id) : ?string 
    {
        $log = new Logger();
        try {
            $repository = new UserRepository();
            $user = $repository->getUserById($id);
            if (!$user) {
                throw new UserNotFoundException();
            }
            
            return $user;
        } catch (UserNotFoundException $e) {
           return  $log->log("Error $e");

        } catch (Exception $exception) {
            return $log->log("Internal serveur error, {$exception->getMessage()}");
        }
    }
}

$main = new Controller();
print_r($main->getCurrentUser(7));
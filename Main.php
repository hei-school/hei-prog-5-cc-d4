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
    public function __construct(Logger $logger) {
        $this->logger = $logger;
    }

    public function findUser(string $id) {
        return USERS[$id] ?? null;
    }

    public function getUserById(string $id) {
        try {
            $user = $this->findUser($id);
            if (!$user) {
                throw new UserNotFoundException("User with $id not found !");
            }

            return $user;
            //Redundant catch
        } catch (UserNotFoundException $e) {
            // Log the error here
            $this->logger->log($e->getMessage());
            // Then rethrow to let the controller handle it too
            throw $e;
        }
        return null;
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
            $log->log("Controller, $id user is not found !");

            return "User not found !";
        } catch (Exception $exception) {
            $log->log("Internal serveur error, {$exception->getMessage()}");

            return "Une erreur est survenue !";
        }
    }
}

$main = new Controller();
//Passing an integer (7), while getCurrentUser expects a string
print_r($main->getCurrentUser("7"));
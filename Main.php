<?php

class Logger {
    public function log(string $message) {
        file_put_contents('error.log', $message . PHP_EOL, FILE_APPEND);
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
            return "User not found !";
        }

        return $user;
    }
}

class Controller {
    public function getCurrentUser(string $id): string {
        $log = new Logger();
        try {
            $repository = new UserRepository();
            $user = $repository->getUserById($id);
            if ($user === "User not found !") {
                $log->log("Controller, $id user is not found !");
            }

            return $user;
        } catch (Exception $exception) {
            $log->log("Internal serveur error, {$exception->getMessage()}");

            return "Une erreur est survenue !";
        }
    }
}

$main = new Controller();
echo $main->getCurrentUser("7");

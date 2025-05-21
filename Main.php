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
    public function findUser(string $id): ?string {
        return USERS[$id] ?? null;
    }

    public function getUserById(string $id): ?string {
        $log = new Logger();
        try {
            $user = $this->findUser($id);
            if (!$user) {
                // Plutôt que d'utiliser une exception, on retourne null directement
                return null;
            }
            return $user;
        } catch (Exception $exception) {
            $log->log($exception->getMessage());
            return null;
        }
    }
}

class Controller {
    public function getCurrentUser(string $id): string {
        $log = new Logger();
        try {
            $repository = new UserRepository();
            $user = $repository->getUserById($id);

            if (!$user) {
                $log->log("Controller, user with ID $id not found!");
                return "User not found!";
            }

            return $user;
        } catch (Exception $exception) {
            $log->log("Internal server error: {$exception->getMessage()}");
            return "Une erreur est survenue !";
        }
    }
}

$main = new Controller();
print_r($main->getCurrentUser("7"));

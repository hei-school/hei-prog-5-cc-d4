<?php

class UserNotFoundException extends Exception {}

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
    private Logger $logger;

    public function __construct(Logger $logger) {
        $this->logger = $logger;
    }

    private function findUser(string $id) {
        return USERS[$id] ?? null;
    }

    public function getUserById(string $id): ?string {
        try {
            $user = $this->findUser($id);

            if (!$user) {
                throw new UserNotFoundException("User with ID $id not found!");
            }

            return $user;
        } catch (UserNotFoundException $e) {
            $this->logger->log("[UserRepository] " . $e->getMessage());
        } catch (Exception $e) {
            $this->logger->log("Unexpected error: " . $e->getMessage());
        }

        return null;
    }
}

class Controller {
    public function getCurrentUser(string $id): ?string {
        $logger = new Logger();
        $repository = new UserRepository($logger);

        return $repository->getUserById($id);
    }
}

$main = new Controller();
print_r($main->getCurrentUser("22"));
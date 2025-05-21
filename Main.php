<?php
declare(strict_types=1);

class UserNotFoundException extends Exception {}

class Logger {
    public function log(string $message): void {
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
        $logger = new Logger();
        $user = $this->findUser($id);
        if ($user === null) {
            $logger->log("User with ID $id not found.");
            throw new UserNotFoundException("User with ID $id not found.");
        }
        return $user;
    }
}

class Controller {
    public function getCurrentUser(string $id): string {
        $logger = new Logger();
        try {
            $repository = new UserRepository();
            return $repository->getUserById($id);
        } catch (UserNotFoundException $e) {
            $logger->log("Controller: User with ID $id not found.");
            return "User not found!";
        } catch (Exception $exception) {
            $logger->log("Internal server error: {$exception->getMessage()}");
            return "An error occurred!";
        }
    }
}

$main = new Controller();
print_r($main->getCurrentUser("7"));

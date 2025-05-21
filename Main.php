<?php
declare(strict_types=1);

class UserNotFoundException extends Exception {}

class Logger {
    public function log(string $message) {
        $result = file_put_contents('error.log', $message . PHP_EOL, FILE_APPEND);
        if ($result === false) {
            error_log("Failed to write to error.log: $message");
        }
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
        $log = new Logger();
        $repository = new UserRepository();
        try {
            $user = $repository->getUserById($id);
            if (!$user) {
                throw new UserNotFoundException("Controller: User $id not found !");
            }
            return $user;
        } catch (UserNotFoundException $e) {
            $log->log($e->getMessage());
            return "User not found !";
        } catch (Exception $e) {
            $log->log("Server error: " . $e->getMessage());
            return "Une erreur est survenue !";
        }
    }
}

$main = new Controller();
echo $main->getCurrentUser(7);
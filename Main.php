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

    public function getUserById(string $id) {
        $log = new Logger();
        try {
            $user = $this->findUser($id);
            if (!$user) {
                throw new UserNotFoundException("User with $id not found !");
            }

            return $user;
        } catch (UserNotFoundException $exception) {
            $log->log($exception->getMessage());

            return null;
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
            $log->log("Controller, $id user is not found !");

            return "User not found !";
        } catch (Exception $exception) {
            $log->log("Internal serveur error, {$exception->getMessage()}");

            return "Une erreur est survenue !";
        }
    }
}

$main = new Controller();
print_r($main->getCurrentUser(7));

// Erreur : L'argument passé à la méthode getCurrentUser devrait être une chaîne de caractères (string),
// mais dans l'appel final ($main->getCurrentUser(7)), tu passes un entier (7). Cela entraînera une erreur
// car la méthode attend un type 'string' en paramètre.

// print_r($main->getCurrentUser("7"))

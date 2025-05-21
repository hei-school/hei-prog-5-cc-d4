<?php
declare(strict_types=1);
// créer une exception personnalisée mais il n'est pas spécifié
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
        // Problème : USERS est une constante globale, il serait préférable d'utiliser une propriété de classe ou une source de données externe.
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
        // userNotFoundExeption extends Exception donc c'est la même chose
        } catch (Exception $exception) {
            $log->log($exception->getMessage());

            return null;
        }
        // Problème : Attraper une exception pour retourner null n'est pas optimal, il serait mieux de laisser l'exception remonter ou de gérer différemment.
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
                // Problème : On lance une exception alors que getUserById a déjà géré l'absence d'utilisateur et retourné null.
            }
            
            return $user;
        } catch (UserNotFoundException $e) {
            $log->log("Controller, $id user is not found !");

            return "User not found !";

        } catch (Exception $exception) {
            $log->log("Internal serveur error, {$exception->getMessage()}");

            return "Une erreur est survenue !";
        }
        // Problème : La gestion des exceptions est redondante entre UserRepository et Controller.
    }
}

$main = new Controller();
print_r($main->getCurrentUser(7));
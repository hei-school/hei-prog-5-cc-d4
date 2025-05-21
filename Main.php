<?php
declare(strict_types=1);

class UserNotFoundException extends Exception {}

class Logger {
    public function log(string $message) {
        // aucune vérification si l'ecriture dans le fichier error.log réussit ou si le fichier est accessible, ce qui peut causer des erreur 
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
        // Instanciation directe de Logger, ce qui crée un couplage fort et rend les tests unitaire difficile
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
        // Retourner null au lieu de propager l'exception masque les erreurs inattendue
            return null;
        }
    }
}

class Controller {
    public function getCurrentUser(string $id) : ?string 
    {
        // Du meme que celui de getUserById sur l'instanciation directe de logger et userRepository
        $log = new Logger();
        try {
            $repository = new UserRepository();
            $user = $repository->getUserById($id);
        // Verification redondante si user est null, car getUserById retourne déjà null en cas d'échec, ce qui rend cette vérification inutile
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

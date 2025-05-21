<?php

class UserNotFoundException extends Exception {}

class Logger {
    public function log(string $message) {
        file_put_contents('error.log', $message.PHP_EOL, FILE_APPEND);
        // ici le chemin du log est codé en dur
    }
}

const USERS = [
    "1" => "Julien",
    "2" => "Rajerison",
    "3" => "Jul",
    // utilisation d'une constante globale pour stocker les utilisateurs.
];

class UserRepository {
    public function findUser(string $id) {
        return USERS[$id] ?? null;
        // accès direct à la constante globale USERS.
    }

    public function getUserById(string $id) {
        $log = new Logger();
        // instanciation répété de la classe Logger
        try {
            $user = $this->findUser($id);
            if (!$user) {
                throw new UserNotFoundException("User with $id not found !");
                // lancer une exception pour un cas évident (utilisateur non trouvé)
            }

            return $user;
        } catch (UserNotFoundException $exception) {
            $log->log($exception->getMessage());
            // attraper l'exception juste après l'avoir lancée
            
            return null;
        } catch (Exception $exception) {
            $log->log($exception->getMessage());
            //même que l'en haut 

            return null;
        }
    }
}

class Controller {
    public function getCurrentUser(string $id) : ?string 
    {
        $log = new Logger();
        // trop de redondance pour le logger
        try {
            $repository = new UserRepository();
            //  instanciation du repository dans la méthode , pourquoi pas l'injecter
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
            // attraper une exception sans traiter le cas
            // error bilingue ? 
            return "Une erreur est survenue !";
        }
    }
}

$main = new Controller();
print_r($main->getCurrentUser(7));
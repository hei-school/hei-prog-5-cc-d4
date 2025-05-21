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

//Injection du Logger dans UserRepository
class UserRepository {
    private Logger $logger;
    public function __construct(Logger $logger) {
        $this->logger = $logger;
    }

    public function findUser(string $id) {
        return USERS[$id] ?? null;
    }

    // On ne log plus ici, la gestion des erreurs est dans le Controller
    public function getUserById(string $id) {
        $user = $this->findUser($id);
        if (!$user) {
            throw new UserNotFoundException("User with $id not found !");
        }
        return $user;
    }
}

// Injection du Logger et du UserRepository dans Controller
class Controller {
    private Logger $logger;
    private UserRepository $repository;

    public function __construct(Logger $logger, UserRepository $repository) {
        $this->logger = $logger;
        $this->repository = $repository;
    }

    public function getCurrentUser(string $id) : ?string
    {
        try {
            $user = $this->repository->getUserById($id);
            return $user;
        } catch (UserNotFoundException $e) {
            $this->logger->log("Controller, $id user is not found !");
            //  erreur loggée une seule fois ici

            return "User not found !";
        } catch (Exception $exception) {
            $this->logger->log("Internal serveur error, {$exception->getMessage()}");

            return "Une erreur est survenue !";
        }
    }
}

$logger = new Logger();
$repository = new UserRepository($logger);
$main = new Controller($logger, $repository);
// id est devenu un entier alors que la méthode attend une string. on fait  passer "7" au lieu de 7.
print_r($main->getCurrentUser("7"));

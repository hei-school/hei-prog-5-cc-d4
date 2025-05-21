<?php
declare(strict_types=1);


class UserNotFoundException extends Exception {}

class Logger {
    public function log(string $message): void {
        try {
            file_put_contents('error.log', date('Y-m-d H:i:s') . " - " . $message . PHP_EOL, FILE_APPEND);
           
        } catch (Throwable $e) {
     
        }
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

    public function findUser(string $id): ?string {
        return USERS[$id] ?? null;
        
    }

    public function getUserById(string $id): ?string {
        try {
            $user = $this->findUser($id);
            if (!$user) {
                throw new UserNotFoundException("UserRepository::getUserById => User with ID {$id} not found.");
              
            }

            return $user;
        } catch (UserNotFoundException $exception) {
            $this->logger->log($exception->getMessage());
            return null;
        }
    }
}

class Controller {
    private Logger $logger;
    private UserRepository $repository;

    public function __construct() {
        $this->logger = new Logger();
        $this->repository = new UserRepository($this->logger);
      
    }

    public function getCurrentUser(string $id): ?string {
        try {
            $user = $this->repository->getUserById($id);
            if (!$user) {
                $this->logger->log("Controller::getCurrentUser => User ID {$id} not found.");
                
                return "User not found !";
            }

            return $user;
        } catch (Exception $exception) {
            $this->logger->log("Controller::getCurrentUser => Internal server error: {$exception->getMessage()}");
           
            return "Une erreur est survenue !";
        }
    }
}

$main = new Controller();
echo $main->getCurrentUser("7");

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
      $user = $this->findUser($id);
      if (!$user) {
          throw new UserNotFoundException("User with $id not found !");
      }
      return $user;
    }
}

class Controller {
    private UserRepository $repository;

    public function __construct(UserRepository $repository) {
      $this->repository = $repository;
    }

    public function getCurrentUser(string $id) : ?string 
    {
      return $this->repository->getUserById($id);
    }
}

$logger = new Logger();
$repository = new UserRepository($logger);
$main = new Controller($repository, $logger);

try{
  print_r($main->getCurrentUser("7"));
} catch (Exception $e) {
  $logger->log($e->getMessage());
}

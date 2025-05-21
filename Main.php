<?php
declare(strict_types=1);

class UserNotFoundException extends Exception
{
}

class Logger
{
    private $logFile;

    public function __construct($logFile = 'error.log')
    {
        $this->logFile = $logFile;
    }

    public function log(string $message)
    {
        file_put_contents($this->logFile, $message . PHP_EOL, FILE_APPEND);
    }
}

class UserDataStore
{
    private $users;

    public function __construct(array $users = [])
    {
        $this->users = !empty($users) ? $users : [
            "1" => "Julien",
            "2" => "Rajerison",
            "3" => "Jul",
        ];
    }

    public function getUsers(): array
    {
        return $this->users;
    }
}

class UserRepository
{
    private $dataStore;
    private $logger;

    public function __construct(UserDataStore $dataStore, Logger $logger)
    {
        $this->dataStore = $dataStore;
        $this->logger = $logger;
    }

    public function findUser(string $id)
    {
        $users = $this->dataStore->getUsers();
        return $users[$id] ?? null;
    }

    public function getUserById(string $id): string
    {
        $user = $this->findUser($id);
        if (!$user) {
            $errorMessage = "User with ID $id not found!";
            $this->logger->log("Repository: $errorMessage");
            throw new UserNotFoundException($errorMessage);
        }

        return $user;
    }
}

class Controller
{
    private $repository;
    private $logger;

    public function __construct(UserRepository $repository, Logger $logger)
    {
        $this->repository = $repository;
        $this->logger = $logger;
    }
    
    public function getCurrentUser(string $id): string
    {
        try {
            return $this->repository->getUserById($id);
        } catch (UserNotFoundException $e) {
            $this->logger->log("Controller: {$e->getMessage()}");
            return "User not found!";
        } catch (Exception $e) {
            $this->logger->log("Internal server error: {$e->getMessage()}");
            return "An error occurred!";
        }
    }
}

$logger = new Logger();
$dataStore = new UserDataStore();
$repository = new UserRepository($dataStore, $logger);
$controller = new Controller($repository, $logger);

print_r($controller->getCurrentUser("7"));

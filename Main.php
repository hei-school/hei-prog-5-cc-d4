<?php
declare(strict_types=1);

class UserNotFoundException extends Exception {
    protected string $id;

    public function __construct(string $id, string $message = "User not found", int $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->id = $id;
    }

    public function errorMessage(): string {
        return 'Error on line '.$this->getLine().' in '.$this->getFile()
            .': '.$this->getMessage().' User with id '.$this->id.' not found!';
    }
}

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

    public function getUserById(string $id): string {
        $user = $this->findUser($id);
        if (!$user) {
            throw new UserNotFoundException($id);
        }

        return $user;
    }
}

class Controller {
    private Logger $log;
    private UserRepository $repository;

    public function __construct() {
        $this->log = new Logger();
        $this->repository = new UserRepository();
    }

    public function getCurrentUser(string $id): ?string {
        try {
            $repository = new UserRepository();
            $user = $repository->getUserById($id);
            
            return $user;
        } catch (UserNotFoundException $exception) {
            $this->log->log($exception->errorMessage());
            return "User not found!";
        } catch (Exception $exception) {
            $this->log->log("Internal server error: {$exception->getMessage()}");
            return "Une erreur est survenue !";
        }
    }
}

$main = new Controller();
echo $main->getCurrentUser("7");

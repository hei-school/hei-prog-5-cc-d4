<?php

class UserNotFoundException extends Exception
{
}

class Logger
{
    public function log(string $message)
    {
        file_put_contents('error.log', $message . PHP_EOL, FILE_APPEND);
    }
}

const USERS = [
    "1" => "Julien",
    "2" => "Rajerison",
    "3" => "Jul",
];

class UserRepository
{
    public function findUser(string $id)
    {
        return USERS[$id] ?? null;
    }

    public function getUserById(string $id)
    {
        $log = new Logger();
        try {
            $user = $this->findUser($id);
            if (!$user) {
                // Include id string to the exception message
                throw new UserNotFoundException("User with id $id not found !");
            }

            return $user;
        } catch (UserNotFoundException $exception) {
            $log->log($exception->getMessage());

            return null;
        }
    }
}

class Controller
{
    public function getCurrentUser(string $id): ?string
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
            // Use English in logs for better consistency and readability
            $log->log("Internal server error, {$exception->getMessage()}");

            return "An error occurred!";
        }
    }
}

$main = new Controller();
print_r($main->getCurrentUser(7));
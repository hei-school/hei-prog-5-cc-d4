class UserNotFoundException extends Error {
  constructor(message: string) {
    super(message);
    this.name = "UserNotFoundException";
  }
}

const log = (message: string) => console.log(message);
const logError = (message: string) => console.error(`\x1b[31m${message}\x1b[0m`);
const logSuccess = (message: string) => console.log(`\x1b[32m${message}\x1b[0m`);
const logInfo = (message: string) => console.log(`\x1b[34m${message}\x1b[0m`);
const logWarning = (message: string) => console.log(`\x1b[33m${message}\x1b[0m`);
const logDebug = (message: string) => console.log(`\x1b[35m${message}\x1b[0m`);

type User = {
  id: number;
  name: string;
}

const USERS: User[] = [
  { id: 1, name: "John Doe" },
  { id: 2, name: "Jane Smith" },
  { id: 3, name: "Alice Johnson" },
]

class UserRepository {
  private users: User[];

  constructor(users: User[]) {
    this.users = users;
  }

  getUserById(id: number): User | void {
    try {
      const user = this.users.find(user => user.id === id);
      if (!user) {
        throw new UserNotFoundException(`User with ID ${id} not found`);
      }
      return user;
    } catch (error) {
      if (error instanceof UserNotFoundException) {
        logWarning(error.message);
        return;
      } else if (error instanceof TypeError) {
        logError("Type error occurred while finding user");
        return;
      }
    }
  }
}

class Controller {
  getCurrentUser(id: number): User | void {
    try {
      const userRepository = new UserRepository(USERS);
      const user = userRepository.getUserById(id);
      if (user) {
        logSuccess(`User found: ${JSON.stringify(user)}`);
      }
      return user;
    } catch (error) {
      if (error instanceof UserNotFoundException) {
        logError(error.message);
      } else {
        logError("An unexpected error occurred");
      }
    }
  }
}

const main = new Controller();
main.getCurrentUser(4);

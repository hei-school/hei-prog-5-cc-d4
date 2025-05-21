class UserNotFoundExeption extends Error{
    constructor(message: string) {
        super(message)
    }
}


const USERS = {
    '1': 'Julien',
    '2': 'Rajerison',
    '3': 'Jul'
}

class UserRepository {
    findUser(id: string): void | string | null{
        USERS[id] ?? null
    }

    getUserById(id: string){
        try {
            const user = this.findUser(id)
            if(!user) {
                throw new UserNotFoundExeption(`User with ${id} not found`)
            }
            return user
        } 
        catch (error) {
            console.log('error',error);
            return 
        }
    }
}

class Controller {

    private userRepository: UserRepository
    getCurrentUser(id:string): string | void{
        try {
            const user = this.userRepository.getUserById(id)
            if(!user){
                throw new UserNotFoundExeption(`Controller, ${id} user is not found !`)
            }
            return user
        } catch (error) {
            console.log('Internal server error',error);
            
        }
    }
}

const main = new Controller()

console.log(main.getCurrentUser('7'));

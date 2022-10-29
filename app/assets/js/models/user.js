export class User{
    id = 0;
    email = '';
    password = '';
    constructor(id, email, password){
        this.id = id;
        this.email = email;
        this.password = password;
    }

    static toJson(user){
        return {
            id: user.id,
            email: user.email,
            password: user.password
        }

    }
    //method to from json
    static fromJSON(json){
        return new User(json.id, json.email, json.password);
    }
}
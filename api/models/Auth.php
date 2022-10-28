<?php 
    class Auth{
        
        private $conn;
        private $table = 'users';
        public $id;
        public $first_name;
        public $last_name;
        public $phone;
        public $type; // 0 = admin
        public $password;
        public $email;
        public $created_at;
        public $message;
        
        public function __construct($db){
            $this->conn = $db;
        }


        public function getUserById($id){
            $query = 'SELECT * FROM '.$this->table.' WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $this->id = $row['id'];
                $this->first_name = $row['first_name'];
                $this->last_name = $row['last_name'];
                $this->phone = $row['phone'];
                $this->type = $row['type'];
                $this->email = $row['email'];
                $this->created_at = $row['created_at'];
                $stmt->close();
                return true;
            }
            $stmt->close();
            $this->message = array('message' => 'user not found', 'status' => 404);
            return false;
        }
        
        public function login(){
            $query = 'SELECT * FROM ' . $this->table . ' WHERE email = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $this->email);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $stmt->close();
                if(password_verify(trim($this->password), $row['password'])){
                    $this->id = $row['id'];
                    $this->email = $row['email'];
                    $this->created_at = $row['created_at'];
                    $this->first_name = $row['first_name'];
                    $this->last_name = $row['last_name'];
                    $this->phone = $row['phone'];
                    $this->type = $row['type'];
                    $this->message = array('message' => 'Login success', 'status' => 200); 
                    return true;
                }else{
                    $this->message = array('message' => 'invalid password', 'status' => 401); 
                    return false;
                }
            }
            $stmt->close();
            $this->message = array('message' => 'User not found', 'status' => 404);  
            return false;
        }

        public function register()
        {
            $query = 'SELECT * FROM ' . $this->table . ' WHERE email = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $this->email);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                $stmt->close();
                $this->message = array('message' => 'User already exists', 'status' => 409); 
                return false;
            }
            $query = 'INSERT INTO ' . $this->table . ' (first_name, last_name, phone, type, password, email) VALUES (?, ?, ?, ?, ?, ?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sssiss', $this->first_name, $this->last_name, $this->phone, $this->type, $this->password, $this->email);
            if($stmt->execute()){
                $this->id = mysqli_insert_id($this->conn);
                $this->message = array('message' => 'Registration success !', 'status' => 201); 
                $this->created_at = date('Y-m-d H:i:s');
                return true;
            }else{
                $this->message = array('message' => 'Registration failed !', 'status' => 500); 
                return false;
            }
        }
    }

?>
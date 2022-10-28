<?php 

    class AuthController{
        private $auth;
        public function __construct(Auth $auth){
            $this->auth = $auth;
        }
        

        public function login(string $method):void{
            if($method == 'POST'){
                $data = json_decode(file_get_contents("php://input"));
                $this->auth->email = $data->email;
                $this->auth->password = $data->password;
                if($this->auth->login()){
                    echo json_encode(array(
                        'response' => $this->auth->message,
                        'user' => array(
                            'id' => $this->auth->id,
                            'email' => $this->auth->email,
                            'first_name' => $this->auth->first_name,
                            'last_name' => $this->auth->last_name,
                            'phone' => $this->auth->phone,
                            'type' => $this->auth->type,
                            'created_at' => $this->auth->created_at
                        )
                    ));
                }else{
                    http_response_code($this->auth->message['status']);
                    echo json_encode(array("response"=>$this->auth->message));
                }
            }else{
                http_response_code(404);
                echo json_encode(array('message' => 'Page not found'));
            }    
        }

        public function register(string $method):void{
            if($method == 'POST'){
                $data = json_decode(file_get_contents("php://input"));
                $this->auth->email = $data->email;
                $this->auth->password = password_hash($data->password, PASSWORD_DEFAULT);;
                $this->auth->first_name = $data->first_name;
                $this->auth->last_name = $data->last_name;
                $this->auth->phone = $data->phone;
                $this->auth->type = $data->type;
                if($this->auth->register()){
                    echo json_encode(array(
                        'response' => $this->auth->message,
                        'user' => array(
                            'id' => $this->auth->id,
                            'email' => $this->auth->email,
                            'first_name' => $this->auth->first_name,
                            'last_name' => $this->auth->last_name,
                            'phone' => $this->auth->phone,
                            'type' => $this->auth->type,
                            'created_at' => $this->auth->created_at
                        )
                    ));
                }else{
                    http_response_code($this->auth->message['status']);
                    echo json_encode(array("response"=>$this->auth->message));
                }
            }else{
                http_response_code(404);
                echo json_encode(array('message' => 'Page not found'));
            }    
        }
    }
?>
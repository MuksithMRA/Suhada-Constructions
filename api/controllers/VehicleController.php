<?php

class VehicleController{
    private $vehicle;

    public function __construct($vehicle) {
        $this->vehicle = $vehicle;
    }

    public function vehicle($method , $vehicle_no)
    {
        switch ($method) {
            case 'GET':
                if($vehicle_no == null){
                    $this->getVehicles();
                }else{
                    $this->getVehicle($vehicle_no);
                }
                break;
            case 'POST':
                $this->createVehicle();
                break;
            case 'PUT':
                $this->updateVehicle();
                break;
            case 'DELETE':
                if($vehicle_no != null){
                    $this->deleteVehicle($vehicle_no);
                    break;
                }   
            default:
                http_response_code(404);
                echo json_encode(array('message' => 'Page not found'));
                break;
        }
    }

    //get single vehicle
    public function getVehicle($vehicle_no)
    {
        $vehicleData = $this->vehicle->getVehicle($vehicle_no);
        if($vehicleData != false){
            echo json_encode(array("vehicle"=>$vehicleData , "response"=>$this->vehicle->message));
        }else{
            http_response_code(404);
            echo json_encode(array('response' => $this->vehicle->message));
        }
    }

    //update vehicle
    public function updateVehicle(){
        $data = json_decode(file_get_contents("php://input"));
        if($data->vehicle_no == null){
            http_response_code(404);
            echo json_encode(array('response' => 'Vehicle no is required'));
            die;
        }
        $this->vehicle->getVehicle($data->vehicle_no);
        $this->vehicle->vehicle_no = $data->vehicle_no;
        if(isset($data->vehicle_class)){
            $this->vehicle->vehicle_class = $data->vehicle_class;
        }
        if(isset($data->vehicle_owner)){
            $this->vehicle->vehicle_owner = $data->vehicle_owner;
        }
        if(isset($data->model)){
            $this->vehicle->model = $data->model;
        }
        if(isset($data->license_expiry)){
            $this->vehicle->license_expiry = $data->license_expiry;
        }
        if(isset($data->license_issued)){
            $this->vehicle->license_issued = $data->license_issued;
        }
        if(isset($data->license_number)){
            $this->vehicle->license_number = $data->license_number;
        }
        if(isset($data->doc)){
            $this->vehicle->doc = $data->doc;
        }

        $this->vehicle->updateVehicle($data->vehicle_no);
        http_response_code($this->vehicle->message["status"]);
        echo json_encode(array('response' => $this->vehicle->message));
    }

    //get all vehicles
    public function getVehicles()
    {
        $vehicleData = $this->vehicle->getVehicles();
        if($vehicleData != false){
            echo json_encode(array("vehicles"=>$vehicleData , "response"=>$this->vehicle->message));
        }else{
            http_response_code(404);
            echo json_encode(array('response' => $this->vehicle->message));
        }

    }

    //create vehicle
    public function createVehicle()
    {
        $data = json_decode(file_get_contents("php://input"));
        if($data->vehicle_class == null){
            http_response_code(404);
            echo json_encode(array('response' => 'Vehicle data is required'));
            die;
        }
        $this->vehicle->vehicle_no = $data->vehicle_no;
        $this->vehicle->vehicle_class = $data->vehicle_class;
        $this->vehicle->vehicle_owner = $data->vehicle_owner;
        $this->vehicle->model = $data->model;
        $this->vehicle->license_expiry = $data->license_expiry;
        $this->vehicle->license_issued = $data->license_issued;
        $this->vehicle->license_number = $data->license_number;
        $this->vehicle->doc = $data->doc;

        $vehicleExist = $this->vehicle->getVehicle($data->vehicle_no);
        
        if($vehicleExist != false){
           
            http_response_code(409);
            echo json_encode(array("response"=>array('message' => 'Vehicle already exists', 'status' => 409)));
            die;
        }
        if($this->vehicle->createVehicle()){
            echo json_encode(array(
                'response' => $this->vehicle->message,
                'vehicle' => $this->vehicle->toArray()
            ));
        }else{
            http_response_code($this->vehicle->message['status']);
            echo json_encode(array("response"=>$this->vehicle->message));
        }
    }

    //delete vehicle
    public function deleteVehicle($vehicle_no)
    {
        if($this->vehicle->deleteVehicle($vehicle_no)){
            http_response_code(200);
            echo json_encode(array('message' => 'Vehicle deleted successfully', 'status' => 200));
        }else{
            http_response_code($this->vehicle->message['status']);
            echo json_encode(array('message' => $this->vehicle->message['message'], 'status' => $this->vehicle->message['status']));
        }
    }
}

?>
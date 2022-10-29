<?php

class EmployeeController{
    private $employee;

    public function __construct($employee) {
        $this->employee = $employee;
    }

    public function employee($method , $id)
    {
        switch ($method) {
            case 'GET':
                if($id == null){
                    $this->getEmployees();
                }else{
                    $this->getEmployee($id);
                }
                break;
            case 'POST':
                $this->createEmployee();
                break;
            case 'PUT':
                $this->updateEmployee();
                break;
            case 'DELETE':
                if($id != null){
                    $this->deleteEmployee($id);
                    break;
                }   
            default:
                http_response_code(404);
                echo json_encode(array('message' => 'Page not found'));
                break;
        }
    }

    //get single employee
    public function getEmployee($id)
    {
        $employeeData = $this->employee->getEmployee($id);
        if($employeeData != false){
            echo json_encode(array($employeeData , "response"=>$this->employee->message));
        }else{
            http_response_code(404);
            echo json_encode(array('response' => $this->employee->message));
        }
    }

    //update employee
    public function updateEmployee(){
        $data = json_decode(file_get_contents("php://input"));
        if($data->id == null){
            http_response_code(404);
            echo json_encode(array('response' => 'Employee id is required'));
            die;
        }
        $this->employee->getEmployee($data->id);
        $this->employee->id = $data->id;
        if(isset($data->name)){
            $this->employee->name = $data->name;
        }
        if(isset($data->designation)){
            $this->employee->designation = $data->designation;
        }
        if(isset($data->contact)){
            $this->employee->contact = $data->contact;
        }
        if(isset($data->doc)){
            $this->employee->doc = $data->doc;
        }

        if($this->employee->updateEmployee($data->id)){
            http_response_code(200);
            echo json_encode(array(
                'message' => 'Employee updated successfully',
                'employee'=>$this->employee->toArray(), 
                'status' => 200
            ));
        }else{
            http_response_code(404);
            echo json_encode(array('message' => 'Employee not updated', 'status' => 404));
        }
    }

    //get all employees
    public function getEmployees()
    {
        $employeeData = $this->employee->getEmployees();
        if($employeeData != false){
            echo json_encode(array($employeeData , "response"=>$this->employee->message));
        }else{
            http_response_code(404);
            echo json_encode(array('response' => $this->employee->message));
        }

    }

    //create employee
    public function createEmployee()
    {
        $data = json_decode(file_get_contents("php://input"));
        if($data->name == null){
            http_response_code(404);
            echo json_encode(array('response' => 'Employee data is required'));
            die;
        }
        $this->employee->name = $data->name;
        $this->employee->designation = $data->designation;
        $this->employee->contact = $data->contact;
        $this->employee->doc = $data->doc;
        if($this->employee->createEmployee()){
            echo json_encode(array(
                'response' => $this->employee->message,
                'employee' => $this->employee->toArray()
            ));
        }else{
            http_response_code($this->employee->message['status']);
            echo json_encode(array("response"=>$this->employee->message));
        }
    }

    //delete employee
    public function deleteEmployee($id)
    {
        if($this->employee->deleteEmployee($id)){
            http_response_code(200);
            echo json_encode(array('message' => 'Employee deleted successfully', 'status' => 200));
        }else{
            http_response_code($this->employee->message["status"]);
            echo json_encode(array('message' => $this->employee->message["message"], 'status' => $this->employee->message["status"]));
        }
    }
}

?>
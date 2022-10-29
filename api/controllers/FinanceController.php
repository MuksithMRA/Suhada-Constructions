<?php

class FinanceController{
    private $finance;

    public function __construct($finance) {
        $this->finance = $finance;
    }

    public function finance($method , $id)
    {
        switch ($method) {
            case 'GET':
                if($id == null){
                    $this->getFinances();
                }else{
                    $this->getFinance($id);
                }
                break;
            case 'POST':
                $this->createFinance();
                break;
            case 'PUT':
                $this->updateFinance();
                break;
            case 'DELETE':
                if($id != null){
                    $this->deleteFinance($id);
                    break;
                }   
            default:
                http_response_code(404);
                echo json_encode(array('message' => 'Page not found'));
                break;
        }
    }

    //get single finance
    public function getFinance($id)
    {
        $financeData = $this->finance->getFinance($id);
        if($financeData != false){
            echo json_encode(array($financeData , "response"=>$this->finance->message));
        }else{
            http_response_code(404);
            echo json_encode(array('response' => $this->finance->message));
        }
    }

    //update finance
    public function updateFinance(){
        $data = json_decode(file_get_contents("php://input"));
        if($data->id == null){
            http_response_code(404);
            echo json_encode(array('response' => 'Finance id is required'));
            die;
        }
        $this->finance->getFinance($data->id);
        $this->finance->id = $data->id;
        if(isset($data->expense_name)){
            $this->finance->expense_name = $data->expense_name;
        }
        if(isset($data->expense_type)){
            $this->finance->expense_type = $data->expense_type;
        }
        if(isset($data->amount)){
            $this->finance->amount = $data->amount;
        }
        if(isset($data->doc)){
            $this->finance->doc = $data->doc;
        }

        if($this->finance->updateFinance($data->id)){
            http_response_code(200);
            echo json_encode(array(
                'message' => 'Finance updated successfully',
                'finance'=>$this->finance->toArray(), 
                'status' => 200
            ));
        }else{
            http_response_code(404);
            echo json_encode(array('message' => 'Finance not updated', 'status' => 404));
        }
    }

    //get all finances
    public function getFinances()
    {
        $financeData = $this->finance->getFinances();
        if($financeData != false){
            echo json_encode(array($financeData , "response"=>$this->finance->message));
        }else{
            http_response_code(404);
            echo json_encode(array('response' => $this->finance->message));
        }

    }

    //create finance
    public function createFinance()
    {
        $data = json_decode(file_get_contents("php://input"));
        if($data->expense_name == null){
            http_response_code(404);
            echo json_encode(array('response' => 'Finance data is required'));
            die;
        }
        $this->finance->expense_name = $data->expense_name;
        $this->finance->expense_type = $data->expense_type;
        $this->finance->amount = $data->amount;
        $this->finance->doc = $data->doc;
        if($this->finance->createFinance()){
            echo json_encode(array(
                'response' => $this->finance->message,
                'finance' => $this->finance->toArray()
            ));
        }else{
            http_response_code($this->finance->message['status']);
            echo json_encode(array("response"=>$this->finance->message));
        }
    }

    //delete finance
    public function deleteFinance($id)
    {
        if($this->finance->deleteFinance($id)){
            http_response_code(200);
            echo json_encode(array('message' => 'Finance deleted successfully', 'status' => 200));
        }else{
            http_response_code($this->finance->message["status"]);
            echo json_encode(array('message' => $this->finance->message["message"], 'status' => $this->finance->message["status"]));
        }
    }
}

?>
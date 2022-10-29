<?php 

    class DashboardController{
        private $dashboard;
        public function __construct(Dashboard $dashboard){
            $this->dashboard = $dashboard;
        }
        

        public function getDataCount(string $method):void{
            if($method == 'GET'){
                $datacount = $this->dashboard->getTableRowCounts();
                if($datacount != false){
                    echo json_encode(array("datacount"=>$datacount , "response"=>$this->dashboard->message));
                }else{
                    http_response_code($this->dashboard->message['status']);
                    echo json_encode(array("response"=>$this->dashboard->message));
                }
            }else{
                http_response_code(404);
                echo json_encode(array('message' => 'Page not found'));
            }    
        }

        
    }
?>
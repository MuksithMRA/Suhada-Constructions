<?php 
    class Dashboard{
        
        private $conn;
        private $db_name = 'suhada_constructions';
        public $message;
      
        
        public function __construct($db){
            $this->conn = $db;
        }


        public function getTableRowCounts()
        {
            $query = "SELECT table_name, table_rows FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $this->db_name);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                
                while($row = $result->fetch_assoc()){
                    $dataSet[] = $row;
                }
                $stmt->close();
                $this->message = array('message' => 'data found', 'status' => 200);
                return $dataSet;
            }else{
                $stmt->close();
                $this->message = array('message' => 'no data found', 'status' => 404);
                return false;
            }
        }
    }

?>
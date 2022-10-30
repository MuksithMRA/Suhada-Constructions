<?php 
    class Dashboard{
        
        private $conn;
        private $db_name = 'suhada_constructions';
        private $tables = array('employees','projects','finance');
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

        public function getIncrements()
        {
           $query = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?";
           $dataSet = [];
           foreach ($this->tables as $table ) {
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param('ss', $this->db_name, $table);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows > 0){
                     $dataSet[] = array($table => $result->fetch_assoc());
                }else{
                     $stmt->close();
                     $this->message = array('message' => 'no data found', 'status' => 404);
                     return false;
                }
           }
              $stmt->close();   
              return $dataSet;
        }
    }

?>
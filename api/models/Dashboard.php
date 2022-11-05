<?php 
    class Dashboard{
        
        private $conn;
        private $db_name = 'suhada_constructions';
        private $tables = array('employees','projects','finance','vehicles');
        public $message;
      
        
        public function __construct($db){
            $this->conn = $db;
        }


        public function getTableRowCounts()
        {
            $datacount = [];
            $data = array();
            for ($i=0; $i < count($this->tables); $i++) { 
                $query = "SELECT COUNT(*) FROM " . $this->tables[$i];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                $data += [$this->tables[$i]=>$result->field_count]; 
               
            }
         
            if(count($data) > 0){
                $stmt->close();
                $this->message = array('message' => 'data found', 'status' => 200);
                return $data;
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
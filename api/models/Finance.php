<?php
    class Finance{

        // DB stuff
        private $conn;
        private $table = 'finance';
        public $message = array();

        // Finance Properties
        public $id;
        public $expense_name;
        public $expense_type;
        public $amount;
        public $doc;
        public $created;

        // Constructor with DB
        public function __construct($db){
            $this->conn = $db;
        }

        // Get Finances
        public function getFinances(){
                $query = 'SELECT * FROM '.$this->table;
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows > 0){
                    $finances = array();
                    while($row = $result->fetch_assoc()){
                        $this->id = $row['id'];
                        $this->expense_name = $row['expense_name'];
                        $this->expense_type = $row['expense_type'];
                        $this->amount = $row['amount'];
                        $this->doc = $row['doc'];
                        $this->created = $row['created'];
                        $finance = $this->toArray();
                        array_push($finances, $finance);
                    }
                    $stmt->close();
                    
                    $this->message = array('message' => 'success', 'status' => 200);
                    return $finance;
                }
                $stmt->close();
                $this->message = array('message' => 'Finances not found', 'status' => 404);
                return false;     
        }

        // Create Finance
        public function createFinance(){
            $query = 'INSERT INTO '.$this->table.'(expense_name,expense_type,amount,doc) VALUES (?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ssds', $this->expense_name, $this->expense_type, $this->amount,$this->doc);
            if($stmt->execute()){
                $this->id = $stmt->insert_id;
                $this->created = date('Y-m-d H:i:s');
                $stmt->close();
                $this->message = array('message' => 'Finance Created Successfully !', 'status' => 201);
                return true;
            }
            $stmt->close();
            $this->message = array('message' => 'Something went wrong', 'status' => 500);
            return false;
        }

        //get single finance
        public function getFinance($id){
            $query = 'SELECT * FROM '.$this->table.' WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $stmt->close();
                $this->id = $row['id'];
                $this->expense_name = $row['expense_name'];
                $this->expense_type = $row['expense_type'];
                $this->amount = $row['amount'];
                $this->doc = $row['doc'];
                $this->created = $row['created'];
                $this->message = array('message' => 'success', 'status' => 200);
                return $this->toArray();
            }
            $stmt->close();
            $this->message = array('message' => 'finance not found', 'status' => 404);
            return false;
        }


        // Update Finance
        public function updateFinance($id){
            $query = 'UPDATE '.$this->table.' SET expense_name = ?, expense_type = ?, amount = ?, doc = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ssdsi', $this->expense_name, $this->expense_type, $this->amount, $this->doc, $id);
            if($stmt->execute()){
                $stmt->close();
                $this->message = array('message' => 'Finance Updated Successfully !', 'status' => 200);
                return true;
            }
            $stmt->close();
            $this->message = array('message' => 'Something went wrong', 'status' => 500);
            return false;
        }

        // Delete Finance
        public function deleteFinance($id){
            if($this->getFinance($id) == false){
                $this->message = array('message' => 'No finance associated with this id', 'status' => 404);
                return false;
            }else{
                $query = 'DELETE FROM '.$this->table.' WHERE id = ?';
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param('i', $id);
                
                if($stmt->execute()){
                    $stmt->close();
                    $this->message = array('message' => 'Finance Deleted Successfully !', 'status' => 200);
                    return true;
                }
                $stmt->close();
                $this->message = array('message' => 'Something went wrong', 'status' => 500);
                return false;
            }
        }

        // Convert to array
        public function toArray(){
            return array(
                'id' => $this->id,
                'expense_name' => $this->expense_name,
                'expense_type' => $this->expense_type,
                'amount' =>  $this->amount,
                'doc' => $this->doc,
                'created' => $this->created
            );
        }
    }

?>
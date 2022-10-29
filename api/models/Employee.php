<?php
    class Employee{

        // DB stuff
        private $conn;
        private $table = 'employees';
        public $message = array();

        // Employee Properties
        public $id;
        public $name;
        public $designation;
        public $contact;
        public $doc;
        public $created;

        // Constructor with DB
        public function __construct($db){
            $this->conn = $db;
        }

        // Get Employees
        public function getEmployees(){
                $query = 'SELECT * FROM '.$this->table;
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows > 0){
                    $employees = array();
                    while($row = $result->fetch_assoc()){
                        $this->id = $row['id'];
                        $this->name = $row['name'];
                        $this->designation = $row['designation'];
                        $this->contact = $row['contact'];
                        $this->doc = $row['doc'];
                        $this->created = $row['created'];
                        $employee = $this->toArray();
                        array_push($employees, $employee);
                    }
                    $stmt->close();
                    
                    $this->message = array('message' => 'success', 'status' => 200);
                    return $employee;
                }
                $stmt->close();
                $this->message = array('message' => 'Employees not found', 'status' => 404);
                return false;     
        }

        // Create Employee
        public function createEmployee(){
            $query = 'INSERT INTO '.$this->table.'(name, designation,contact,doc) VALUES (?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ssss', $this->name, $this->designation, $this->contact,$this->doc);
            if($stmt->execute()){
                $this->id = $stmt->insert_id;
                $this->created = date('Y-m-d H:i:s');
                $stmt->close();
                $this->message = array('message' => 'Employee Created Successfully !', 'status' => 201);
                return true;
            }
            $stmt->close();
            $this->message = array('message' => 'Something went wrong', 'status' => 500);
            return false;
        }

        //get single employee
        public function getEmployee($id){
            $query = 'SELECT * FROM '.$this->table.' WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $stmt->close();
                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->designation = $row['designation'];
                $this->contact = $row['contact'];
                $this->doc = $row['doc'];
                $this->created = $row['created'];
                $this->message = array('message' => 'success', 'status' => 200);
                return $this->toArray();
            }
            $stmt->close();
            $this->message = array('message' => 'employee not found', 'status' => 404);
            return false;
        }


        // Update Employee
        public function updateEmployee($id){
            $query = 'UPDATE '.$this->table.' SET name = ?, designation = ?, contact = ?, doc = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ssssi', $this->name, $this->designation, $this->contact, $this->doc, $id);
            if($stmt->execute()){
                $stmt->close();
                $this->message = array('message' => 'Employee Updated Successfully !', 'status' => 200);
                return true;
            }
            $stmt->close();
            $this->message = array('message' => 'Something went wrong', 'status' => 500);
            return false;
        }

        // Delete Employee
        public function deleteEmployee($id){
            if($this->getEmployee($id) == false){
                $this->message = array('message' => 'No employee associated with this id', 'status' => 404);
                return false;
            }
            $query = 'DELETE FROM '.$this->table.' WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
            if($stmt->execute()){
                $stmt->close();
                $this->message = array('message' => 'Employee Deleted Successfully !', 'status' => 200);
                return true;
            }
            $stmt->close();
            $this->message = array('message' => 'Something went wrong', 'status' => 500);
            return false;
        }

        // Convert to array
        public function toArray(){
            return array(
                'id' => $this->id,
                'name' => $this->name,
                'designation' => $this->designation,
                'contact' => $this->contact,
                'doc' => $this->doc,
                'created' => $this->created
            );
        }
    }

?>
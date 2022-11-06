<?php
    class Vehicle{

        // DB stuff
        private $conn;
        private $table = 'vehicles';
        public $message = array();

        // Vehicle Properties
        public $vehicle_no;
        public $vehicle_class;
        public $vehicle_owner;
        public $model;
        public $license_issued;
        public $license_expiry;
        public $license_number;
        public $doc;
        public $created;

        // Constructor with DB
        public function __construct($db){
            $this->conn = $db;
        }

        // Get Vehicles
        public function getVehicles(){
                $query = 'SELECT * FROM '.$this->table;
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows > 0){
                    $vehicles = [];
                    while($row = $result->fetch_assoc()){
                        $this->vehicle_no = $row['vehicle_no'];
                        $this->vehicle_class = $row['vehicle_class'];
                        $this->vehicle_owner = $row['vehicle_owner'];
                        $this->model = $row['model'];
                        $this->license_expiry = $row['license_expiry'];
                        $this->license_issued = $row['license_issued'];
                        $this->license_number = $row['license_number'];
                        $this->doc = $row['doc'];
                        $this->created = $row['created'];
                        $vehicle = $this->toArray();
                        $vehicles[] =  $vehicle;
                    }
                    $stmt->close();
                    
                    $this->message = array('message' => 'success', 'status' => 200);
                    return $vehicles;
                }
                $stmt->close();
                $this->message = array('message' => 'Vehicles not found', 'status' => 404);
                return false;     
        }

        // Create Vehicle
        public function createVehicle(){
            $query = 'INSERT INTO '.$this->table.'(vehicle_no,vehicle_class,vehicle_owner,model,license_expiry,license_issued,license_number,doc) VALUES (?,?,?,?,?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ssssssis',
                $this->vehicle_no,
                $this->vehicle_class,
                $this->vehicle_owner,
                $this->model,
                $this->license_expiry,
                $this->license_issued,
                $this->license_number,
                $this->doc
            );
            if($stmt->execute()){
                $this->created = date('Y-m-d H:i:s');
                $stmt->close();
                $this->message = array('message' => 'Vehicle Created Successfully !', 'status' => 201);
                return true;
            }
            $stmt->close();
            $this->message = array('message' => 'Something went wrong', 'status' => 500);
            return false;
        }

        //get single vehicle
        public function getVehicle($vehicle_no){
            $query = 'SELECT * FROM '.$this->table.' WHERE vehicle_no = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $vehicle_no);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $stmt->close();
                $this->vehicle_no = $row['vehicle_no'];
                $this->vehicle_class = $row['vehicle_class'];
                $this->vehicle_owner = $row['vehicle_owner'];
                $this->model = $row['model'];
                $this->license_expiry = $row['license_expiry'];
                $this->license_issued = $row['license_issued'];
                $this->license_number = $row['license_number'];
                $this->doc = $row['doc'];
                $this->created = $row['created'];
                $this->message = array('message' => 'success', 'status' => 200);
                return $this->toArray();
            }
            $stmt->close();
            $this->message = array('message' => 'vehicle not found', 'status' => 404);
            return false;
        }


        // Update Vehicle
        public function updateVehicle($vehicle_no){
            $query = 'UPDATE '.$this->table.' SET vehicle_class = ?, vehicle_owner = ?, model = ?, license_expiry = ?, license_issued = ?, license_number = ? , doc = ? WHERE vehicle_no = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sssssisi',
             $this->vehicle_class, 
             $this->vehicle_owner, 
             $this->model, 
             $this->license_expiry,
             $this->license_issued,
             $this->license_number,
             $this->doc, 
             $vehicle_no
            );
            if($stmt->execute()){
                $stmt->close();
                $this->message = array('message' => 'Vehicle Updated Successfully !', 'status' => 200);
                return true;
            }
            $stmt->close();
            $this->message = array('message' => 'Something went wrong', 'status' => 500);
            return false;
        }

        // Delete Vehicle
        public function deleteVehicle($vehicle_no){
            if($this->getVehicle($vehicle_no) == false){
                $this->message = array('message' => 'No vehicle associated with this id', 'status' => 404);
                return false;
            }
            $query = 'DELETE FROM '.$this->table.' WHERE vehicle_no = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $vehicle_no);
            
            if($stmt->execute()){
                $stmt->close();
                $this->message = array('message' => 'Vehicle Deleted Successfully !', 'status' => 200);
                return true;
            }
            $stmt->close();
            $this->message = array('message' => 'Something went wrong', 'status' => 500);
            return false;
        }

        // Convert to array
        public function toArray(){
            return array(
                'vehicle_no' => $this->vehicle_no,
                'vehicle_class' => $this->vehicle_class,
                'vehicle_owner' => $this->vehicle_owner,
                'model' => $this->model,
                'license_expiry' => $this->license_expiry,
                'license_issued' => $this->license_issued,
                'license_number' => $this->license_number,
                'doc' => $this->doc,
                'created' => $this->created
            );
        }
    }

?>
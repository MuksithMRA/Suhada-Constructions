<?php
    class Project{

        // DB stuff
        private $conn;
        private $table = 'projects';
        public $message = array();

        // Project Properties
        public $id;
        public $project_name;
        public $category;
        public $location;
        public $doc;
        public $created;

        // Constructor with DB
        public function __construct($db){
            $this->conn = $db;
        }

        // Get Projects
        public function getProjects(){
                $query = 'SELECT * FROM '.$this->table;
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows > 0){
                    $projects = [];
                    while($row = $result->fetch_assoc()){
                        $this->id = $row['id'];
                        $this->project_name = $row['project_name'];
                        $this->category = $row['category'];
                        $this->location = $row['location'];
                        $this->doc = $row['doc'];
                        $this->created = $row['created'];
                        $project = $this->toArray();
                        $projects[]= $project;
                    }
                    $stmt->close();
                    
                    $this->message = array('message' => 'success', 'status' => 200);
                    return $projects;
                }
                $stmt->close();
                $this->message = array('message' => 'Projects not found', 'status' => 404);
                return false;     
        }

        // Create Project
        public function createProject(){
            $query = 'INSERT INTO '.$this->table.'(project_name,category,location,doc) VALUES (?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ssss', $this->project_name, $this->category, $this->location,$this->doc);
            if($stmt->execute()){
                $this->id = $stmt->insert_id;
                $this->created = date('Y-m-d H:i:s');
                $stmt->close();
                $this->message = array('message' => 'Project Created Successfully !', 'status' => 201);
                return true;
            }
            $stmt->close();
            $this->message = array('message' => 'Something went wrong', 'status' => 500);
            return false;
        }

        //get single project
        public function getProject($id){
            $query = 'SELECT * FROM '.$this->table.' WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $stmt->close();
                $this->id = $row['id'];
                $this->project_name = $row['project_name'];
                $this->category = $row['category'];
                $this->location = $row['location'];
                $this->doc = $row['doc'];
                $this->created = $row['created'];
                $this->message = array('message' => 'success', 'status' => 200);
                return $this->toArray();
            }
            $stmt->close();
            $this->message = array('message' => 'project not found', 'status' => 404);
            return false;
        }


        // Update Project
        public function updateProject($id){
            $query = 'UPDATE '.$this->table.' SET project_name = ?, category = ?, location = ?, doc = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ssssi', $this->project_name, $this->category, $this->location, $this->doc, $id);
            if($stmt->execute()){
                $stmt->close();
                $this->message = array('message' => 'Project Updated Successfully !', 'status' => 200);
                return true;
            }
            $stmt->close();
            $this->message = array('message' => 'Something went wrong', 'status' => 500);
            return false;
        }

        // Delete Project
        public function deleteProject($id){
            if($this->getProject($id) == false){
                $this->message = array('message' => 'No project associated with this id', 'status' => 404);
                return false;
            }
            $query = 'DELETE FROM '.$this->table.' WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
            
            if($stmt->execute()){
                $stmt->close();
                $this->message = array('message' => 'Project Deleted Successfully !', 'status' => 200);
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
                'project_name' => $this->project_name,
                'category' => $this->category,
                'location' => $this->location,
                'doc' => $this->doc,
                'created' => $this->created
            );
        }
    }

?>
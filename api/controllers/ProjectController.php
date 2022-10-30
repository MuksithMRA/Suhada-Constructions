<?php

class ProjectController{
    private $project;

    public function __construct($project) {
        $this->project = $project;
    }

    public function project($method , $id)
    {
        switch ($method) {
            case 'GET':
                if($id == null){
                    $this->getProjects();
                }else{
                    $this->getProject($id);
                }
                break;
            case 'POST':
                $this->createProject();
                break;
            case 'PUT':
                $this->updateProject();
                break;
            case 'DELETE':
                if($id != null){
                    $this->deleteProject($id);
                    break;
                }   
            default:
                http_response_code(404);
                echo json_encode(array('message' => 'Page not found'));
                break;
        }
    }

    //get single project
    public function getProject($id)
    {
        $projectData = $this->project->getProject($id);
        if($projectData != false){
            echo json_encode(array($projectData , "response"=>$this->project->message));
        }else{
            http_response_code(404);
            echo json_encode(array('response' => $this->project->message));
        }
    }

    //update project
    public function updateProject(){
        $data = json_decode(file_get_contents("php://input"));
        if(!isset($data->id)){
            http_response_code(404);
            echo json_encode(array('response' => 'Project id is required'));
            die;
        }
        $this->project->getProject($data->id);
        $this->project->id = $data->id;
        if(isset($data->project_name)){
            $this->project->project_name = $data->project_name;
        }
        if(isset($data->category)){
            $this->project->category = $data->category;
        }
        if(isset($data->location)){
            $this->project->location = $data->location;
        }
        if(isset($data->doc)){
            $this->project->doc = $data->doc;
        }

        $this->project->updateProject($data->id);
        http_response_code($this->project->message["status"]);
        echo json_encode(array('response' => $this->project->message));
        
    }

    //get all projects
    public function getProjects()
    {
        $projectData = $this->project->getProjects();
        if($projectData != false){
            echo json_encode(array("projects"=>$projectData , "response"=>$this->project->message));
        }else{
            http_response_code(404);
            echo json_encode(array('response' => $this->project->message));
        }

    }

    //create project
    public function createProject()
    {
        $data = json_decode(file_get_contents("php://input"));
        if($data->project_name == null){
            http_response_code(404);
            echo json_encode(array('response' => 'Project data is required'));
            die;
        }
        $this->project->project_name = $data->project_name;
        $this->project->category = $data->category;
        $this->project->location = $data->location;
        $this->project->doc = $data->doc;
        if($this->project->createProject()){
            echo json_encode(array(
                'response' => $this->project->message,
                'project' => $this->project->toArray()
            ));
        }else{
            http_response_code($this->project->message['status']);
            echo json_encode(array("response"=>$this->project->message));
        }
    }

    //delete project
    public function deleteProject($id)
    {
        if($this->project->deleteProject($id)){
            http_response_code(200);
            echo json_encode(array('message' => 'Project deleted successfully', 'status' => 200));
        }else{
            http_response_code($this->project->message["status"]);
            echo json_encode(array('message' => $this->project->message["message"], 'status' => $this->project->message["status"]));
        }
    }
}

?>
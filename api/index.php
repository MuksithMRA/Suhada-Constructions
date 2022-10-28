<?php 
declare(strict_types=1);

spl_autoload_register(function ($class_name) {
    if (file_exists( 'controllers/' . $class_name . '.php')) {
        require_once 'controllers/' . $class_name . '.php';
     }
     elseif (file_exists('models/' . $class_name . '.php')) {
        require_once 'models/' . $class_name . '.php';
     }
     elseif (file_exists('database/' . $class_name . '.php')) {
        require_once 'database/' . $class_name . '.php';
     }
});

header("Content-type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
$database = new DatabaseService();
$db = $database->getConnection();


$parts = explode('/', $_SERVER['REQUEST_URI']);
$path = $parts[2];

    switch ($path) {
        case 'login':
            $auth = new Auth($db);
            $controller = new AuthController($auth);
            $controller->login($_SERVER['REQUEST_METHOD']);
            break;
        case 'register':
            $auth = new Auth($db);
            $controller = new AuthController($auth);
            $controller->register($_SERVER['REQUEST_METHOD']);
            break;
        case 'employees':
            $employee = new Employee($db);
            $controller = new EmployeeController($employee);
            $controller->employee($_SERVER['REQUEST_METHOD'], isset($parts[3]) ? $parts[3] : null);
            break;
        case 'projects':
            $project = new Project($db);
            $controller = new ProjectController($project);
            $controller->project($_SERVER['REQUEST_METHOD'],isset($parts[3]) ? $parts[3] : null);
            break;
        case 'vehicles':
            $vehicle = new Vehicle($db);
            $controller = new VehicleController($project);
            $controller->vehicle($_SERVER['REQUEST_METHOD'],isset($parts[3]) ? $parts[3] : null);
            break;
        case 'finance':
            $finance = new Finance($db);
            $controller = new FinanceController($project);
            $controller->finance($_SERVER['REQUEST_METHOD'],isset($parts[3]) ? $parts[3] : null);
            break;
        default:
            http_response_code(404);
            echo json_encode(array('status'=>404 , 'message' => 'Page not found'));
    }
    
?>
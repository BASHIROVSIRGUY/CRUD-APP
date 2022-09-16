<?php
// require_once '\\vendor\\autolader.php'
require __DIR__ . '\\controllers\\PageController.php';
require __DIR__ . '\\controllers\\AuthController.php';
require __DIR__ . '\\controllers\\dbController.php';
require __DIR__ . '\\controllers\\dbHandler.php';
require __DIR__ . '\\controllers\\functions.php';
use App\Database\dbHandler;
use App\Controllers\AuthController;
use App\Controllers\PageController;
use App\Controllers\dbController;
use App\functions\checkAuth;
use App\functions\crudHandlerPages;

error_reporting(E_ALL);
date_default_timezone_set("Europe/Moscow");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: [GET,POST,PUT,DELETE]");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$db = new dbHandler();

if(checkAuth()){
    $controller = new PageController($db);
} else{
    $controller = new AuthController($db);
}

function router($url_params) {
    $response = [];
    try{
        switch ($url_params[0]){
            case 'registration':
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $data = json_decode(file_get_contents("php://input"), true);
                    $response = $controller->registrationUser($data['name'], $data['login'], $data['password']);    
                }
                break;
            case 'authorization':
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $data = json_decode(file_get_contents("php://input"), true);
                    $response = $controller->logIn($data['name'], $data['login'], $data['password']);
                }
                break;
            case 'logout':
                $id = $url_params[0];
                $response = $controller->logOut($id);
                break;
            case 'pages':
                $response = crudHandlerPages($controller, $url_params);
                break;
            default:
                http_response_code(404);
                $response = [
                    'id' => null,
                    'status' => false,
                    'message' => 'Path not found'
                ];
        }
    } catch(Exception $e){
        if(!checkAuth()){
            http_response_code(401);
        }
        $response = [
            'id' => null,
            'status' => false,
            'message' => 'Error'
        ];
    }
    return $response;
}

$response = [];
if(!empty($_GET['url_param'])){
    $url_params = explode('/', $_GET['url_param']);
    $response = router($url_params);
}

echo json_encode($response);
// $json = getRequestJSON($controller);
// echo $json;
<?php
namespace App\functions;

function checkAuth(){
    session_start();
    if(isset($_SESSION['auth'])){
        if( $_SESSION['auth'] === true
            &&
            $_COOKIE['auth_user_id'] == $_SESSION['auth_user_id'] 
            && 
            $_COOKIE['auth_login'] == $_SESSION['auth_login']){
            return true;
        }
    }
    return false;
}

function crudHandlerPages($url_params){
    $response = [];
    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            if(isset($url_params[1]) && is_numeric($url_params[1])){
                $id = $url_params[1];
                $response = $controller->getPageByID($id);
            }else{
                $response = $controller->getPages();
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            $response = $controller->createPage($data['title'], $data['text'], $data['theme']);
            break;
        case 'PUT':
            if(isset($url_params[1]) && is_numeric($url_params[1])){
                $id = $url_params[1];
                $data = json_decode(file_get_contents("php://input"), true);
                $response = $controller->updatePage($id, $data['title'], $data['text'], $data['theme']);
            }else{
                http_response_code(422);
                $response = [
                    'id' => null,
                    'status' => false,
                    'message' => 'Incorrect path',
                ];
            }
            break;
        case 'DELETE':
            if(isset($url_params[1]) && is_numeric($url_params[1])){
                $id = $url_params[1];
                $response = $controller->deletePage($id);
            }else{
                http_response_code(422);
                $response = [
                    'id' => null,
                    'status' => false,
                    'message' => 'Incorrect path',
                ];
            }
            break;
    }
    return $response; 
}

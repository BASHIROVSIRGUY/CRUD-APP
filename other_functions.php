<?php

function logout() {
    if (!empty($_SESSION['auth']) and $_SESSION['auth']) {
        session_start();
        session_destroy();
    
        setcookie('login', '', time());
        setcookie('key', '', time());
    }
}

function getRequestJSON($controller){
    $response = [];
    if(!empty($_GET['url_param'])){
        $url_params = explode('/', $_GET['url_param']);
        if($url_params[0] == 'pages'){
            switch($_SERVER['REQUEST_METHOD']){
                case 'GET':
                    $response = handleGetRequestPages($url_params);
                    break;
                case 'POST':
                    $response = handlePostRequestPages();
                    break;
                case 'PUT':
                    $response = handlePutRequestPages($url_params);
                    break;
                case 'DELETE':
                    $response = handleDeleteRequestPages($url_params);
                    break;
            }
        } else{
            http_response_code(404);
            $response = [
                'status' => false,
                'message' => 'Path not found'
            ];
        }
    }
    return json_encode($response);
}

function handleGetRequest($url_params){
    $response = [];
    if(isset($url_params[1]) && is_numeric($url_params[1])){
        $id = $url_params[1];
        $response = $controller->getPageByID($id);
    }else{
        $response = $controller->getPages();
    }
    return $response;
}

function handlePostRequest(){
    $_POST;
    return $controller->createPage();
}

function handlePutRequestPages(){
    $response = [];
    if(isset($url_params[1]) && is_numeric($url_params[1])){
        $id = $url_params[1];
        $response = $controller->updatePage();
    }else{
        http_response_code(422);
        $response = [
            'id' => null,
            'status' => false,
            'message' => 'Incorrect path',
        ];
    }
    return $response;   
}


function handleDeleteRequestPages($url_params){
    $response = [];
    if(isset($url_params[1]) && is_numeric($url_params[1])){
        $id = $url_params[1];
        $response = $controller->deletePage();
    }else{
        http_response_code(422);
        $response = [
            'id' => null,
            'status' => false,
            'message' => 'Incorrect path',
        ];
    }
    return $response;
}
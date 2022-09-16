<?php 
namespace App\Controllers;

class AuthController {
    protected $dbController = null;

    function __construct($db){      
        $this->dbController = new dbController($db);
    }

    public function registrationUser($name, $login, $password){
        $create_info = $this->dbController->createUser($name, $login, $password);
        return [
            'id' => $create_info['id'],
            'status' => true,
            'message' => 'User is registered'
        ];
    }

    public function logIn($name, $login, $password){
        $user_info = $this->dbController->readUser($name, $login, $password);
        if(!empty($user_info)){
            if(password_verify($password, $user_info['password_hash'])){
                session_start();
                $_SESSION = [];
                $_SESSION['auth'] = true;
                $_SESSION['auth_user_id'] = $user_info['ID'];
                $_SESSION['auth_login'] = $user_info['login'];
                set_cookie('auth_user_id', $user_info['ID']);
                set_cookie('auth_login', $user_info['login']);
            }
            $response = [
                'id' => $user_info['id'],
                'status' => true,
                'message' => 'User is log in'
            ];
        } else{
            $response = [
                'id' => null,
                'status' => false,
                'message' => 'User is not exist'
            ];
        }
        return $response;
    }

    // public function createToken($user_id, $login, $key){
    //     $headers = json_encode([
    //         'alg' => 'HS256',
    //         'typ' => 'JWT',
    //     ]);
    //     $payload = json_encode([
    //         'id' => $user_id,
    //         'login' => $login,
    //         'role' => 'user',
    //     ]);
    //     $base64UrlEncode = function($text) {
    //         return str_replace(
    //             ['+', '/', '='],
    //             ['-', '_', ''],
    //             base64_encode($text)
    //         );
    //     };
    //     $headers_encoded = $base64UrlEncode($headers);
    //     $payload_encoded = $base64UrlEncode($payload);
    //     $signature = hash_hmac('sha512',"$headers_encoded.$payload_encoded",$key,true);
    //     return "$headers_encoded.$payload_encoded.$signature_encoded";;
    // }

}

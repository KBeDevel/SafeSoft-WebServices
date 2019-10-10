<?php

require "./header.php";
// include "../objects/user.php";

$response = [];
$error = "";
$valid = false;

try{

    $id = base64_decode($_GET['id']);
    $criteria = $_GET['criteria']; # token, email, username
    $pass = base64_decode($_GET['pass']);

    if($id==NULL||trim($id)==""){
        $error .= " [User ID not set] ";
    }else{
        if($criteria=='username'||$criteria=='email'){
            if($pass==NULL||trim($pass)==''){
                $error .= " [Password not set] ";
            }else{
                $valid = true;
            }
        }else{
            if($criteria=='token'){
                $valid = true;
            }else if($criteria==NULL||trim($criteria)==''){
                $error .= " [Criteria not set] ";
            }else{
                $error .= " [Criteria not allowed] ";
            }
        }    
    }

    // $tempUser = new User();

    // $response = $tempUser->auth($id,$criteria,$pass);

    if($valid){
        require '../shared/core.php';

        $core = new Core();

        $token = $core->generateRandomString(64);

        $response['hasErrors'] = false;
        $response['token'] = $token;
        echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }else{
        $response['hasErrors'] = true;
        $response['error'] .= $error;
        echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}catch(Exception $e){
    $response['hasErrors'] = true;
    $response['error'] = "Internal server error. ".$e->getMessage();
    $response['token'] = null;
    echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

# REQUEST DATA [POST]
#
# URL: https://pftsafesoft.000webhostapp.com/api/auth/Authenticate.php?id=dG9wb2NvbmFsZXJnaWE=&criteria=username&pass=ZTBlNjA5N2E2ZjhhZjA3ZGFmNWZjNzI0NDMzNmJhMzcxMzM3MTNhOGZjNzM0NWMzNmQ2NjdkZmE1MTNmYWJhYQ==
#
# id=dG9wb2NvbmFsZXJnaWE=
# criteria=username
# pass=ZTBlNjA5N2E2ZjhhZjA3ZGFmNWZjNzI0NDMzNmJhMzcxMzM3MTNhOGZjNzM0NWMzNmQ2NjdkZmE1MTNmYWJhYQ==
#

# RESPONSE [IDEAL]:
#
# {
#   "hasErrors" : false,
#   "token" : "VMwx8AWlmKQ1PzG4xUbdIIGYeq1HHD5d" 
# }

# RESPONSE [WRONG]:
#
# {
#   "hasErrors" : true,
#   "token" : null,
#   "error" : "Error message"
# }

?>
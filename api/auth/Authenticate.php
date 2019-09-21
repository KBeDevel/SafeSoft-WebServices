<?php

include "./header.php";
include "../objects/user.php";

$outerData = [];
$error = true;

try{
    $error = "";

    $id = base64_decode($_POST['id']);
    $criteria = $_POST['criteria']; # token, email, username
    $pass  = base64_decode($_POST['pass']);

    if($id==NULL||trim($id)==''){
        $error .= " User ID not set. ";
    }

    if($criteria==NULL||trim($criteria)==''){
        $error .= " Criteria not set. ";
    }

    if($pass==NULL||trim($pass)==''){
        $error .= " Password not set ";
    }

    $tempUser = new User();

    $outerResponse = $tempUser->auth($id,$criteria,$pass);

    if(trim($error)==''){
        echo json_encode($outerResponse, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }else{
        $outerResponse['error'] .= $error;
        echo json_encode($outerResponse, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}catch(Exception $e){
    array_push($outerData['hasErrors'], true);
    array_push($outerData['error'], "Internal server error. ".$e->getMessage());
    array_push($outerData['token'], null);
    echo json_encode($outerData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

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
<?php

include "../../src/top/headers.php";
include "../objects/user.php";

$outerData = [];
$error = true;

try{
    if(isset($_POST['email'])&&isset($_POST['pass'])){
        $tempUser = new User();
    
        $valid = $tempUser->auth($_POST['email'],$_POST['pass']);
    
        if(count($valid)===1){
            $error = false;
            array_push($outerData['token'], $valid[0]);
        }
    }
    
    array_push($outerData['hasErrors'], $error);
    array_push($outerData['message'], null);
    
    if($error){
        array_push($outerData['message'], $errorMessage);
        array_push($outerData['token'], false);
    }
}catch(Exception $e){
    array_push($outerData['hasErrors'], $error);
    array_push($outerData['message'], $e->getMessage());
    array_push($outerData['token'], null);
}

echo json_encode($outerData, JSON_UNESCAPED_UNICODE);

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
#   "token" : null
# }

?>
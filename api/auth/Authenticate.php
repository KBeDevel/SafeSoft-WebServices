<?php

include "../../src/top/headers.php";
include "../objects/user.php";

$outerData = [];
$error = true;

if(isset($_POST['email'])&&isset($_POST['pass'])){
    $tempUser = new User();

    $valid = $tempUser->auth($_POST['email'],$_POST['pass']);

    if(count($valid)===1){
        $error = false;        
        array_push($outerData['token'], $valid[0]);
    }
}

array_push($outerData['hasErrors'], $error);

if($error){
    array_push($outerData['token'], false);
}

echo json_encode($outerData, JSON_UNESCAPED_UNICODE);

?>
<?php

# PARAMS

// token
// data
// title
// type
// eventId

try{

    // require '../objects/file.php';

    $valid = true;
    $error = null;

    if(!isset($_REQUEST['token'])){
        $valid = false;
        $error .= "Token not set. ";
    }

    if(!isset($_REQUEST['data'])){
        $valid = false;
        $error .= "File data not set. ";
    }

    if(!isset($_REQUEST['title'])){
        $valid = false;
        $error .= "File title not set. ";
    }

    if(!isset($_REQUEST['type'])){
        $valid = false;
        $error .= "Document type not set. ";
    }

    if(!isset($_REQUEST['eventId'])){
        $valid = false;
        $error .= "Event ID not set. ";
    }

    if($valid){

        $response['hasErrors'] = false;
        echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    }else{
        
        $response['hasErrors'] = true;
        $response['error'] = $error;
        echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    }

}catch(Exception $e){

    $response['hasErrors'] = true;
    $response['error'] = "Internal server error. ".$e->getMessage();
    echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

}

?>
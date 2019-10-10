<?php

try{
    require "../api/objects/connector.php";
    
    $query = "SELECT * FROM USERS";
    echo $query;
    $outArr = $connector::query($query);

    echo $outArr;
}catch(Exception $e){
    echo $e->getMessage();
}

?>
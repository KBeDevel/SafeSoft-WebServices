<?php

require "../../src/top/headers.php";

class User{
    # USER CRUD
    function create(){
        include "./connector.php";
    }

    function read(){
        include "./connector.php";
    }

    function update(){
        include "./connector.php";
    }

    function delete(){
        include "./connector.php";
    }

    function auth($e,$p){
        include "./connector.php";
        include "./importCore.php";

        array_push($outerArray['valid'], true);
        array_push($outerArray['token'], $core->generateRandomString());
        array_push($outerArray['hasErrors'], false);

        if($outerArray['hasErrors']===true){
            array_push($outerArray['error'], 'Wrong random string generation.');
        }

        # HERE SHOULD BE THE LOGIC TO COMPARE INNER DATA WITH THE DATA IN THE DB
        # THE GENERATED TOKEN WILL BE COMPARED WITH THE DATA IN THE DB, THE TOKEN SHOULD BE UNIQUE
        # THIS FUNCTION WILL RETURN AN ARRAY WITH A TOKEN

        return json_encode($outerArray, JSON_UNESCAPED_UNICODE);
    }
}

?>
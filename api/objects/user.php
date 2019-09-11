<?php

require "../../src/top/headers.php";

class User{
    # USER CRUD
    function create(){

    }

    function read(){

    }

    function update(){

    }

    function delete(){

    }

    function auth($e,$p){
        include "../config/core.php";

        $core = new Core();

        $token[0] = $core->generateRandomString();

        # HERE SHOULD BE THE LOGIC TO COMPARE INNER DATA WITH THE DATA IN THE DB
        # THE GENERATED TOKEN WILL BE COMPARED WITH THE DATA IN THE DB, THE TOKEN SHOULD BE UNIQUE
        # THIS FUNCTION WILL RETURN AN ARRAY WITH A TOKEN

        return $token;
    }
}

?>
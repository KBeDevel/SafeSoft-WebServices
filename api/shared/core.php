<?php

class Core{
    public function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function validateToken($token){
        include "../objects/connector.php";

        if($token===NULL||trim($token)==''){
            return false;
        }else{
            $query = "SELECT Code FROM USERS WHERE Token = '$token'";
            $response = $connector->getOne($query);
        }        
    }
}

?>
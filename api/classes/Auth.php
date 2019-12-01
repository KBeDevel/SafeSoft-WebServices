<?php

class Auth {

    private $unique_id;
    private $key;
    private $criteria;

    private $valid_criteria = array(
        "token",
        "username",
        "email"
    );

    function __construct($unique_id, $key, $criteria) {
        $this->unique_id = $unique_id;
        $this->key = $key;
        $this->criteria = $criteria;
    }

    private function filter_criteria($id, $criteria, $key){
        $outer_array = array(
            "hasErrors" => true,
            "token" => null,
            "error" => null
        );

        if (is_null($criteria, $key)) {
            $outer_array['error'] = "Criteria and/or key are not defined";
        }

        if (is_null($id)) {
            if (!in_array(strtolower($criteria), $this->valid_criteria)) {
                return "";
            } else {
                if ($this->validate_token($key)) {

                } else {
                    return "";
                }
            }
        } else {
            return "";
        }

        return $outer_array;
    }

    private function validate_token($token) {

        $valid_query = false;
        $response = array();

        include_once '../common/Connector.php';

        $stmt = mysqli_stmt_init($connector);

        mysqli_stmt_prepare($stmt, "SELECT Email FROM `USERS` WHERE Token = ?");
        mysqli_stmt_bind_param($stmt, 's', $token);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $email);
            mysqli_stmt_fetch($stmt);


        } else {

            
        }
    }
};

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
#   "error" : "Error message"
# }

?>

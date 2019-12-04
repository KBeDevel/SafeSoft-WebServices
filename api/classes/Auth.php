<?php

include_once __DIR__.'/../shared/Strings.php';
include_once __DIR__.'/../shared/Connector.php';

class Auth {

    private $unique_id;
    private $key;
    private $criteria;
    private $connector;

    private $valid_criteria = array(
        "token",
        "email"
    );

    public function __construct($unique_id, $key, $criteria) {
        $this->unique_id = $unique_id;
        $this->key = $key;
        $this->criteria = $criteria;
        $this->connector = Connector::getConnector();
    }

    private function validate_criteria($criteria){

        if (is_null($criteria)) {
            return false;
        } else {
            if (!in_array(strtolower($criteria), $this->valid_criteria)) {
                return false;
            } else {
                return true;
            }
        }
    }

    private function validate_token($token) {

        $valid_query = false;

        $stmt = mysqli_stmt_init($this->connector);

        mysqli_stmt_prepare($stmt, "SELECT Email FROM `USERS` WHERE Token = ?");
        mysqli_stmt_bind_param($stmt, 's', $token);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $email);
            mysqli_stmt_fetch($stmt);

            if ($email != null) {
                $valid_query = true;
            }
        }

        mysqli_stmt_close($stmt);
        mysqli_close($this->connector);

        return $valid_query;
    }

    private function login($id, $key) {
        $token = null;

        $stmt = mysqli_stmt_init($this->connector);

        mysqli_stmt_prepare($stmt, "SELECT Code, Token FROM `USERS` WHERE Email = ? AND Pass = ?");
        mysqli_stmt_bind_param($stmt, 'ss', $id, $key);


        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $code, $token);
            mysqli_stmt_fetch($stmt);

            if ($code != null) {
                if (is_null($token)) {

                    mysqli_stmt_prepare($stmt, "SELECT Code FROM `USERS` WHERE Token = ?");

                    do {
                        $token_exists = true;

                        $generated_token = Strings::generateRandomString(32);

                        mysqli_stmt_bind_param($stmt, 's', $generated_token);

                        if (mysqli_stmt_execute($stmt)) {

                            mysqli_stmt_bind_result($stmt, $temp_code);
                            mysqli_stmt_fetch($stmt);

                            if ($temp_code == null) {
                                $token_exists = false;
                            }
                        }

                    } while ($token_exists);

                    if (!is_null($generated_token)) {

                        mysqli_stmt_prepare($stmt, "UPDATE `USERS` SET Token = ?, TokenTime = CURRENT_TIMESTAMP, UpdatedAt = CURRENT_TIMESTAMP, LoggedAt = CURRENT_TIMESTAMP WHERE Code = ?");
                        mysqli_stmt_bind_param($stmt, 'ss', $generated_token, $code);

                        if (mysqli_stmt_execute($stmt)) {

                            $token = $generated_token;
                        }
                    }
                }
            }
        }

        mysqli_stmt_close($stmt);
        mysqli_close($this->connector);

        return $token;
    }

    public function authenticate() {
        $id = $this->unique_id;
        $criteria = $this->criteria;
        $key = $this->key;

        $outer_array = array(
            "hasErrors" => true,
            "token" => null,
            "error" => null,
        );

        if ($this->validate_criteria($criteria)) {
            if ($criteria !== "token") {

                $token = $this->login($id, $key);

                if ($token != null) {
                    $outer_array['token'] = $token;
                    $outer_array['hasErrors'] = false;
                } else {
                    $outer_array['error'] = "Not valid credentials";
                }
            } else {
                if ($this->validate_token($key)) {
                    $outer_array['token'] = $key;
                    $outer_array['hasErrors'] = false;
                } else {
                    $outer_array['error'] = "Token not valid";
                }
            }
        } else {
            $outer_array['error'] = "Not valid criteria";
        }

        return $outer_array;
    }
}

?>

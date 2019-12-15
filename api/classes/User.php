<?php

include_once __DIR__.'/../shared/Connector.php';
include_once __DIR__.'/../shared/Strings.php';

class User {

    private $connector;

    private $possible_types_prefix = array(
        "EM" => "employee",
        "CO" => "corp",
        "ME" => "medic",
        "SV" => "supervisor",
        "TS" => "technical_specialist",
        "AD" => "admin",
        "EG" => "engineer"
    );

    private $possible_types_code = array(
        'employee' => 0,
        'corp' => 1,
        'medic' => 2,
        'supervisor' => 3,
        'tecnical_specialist' => 4,
        'admin' => 5,
        'engineer' => 6
    );

    public function __construct(){
        $this->connector = Connector::getConnector();
    }

    public function getByRut($rut){
        $query = mysqli_query($this->connector, "SELECT * FROM `USERS` WHERE RUT LIKE '%$rut'");
        $result = mysqli_fetch_array($query, MYSQLI_ASSOC);

        return $result;
    }

    public function get($code, $token) {

        $query = mysqli_query($this->connector, "SELECT * FROM `USERS` WHERE Code LIKE '%$code' OR Token LIKE '%$token'");
        $result = mysqli_fetch_array($query, MYSQLI_ASSOC);

        return $result;
    }
    
    public function delete($code) {

        $does_have_events = false;

        $out_data = array();

        $stmt = mysqli_stmt_init($this->connection);

        mysqli_stmt_prepare($stmt, "SELECT EventId FROM `EVENTS` WHERE UserCode = ?");
        mysqli_stmt_bind_param($stmt, 's', $code);

        if (mysqli_stmt_execute($stmt)) {

            mysqli_stmt_bind_result($stmt, $event_id);
            $does_have_events = false;
            $temp_events = mysqli_stmt_fetch($stmt);
            mysqli_stmt_free_result($stmt);

            if ($temp_events !== null) {

                $does_have_events = true;

                while ($temp_events) {
                    if ($event_id !== null) {
    
                        $temp = array();
                        $temp["event_id"] = $event_id;
                        $temp_events[] = $temp;                      
                    }
                }
            }
        }

        if ($does_have_events) {

            $collateral_data_removed = false;

            foreach ( $temp_events as $key => $value ) {               

                mysqli_stmt_prepare($stmt, "DELETE FROM TOOLS WHERE EventId = ?");
                mysqli_stmt_bind_param($stmt, 's', $value['event_id']);

                if (mysqli_stmt_execute($stmt)) {

                    mysqli_stmt_prepare($stmt, "DELETE FROM COMMENTS WHERE EventId = ?");
                    mysqli_stmt_bind_param($stmt, 's', $value['event_id']);

                    if (mysqli_stmt_execute($stmt)) {

                        mysqli_stmt_prepare($stmt, "DELETE FROM `EVENTS` WHERE EventId = ?");
                        mysqli_stmt_bind_param($stmt, 's', $value['event_id']);

                        if (mysqli_stmt_execute($stmt)) {
                            
                            $collateral_data_removed = true;
                        } else {

                            $out_data['error'] = "Internal server error. ".mysqli_error($this->connection);
                        }
                    } else {

                        $out_data['error'] = "Internal server error. ".mysqli_error($this->connection);
                    }
                } else {

                    $out_data['error'] = "Internal server error. ".mysqli_error($this->connection);
                }
            }

            mysqli_stmt_close($stmt);
            $stmt = mysqli_stmt_init($this->connector);

            if ($collateral_data_removed) {
                mysqli_stmt_prepare($stmt, "DELETE FROM `USERS` WHERE Code = ?");
                mysqli_stmt_bind_param($stmt, 's', $code);
    
                if (mysqli_stmt_execute($stmt)) {
    
                    $out_data['message'] = "Deleted user with code: ".$code;
                } else {
    
                    $out_data['error'] = "Internal server error. ".mysqli_error($this->connection);
                }
            }

        } else {

            mysqli_stmt_close($stmt);
            $stmt = mysqli_stmt_init($this->connector);

            mysqli_stmt_prepare($stmt, "DELETE FROM `USERS` WHERE `Code` = ?");
            mysqli_stmt_bind_param($stmt, 's', $code);

            if (mysqli_stmt_execute($stmt)) {

                $out_data['message'] = "Deleted user with code: ".$code;

            } else {

                $out_data['error'] = "Internal server error. ".mysqli_errno($this->connector)." - ".mysqli_error($this->connection);
            }
        }

        mysqli_stmt_close($stmt);
        mysqli_close($this->connection);

        return $out_data;
    }

    public function create($data) {

        $out_data = array(
            'error' => null,
            'user_code' => null,
            'user_token' => null,
        );

        $temp_code_type = null;

        if (in_array($data['type'], $this->possible_types_code)) {
            foreach ($this->possible_types_code as $key => $value) {
                if ($value === (int)$data['type']) {
                    $temp_code_type = $key;
                }                
            }
        }

        if ($temp_code_type != null) {

            $temp_code_prefix = null;

            if (in_array($temp_code_type, $this->possible_types_prefix)) {
                foreach ($this->possible_types_prefix as $key => $value) {
                    if ($value === $temp_code_type) {
                        $temp_code_prefix = $key;
                    }
                }
            }
        
            $stmt = mysqli_stmt_init($this->connector);

            do {
                $code_exists = true;

                mysqli_stmt_prepare($stmt, "SELECT Email FROM `USERS` WHERE Code = ?");

                $generated_code = Strings::generateRandomString(6);

                $temp_user_code = $temp_code_prefix . "-" .$generated_code;

                mysqli_stmt_bind_param($stmt, 's', $temp_user_code);

                if (mysqli_stmt_execute($stmt)) {

                    mysqli_stmt_bind_result($stmt, $temp_email);
                    mysqli_stmt_fetch($stmt);

                    if ($temp_email == null) {
                        $code_exists = false;
                    }
                }

            } while ($code_exists);

            do {
                $token_exists = true;

                mysqli_stmt_prepare($stmt, "SELECT Code FROM `USERS` WHERE Token = ?");

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
        

            if ($temp_user_code != null) {

                $generated_code = $temp_code_prefix . "-" . $generated_code;

                mysqli_stmt_prepare($stmt, "INSERT INTO `USERS` (`Code`, `RUT`, `Firstname`, `Lastname`, `Phone`, `Street`, `HouseNumber`, `City`, `State`, `Country`, `Type`, `Email`, `Pass`, `Token`, `TokenTime`, `CreatedAt`, `UpdatedAt`, `LoggedAt`, `ParentOrg`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), NULL, NULL, ?)");

                mysqli_stmt_bind_param($stmt, 'ssssisssssissss', $generated_code, $data['rut'], $data['firstname'], $data['lastname'], $data['phone'], $data['street'], $data['house_number'], $data['city'], $data['state'], $data['country'], $data['type'], $data['email'], $data['pass'], $generated_token, $data['parent_org']);

                if (mysqli_stmt_execute($stmt)) {
                    $out_data['user_code'] = $generated_code;
                    $out_data['user_token'] = $generated_token;
                } else {
                    $out_data['error'] = mysqli_error($this->connector);
                }

            } else {
                $out_data['error'] = "Internal server error";
            }            
        } else {
            $out_data['error'] = "User type is not correct";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($this->connector);
     
        return $out_data;
    }    

}

?>

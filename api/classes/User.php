<?php

class User {

    private $code;
    private $rut;
    private $firstname;
    private $lastname;
    private $phone;
    private $street;
    private $house_number;
    private $city;
    private $state;
    private $country;
    private $type;
    private $email;
    private $username;
    private $pass;
    private $token;
    private $token_time;
    private $created_at;
    private $updated_at;
    private $logged_at;
    private $parent_org;

    private $possible_types_prefix = array(
        "EM",
        "CO",
        "ME",
        "SV",
        "TS",
        "AD",
        "EG"
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

    function __construct() {
        $this->possible_types_code = null;
    }
}

?>

<?php

class User{
    private $username;
    private $rut;
    private $firstname;
    private $lastname;
    private $role;
    private $email;
    private $password;
    private $type;
    private $code;
    private $createdAt;
    private $updatedAt;
    private $lastlogin;
    private $parentOrg;

    private function createCode($context){
        include "./misc.php";

        switch($context){
            case 0: # EMPLOYEE
                $prefix = 'EM-';
            break;
            case 1: # CORP
                $prefix = 'CO-';
            break;
            case 2: # MEDIC
                $prefix = 'ME-';
            break;
            case 3: # SUPERVISOR
                $prefix = 'SV-';
            break;
            case 4: # TEC. SPECIALIST
                $prefix = 'TS-';
            break;
            case 5: # ADMIN
                $prefix = 'AD-';
            break;
            case 6: # ENGINEER
                $prefix = 'EG-';
            break;
            default:
                $prefix = FALSE;
            break;
        }

        if($prefix){
            $code = strtoupper($core->generateRandomString(10));

            while($this->validateCode($prefix.$code)){
                $code = strtoupper($core->generateRandomString(10));
            }

            return $prefix.$code;
        }else{
            return FALSE;
        }
    }

    private function validateCode($code){
        include "./connector.php";

        $query = "SELECT * FROM USERS WHERE Code = '$code'";
        $response = $connector->getOne($query);
        $quantity = count($response);

        if($quantity>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    private function read($id,$criteria){
        include "./connector.php";

        if($id!==NULL&&$criteria!==NULL){
            if(trim($id)!=''&&trim($criteria)!=''){
                switch($criteria){
                    case 'token':
                        $query = "SELECT * FROM USERS WHERE Token = '$id'";
                    break;
                    case 'email':
                        $query = "SELECT * FROM USERS WHERE Email = '$id'";
                    break;
                    case 'username':
                        $query = "SELECT * FROM USERS WHERE Username = '$id'";
                    break;
                    default:
                        $query = FALSE;
                    break;
                }

                $response = $connector->getOne($query);

                if($response !== NULL){
                    return $response;
                }else{
                    return FALSE;
                }
            }
        }
    }

    private function login($id, $criteria, $password){
        include "./connector.php";

        if($id!==NULL&&$criteria!==NULL){
            if(trim($id)!=''&&trim($criteria)!=''){
                switch($criteria){
                    case 'email':
                        if($password==NULL||trim($password)==''){
                            $query = FALSE;
                        }else{
                            $query = "SELECT * FROM USERS WHERE Email = '$id' AND Pass = '$password'";
                        }                        
                    break;
                    case 'username':
                        if($password==NULL||trim($password)==''){
                            $query = FALSE;
                        }else{
                            $query = "SELECT * FROM USERS WHERE Username = '$id' AND Pass = '$password'";
                        }                        
                    break;
                    default:
                        $query = FALSE;
                    break;
                }

                if(!$query){
                    return FALSE;
                }else{
                    $response = $connector->getOne($query);

                    if($response !== NULL){
                        return $response;
                    }else{
                        return FALSE;
                    }
                }                
            }
        }
    }

    # USER CUD
    public function create($username, $email, $password, $firstname, $lastname, $rut, $role, $type, $parentOrg){
        include "./connector.php";

        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->rut = $rut;
        $this->role = $role;
        $this->type = $type;
        $this->code = $this->createCode($this->type);
        $this->createdAt = $this->lastlogin = time();

        if($type==0 && $parentOrg != NULL){
            $this->parentOrg = $parentOrg;
        }else{
            $this->parentOrg = NULL;
        }
    }    

    public function update(){
        include "./connector.php";

        $this->updatedAt = time();
    }

    public function delete(){
        include "./connector.php";
    }
    # END USER CUD    

    public function auth($id, $criteria, $password){
        include "./connector.php";
        include "./misc.php";

        $token = NULL;

        $read = $this->read($id, $criteria);

        if(!$read){
            $hasErrors = TRUE;
            $errorMessage = 'The specified user doesn\'t exists.';
        }else{
            if($criteria=='token'){
                $token = $read['Token'];
            }else{
                $log = $this->login($id, $criteria, $password);
                if(!$log){
                    $hasErrors = TRUE;
                    $errorMessage = 'Wrong credentials.';
                }else{
                    $hasErrors = FALSE;
                    $this->lastlogin = time();
                    while($token===NULL||!$core->validateToken($token)){
                        $token = $core->generateRandomString(32);
                    }
                }
            }
        }        

        array_push($outerArray['token'], $token);
        array_push($outerArray['hasErrors'], $hasErrors);

        if($hasErrors===TRUE){
            array_push($outerArray['error'], $errorMessage);
        }

        return $outerArray;
    }
}

?>
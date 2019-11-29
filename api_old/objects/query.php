<?php

class Query{
    public function select($origin, $criteria){
        include "connector.php";
        if($criteria==NULL){
            
        }
        $query = "SELECT * FROM USERS WHERE 1=1";
        return 0;
    }

    public function update($data, $destinyTable, $criteria){
        include "connector.php";

        if($criteria!=NULL&&$destinyTable!=NULL){
            $setterElements = FALSE;

            if(gettype($data)==="array"){
                settype($setterElements, "array");
                foreach($data as $key => $value){
                    if(gettype($data)=="double"||gettype($data)=="integer"||gettype($data)=="float"){
                        array_push($setterElements, $data[$key]." = ".$value);
                    }else{
                        array_push($setterElements, $data[$key]." = '".$value."'");
                    }
                }                
            }

            if($setterElements){
                $query = "UPDATE ".$destinyTable." SET ".$data." WHERE $criteria";
            }
        }else{
            return FALSE;
        }
    }

    public function insert($data, $destinyData, $destinyTable, $criteria){
        include "connector.php";
        if($criteria==NULL){
            
        }
        $query = "INSERT INTO VALUES";
        return 0;
    }

    public function delete($destinyTable, $criteria){
        include "connector.php";
        if($criteria==NULL){

        }
        $query = "DELETE FROM";
        return 0;
    }
}

?>
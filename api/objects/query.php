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
        if($criteria==NULL){
            
        }
        $query = "UPDATE SET WHERE";
        return 0;
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
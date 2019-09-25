<?php

class Connector{
	private $conn;
	private $host;
	private $user;
	private $password;
	private $baseName;
	private $port;
	private $Debug;
	
	function __construct($params=array()){
		$this->conn = false;
		$this->host = '127.0.0.1';
		$this->user = 'ssws';
		$this->password = 'sspw';
		$this->baseName = 'ssdb';
		$this->port = '3306';
		$this->debug = true;
		$this->connect();
	}
 
	function __destruct(){
		$this->disconnect();
	}
	
	function connect(){
		if (!$this->conn){
			$this->conn = mysql_connect($this->host, $this->user, $this->password);	
			mysql_select_db($this->baseName, $this->conn); 
			mysql_set_charset('utf8',$this->conn);
			
			if (!$this->conn){
				$this->status_fatal = true;
				echo 'Connection BDD failed';
				die();
			} 
			else{
				$this->status_fatal = false;
			}
		}
 
		return $this->conn;
	}
 
	function disconnect(){
		if ($this->conn){
			@pg_close($this->conn);
		}
	}
	
	function getOne($query){
		$cnx = $this->conn;
		if (!$cnx || $this->status_fatal){
			echo 'GetOne -> Connection BDD failed';
			die();
		}
 
		$cur = @mysql_query($query, $cnx);
 
		if ($cur == FALSE){		
			$errorMessage = @pg_last_error($cnx);
			$this->handleError($query, $errorMessage);
		} 
		else{
			$this->Error=FALSE;
			$this->BadQuery="";
			$tmp = mysql_fetch_array($cur, MYSQL_ASSOC);
			
			$return = $tmp;
		}
 
		@mysql_free_result($cur);
		return $return;
	}
	
	function getAll($query){
		$cnx = $this->conn;
		if (!$cnx || $this->status_fatal){
			echo 'GetAll -> Connection BDD failed';
			die();
		}
		
		mysql_query("SET NAMES 'utf8'");
		$cur = mysql_query($query);
		$return = array();
		
		while($data = mysql_fetch_assoc($cur)){ 
			array_push($return, $data);
		} 
 
		return $return;
	}
    
    # FOR INSERT AND UPDATE
	function execute($query,$use_slave=false){
		$cnx = $this->conn;
		if (!$cnx||$this->status_fatal){
			return null;
		}
 
		$cur = @mysql_query($query, $cnx);
 
		if ($cur == FALSE){
			$ErrorMessage = @mysql_last_error($cnx);
			$this->handleError($query, $ErrorMessage);
		}
		else{
			$this->Error=FALSE;
			$this->BadQuery="";
			$this->NumRows = mysql_affected_rows();
			return;
		}
		@mysql_free_result($cur);
	}
	
	function handleError($query, $str_erreur){
		$this->Error = TRUE;
		$this->BadQuery = $query;
		if ($this->Debug){
			echo "Query : ".$query."<br>";
			echo "Error : ".$str_erreur."<br>";
		}
	}
}

?>
<?php

# CODE SOURCE: https://gist.github.com/anjerodesu/4502474
# MODIFIED BY: KBeDeveloper

require_once "db.settings.php";

class DBClass extends DatabaseSettings{

	var $classQuery;
	var $link;
	
	var $errno = '';
	var $error = '';
	
	# Init link
	function DBClass(){
		$settings = DatabaseSettings::getSettings();
		
		$host = $settings['dbhost'];
		$name = $settings['dbname'];
		$user = $settings['dbusername'];
		$pass = $settings['dbpassword'];
		
		$this->link = new mysqli( $host , $user , $pass , $name );
	}

	static function init(){
		$this->DBClass();
	}
	
	# Executes a database query
	function query( $query ){
		$this->classQuery = $query;
		return $this->link->query( $query );
	}
	
	function escapeString( $query ){
		return $this->link->escape_string( $query );
	}
	
	# Get the data return int result
	function numRows( $result ){
		return $result->num_rows;
	}
	
	function lastInsertedID(){
		return $this->link->insert_id;
	}
	
	# Get query using assoc method
	function fetchAssoc( $result ){
		return $result->fetch_assoc();
	}
	
	# Gets array of query results
	function fetchArray( $result , $resultType = MYSQLI_ASSOC ){
		return $result->fetch_array( $resultType );
	}
	
	# Fetches all result rows as an associative array, a numeric array, or both
	function fetchAll( $result , $resultType = MYSQLI_ASSOC ){
		return $result->fetch_all( $resultType );
	}
	
	# Get a result row as an enumerated array
	function fetchRow( $result ){
		return $result->fetch_row();
	}
	
	# Free all MySQL result memory
	function freeResult( $result ){
		$this->link->free_result( $result );
	}
	
	# Closes the database connection
	function close(){
		$this->link->close();
	}
	
	function sql_error(){
		if( empty( $error ) )
		{
			$errno = $this->link->errno;
			$error = $this->link->error;
		}
		return $errno . ' : ' . $error;
	}
}

?>
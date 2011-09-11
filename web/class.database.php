<?php
	class Database
	{
		/***
		Change these to match your database environment
		***/  
          
      private $_dev = array(
         'HOST' => 'localhost',
   		'USER' => 'pgweb',
         'PASS' => '____',
         'DBNAME' => 'pg_standard'
      );
      
      private $_prod = array(
         'HOST' => 'localhost',
   		'USER' => 'pgweb',
         'PASS' => '____',
         'DBNAME' => 'pg_standard'
      );
		
		/***
		DESCRIPTION: opens connection to the database
		POST:	Connection is open or app dies.
		***/
		protected function connect()
		{
			$dbenv = $this->_prod;
         
         $db = new mysqli($dbenv['HOST'], $dbenv['USER'], $dbenv['PASS'], $dbenv['DBNAME']);
			
			if (mysqli_connect_errno()) {
				echo sprintf('{success : false, msg : "%s"}', mysqli_connect_error());
			}
			
			return $db;
		}
	}
	
?>
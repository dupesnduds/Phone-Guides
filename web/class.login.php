<?php
   require_once('config.ini.php');
   require_once('class.database.php');

   class Login extends Database
   {
      private $_db;
      
      /***
      DESCRIPTION: validates a username and password for login
      PRE:   string alphanum username and password
      POST:   Query data is returned with 0 or more records
      ***/
      public function validate($username, $password)
      {
         $this->_db = Database::connect();
         
         $clean = array();
         $mysql = array();
         $data = array();
         
         if(ctype_alnum($username))
            $clean['username'] = $username;
         else
            $clean['username'] = false;
         
         if(!$clean['username'])
         {
            return false;
         }
         
         $clean['password'] = sha1($password);
         
         $mysql['username'] = $this->_db->real_escape_string($clean['username']);
         $mysql['password'] = $this->_db->real_escape_string($clean['password']);
         
         $sql=sprintf("SELECT u.username, r.role FROM users u INNER JOIN roles r ON  u.role_id = r.id WHERE username = '%s' AND password = '%s' LIMIT 1", $mysql['username'], $mysql['password']);
         $query = $this->_db->query($sql);
         $data = $query->fetch_assoc();
         
         $query->close();
         $this->_db->close();
         
         return $data;
      }   
      
      /***
      DESCRIPTION: logout
      POST: Terminates session
      ***/
      public function logout()
      {
         $_SESSION = array();
         
         if (isset($_COOKIE[session_name()])) {
             setcookie(session_name(), '', time()-42000, '/');
         }
         
         session_destroy();
      }      
   
   }
?>
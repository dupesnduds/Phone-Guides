<?php
	
	require('class.database.php');
    require('class.purifier.php');
    require('class.cache.php');
	
	class ExtensionSet extends Database
	{
		private $_db;
		private $_sanitize;
      
      function __construct() {
         $this->_sanitize = new Sanitize();
      }
      
		/***
		DESCRIPTION: Creates a content block
		@data (string), @desc (string), @locked (int) 0 - unlocked, 1 - locked
		POST:	A content block is created
		***/
		public function create($filename, $description, $order = int)
		{
			$this->_db = Database::connect();
         $cache = new Cache();
			
			$clean = array();
         $mysql = array();
			
         if (get_magic_quotes_gpc()) {
            $filename = stripslashes($filename);
            $description = stripslashes($description);
         }
         
         $clean['filename'] = $this->_sanitize->filter($filename);
         $clean['description'] = $this->_sanitize->filter($description);
         $clean['order'] = $this->_sanitize->filter($order);
         
         $mysql['filename'] = $this->_db->real_escape_string($clean['filename']);
         $mysql['description'] = $this->_db->real_escape_string($clean['description']);
         $mysql['order'] = $this->_db->real_escape_string($clean['order']);		  
			
			$sql=sprintf("INSERT INTO extensions (file_name, description, ext_order) VALUES ('%s', '%s', %s)", $mysql['filename'], $mysql['description'], $mysql['order']);
			$this->_db->query($sql);
         $lastInsertId = $this->_db->insert_id;
			$this->_db->close();
         
         $cache->create($lastInsertId, $clean['filename'], $clean['description'], $clean['order']);
		}
				
		/***
		DESCRIPTION: Grabs data for a content block and wraps it in a _wrapper
		@id (int)
		POST:	data is wrapped and returned in _wrapper
		***/
		public function get($id = int)
		{
			$cache = new Cache();
         $cachedData = $cache->retrieve($id);
         
         if(!empty($cacheData)) {
            $cacheData = json_decode($cacheData);
            return extensionset::_wrapper($id, $cacheData['block'], $cacheData['description']);
         }
         
         $this->_db = Database::connect();
			
			$clean = array();
         $mysql = array();
         
         $clean['id'] = (int)$id;
         $mysql['id'] = $this->_db->real_escape_string($clean['id']);
			
			$sql=sprintf("SELECT block, description FROM extensions WHERE id = %s", $mysql['id']);
			$query = $this->_db->query($sql);
			$content = '';
			
			if($query->num_rows > 0)
         {
            $data = $query->fetch_assoc();            
            $data['block'] = $this->_sanitize->filter($data['block']);
            $data['description'] = $this->_sanitize->filter($data['description']);
            
				$content = extensionset::_wrapper($id, $data['block'], $data['description']);
         }
         else
         {
            $content = "<p>Error: Content block <strong>${id}</strong> does not exist.  Please specify a valid content block.</p>";
         }
			
         $query->close();
			$this->_db->close();
			
			return $content;
		}
      
      /***
		DESCRIPTION: Grabs raw content block from the database for an id
		@id (int)
		***/
		public function getUnwrapped($id = int)
		{
			$cache = new Cache();
         $cachedData = $cache->retrieve($id);
         
         if($cacheData) {
            $cacheData = json_decode($cacheData);
            return $cacheData['block'];
         }
         
         $this->_db = Database::connect();
			
			$clean = array();
         $mysql = array();
         
         $clean['id'] = (int)$id;
         $mysql['id'] = $this->_db->real_escape_string($clean['id']);
			
			$sql=sprintf("SELECT block FROM extensions WHERE id = %s", $mysql['id']);
			$query = $this->_db->query($sql);
			
			if($query->num_rows > 0)
			{
				$data = $query->fetch_assoc();
			}
			else
			{
				$data['block'] = 'Error: This block does not exist.';
			}
			
         $query->close();
			$this->_db->close();
			
			return $this->_sanitize->filter($data['block']);
		}
		
      /***
      DESCRIPTION: grabs all content extensions
      POST: Returns the result set as encoded json
      ***/
      public function getContentExtensions()
      {     
         $this->_db = Database::connect();
         
         $arr = array();

         $sql = "SELECT id, file_name, description, ext_order FROM extensions ORDER BY ext_order";
         
         if($query = $this->_db->query($sql))
         {
            while ($obj = $query->fetch_object()) {					
					$arr[] = $obj;
            }
            
            foreach($arr as &$value) {              
               $value->id = (int)$value->id;
			   $value->file_name = stripslashes($value->file_name);
               $value->description = stripslashes($value->description);
               $value->ext_order = (int)$value->ext_order;
            }

            $query->close();
         }        
         
         $this->_db->close();
         
         return '{"results":' . json_encode($arr) . '}';
      }
		
		/***
		DESCRIPTION: updates content of a content block
		@id (int), @content (string)
		POST:	content block is updated w/ new content
		***/
		public function blockUpdate($id = int, $content)
		{			
			$this->_db = Database::connect();
			
			$clean = array();
         $mysql = array();
         
         if (get_magic_quotes_gpc()) {
            $content = stripslashes($content);
         }
         
         $clean['id'] = (int)$id;
			$clean['content'] = $this->_sanitize->filter($content);
			
         $mysql['id'] = $this->_db->real_escape_string($clean['id']);
			$mysql['content'] = $this->_db->real_escape_string($clean['content']);
			
			$sql=sprintf("UPDATE extensions SET block = '%s' WHERE id = %s", $mysql['content'], $mysql['id']);
			$this->_db->query($sql);
         $this->_db->close();
         
			$cache = new Cache();
         $cache->update($clean['id'], $clean['content']);
		}
		
      /***
      DESCRIPTION: updates content of a content block
      @id (int), @content (string), @description (string), @locked (int) 0 - unlocked, 1 - locked
      POST: content block is updated w/ new content
      ***/
      public function blockUpdateAll($id = int, $filename, $description, $order = int)
      {        
         $this->_db = Database::connect();
			
         $clean = array();
         $mysql = array();
         
         if (get_magic_quotes_gpc()) {
            $filename = stripslashes($filename);
            $description = stripslashes($description);
         }
         
         $clean['id'] = (int)$id;
         $clean['filename'] = $this->_sanitize->filter($filename);
         $clean['description'] = $this->_sanitize->filter($description);
         $clean['order'] = $this->_sanitize->filter($order);
         
         $mysql['id'] = $this->_db->real_escape_string($clean['id']);
         $mysql['filename'] = $this->_db->real_escape_string($clean['filename']);
         $mysql['description'] = $this->_db->real_escape_string($clean['description']);
         $mysql['order'] = $this->_db->real_escape_string($clean['order']);
			
         $sql=sprintf("UPDATE extensions SET file_name = '%s', description = '%s', ext_order = %s WHERE id = %s", $mysql['filename'], $mysql['description'], $mysql['order'], $mysql['id']);
         $this->_db->query($sql);
         $this->_db->close();
         
			$cache = new Cache();
         $cache->update($clean['id'], $clean['filename'], $clean['description'], $clean['order']);
      }
      
      /***
      DESCRIPTION: deletes a content block
      @id (int)
      POST: content block is removed
      ***/
      public function blockDelete($id = int)
      {        
         $this->_db = Database::connect();
			
			$clean = array();
         $mysql = array();
         
         $clean['id'] = (int)$id;
         $mysql['id'] = $this->_db->real_escape_string($clean['id']);
			
         $sql=sprintf("DELETE FROM extensions WHERE id = %s", $mysql['id']);
         $this->_db->query($sql);
         $this->_db->close();
         
			$cache = new Cache();
         $cache->delete($clean['id']);
      }
		
		/***
		DESCRIPTION: creates an editable wrapper for content extensions
      @id (int), @data (string), @description (string)
		POST:	Query data is returned with 0 or more records
		***/
		private function _wrapper($id = int, $data, $description) 
		{
			if($this->_isAdmin() || $this->_isEditor())
				return '<div id="' . $id . '" class="editable" title="' . $description . '">' . $data . '</div>';
			else
				return $data;
		}
				
		/***
		DESCRIPTION: checks to see if user is admin
		POST:	Returns true or false
		***/
		private function _isAdmin()
		{
			return (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == true);
		}
		
		/***
		DESCRIPTION: checks to see if user is an editor
		POST:	Returns true or false
		***/
		private function _isEditor()
		{
			return (isset($_SESSION['isEditor']) && $_SESSION['isEditor'] == true);
		}
	}
?>
<?php
   require_once('config.ini.php');
   require_once('class.purifier.php');
   
   class Cache
   {
      private $_sanitize = null;
      
      function __construct() {
         $this->_sanitize = new Sanitize();
      }
      
      /*
       * Create a cache entry for a unique identifier
       * @cacheId (int)
       * @data (string)
       * @description (string)
       */
      public function create($cacheId = int, $filename, $description, $order = int)
      {         
         $cacheName = md5($cacheId);
         $file = CACHE_PATH . $cacheName;
         
         $entry = array(
            'filename' => $this->_sanitize->addSlashes($filename),
            'description' => $this->_sanitize->addSlashes($description),
			'order' => $this->_sanitize->addSlashes($order)
         );

         @file_put_contents($file, json_encode($entry));
      }
      
      /*
       * Update cache file
       * @cacheId (int)
       * @data (string)
       * @description (string)
       */
      public function update($cacheId = int, $filename, $description = null, $order = int)
      {         
         $cacheName = md5($cacheId);
         $file = CACHE_PATH . $cacheName;
         $entry = array();
         
         $entry['filename'] = $this->_sanitize->addSlashes($filename);
         $cachedData = $this->retrieve($cacheId);
         
         if($cachedData) {
            $dataArray = json_decode($cachedData);
            $entry['description'] = $this->_sanitize->addSlashes($dataArray['description']);
         }
         else
         {
            $entry['description'] = $this->_sanitize->addSlashes($description);
         }
         
         @file_put_contents($file, json_encode($entry));
      }
      
      /*
       * Delete cache file
       * @cacheId (int)
       */
      public function delete($cacheId = int)
      {         
         $cacheName = md5($cacheId);
         $file = CACHE_PATH . $cacheName;
         @unlink($file);
      }
      
      /*
       * Read data from a cache entry
       * @cacheId (int)
       */
      public function retrieve($cacheId = int)
      {         
         $cacheName = md5($cacheId);
         $file = CACHE_PATH . $cacheName;
         
         if(file_exists($file)) {
            return $this->_sanitize->filter(file_get_contents($file));
         }
         else
         {
            return false;
         }

      }
      
   }
   
?>

<?php
   /*
    * This is rare, but if you need to set the session save path do it here
    */
   //session_save_path("/path/to/phpsessions");
   session_start();
   date_default_timezone_set('Pacific/Auckland');
   
   /*
    * Server path to site root (not including trailing slash)
    */
   define("ROOT", '/var/www/phone_guides/');
   
   /*
    * What is the host address of this site (not including the trailing slash)
    *    for example, http://www.google.com
    */
   define("BASE_URL", 'http://' . $_SERVER["HTTP_HOST"]);
   
   /*
    * Full server path to cache directory.  This should not have to be modified
    */
   define("CACHE_PATH", ROOT . 'cache' . '/');
      
   /*
    * Path to javascript and css directories.  This should not have to be modified.
    */
   define("JAVASCRIPTS", ROOT . '/support/javascripts/');
   define("CSS", ROOT . 'support/stylesheets/');
   define("EXT_VERSION", 'ext-2.1');
   define("UPLOAD_DIR", ROOT. 'audio/');
   define("OVERRIGHT_FILE", true);
   define("COPYRIGHT", 'Cleave Pokotea');
   define("COPYRIGHT_URL", 'http://www.tumunu.com/');                      
   define('SHOW_ERRORS', true);
   
   /********************************************************************
   DO NOT EDIT BELOW THIS LINE
   ********************************************************************/
   
   if(SHOW_ERRORS) {
      ini_set('error_reporting', E_ALL | E_STRICT);
      ini_set('display_errors', 'On');
      //ini_set('log_errors', 'Off');
	  ini_set('log_errors', 'On');
   }
   else
   {
      error_reporting( 0 );   
   }
   
   if(!isset($_SESSION['isAdmin']))
   {
      $_SESSION['isAdmin'] = false;
   }
   
   if(!isset($_SESSION['isEditor']))
   {
      $_SESSION['isEditor'] = false;
   }
   
   if(!isset($_SESSION['isMember']))
   {
      $_SESSION['isMember'] = false;
   }
   
   if(!isset($_SESSION['loggedIn']))
   {
      $_SESSION['loggedIn'] = false;
   }
   
   require_once(CLASSES.'class.set.php');
   $block  = new ExtensionSet(); 
   
?>
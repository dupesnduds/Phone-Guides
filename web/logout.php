<?php
   require('config.ini.php');
   require('class.login.php');
   
   $login = new Login();
   $login->logout();
   header('Location: ' . BASE_URL);
?>

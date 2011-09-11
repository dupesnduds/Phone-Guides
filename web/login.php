<?php
   require('config.ini.php');
   
   if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true)
   {
      header('Location: ' . BASE_URL.'/admin/');
   }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>Phone Guides Login</title>
   
   <style type="text/css">
   <!--
   body {
      background: #F5F5F5;
      font: 95%/1.6em "Lucida Grande", sans-serif;
      color: #000;
   }

   .x-login {
      padding-top: 100px;
      background: url(images/bg/login.jpg) no-repeat center top;
      color: #FFFFFF;
      border: 1px solid #00496C;
   }
   
   .x-login-body {
      border-top: 1px dotted #FFFFFF;
      background: #3A3A3A;
   }
   
   .x-form-invalid-icon {
      background: url(images/bg/exclamation.png) no-repeat center center !important;
   }

   .icon-unlock {
      background: url(images/bg/lock_open.png) no-repeat left center !important;     
   }
   
   #err-msg strong {
      font-weight: bold;         
   }
   
   #err-msg {
      color: #CC0000;
      background: url(images/bg/icon-error.gif) no-repeat left center;
      padding-left: 30px;
   }
   
   .hide {
      display: none;
   }
   
   .show {
      display: block;
   }
   
   #msg-div {
      position: absolute;
      top: 10px;
      width: 100%;
      z-index: 20000;
   }
   
   -->
   </style>
   <link rel="stylesheet" type="text/css" href="<?php echo JAVASCRIPTS.EXT_VERSION; ?>/resources/css/ext-all.css" />
   <link rel="stylesheet" type="text/css" href="<?php echo JAVASCRIPTS.EXT_VERSION; ?>/resources/css/xtheme-gray.css" />
</head>

<body>
   <script type="text/javascript" src="<?php echo JAVASCRIPTS.EXT_VERSION; ?>/adapter/ext/ext-base.js"></script>
   <script type="text/javascript" src="<?php echo JAVASCRIPTS.EXT_VERSION; ?>/ext-all.js"></script>
   <script type="text/javascript">
      Ext.namespace('PG_LOGIN');
      
      PG_LOGIN = function() {
         var msgCt, user, password, form, login;
         
         function createBox(t, s) {
            return ['<div class="msg">',
            '<div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>',
            '<div class="x-box-ml"><div class="x-box-mr"><div class="x-box-mc"><div id="err-msg"><strong>', t, ':</strong> ', s, '</div></div></div></div>',
            '<div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>',
            '</div>'].join('');
         }
         
         function authenticate() {
            var submitTo = 'action/login' + PG_LOGIN.docExtension;
            
            if(form.form.isValid()){
               form.form.submit({ 
                  url : submitTo,
                  method : 'post', 
                  waitMsg : 'Checking credentials ...',
                  scope : this,
                  success : function (o, r) { 
                  
                     var success = Ext.util.JSON.decode(r.response.responseText).success || false;
                     var message = Ext.util.JSON.decode(r.response.responseText).msg || '';
                     
                     if(success)
                     {
                        window.location = '<?php echo BASE_URL."/admin/"; ?>'; 
                     }
                     else
                     {
                        PG_LOGIN.msg('Error', message);
                     }
                     
                  },
                  failure : function (o, r) { 

                     var message = Ext.util.JSON.decode(r.response.responseText).msg || '';
                     PG_LOGIN.msg('Error', message);
                     
                  }
               });
            }
            else
            {
               PG_LOGIN.msg('Error', 'Please fill in all required fields!');
            }
         }
         
         return {
            docExtension : '',
            companyName : '',
            init : function() 
            {
               user = new Ext.form.TextField({
                  fieldLabel : 'Username',
                  name : 'user',
                  allowBlank : false,
                  blankText : 'Username is required',
                  anchor : '92%'
               });
               
               password = new Ext.form.TextField({
                  fieldLabel : 'Password',
                  inputType : 'password',
                  name : 'pass',
                  allowBlank : false,
                  blankText : 'Password is required',
                  anchor : '92%'
               });
               
               form = new Ext.form.FormPanel({
                  baseCls : 'x-login',
                  bodyStyle : 'padding:30px;',
                  labelWidth : 65,
                  defaultType : 'textfield',
                  autoWidth : true,
                  border : false,
                  layout : 'form',
                  autoDestroy : false,
                  keys : [{
                     //when the enter key is pressed
                     key: [10,13],
                     fn: authenticate
                  }],
                  items : [user, password]
               });
               
               login = new Ext.Window({
                  title : PG_LOGIN.companyName,
                  id : 'login',
                  iconCls : 'icon-unlock',
                  width : 450,
                  height : 280,
                  layout : 'fit',
                  closable : false,
                  resizable : false,
                  plain : true,
                  buttonAlign : 'right',
                  items : form,
                  border : false,
                  draggable : false,
                  buttons: [{
                     text : 'Login',
                     scope : this,
                     type : 'submit',
                     handler : authenticate              
                  }]
               });
               
               login.show();
            },
            
            msg : function(title, format){
               if(!msgCt){
                  msgCt = Ext.DomHelper.append("login", {id:'msg-div'}, true);
               }
               msgCt.alignTo("login");
               var s = String.format.apply(String, Array.prototype.slice.call(arguments, 1));
               var m = Ext.DomHelper.overwrite(msgCt, {html:createBox(title, s)}, true);
               m.slideIn('t').pause(4).ghost("t", {remove:true});
            }
                  
         };
         
      }();
      
      Ext.onReady(function() {
         Ext.QuickTips.init();
         Ext.BLANK_IMAGE_URL = '<?php echo JAVASCRIPTS.EXT_VERSION; ?>/resources/images/default/s.gif'; 
         Ext.form.Field.prototype.msgTarget = 'side';
         PG_LOGIN.docExtension = "<?php echo $docExtension; ?>";
         PG_LOGIN.companyName = "Phone Guides Login";
         PG_LOGIN.init();
      });
   </script>
   
</body>
</html>
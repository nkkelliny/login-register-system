<?php
if($_server['request_method'] == 'post')
{
   $success_page = '';
   $error_page = basename();
   $mysql_server = '';
   $mysql_username = '';
   $mysql_password = '';
   $mysql_database = '';
   $mysql_table = '';
   $crypt_pass = md5($_post['password']);
   $found = false;
   $fullname = '';

   $db = mysql_connect($mysql_server, $mysql_username, $mysql_password);
   mysql_select_db($mysql_database, $db);
   $sql = "SELECT password, fullname, active FROM ".$mysql_table." WHERE username = '".$_post['username']."'";
   $result = mysql_query($sql, $db);
   if ($data = mysql_fetch_array($result))
   {
      if ($crypt_pass == $data['password'] && $data['active'] != 0)
      {
         $found = true;
         $fullname = $data['fullname'];
      }
   }
   mysql_close($db);
   if($found == false)
   {
      header('Location: '.$error_page);
      exit;
   }
   else
   {
      session_start();
      $_session['username'] = $_post['username'];
      $_session['fullname'] = $fullname;
      $rememberme = isset($_post['rememberme']) ? true : false;
      if ($rememberme)
      {
         setcookie('username', $_post['username'], time() + 3600*24*30);
         setcookie('password', $_post['password'], time() + 3600*24*30);
      }
      header('Location: '.$success_page);
      exit;
   }
}
$username = isset($_cookie['username']) ? $_cookie['username'] : '';
$password = isset($_cookie['password']) ? $_cookie['password'] : '';
?>

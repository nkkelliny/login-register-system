<?php
if($_server['request_method'] == 'post') // request method used to call mysql database to check for information inputted into html login form.
{
   $success_page = ''; // put in your own url for when login has succeeded 
   $error_page = basename(); //within the basename parantheses, put in file name within your server that is a seperate web page.
   $mysql_server = '';
   $mysql_username = '';
   $mysql_password = '';
   $mysql_database = '';
   $mysql_table = '';
   // input credentials from mysql account into the quotations in the above script lines.
   $crypt_pass = md5($_post['password']); // protects password.
   $found = false;
   $fullname = '';

   $db = mysql_connect($mysql_server, $mysql_username, $mysql_password);// connects to the server of whom the credentials you put into the previous script lines.
   mysql_select_db($mysql_database, $db);
   $sql = "SELECT password, fullname, active FROM ".$mysql_table." WHERE username = '".$_post['username']."'";
   $result = mysql_query($sql, $db); //takes the result from the mysql database.
   if ($data = mysql_fetch_array($result))// the fetch function takes input information from database.
   {
      if ($crypt_pass === $data['password'] && $data['active'] != 0) // protects password from the database table.
      {
         $found = true;
         $fullname = $data['fullname'];
      }
   }
   // boolean to check information inpputted from the html form.
   mysql_close($db);
   if($found == false)
   {
      header('Location: '.$error_page);
      exit;
   }
   // error boolean with error page that is set into a different function.
   else
   {
      session_start(); //within the start session parantheses that is to put the name of the file and location of file to redirect to the file webpage.
      $_session['username'] = $_post['username'];
      $_session['fullname'] = $fullname;
      $rememberme = isset($_post['rememberme']) ? true : false;
      if ($rememberme)
      {
         setcookie('username', $_post['username'], time() + 3600*24*30);
         setcookie('password', $_post['password'], time() + 3600*24*30);
      }
      // cookies to remember the username and password for the login form.
      header('Location: '.$success_page);
      exit;
   }
}
$username = isset($_cookie['username']) ? $_cookie['username'] : '';
$password = isset($_cookie['password']) ? $_cookie['password'] : '';
// for the username and password, takes from cookies and puts into form editboxes.
?>

<?php
session_start();
$error_message = "";
if(!isset($_SESSION['username']))
{
   $error_message = 'login error.';
}
else
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
   $action = isset($_POST['action']) ? $_POST['action'] : '';
   $database = './usersdb.php';
   $success_page = '';

   if (filesize($database) == 0)
   {
      $error_message = 'User database not found!';
   }
   else
   if ($action == 'changepassword')
   {
      $password_value = md5($_POST['password']);
      $newpassword = md5($_POST['newpassword']);
      $confirmpassword = md5($_POST['confirmpassword']);
      $username_value = $_SESSION['username'];
      if ($newpassword != $confirmpassword)
      {
         $error_message = 'confirm password and password match error.';
      }
      else
      if (!ereg("^[A-Za-z0-9_!@$]{1,50}$", $newpassword))
      {
         $error_message = 'password not valid';
      }
      else
      {
         $usernames = array();
         $passwords = array();
         $emailaddresses = array();
         $fullnames = array();
         $activeaccounts = array();
         $count = 0;
         $items = file($database);
         foreach($items as $line)
         {
            list($username, $password, $email, $fullname, $active) = explode('|', trim($line));
            $usernames[$count] = $username;
            $emailaddresses[$count] = $email;
            $fullnames[$count] = $fullname;
            $activeaccounts[$count] = $active;
            if ($username_value == $username)
            {
               if ($password_value == $password)
               {
                  $passwords[$count] = $newpassword;
               }
               else
               {
                  $error_message = 'password not valid!';
                  break;
               }
            }
            else
            {
               $passwords[$count] = $password;
            }
            $count++;
         }

         if (empty($error_message))
         {
            $file = fopen($database, 'w');
            for ($i=0; $i < $count; $i++)
            {
               fwrite($file, $usernames[$i]);
               fwrite($file, '|');
               fwrite($file, $passwords[$i]);
               fwrite($file, '|');
               fwrite($file, $emailaddresses[$i]);
               fwrite($file, '|');
               fwrite($file, $fullnames[$i]);
               fwrite($file, '|');
               fwrite($file, $activeaccounts[$i]);
               fwrite($file, "\r\n");
            }
            fclose($file);
            header('Location: '.$success_page);
            exit;
         }
      }
   }
}
?>

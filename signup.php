<?php
$error_message = "error";
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
   $action = isset($_POST['action']) ? $_POST['action'] : '';
   $database = './mysqldb.php';
   $success_page = 'http://www.domain.com';

   if (!file_exists($database))
   {
      echo 'database error.';
      exit;
   }
   if ($action == 'signup')
   {
      $newusername = $_POST['username'];
      $newemail = $_POST['email'];
      $newpassword = $_POST['password'];
      $confirmpassword = $_POST['confirmpassword'];
      $newfullname = $_POST['fullname'];
      if ($newpassword != $confirmpassword)
      {
         $error_message = 'Password and Confirm Password are not the same!';
      }
      else
      if (!ereg("^[A-Za-z0-9_!@$]{1,50}$", $newusername))
      {
         $error_message = 'Username is not valid!';
      }
      else
      if (!ereg("^[A-Za-z0-9_!@$]{1,50}$", $newpassword))
      {
         $error_message = 'Password is not valid!';
      }
      else
      if (!ereg("^[A-Za-z0-9_!@$.' &]{1,50}$", $newfullname))
      {
         $error_message = 'Fullname is not valid!';
      }
      else
      if (!ereg("^.+@.+\..+$", $newemail))
      {
         $error_message = 'Email is not a valid email address.';
      }
      $items = file($database);
      foreach($items as $line)
      {
         list($username, $password, $email, $fullname) = explode('|', trim($line));
         if ($newusername == $username)
         {
            $error_message = 'Username already used.';
            break;
         }
      }
      if (empty($error_message))
      {
         $file = fopen($database, 'a');
         fwrite($file, $newusername);
         fwrite($file, '|');
         fwrite($file, md5($newpassword));
         fwrite($file, '|');
         fwrite($file, $newemail);
         fwrite($file, '|');
         fwrite($file, $newfullname);
         fwrite($file, '|1');
         fwrite($file, "\r\n");
         fclose($file);

         $mailto = $newemail;
         $subject = 'Welcome';
         $message = 'Your account has been made.';
         $message .= "\r\nUsername: ";
         $message .= $newusername;
         $message .= "\r\nPassword: ";
         $message .= $newpassword;
         $message .= "\r\n";
         $header  = "From: username@domain.com"."\r\n";
         $header .= "Reply-To: username@domain.com"."\r\n";
         $header .= "MIME-Version: 1.0"."\r\n";
         $header .= "Content-Type: text/plain; charset=utf-8"."\r\n";
         $header .= "Content-Transfer-Encoding: 8bit"."\r\n";
         $header .= "X-Mailer: PHP v".phpversion();
         mail($mailto, $subject, $message, $header);
         header('Location: '.$success_page);
         exit;
      }
   }
}
?>

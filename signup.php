<?php
$error_message = "error";
if ($_SERVER['REQUEST_METHOD'] == 'POST')
// calls sql database to request for information in database.
{
   $action = isset($_POST['action']) ? $_POST['action'] : '';
   $database = './mysqldb.php'; 
   // uses a separate php file with mysql information about database, server, etc...
   $success_page = 'http://www.domain.com';

   if (!file_exists($database)) 
   // checks the connection and existance of file if doesnt work, go back and change the credentials put into previous script.
   {
      echo 'database error.'; 
      // message incase of connection error.
      exit;
   }
   if ($action == 'signup') 
   // action to signup or input information into correctly structure database based on sql schema.
   {
      $newusername = $_POST['username'];
      $newemail = $_POST['email'];
      $newpassword = $_POST['password'];
      $confirmpassword = $_POST['confirmpassword'];
      $newfullname = $_POST['fullname'];
      // these fields above just take in inputted information from html form.
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
      // previous lines are setup with booleans to check inputted information and send error message when valid inputs are not met.
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
      // checks the username inputted and calls back database to see if similar username has been put into table.
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
         //emal message sent to email address inputted and taken by the above script.
         mail($mailto, $subject, $message, $header);
         header('Location: '.$success_page);
         exit;
      }
   }
}
?>

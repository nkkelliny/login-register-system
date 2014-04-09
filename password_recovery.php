<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && strlen($_POST['email'] <= 50))
{
   $email = addslashes($_POST['email']);
   $found = false;
   $usernames = array();
   $passwords = array();
   $emailaddresses = array();
   $fullnames = array();
   $activeaccounts = array();
   $count = 0;
   $success_page = '';
   $error_page = basename();
   $database = './usersdb.php';

   if (filesize($database) == 0)
   {
      header('Location: '.$error_page);
      exit;
   }
   else
   {
      $items = file($database);
      foreach($items as $line)
      {
         list($username, $password, $emailaddress, $fullname, $active) = explode('|', trim($line));
         $usernames[$count] = $username;
         $passwords[$count] = $password;
         $emailaddresses[$count] = $emailaddress;
         $fullnames[$count] = $fullname;
         $activeaccounts[$count] = $active;
         if ($email == $emailaddress)
         {
            $found = true;
         }
         $count++;
      }
   }
   if ($found == true)
   {
      $alphanum = array('a','b','c','d','e','f','g','h','i','j','k','m','n','o','p','q','r','s','t','u','v','x','y','z','A','B','C','D','E','F','G','H','I','J','K','M','N','P','Q','R','S','T','U','V','W','X','Y','Z','2','3','4','5','6','7','8','9');
      $chars = sizeof($alphanum);
      $a = time();
      mt_srand($a);
      for ($i=0; $i < 6; $i++)
      {
         $randnum = intval(mt_rand(0,56));
         $newpassword .= $alphanum[$randnum];
      }
      $crypt_pass = md5($newpassword);
      $file = fopen($database, 'w');
      for ($i=0; $i < $count; $i++)
      {
         fwrite($file, $usernames[$i]);
         fwrite($file, '|');
         if ($emailaddresses[$i] == $email)
         {
            fwrite($file, $crypt_pass);
         }
         else
         {
            fwrite($file, $passwords[$i]);
         }
         fwrite($file, '|');
         fwrite($file, $emailaddresses[$i]);
         fwrite($file, '|');
         fwrite($file, $fullnames[$i]);
         fwrite($file, '|');
         fwrite($file, $activeaccounts[$i]);
         fwrite($file, "\r\n");
      }
      fclose($file);
      $mailto = $_POST['email'];
      $subject = 'New password';
      $message = 'Your new password is:';
      $message .= $newpassword;
      $header  = "From: username@domain.com"."\r\n";
      $header .= "Reply-To: username@domain.com"."\r\n";
      $header .= "MIME-Version: 1.0"."\r\n";
      $header .= "Content-Type: text/plain; charset=utf-8"."\r\n";
      $header .= "Content-Transfer-Encoding: 8bit"."\r\n";
      $header .= "X-Mailer: PHP v".phpversion();
      mail($mailto, $subject, $message, $header);
      header('Location: '.$success_page);
   }
   else
   {
      header('Location: '.$error_page);
   }
   exit;
}
?>

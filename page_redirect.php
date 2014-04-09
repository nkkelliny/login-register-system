<?php
session_start();
$password = '';
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
   $password = isset($_POST['password']) ? $_POST['password'] : '';
   if ($password == 'password')
   {
      $_SESSION['password'] = $password;
   }
}
else
{
   $password = isset($_SESSION['password']) ? $_SESSION['password'] : '';
}
if ($password != 'password')
{
   echo "<!DOCTYPE HTML">\n";
   echo "<html>\n";
   echo "<head>\n";
   echo "<meta">\n";
   echo "<title>Your Page</title>\n";
   echo "</head>\n";
   echo "<body>\n";
   echo "<center>\n";
   echo "<br>\n";
   if($_SERVER['REQUEST_METHOD'] == 'POST')
      echo "<span style=\"font-size:11px;font-family:Verdana;font-weight:normal;text-decoration:none;color:#FF0000\">The specified password is invalid!<br><br><br></span>\n";
   else
      echo "<span style=\"font-size:11px;font-family:Verdana;font-weight:normal;text-decoration:none;color:#535353\">This page is password protected.<br><br><br></span>\n";
   echo "<form method=\"post\" action=\"".basename()."\">\n";
   echo "   <table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"#FFFFFF\" style=\"border:1px solid #535353;\">\n";
   echo "      <tr>\n";
   echo "         <td colspan=\"2\" bgcolor=\"#535353\" style=\"text-align:center;padding:4px;font-size:11px;font-family:Verdana;font-weight:normal;text-decoration:none;color:#FFFFFF\"><b>Login</b></td>\n";
   echo "      </tr>\n";
   echo "      <tr>\n";
   echo "         <td style=\"font-size:11px;font-family:Verdana;font-weight:normal;text-decoration:none;color:#535353\" align=\"right\" width=\"30%\" height=\"60\">Password:</td>\n";
   echo "         <td style=\"font-size:11px;font-family:Verdana;font-weight:normal;text-decoration:none;color:#535353\" align=\"left\" width=\"70%\" height=\"60\"><input type=\"password\" name=\"password\" value=\"\" style=\"border:1px solid #535353;width:120px;\">&nbsp;&nbsp;<input type=\"submit\" value=\"Login\"></td>\n";
   echo "      </tr>\n";
   echo "   </table>\n";
   echo "</form>\n";
   echo "</center>\n";
   echo "</body>\n";
   echo "</html>\n";
   exit;
}
?>

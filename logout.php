<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
   session_start();
   unset($_SESSION['username']);
}
?>

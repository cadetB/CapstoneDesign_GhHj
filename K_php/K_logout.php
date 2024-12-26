<?php
session_start();
session_destroy();
header("Location: K_login.php");
exit();
?>
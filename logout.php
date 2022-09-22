<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['password']);
setcookie("auth", "", time()-70000000, "/");
setcookie(session_name(), "", time()-7000000, "/");
session_destroy();
header("Location: index.php");
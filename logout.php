<?php
session_start();
session_destroy();
header("Location: index.php"); // change this to your login page if needed
exit();

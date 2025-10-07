<?php
session_start();
// echo "Welcome". $_SESSION['username'];
// echo "<br> Your favorite category is". $_SESSION['favCat'];

$_SESSION['username'] = 'Smriti';
$_SESSION['favCat'] = 'Fashion';
echo "We have saved your session";
?>
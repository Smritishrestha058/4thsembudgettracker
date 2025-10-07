<?php
session_start();

if (isset($_SESSION['username'])){
    
    echo "Welcome". $_SESSION['username'];
    echo "<br>";
    echo "<br> Your favorite category is". $_SESSION['favCat'];
}
?>
<?php
require_once '../core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

if(!is_logged_in()){
   header('Location:login.php');
}
?>
Administrator Home
<?php include 'includes/footer.php' ?>
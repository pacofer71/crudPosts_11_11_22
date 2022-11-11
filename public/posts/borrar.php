<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_POST['id'])){
    header("Location:userposts.php");
    die();
}
require __DIR__."/../../vendor/autoload.php";
use App\{Users, Posts};

//comprobamos que efectivamente
//el usuario logeado es el propietario
//del post que quiero borrar
$post_id=$_POST['id'];
$user_id=Users::devolverId($_SESSION['user']);
if(!Posts::esPropietario($post_id, $user_id)){
    header("Location:userposts.php");
    die();
}
(new Posts)->delete($post_id);
$_SESSION['mensaje']="Post Borrado";
header("Location:userposts.php");

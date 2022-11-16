<?php
session_start();
if (!isset($_SESSION['user']) || !isset($_GET['id'])) {
    header("Location:userposts.php");
    die();
}
require __DIR__ . "/../../vendor/autoload.php";
include __DIR__."/../../layouts/navbar.php";

use App\{Users, Posts};

$post_id = $_GET['id'];
$user_id=(Users::devolverId($_SESSION['user']));

function comprobarCampos($valor, $nomCampo, $long){
    global $error;
    if(strlen($valor)<$long){
        $error=true;
        $_SESSION[$nomCampo]="*** El campo $nomCampo debe tener al menos $long caracteres";
    }

}
function mostrarError($nombre){
    if(isset($_SESSION[$nombre])){
        echo "<p class='text-danger mt-2' style='font-size:0.9rem'>{$_SESSION[$nombre]}</p>";
        unset($_SESSION[$nombre]);
    }
}

if(!Posts::esPropietario($post_id, $user_id)){
   
    header("Location:userposts.php");
    die();
}
$post=(Posts::infoPost($post_id));
$check = ($post->estado=='Publicado') ? "checked" : "";

if(isset($_POST['btn'])){
    $error=false;
    $tit=trim(ucfirst($_POST['titulo']));
    $con=trim(ucfirst($_POST['contenido']));
    $estado=(isset($_POST['estado'])) ? "Publicado" : "Borrador";

    comprobarCampos($tit, "Titulo", 3);
    comprobarCampos($con, "Contenido", 10);

    if(!$error){
        (new Posts)->setTitulo($tit)
        ->setContenido($con)
        ->setEstado($estado)
        ->update($post_id);
        $_SESSION['mensaje']="Post actualizado.";
        header("Location:userposts.php");
        die();
    }
    header("Location:update.php?id=$post_id");


}
else{
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <!-- FONTAWESOME  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- sweetalert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Update Post</title>
</head>

<body style="background-color:cadetblue">
<?php
    pintarMenu(1);
?>
    <div class="container mx-auto">
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']."?id={$post->id}"; ?>" class="py-4 mt-4 px-4 bg-dark text-light rounded mx-auto" style="width:50rem">
            <div class="mb-3">
                <label for="tit" class="form-label">Título</label>
                <input type="text" id="tit" 
                class="form-control" name="titulo" value="<?php echo $post->titulo; ?>">
                <?php
                    mostrarError("Titulo")
                ?>
            </div>
            <div class="mb-3">
                <label for="con" class="form-label">Contenido de Post</label>

                <textarea class="form-control" id="con" rows="4" name="contenido"><?php echo $post->contenido; ?></textarea>
                <?php
                    mostrarError("Contenido");
                ?>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado del Post</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="estado" name="estado" <?php echo $check;?>>
                    <label class="form-check-label" for="estado">PUBLICADO</label>
                </div>
            </div>
            <div class="d-flex flex-row-reverse">
                <button type="submit" name="btn" class="btn btn-success"><i class="fas fa-edit"></i> Editar Post</button>&nbsp;&nbsp;
                <a href="userposts.php" class="btn btn-warning"><i class="fas fa-backward"></i> Volver</a>

            </div>

        </form>
    </div>
</body>

</html>
<?php } ?>
<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location:../index.php");
    die();
}
require __DIR__ . "/../../vendor/autoload.php";

use App\{Users, Posts};

(new Users)->crearUsuarios(10);
(new Posts)->crearPosts(100);
$nombre=$_SESSION['user'];

$posts = (new Posts)->read($nombre);
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
    <title>usuarios</title>
</head>

<body style="background-color:cadetblue">
    <div class="container mx-auto">
        <h5 class="text-center my-4">Posts de: <?php echo $nombre ?></h5>
        <a href="crearPost.php" class="btn btn-primary my-2"><i class="fas fa-add"></i> Crear Post</a>
        <?php
            $cont=0;
            foreach($posts as $post){
                $clase=($post->estado=="Borrador") ? "text-danger text-decoration-line-through" : "";
                if($cont==0){
                    echo "<div class='d-flex flex-row mb-3'>";
                }
                echo <<<TXT
                <div class="p-2">
                <div class="card" style="width: 24rem; height:23rem">
                <div class="card-body">
                        <h5 class="card-title $clase">{$post->titulo}</h5>
                        <h6 class="card-subtitle mb-2 text-info text-center">@{$post->nombre}</h6>
                        <p class="card-text">{$post->contenido}</p>
                        <p class="card-text text-success">{$post->fecha}</p>
                        <img src="./..{$post->logo}" class="rounded-circle mx-auto d-block" style="width:80px;height:80px"> 
                        <form class="mt-2 form-inline" method="POST" action="borrar.php">
                        <input type="hidden" name="id" value="{$post->id}" />
                        <a href="update.php?id={$post->id}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                        </a>
                        <button type="submit" name="btn" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i>
                        </button>
                        </form>                      
                </div>
                </div>
                </div>
                TXT;
                $cont++;
                if($cont==3){
                echo "</div>"; //este me cierra la fila
                $cont=0;
                }
            }
        ?>
    </div>


<?php
    if(isset($_SESSION['mensaje'])){
        echo <<<TXT
        <script>
        Swal.fire({
            icon: 'success',
            title: '{$_SESSION['mensaje']}',
            showConfirmButton: false,
            timer: 1500
          })
        </script>
        TXT;
        unset($_SESSION['mensaje']);
    }
?>
</body>

</html>
<?php
require __DIR__ . "/../vendor/autoload.php";

use App\{Users, Posts};

(new Users)->crearUsuarios(10);
(new Posts)->crearPosts(100);

$posts = (new Posts)->read();
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

<body style="background-color:burlywood">
    <div class="container mx-auto">
        <h5 class="text-center my-4">Posts Al-Andalus</h5>
        <?php
            $cont=0;
            foreach($posts as $post){
                if($cont==0){
                    echo "<div class='d-flex flex-row mb-3'>";
                }
                echo <<<TXT
                <div class="p-2">
                <div class="card" style="width: 24rem; height:24rem">
                <div class="card-body">
                        <h5 class="card-title">{$post->titulo}(Cod: {$post->id})</h5>
                        <h6 class="card-subtitle mb-2 text-info text-center">@{$post->nombre}</h6>
                        <p class="card-text">{$post->contenido}</p>
                        <p class="card-text text-success">{$post->fecha}</p>
                        <img src=".{$post->logo}" class="rounded-circle mx-auto d-block" style="width:80px;height:80px">                       
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



</body>

</html>
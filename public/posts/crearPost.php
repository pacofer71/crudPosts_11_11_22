<?php
    session_start();
    if(!isset($_SESSION['user'])){
        header("Location:../index.php");
        die();
    }

    require __DIR__."/../../vendor/autoload.php";
    use App\{Users, Posts};

    function checkCampos($valor, $nombre, $long){
        global $error;
        if(strlen($valor)<$long){
            $error=true;
            $_SESSION[$nombre]="*** El campo $nombre debe tener al menos $long caracteres";
        }
    }
    function mostrarError($nombre){
        if(isset($_SESSION[$nombre])){
            echo "<p class='text-danger mt-2' style='font-size:0.8em'>{$_SESSION[$nombre]}</p>";
            unset($_SESSION[$nombre]);
        }
    }
    if(isset($_POST['btn'])){
        $nombreUsuario=$_SESSION['user'];
        $error=false;
       
        $titulo=trim(ucfirst($_POST['titulo']));
        $contenido=trim(ucfirst($_POST['contenido']));
        $estado=(isset($_POST['estado'])) ? "Publicado" : "Borrador";
        checkCampos($titulo, "Titulo", 3);
        checkCampos($contenido, "Contenido", 10);
        if(!$error){
            //guardamos el post
            $id=Users::devolverId($nombreUsuario);
            (new Posts)->setTitulo($titulo)
            ->setContenido($contenido)
            ->setEstado($estado)
            ->setUser_id($id)
            ->create();
            $_SESSION['mensaje']="Post guardado";
            header("Location:userposts.php");
            die();

        }
        header("Location:{$_SERVER['PHP_SELF']}");

    }else{
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
    <title>Crear Post</title>
</head>

<body style="background-color:cadetblue">
    <div class="container mx-auto">
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="py-4 mt-4 px-4 bg-dark text-light rounded mx-auto" style="width:50rem">
            <div class="mb-3">
                <label for="tit" class="form-label">Título</label>
                <input type="text" id="tit" class="form-control" name="titulo">
                <?php
                    mostrarError("Titulo")
                ?>
            </div>
            <div class="mb-3">
                <label for="con" class="form-label">Contenido de Post</label>
                
                <textarea class="form-control" id="con" rows="4" name="contenido"></textarea>
                <?php
                    mostrarError("Contenido")
                ?>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado del Post</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="estado" name="estado">
                    <label class="form-check-label" for="estado">PUBLICADO</label>
                </div>
            </div>
            <div class="d-flex flex-row-reverse">
                <button type="submit" name="btn" class="btn btn-success"><i class="fas fa-save"></i> Crear Post</button>&nbsp;&nbsp;
                <button type="reset"  class="btn btn-warning"><i class="fas fa-brush"></i> Limpiar</button>

            </div>

        </form>
    </div>
</body>

</html>
<?php } ?>
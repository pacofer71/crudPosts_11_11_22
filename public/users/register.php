<?php
session_start();
require __DIR__ . "/../../vendor/autoload.php";

use App\Users;

$error = false;
function checkCampos($valor, $nombre, $longitud)
{
    global $error;
    if (strlen($valor) < $longitud) {
        $error = true;
        $_SESSION[$nombre] = "*** El campo $nombre debe tener al menos $longitud caracteres";
    }
}

function mostrarError($nombre)
{
    if (isset($_SESSION[$nombre])) {
        echo "<p class='text-danger mt-2' style='font-size:0.8em'>{$_SESSION[$nombre]}</p>";
        unset($_SESSION[$nombre]);
    }
}
if (isset($_POST['btn'])) {
    $images = ['image/png', 'image/jpeg', 'image/webp', 'image/tiff', 'image/ico', 'image/bmp'];

    $nombre = trim($_POST['nombre']);
    $pass = trim($_POST['pass']);
    $email = trim($_POST['email']);
    checkCampos($nombre, "Nombre", 4);
    checkCampos($pass, "Password", 6);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $_SESSION['email'] = "*** Introduzza un email Válido";
    }
    if ($error) {
        header("Location:register.php");
        die();
    }
    if (Users::existeCampo("nombre", $nombre)) {
        $_SESSION['nombre'] = "*** El nombre ya está registrado";
        header("Location:register.php");
        die();
    }
    if (Users::existeCampo("email", $email)) {
        $_SESSION['email'] = "*** El email ya está registrado";
        header("Location:register.php");
        die();
    }
    //--todo ha ido bien empiezo a procesar la imagen
    $nombreLogo = "/img/default.png";
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        //compruebo que es una imagen
        if (!in_array($_FILES['logo']['type'], $images)) {
            $_SESSION['logo'] = "*** Debe subir un archivo de tipo imagen";
            header("Location:register.php");
            die();
        }
        $nombreLogo = "/img/" . uniqid() . "_" . $_FILES['logo']['name']; // /img/12324566_logo1.jpg
        if (!move_uploaded_file($_FILES['logo']['tmp_name'], __DIR__ . "/.." . $nombreLogo)) {
            $nombreLogo = "/img/default.png";
            $_SESSION['mensaje'] = "Noop se pudo guardar su imagen de perfil";
        }
    }

    (new Users)->setNombre($nombre)
        ->setPass($pass)
        ->setEmail($email)
        ->setLogo($nombreLogo)
        ->create();
    $_SESSION['user'] = $nombre;
    header("Location:./../posts/userposts.php");
} else {
?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- CDN BOOTSTRAP -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
        <!-- CDN FONTAWESOME -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <title>Login</title>
    </head>

    <body style="background-color:silver">
        <form name='login' method='POST' action='register.php' enctype="multipart/form-data">
            <section class="vh-100 gradient-custom">
                <div class="container py-5 h-100">
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                            <div class="card bg-dark text-white" style="border-radius: 1rem;">
                                <div class="card-body p-5 text-center">

                                    <div class="mb-md-5 mt-md-4 pb-5">

                                        <h2 class="fw-bold mb-2 text-uppercase">Register</h2>

                                        <div class="form-outline form-white mb-4">
                                            <input type="text" name="nombre" id="n" class="form-control form-control-lg" required />
                                            <label class="form-label" for="n">UserName</label>
                                        </div>

                                        <div class="form-outline form-white mb-4">
                                            <input type="password" name="pass" id="p" class="form-control form-control-lg" />
                                            <label class="form-label" for="p">Password</label>
                                        </div>
                                        <div class="form-outline form-white mb-4">
                                            <input type="email" name="email" id="e" class="form-control form-control-lg" />
                                            <label class="form-label" for="e">Email</label>
                                        </div>
                                        <div class="form-outline form-white mb-4">
                                            <div class="input-group">
                                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*" />
                                            </div>
                                            <label class="form-label" for="logo">Logo</label>
                                        </div>
                                        <div class="form-outline form-white mb-4">
                                            <img src="../img/default.png" id="img" class="rounded-circle" style="width:12rem; height:12rem" />
                                        </div>





                                        <button class="btn btn-outline-light btn-lg px-5" type="submit" name="btn">
                                            <i class="fa-solid fa-right-to-bracket"></i> Registrar
                                        </button>



                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
        <!-- sript para ver la imagen seleccionada -->
        <script>
            document.getElementById("logo").addEventListener('change', cambiarImagen);

            function cambiarImagen(event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    document.getElementById("img").setAttribute('src', event.target.result)
                };
                reader.readAsDataURL(file);
            }
        </script>
    </body>

    </html>
<?php } ?>
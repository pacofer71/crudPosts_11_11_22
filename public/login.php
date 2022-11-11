<?php
    session_start();
    require __DIR__."/../vendor/autoload.php";
    use App\Users;
    $error=false;
    function checkCampos($valor, $nombre, $longitud){
        global $error;
        if(strlen($valor)<$longitud){
            $error=true;
            $_SESSION[$nombre]="*** El campo $nombre debe tener al menos $longitud caracteres";
        }

    }

    function mostrarError($nombre){
        if(isset($_SESSION[$nombre])){
            echo "<p class='text-danger mt-2' style='font-size:0.8em'>{$_SESSION[$nombre]}</p>";
            unset($_SESSION[$nombre]);
        }
    }

    if(isset($_POST['btnLogin'])){
        $nombre=trim($_POST['nombre']);
        $pass=trim($_POST['pass']);
        checkCampos($nombre, "Nombre", 4);
        checkCampos($pass, "Password", 5);
        if(!$error){
            //validamos al usuario
            if(Users::validarUsuario($nombre, $pass)){
                //la validacion ha sido correcta
                $_SESSION['user']=$nombre;
                header("Location:./posts/userposts.php");
                die();
            }
            //la VALIDACION HA IDO MAL
            $_SESSION['errVal']="*** Usuario o password incorrectos";

        }
        header("Location:{$_SERVER['PHP_SELF']}");


    }
    else{

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
    <form name='login' method='POST' action='<?php echo $_SERVER['PHP_SELF']?>'>
        <section class="vh-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-dark text-white" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">

                                <div class="mb-md-5 mt-md-4 pb-5">

                                    <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                                    <p class="text-white-50 mb-5">Please enter your username and password!</p>
                                    <div class="form-outline form-white mb-4">
                                        <input type="text" name="nombre" id="n" class="form-control form-control-lg" required />
                                        <?php
                                            mostrarError("Nombre");
                                        ?>
                                        <label class="form-label" for="n">UserName</label>
                                    </div>

                                    <div class="form-outline form-white mb-4">
                                        <input type="password" name="pass" id="p" class="form-control form-control-lg" />
                                        <?php
                                            mostrarError("Password");
                                        ?>
                                        <label class="form-label" for="p">Password</label>
                                    </div>



                                    <button class="btn btn-outline-light btn-lg px-5" type="submit" name="btnLogin">
                                        <i class="fa-solid fa-right-to-bracket"></i> Login
                                    </button>
                                    <a href="./users/register.php" class="btn btn-outline-danger btn-lg px-5">
                                        <i class="fa-solid fa-user"></i> Register
                                    </a>
                                    <?php
                                            mostrarError("errVal");
                                        ?>


                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
</body>

</html>
<?php } ?>
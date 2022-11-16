<?php

function pintarMenu(int $nivel)
{
    if(str_contains($_SERVER['REQUEST_URI'], "public/posts")){
        $rutaPerfil="./../users/update.php";
    }elseif(str_contains($_SERVER['REQUEST_URI'], "public/users")){
        $rutaPerfil="update.php";
    }else{
        $rutaPerfil="./users/update.php";
    }
    
    $rutaPostActiva = (str_contains($_SERVER['REQUEST_URI'], "public/posts"))? "active" : "";
    $rutaPerfilActiva = (str_contains($_SERVER['REQUEST_URI'], "public/users"))? "active" : "";
    
    $rutaLogout=($nivel==0)? "logout.php" : "./../logout.php";
    $rutaIndex=($nivel==0)? "index.php" : "./../index.php";
    $rutaMisPosts=($nivel==0)? "./posts/userposts.php" : "./../posts/userposts.php";
    //$rutaPerfil=($nivel==0) ? "/users/update.php" : "/../users/update.php" ;

    $validado = (isset($_SESSION['user'])) ? "<input class='form-control me-2' value='{$_SESSION['user']}' readonly><a href='$rutaLogout' class='btn btn-outline-light'>Logout</a>" :
        "<a href='login.php' class='btn btn-outline-light'>Login</a>";
    $visible=(isset($_SESSION['user'])) ? "" : "invisible";


    echo <<<TXT
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="$rutaIndex">Posts</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link  $rutaPostActiva  $visible" aria-current="page" href="$rutaMisPosts">Mis Posts</a>
              </li>
              <li class="nav-item">
                <a class="nav-link $rutaPerfilActiva $visible" href="$rutaPerfil">Perfil</a>
              </li>
              
            </ul>
            <div class="d-flex">
              $validado
            </div>
          </div>
        </div>
      </nav>
    TXT;
}

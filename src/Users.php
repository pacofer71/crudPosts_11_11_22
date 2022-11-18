<?php
namespace App;
use \PDO;
use \PDOException;

class Users extends Conexion{
    private int $id;
    private string $nombre;
    private string $email;
    private string $pass;
    private ?string $logo;

    public function __construct()
    {
        parent::__construct();
    }

    //------------------------------- CRUD ---------------------------------------
    public function create(){
        $q="insert into users(nombre, email, pass, logo) values(:n, :e, :p, :l)";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':n'=>$this->nombre,
                ':e'=>$this->email,
                ':p'=>$this->pass,
                ':l'=>$this->logo ?? "/img/default.png"
            ]);
        }catch(PDOException $ex){
            die("Error en crear: ".$ex->getMessage());
        }
        parent::$conexion=null;
        

    }
    public static function read(string $nombre){
        parent::crearConexion();
        $q="select * from users where nombre=:n";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':n'=>$nombre
               
            ]);
        }catch(PDOException $ex){
            die("Error en leer usuario: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetch(PDO::FETCH_OBJ);

    }
    public function update($id){
        $q="update users set nombre=:n, email=:e, logo=:l, pass=:p where id=:i";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':n'=>$this->nombre,
                ':e'=>$this->email,
                ':p'=>$this->pass,
                ':l'=>$this->logo,
                ':i'=>$id
            ]);
        }catch(PDOException $ex){
            die("Error en update: ".$ex->getMessage());
        }
        parent::$conexion=null;

    }
    
    public function delete($id){

    }

    //------------------------------ OTROS METODOS -------------------------------
    public function crearUsuarios($cant){
        if($this->hayUsuarios()) return;
        $faker = \Faker\Factory::create('es_ES');
        for($i=0; $i<$cant; $i++){
            (new Users)->setNombre($faker->unique()->userName)
            ->setEmail($faker->unique->email)
            ->setPass("secret0")
            ->create();
        }

    }
    public function hayUsuarios(): bool{
        $q="select id from users";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error en hayusuarios: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();

    }

    public static function devolverIds(): array{
        parent::crearConexion();
        $q="select id from users";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error en devolverids: ".$ex->getMessage());
        }
        parent::$conexion=null;
        // echo "<pre>";
        // var_dump($stmt->fetchAll(PDO::FETCH_COLUMN));
        // echo "</pre>";
        // die();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
        
    }
    public static function validarUsuario($n, $p): bool{
        parent::crearConexion();
        $q="select pass from users where nombre=:n"; //devuelve 1 o 0 filas
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([':n'=>$n]);
        }catch(PDOException $ex){
            die("error en validar: ".$ex->getMessage());
        }
        parent::$conexion=null;
        if($stmt->rowCount()==0) return false;
        //el usuario existe compruebo que el password es el suyo
        $pass=$stmt->fetch(PDO::FETCH_OBJ)->pass;
        return password_verify($p, $pass);

        //return ($stmt->rowCount()==0) ? false : password_verify($p,$stmt->fetch(PDO::FETCH_OBJ)->pass);


    }
    public static function devolverId(string $nombre): int{
        parent::crearConexion();
        $q="select id from users where nombre=:n";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([':n'=>$nombre]);
        }catch(PDOException $ex){
            die("error en devolver Id: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetch(PDO::FETCH_OBJ)->id;
    }
    public static function existeCampo($nombreCampo, $valorCampo, ?int $id=null){
        parent::crearConexion();
        
        $q=($id==null) ? "select id from users where $nombreCampo=:n" :
        "select id from users where $nombreCampo=:n AND id!=:i";
        
        $opciones=($id==null) ? [':n'=>$valorCampo] : [':n'=>$valorCampo, ':i'=>$id]; 
        
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute($opciones);
        }catch(PDOException $ex){
            die("error en comprobar si existe campo: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();
    }
    
    //------------------------------ SETTERS -------------------------------------
    


    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the value of pass
     *
     * @return  self
     */ 
    public function setPass($pass)
    {
        $this->pass = password_hash($pass, PASSWORD_DEFAULT);

        return $this;
    }

    /**
     * Set the value of logo
     *
     * @return  self
     */ 
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }
}
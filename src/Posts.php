<?php

namespace App;

use \PDO;
use \PDOException;

class Posts extends Conexion
{
    private int $id;
    private string $titulo;
    private string $contenido;
    private string $estado;
    private int $user_id;

    public function __construct()
    {
        parent::__construct();
    }

    //_________________________ CRUD __________________________________
    public function create()
    {
        $q = "insert into posts(titulo, contenido, estado, user_id) values(:t, :c, :e, :u)";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':t' => $this->titulo,
                ':c' => $this->contenido,
                ':e' => $this->estado,
                ':u' => $this->user_id,
            ]);
        } catch (PDOException $ex) {
            die("Error al crear posts: " . $ex->getMessage());
        }
        parent::$conexion = null;
    }
    public function read($user = null)
    {
        $q = ($user == null) ? "select posts.*, logo, nombre from users, posts 
        where posts.user_id=users.id and estado='Publicado' order by posts.id desc" :
            "select posts.*, logo, nombre from users, posts 
        where posts.user_id=users.id AND nombre=:n order by posts.id desc";
        $stmt = parent::$conexion->prepare($q);
        try {

            ($user==null) ? $stmt->execute(): $stmt->execute([':n'=>$user]);
        } catch (PDOException $ex) {
            die("Error en read() posts: " . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function update($id)
    {
        $q="update posts set titulo=:t, contenido=:c, estado=:e where id=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':i'=>$id,
                ':t'=>$this->titulo,
                ':c'=>$this->contenido,
                ':e'=>$this->estado
            ]);
        } catch (PDOException $ex) {
            die("Error en update posts: " . $ex->getMessage());
        }
        parent::$conexion = null;

    }
    public function delete($id)
    {
        $q="delete from posts where id=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([':i'=>$id]);
        } catch (PDOException $ex) {
            die("Error en delete() posts: " . $ex->getMessage());
        }
        parent::$conexion = null;

    }

    //_________________________OTROS METODOS ___________________________
    public function crearPosts($cant)
    {
        if ($this->hayPosts()) return;
        $faker = \Faker\Factory::create('es_ES');
        $ids = Users::devolverIds(); //array com los id de los usuarios
        for ($i = 0; $i < $cant; $i++) {
            (new Posts)->setTitulo($faker->unique()->sentence())
                ->setContenido($faker->text)
                ->setEstado($faker->randomElement(["Publicado", "Borrador"]))
                ->setUser_id($faker->randomElement($ids))
                ->create();
        }
    }
    public function hayPosts()
    {
        $q = "select id from posts";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error en hay posts:" . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->rowCount();
    }

    public static function esPropietario($id, $id_user){
        parent::crearConexion();
        $q="select id from posts where id=:i AND user_id=:ui";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':i'=>$id,
                ':ui'=>$id_user
            ]);
        } catch (PDOException $ex) {
            die("Error en hay posts:" . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->rowCount();

    }
    public static function infoPost(int $id){
        parent::crearConexion();
        $q="select * from posts where id=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':i'=>$id,
            ]);
        } catch (PDOException $ex) {
            die("Error en hay posts:" . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    //_______________________SETTERS ____________________________________



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
     * Set the value of titulo
     *
     * @return  self
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Set the value of contenido
     *
     * @return  self
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Set the value of estado
     *
     * @return  self
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Set the value of user_id
     *
     * @return  self
     */
    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }
}

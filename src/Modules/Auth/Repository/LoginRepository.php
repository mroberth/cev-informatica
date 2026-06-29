<?php
namespace App\Repository;
use PDO;
use Core\Database\Conexion;

class LoginRepository{
    private readonly PDO $conn_security;
    public function __construct(){
        $this->conn_security = Conexion::ObtenerConexion('Security');
    }

    public static function login($loginDTO){
        
    }
}
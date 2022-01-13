<?php

include ('environment.php');

class Conexion extends PDO {

    private $tipo_de_base = typeDatabase;
    private $host = host;
    private $nombre_de_base = database;
    private $usuario = dbUser;
    private $contrasena = dbPassword;
    private $port = dbPort;

    public function __construct() {
        try {
            parent::__construct($this->tipo_de_base . ':host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->nombre_de_base, $this->usuario, $this->contrasena);

        } catch (PDOException $e) {
            print 'Ha surgido un error y no se puede conectar a la base de datos. Detalle: ' . $e->getMessage();

            exit;
        }
    }

}



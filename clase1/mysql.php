<?php
    /*
    existe tres manera de trabajar con mysql
    orientado a objetos(OB)
    procedimientos o funciones(p)
    PDO
    */
    include 'config.php';

    // nuestro clase para la conexion de base de dato
    class Mysql{
            private $conBD = null; // nuestro atributo que se iniciara como objeto de la clase mysqli
            public function __construct(){ // constructor de la clase;
                global $usuario, $pass, $direccion;
                $this->usuario = $usuario;
                $this->pass = $pass;
                $this->direccion = $direccion;
                // !dato las variable del constructor es sin $;
            }
    /*
        conexion a la base de dato por objetos
    */
            public function conexionBD(){ // funcion para conectar a la base
                $this->conBD = new mysqli($this->direccion, $this->usuario, $this->pass);
                if($this->conBD->connect_error){ // nuestro objecto llama a un atributo de la clase mysqli para verificar si ha ocurrido un error
                    echo 'error al conectar al base de dato'.$this->conBD->connect_error."\n";
                    return false;
                }
                echo 'conexion exitosa';
                return true;
            }


            // conexion por procedimiento

            public function conPro(){
                $this->conBD = mysqli_connect($this->direccion, $this->usuario, $this->pass);
                if(!$this->conBD){
                    echo "error al conectar al base de datos". mysqli_connect_error(). "\n";
                    return false;
                }
                echo 'conexion exitosa'. "\n";
                return true;
            }

            public function conPDO(){
                try{
                    $this->conBD = new PDO("mysql:host=". $this->direccion,$this->usuario,$this->pass);
                    echo 'conexion exitosa';
                    return true;
                }
                catch(PDOException $e){
                    echo 'error al conectar a la base de datos'. $e ."\n";
                    return false;
                }
            }

    }

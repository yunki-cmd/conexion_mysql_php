<?php
    /*
    existe tres manera de trabajar con mysql
    orientado a objetos(OB)
    procedimientos o funciones(p)
    PDO
    */
    include './config.php';

    // nuestro clase para la conexion de base de dato
    class Mysql{
            private $conBD = null; // nuestro atributo que se iniciara como objeto de la clase mysqli
            public $NuevoBaseDato = 'CREATE DATABASE BasePrueba';

            public $tabla = "CREATE TABLE  resumen_productos(
                id_resumen int(11) unsigned auto_increment primary key,
                nombre varchar(45) not null,
                categoria varchar(45) not null,
                precio float not null,
                cantidad_vendidos int(11) not null,
                en_almacen int(11) not null,
                fecha_alta datetime not null)";

            public function __construct(){ // constructor de la clase;
                global $usuario, $pass, $direccion, $dataName;
                $this->usuario = $usuario;
                $this->pass = $pass;
                $this->direccion = $direccion;
                $this->dataName = $dataName;
                // !dato las variable del constructor es sin $;
            }
    /*
        conexion a la base de dato por objetos
    */
            public function conexionBD(){ // funcion para conectar a la base
                $this->conBD = new mysqli($this->direccion, $this->usuario, $this->pass,$this->dataName);
                if($this->conBD->connect_error){ // nuestro objecto llama a un atributo de la clase mysqli para verificar si ha ocurrido un error
                    echo 'error al conectar al base de dato'.$this->conBD->connect_error."\n";
                    return false;
                }
                echo 'conexion exitosa';
                return true;
            }


            // conexion por procedimiento

            public function conPro(){
                $this->conBD = mysqli_connect($this->direccion, $this->usuario, $this->pass, $this->dataName);
                if(!$this->conBD){
                    echo "error al conectar al base de datos". mysqli_connect_error(). "\n";
                    return false;
                }
                echo 'conexion exitosa'. "\n";
                return true;
            }

            public function conPDO(){
                try{
                    $this->conBD = new PDO("mysql:host=". $this->direccion.";dbname=". $this->dataName,$this->usuario,$this->pass);
                    echo 'conexion exitosa';
                    return true;
                }
                catch(PDOException $e){
                    echo 'error al conectar a la base de datos'. $e ."\n";
                    return false;
                }
            }

            // creacion de query

            // creacion de base de dato por objecto

            public function exceStrQueryOB($query){
                if($this->conexionBD() && $query !=''){ // llamamos a la funcion de conexcion de base de datos por objetos ya que si la conexcion es extosa devuelve true asi se cumple la condicion del if
                    if($this->conBD->query($query) === true ){
                        echo 'consulta exitosa \n';
                    }else{
                        echo 'consulta no exitosa'. $this->conBD->connect_error ."\n";
                    }
                    $this->conBD->close();
                    
                }
            }

            public function exceStrQuerypro($query){
                if($this->conexionBD() && $query !=''){ // llamamos a la funcion de conexcion de base de datos por objetos ya que si la conexcion es extosa devuelve true asi se cumple la condicion del if
                    if(mysqli_query($this->conBD, $query)){
                        echo 'consulta exitosa \n';
                    }else{
                        echo 'consulta no exitosa'. mysqli_error($this->conBD)."\n";
                    }
                    mysqli_close($this->conBD);
                }
            }


            public function exceStrQueryPDO($query){
                try{
                    if($this->conPDO() && $query !=''){ // se crea la conexion
                        $this->conBD->exec($query); // y se llama al metodo query
                        echo 'consulta ejecutada \n';
                    } 
                }catch(PDOException $e) {
                        echo 'consulta error '. $e->getMessage(). "\n";
                    }
                }
    }

?>
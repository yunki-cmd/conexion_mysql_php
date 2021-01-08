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

            public $strInsert_old='insert into resumen_productos(nombre,categoria,precio,cantidad_vendidos,en_almacen,fecha_alta) values("producto-1","categoria-2","199.00","30","100","2019-01-01")';

            public $strInsert='insert into resumen_productos(nombre,categoria,precio,cantidad_vendidos,en_almacen,fecha_alta) values(?,?,?,?,?,?)';

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
                $id='';
                if($this->conexionBD() && $query !=''){ // llamamos a la funcion de conexcion de base de datos por objetos ya que si la conexcion es extosa devuelve true asi se cumple la condicion del if
                    if($this->conBD->query($query) === true ){
                        $id = $this->conBD->insert_id;
                        echo 'consulta exitosa OB'.'\n',' id: ',  $id;
                    }else{
                        echo 'consulta no exitosa'. $this->conBD->connect_error ."\n";
                    }
                    $this->conBD->close();
                    
                }
                return $id;
            }

            public function exceStrQuerypro($query){
                $id='';
                if($this->conexionBD() && $query !=''){ // llamamos a la funcion de conexcion de base de datos por objetos ya que si la conexcion es extosa devuelve true asi se cumple la condicion del if
                    if(mysqli_query($this->conBD, $query)){
                        $id = mysqli_insert_id($this->conBD);
                        echo 'consulta exitosa PRO'.'\n',' id: ',  $id;
                    }else{
                        echo 'consulta no exitosa'. mysqli_error($this->conBD)."\n";
                    }
                    mysqli_close($this->conBD);
                }
                return $id;
            }


            public function exceStrQueryPDO($query){
                try{
                    $id = '';
                    if($this->conPDO() && $query !=''){ // se crea la conexion
                        $this->conBD->exec($query);// y se llama al metodo query
                        $id = $this->conBD->lastInsertId();
                        echo 'consulta exitosa PDO'.'\n',' id: ',  $id;
                        return $id;
                    } 
                }catch(PDOException $e) {
                        echo 'consulta error '. $e->getMessage(). "\n";
                    }
                }

                // sintaxis objeto
                // file_get_contents - permite leer un json
                public function insertOB(){
                    $json = file_get_contents('data.json');
                    $dataJson = json_decode($json, true);
                    if($this->conexionBD()){
                        // disminuye el riesgo de inyeccion sql
                        $pQuery = $this->conBD->prepare($this->strInsert);
                        foreach ($dataJson as $id => $value) {
                        $pQuery ->bind_param(
                            "ssdiis",
                            $value['nombre'],
                            $value['categoria'],
                            $value['precio'],
                            $value['cantidad_vendidos'],
                            $value['en_almacen'],
                            $value['fecha_alta']
                        );
                        $pQuery->execute();
                        // comprobamos insert del ultimo id insertado
                        $ultimoid = $this->conBD->insert_id;
                        echo 'nombre: ',$value['nombre'],'ultimo id insertado: ', $ultimoid,'\n';
                        }
                        $pQuery->close();
                        $this->conBD->close();
                    }
                }
                
                public function insertPro(){
                    $json = file_get_contents('data.json');
                    $dataJson = json_decode($json, true);
                    if($this->conPro()){
                        $pQuery = mysqli_stmt_init($this->conBD);
                        mysqli_stmt_prepare($pQuery,$this->strInsert);
                        foreach ($dataJson as $id => $value) {
                            mysqli_stmt_bind_param(
                                $pQuery,
                                'ssdiis',
                                $value['nombre'],
                                $value['categoria'],
                                $value['precio'],
                                $value['cantidad_vendidos'],
                                $value['en_almacen'],
                                $value['fecha_alta']
                            );
                            mysqli_stmt_execute($pQuery);
                            $ultimoid = mysqli_insert_id($this->conBD);
                        echo 'nombre: ',$value['nombre'],'ultimo id insertado: ', $ultimoid,'\n';
                    }
                    mysqli_close($this->conBD);
                }
            }
                public function insertPDO(){
                    $json = file_get_contents('data.json');
                    $dataJson = json_decode($json, true);
                    try {
                        $this->strInsert='insert into resumen_productos(nombre,categoria,precio,cantidad_vendidos,en_almacen,fecha_alta) values(:nombre,:categoria,:precio,:cantidad_vendidos,:en_almacen,:fecha_alta)';
                        if($this->conPDO()){
                            $pquery = $this->conBD->prepare($this->strInsert);
                            foreach ($dataJson as $id => $value) {
                                $pquery->bindParam(':nombre',$value['nombre']);
                                $pquery->bindParam(':categoria',$value['categoria']);
                                $pquery->bindParam(':precio',$value['precio']);
                                $pquery->bindParam(':cantidad_vendidos',$value['cantidad_vendidos']);
                                $pquery->bindParam(':en_almacen',$value['en_almacen']);
                                $pquery->bindParam(':fecha_alta',$value['fecha_alta']);
                                $pquery->execute();
                                $idInsertado = $this->conBD->lastInsertId();
                                echo 'nombre: ',$value['nombre'],'ultimo id insertado: ', $idInsertado,'\n';
                            }
                            $this->conBD =null;
                        }

                    } catch (PDOException $th) {
                        echo 'consulta error '. $th->getMessage(). "\n";
                    }

                }
    
    }
?>
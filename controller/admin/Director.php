<?php
require_once( _RMODEL . 'conexion.php');
require_once( _RCONTROLLER . 'admin/handleSanitize.php');

class Director extends handleSanitize {

    private $idCompetenciaMaths = array(1,2,3,4);
    private $idCompetenciaLengua = array(5,6,7);


    public function RegistrarMatematica()
    {
        $datos = $_POST['datos'];
        $datosSinVacios = array_filter($datos);
        $respuesta = array("status" => "error", "message" => "No se pudo guardar el registro.");
        try{
            if ( count($datosSinVacios) !== count($datos)) {
                throw new Exception('Controlador Director : datos de formularios registro matemática llegan vacios');
                $respuesta = array ("status" => "error", "message" => "Error al enviar formulario. Intente otra vez.");
            }
            if (!$this->isNumeric($datos)) {
                throw new Exception('Controlador Director : no son númericos');
                $respuesta = array ("status" => "error", "message" => "Por favor asegurese de ingresar solo números. Si utiliza decimales use '.' y no ',' " );
            }
            
            $idColegio = $this->getIdColegioForDirector();
            $sqlSentences = $this->makeInsertIntoSenteceMaths($datos, $idColegio);
            $conexion = MySQLConnection::getInstance();
            $param = '';
            $conexion->query($sqlSentences, $param);
            $respuesta = array("status" => "success", "message" => "Registro guardado");
            echo (json_encode($respuesta));
        } catch (Throwable $e) {
            $this->handlerError($e);
            echo (json_encode($respuesta));
        }
    }

    public function RegistrarLenguaje()
    {
        $datos = $_POST['datos'];
        $datosSinVacios = array_filter($datos);
        $respuesta = array("error" => "error", "message" => "No se pudo guardar el registro.");
        try{
            if ( count($datosSinVacios) !== count($datos)) {
                throw new Exception('Controlador Director : datos de formularios registro matemática llegan vacios');
                $respuesta = array ("status" => "error", "message" => "Error al enviar formulario. Intente otra vez.");
            }
            if (!$this->isNumeric($datos)) {
                throw new Exception('Controlador Director : no son númericos');
                $respuesta = array ("status" => "error", "message" => "Por favor asegurese de ingresar solo números. Si utiliza decimales use '.' y no ',' " );
            }
            
            $idColegio = $this->getIdColegioForDirector();
            $sqlSentences = $this->makeInsertIntoSenteceLengua($datos, $idColegio);
            $param = '';
            $conexion = MySQLConnection::getInstance();
            $conexion->query($sqlSentences, $param);
            $this->registrarSubida($idColegio);
            $respuesta = array("status" => "success", "message" => "Registro guardado");
            echo (json_encode($respuesta));
        } catch (Throwable $e) {
            $this->handlerError($e);
            echo (json_encode($respuesta));
        }
    }

    private function isNumeric( array $array)
    {
        foreach ($array as $valor) {
            if (is_numeric($valor)) {
                return TRUE ;
            }
        }
        return FALSE;
    }

    private function getIdColegioForDirector() 
    {
        try {
            $conexion = MySQLConnection::getInstance();
            session_start();
            $user = $_SESSION['username'];
            $sql = "SELECT id_colegio FROM directores WHERE usuario= ? ";
            $param = [$user];
            $stmt = $conexion->query($sql, $param);
            $resultado = $stmt->fetchColumn();
            return $resultado;
        } catch (Throwable $e) {
            throw new Exception( 'Controllador Director : Fallo funcion getIdColegioForDirector : ' . $e->getMessage());
        }

    }

    private function makeInsertIntoSenteceMaths( $datos, $cl)
    {
        $año = date('Y');
        $competencia = 0;
        ##contadorRun contara el numero de recorridos asignar los id_logros al insert
        $contadorRun = 0;
        $logros = 1;
        $valores = array ();
        $sql = "INSERT INTO resultado ( id_competencia, puntuacion, id_logro, id_colegio, id_curso, anio ) VALUES ";
        foreach ($datos as $puntuacion) {
            if ($contadorRun == 4 ) {
                $contadorRun = 0;
                $competencia++;
                $logros++;
            }
            
            $valores[] = "( ' " .   $this->idCompetenciaMaths[$competencia]  . " ', ' " . $puntuacion . " ', ' " . $logros . " ', ' " . $cl . " ' , '1' , ' " . $año . " ' )";
            $contadorRun++;
            
        }    
        $sql .= implode(", ", $valores);
        $sql = rtrim($sql, ","); 
        return $sql;   
    }

    private function makeInsertIntoSenteceLengua( $datos, $cl)
    {
        $año = date('Y');
        $competencia = 0;
        ##contadorRun contara el numero de recorridos asignar los id_logros al insert
        $contadorRun = 0;
        $logros = 17;
        $valores = array ();
        $sql = "INSERT INTO resultado ( id_competencia, puntuacion, id_logro, id_colegio, id_curso, anio ) VALUES ";
        foreach ($datos as $puntuacion) {
            if ($contadorRun == 4 ) {
                $contadorRun = 0;
                $competencia++;
                $logros++;
            }
            
            $valores[] = "( ' " .   $this->idCompetenciaLengua[$competencia]  . " ', ' " . $puntuacion . " ', ' " . $logros . " ', ' " . $cl . " ' , '2' , ' " . $año . " ' )";
            $contadorRun++;
            
        }    
        $sql .= implode(", ", $valores);
        $sql = rtrim($sql, ","); 
        return $sql;   
    }

    private function registrarSubida ($colegio) 
    {   try {
            $sql = "INSERT INTO archivo (anio, fecha_subido, id_colegio) VALUES (?,?,?)";
            $param = [date('Y'), date('Y-m-d H:i:s'), $colegio];
            $conexion = MySQLConnection::getInstance();
            $conexion->query($sql, $param);
        } catch ( Throwable $e) {
            $this->handlerError($e);
        }
    }
}
<?php
require_once (_RMODEL .'conexion.php');
require_once (_RCONTROLLER . 'erroHandler_y_Sanitizevar.php');

class colegio extends erroHandler_y_Sanitizevar{
   
    public function colegio($colegio, $ugel)
    {   
       try {
        $connection = MySQLConnection::getInstance();
        $sql = "CALL colegio(:colegio_param, :ugel_param)";
        $params = array(':colegio_param' => $colegio, ':ugel_param' => $ugel);
        $stmt = $connection->query($sql, $params);
        $results = array();
        while ($row = $stmt->fetchAll()) {
            $results[] = $row;
        }
        $json = json_encode($results);
        print_r($json);
       } catch (Throwable $e) {
            $respuesta = ['error' => 'servicio no disponible en este momento'];
            print_r( json_encode($respuesta));
            $this->handlerError($e);
            die;
       }
       return;
    }
    
    public function colegioListALL ($distrito, $curso, $nivel)
    {
        try{
            $connection = MySQLConnection::getInstance();
            $sql = "CALL colegioListDistrito (:distrito, :curso, :nivel)";
            $params= [':distrito' => $distrito, ':curso' => $curso, 'nivel'=> $nivel];
            $stmt = $connection->query($sql, $params);
            $results = array();
            while($row = $stmt->fetchAll()) {
                $results[] = $row;
            }
            $json = json_encode($results);
            print_r($json);
        } catch (Throwable $e) {
            $this->handlerError($e);
            $respuesta = ['error' => 'servicio no disponible'];
            print_r ($respuesta);
            die;
        }
        return;
    }
    

    public function colegioList ($distrito)
    {
        try{
            $connection = MySQLConnection::getInstance();
            $sql = "CALL colegioListALL (:distrito)";
            $params = [":distrito" => $distrito];
            $stmt = $connection->query($sql, $params);
            $results = array();
            while($row = $stmt->fetchAll()) {
                $results[] = $row;  
            }
            $json = json_encode($results);
            print_r($json);
        } catch (Throwable $e) {
            $this->handlerError($e);
            $respuesta = ['error' => 'servicio no disponible'];
            print_r ($respuesta);
            die;
        }
        return;
    }

    public function colegioDatos()
    {
        try{
            $conexion = MySQLConnection::getInstance();
            $sql = "SELECT c.id_colegio, c.nombre, c.nivel, c.cod_mod_ei, IF(a.id IS NULL, 'No ha subido', 'Eso tilin') AS 'Estado'
                    FROM colegio c
                    LEFT JOIN 
                    (SELECT id_colegio, id FROM archivo WHERE anio = YEAR(CURDATE())) a 
                    ON c.id_colegio = a.id_colegio; ";
            $params = '';
            $stmt = $conexion->query($sql, $params);
            $results = array();
            while ($row = $stmt->fetchAll()) {
                $results[] = $row;
            }
            $json = json_encode($results);
            print_r($json);
        } catch (Throwable $e) {
            $this->handlerError($e);
            $respuesta = ['error' => 'servicio no disponible'];
            print_r ($respuesta);
            die;
        }
        return; 
    }
}
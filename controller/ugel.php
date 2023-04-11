<?php
require_once (_RMODEL .'conexion.php');
require_once (_RCONTROLLER . 'erroHandler_y_Sanitizevar.php');

class ugel extends erroHandler_y_Sanitizevar{
   
    public function ugelDistrito($distrito)
    {   
       try {
        $connection = MySQLConnection::getInstance();
        $sql = "CALL ugel_distrito(:distrito_param)";
        $params = array(':distrito_param' => $distrito);
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
    }

    public function ugelAllDistrict($ugel)
    {
        try {
            $connection = MySQLConnection::getInstance();
            $sql = "CALL ugelAllDistrict(:ugel_param)";
            $params = array(':ugel_param' => $ugel);
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
    }

    public function allUgel()
    {
        try {
            $connection = MySQLConnection::getInstance();
            $sql = "CALL allUgel()";
            $stmt = $connection->query($sql);
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
    }
}
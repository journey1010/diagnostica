<?php
require_once (_RMODEL .'conexion.php');
require_once (_RCONTROLLER . 'erroHandler_y_Sanitizevar.php');

class ugel extends erroHandler_y_Sanitizevar{
   
    public function ugelDistrito($ugel, $distrito)
    {   
       try {
        $connection = MySQLConnection::getInstance();
        $sql = "CALL ugel_distrito(:ugel, :distrito)";
        $params = array(':ugel' => $ugel, ':distrito' => $distrito);
        $stmt = $connection->query($sql, $params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $json = json_encode($results);
        print_r( $json);
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
            $sql = "CALL ugel_all_district(:ugel)";
            $params = array(':ugel' => $ugel);
            $stmt = $connection->query($sql, $params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $json = json_encode($results);
            echo $json;
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
            $sql = "CALL all_ugel()";
            $stmt = $connection->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $json = json_encode($results);
            echo $json;
           } catch (Throwable $e) {
                $respuesta = ['error' => 'servicio no disponible en este momento'];
                print_r( json_encode($respuesta));
                $this->handlerError($e);
                die;
           } 
    }
}
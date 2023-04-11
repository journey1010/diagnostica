<?php
require_once (_RMODEL .'conexion.php');
require_once (_RCONTROLLER . 'erroHandler_y_Sanitizevar.php');

class colegio extends erroHandler_y_Sanitizevar{
   
    public function colegio($ugel, $distrito, $colegio)
    {   
       try {
        $connection = MySQLConnection::getInstance();
        $sql = "CALL colegio(:ugel, :distrito, :colegio)";
        $params = array(':ugel' => $ugel, ':distrito' => $distrito, ':colegio' => $colegio);
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

}
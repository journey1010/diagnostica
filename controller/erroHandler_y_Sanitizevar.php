<?php
class erroHandler_y_Sanitizevar {

    protected function handlerError (Throwable $e)
    {
        $errorMessage = date('Y-m-d H:i:s') . ' : Error : ' . $e->getMessage() . "\n";
        error_log($errorMessage, 3, _ROOT_PATH . '/log/error.log');  
    }

    protected function SanitizeVar($var)
    {
        $var = urldecode($var);
        $var = htmlspecialchars($var, ENT_QUOTES);
        $var = preg_replace('/[^a-zA-Z0-9\/\.=~-Ññáéíóúü]/u', ' ', $var);
        return $var;
    }


}

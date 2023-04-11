<?php
class erroHandler_y_Sanitizevar {

    protected function handlerError (Throwable $e)
    {
        $errorMessage = date('Y-m-d H:i:s') . ' : Error : ' . $e->getMessage() . "\n";
        error_log($errorMessage, 3, _ROOT_PATH . 'log/error.log');  
    }

    protected function SanitizeVar($var)
    {
        $var = filter_var( $var, FILTER_SANITIZE_URL);
        $var = htmlspecialchars($var, ENT_QUOTES);
        $var = strtolower($var);
        $var = preg_replace('/[^a-zA-Z0-9\/.=~-]/', '', $var);
        return $var;
    }


}

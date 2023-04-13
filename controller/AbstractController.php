<?php

abstract class AbstractController {
    
    abstract protected function SanitizeVar( string $var);

    protected function errorLog(Exception $e){
        $errorMessage = date("Y-m-d H:i:s") . " : " . $e->getMessage() .  "\n";
        error_log($errorMessage, 3, _ROOT_PATH . '/log/error.log'  );
    }

    protected function renderView( string $viewName)
    {

        $viewName = ($viewName == 'ErrorCritico') ? 'ErrorCritico' : $viewName;
        
        $fullpath = _RVIEWS .  $viewName . '.php';

        try {
            if(!file_exists($fullpath)){
                throw new Exception ('Vista no encontrada' . $viewName);
            }
            if(pathinfo($fullpath, PATHINFO_EXTENSION)!== 'php'){
                throw new Exception ('Archivo de tipo no permitido' . $viewName);
            }
            ob_start();
            include_once $fullpath;
            echo ob_get_clean();

        }catch (Exception $e){
            $this->errorLog($e);
        }
    }
}
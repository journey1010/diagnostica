<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

require_once(_RMODEL . 'conexion.php');
require_once(_RCONTROLLER . 'erroHandler_y_Sanitizevar.php');

class uploadfile extends erroHandler_y_Sanitizevar {

    private $ruta;
    
    public function __construct()
    {
        $this->ruta = _RFILES . 'evadiag/';  
    }

    public function RegistrarEvadiag () 
    {
        try {    
            session_start();
            if(isset($_SESSION['username'])) {
                $usuario = $_SESSION['username'];
            } else {
                throw new Exception("User name no existe. ¿Quién lo borro?");
            }
            if (
                empty($_POST["fecha"]) || 
                empty($_FILES["archivo"])
            ) {
                throw new Exception("Debe completar todos los campos del formulario.");
            }

            $fecha = $this->SanitizeVar($_POST["fecha"]);

            $archivo = $_FILES["archivo"];
            if ($this->validarArchivo($archivo) == false) {
                throw new Exception("Validacion de archivo en  registro de proyecto de inversion publica ha fallado.");
                return;
            }
        
            $pathFullFile = $this->guardarFichero($archivo, $usuario);
            //$this->registrarIntoBd ($titulo, $tipo, $fecha, $descripcion, $pathFullFile );
            $respuesta = array("success" => "Datos guardados con éxito.");
            print_r(json_encode($respuesta));
        } catch (Throwable $e) {
            $this->handlerError($e);
            return;
        }
    }

    private function validarArchivo ($archivo)
    {
        if (empty ($archivo)) {
            return false; 
        }

        $archivoNombre = $archivo['name'];
        $extensionesPermitidas = ['xlsx', 'xls'];
        $extension = strtolower(pathinfo($archivoNombre , PATHINFO_EXTENSION));

        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            $respuesta = array ("error" => "Error al subir el archivo");
            print_r(json_encode($respuesta));
            return false;
        }

        if (!in_array($extension, $extensionesPermitidas)){
            $respuesta = array ("error" => "Extensión de archivo no permitida.");
            print_r(json_encode($respuesta));
            return false;
        }
        return true; 
    }

    private function guardarFichero ($archivo, $usuario)
    {
        $rutaArchivo = $this->crearRuta();

        $archivotemp = $archivo['tmp_name'];
        $extension = strtolower(pathinfo($archivo["name"] , PATHINFO_EXTENSION));
        $nuevoNombre = $usuario . '-'. date("H-i-s-m-d-Y.") . $extension;
        $pathFullFile = $rutaArchivo . $nuevoNombre;

        if (!move_uploaded_file($archivotemp, $pathFullFile)) {
            $respuesta = array ("error" => "No se pudo guardar el archivo para actualizar el registro.");
            print_r(json_encode($respuesta));
            return;
        } 
        return $pathFullFile;
    }

    private function crearRuta (): string
    {
        $año = date ('Y');
    
        $pathForFile = $this->ruta . $año ;

        if (!file_exists($pathForFile)) {
            mkdir($pathForFile, 0777, true);
        }
        $finalPathForFile = $pathForFile . '/';
        return $finalPathForFile;
    }

    private function analizarExcel ($archivo) 
    {
        $excel = IOFactory::load($archivo);
    }

    private function borrarArchivo ($archivo)
    {
        $pathFull = $archivo; 
        $file_to_delete = $archivo;
        if (!unlink($file_to_delete)) {
            $respuesta = array("error" => "No se puede actualizar el archivo. Inténtelo más tarde o contacte con el soporte de la página.");
            print_r(json_encode($respuesta)); 
            throw new Exception("No se pudo reemplazar el archivo. Controlador de actualizacion, funcion reemplazar archivo");
        }
    }
}
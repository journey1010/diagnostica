<?php
require_once (_ROOT_CONTROLLER . 'admin/handleSanitize.php');
require_once (_ROOT_MODEL . 'conexion.php');

class obra extends handleSanitize {

    private $ruta;
    
    public function __construct()
    {
        $this->ruta = _ROOT_FILES . 'transparencia/obras/';  
    }

    public function RegistrarObra () 
    {
        try {    
            if (
                empty($_POST["titulo"]) || 
                empty($_POST["tipo"]) || 
                empty($_POST["fecha"]) || 
                empty($_POST["descripcion"]) || 
                empty($_FILES["archivo"])
            ) {
                throw new Exception("Debe completar todos los campos del formulario.");
            }

            $titulo = $this->SanitizeVarInput($_POST["titulo"]);
            $tipo = $this->SanitizeVarInput($_POST["tipo"]);
            $fecha = $this->SanitizeVarInput($_POST["fecha"]);
            $descripcion = $this->SanitizeVarInput($_POST["descripcion"]);
        
            $archivo = $_FILES["archivo"];
            if ($this->validarArchivo($archivo) == false) {
                throw new Exception("Validacion de archivo en  registro de proyecto de inversion publica ha fallado.");
                return;
            }
        
            $pathFullFile = $this->guardarFichero($archivo, $titulo);
            $this->registrarIntoBd ($titulo, $tipo, $fecha, $descripcion, $pathFullFile );
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
        $extensionesPermitidas = ['xlsx', 'pdf', 'doc', 'docx', 'xls'];
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

    private function guardarFichero ($archivo, $titulo)
    {
        $rutaArchivo = $this->crearRuta();

        $archivotemp = $archivo['tmp_name'];
        $extension = strtolower(pathinfo($archivo["name"] , PATHINFO_EXTENSION));
        $nuevoNombre = $titulo . '-'. date("H-i-s-m-d-Y.") . $extension;
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
        $mes = date ('m');
        
        $pathForFile = $this->ruta . $año . '/' . $mes;

        if (!file_exists($pathForFile)) {
            mkdir($pathForFile, 0777, true);
        }
        $finalPathForFile = $pathForFile . '/';
        return $finalPathForFile;
    }

    private function registrarIntoBd ($titulo, $tipo, $fecha, $descripcion, $pathFullFile ) 
    {
        $fechaSubida = date('Y-m-d H:i:s');
        $conexion = new MySQLConnection();
        $sql  = "INSERT INTO obras (
            titulo,
            descripcion,
            tipo,
            archivo,
            fecha,
            fecha_subida
        ) VALUES ( ?,?,?,?,?,? )";

        $params = [$titulo, $descripcion, $tipo, $pathFullFile, $fecha, $fechaSubida ];
        $conexion->query($sql, $params, '', false);
        $conexion->close();
        return; 
    }

    public function BuscarObra () 
    {
        $tipo = $this->SanitizeVarInput($_POST['tipo']);
        $fecha = $this->SanitizeVarInput($_POST['fecha']);
        $ordenar = $this->SanitizeVarInput($_POST['ordenar']);
        $palabra = $this->SanitizeVarInput($_POST['palabra']);

        $conexion = new MySQLConnection();
        $sql = "SELECT id, titulo, descripcion, tipo, fecha, fecha_subida FROM obras WHERE 1=1";

        if (!empty($tipo)) {
          $sql .= " AND tipo = :tipo";
          $params[':tipo'] = $tipo;
        }
        
        if (!empty($fecha)) {
          $sql .= " AND fecha = :fecha";
          $params[':fecha'] = $fecha;
        }
        
        if (!empty($palabra)) {
          $sql .= " AND (titulo LIKE :palabra OR descripcion LIKE :palabra_desc OR tipo LIKE :palabra_tipo OR fecha  LIKE :palabra_fech)";
          $params[':palabra'] = '%' . $palabra . '%';
          $params[':palabra_fech'] = '%' . $palabra . '%';
          $params[':palabra_desc'] = '%' . $palabra . '%';
          $params[':palabra_tipo'] = '%' . $palabra . '%';
        }
        
        if ($ordenar == 'DESC') {
          $sql .= " ORDER BY fecha_subida DESC LIMIT 20";
        } elseif ($ordenar == 'ASC') {
          $sql .= " ORDER BY fecha_subida ASC LIMIT 20";
        }else {
            $sql .= " ORDER BY fecha_subida DESC LIMIT 20";
        }

        try {
            $stmt = $conexion->query($sql, $params, '', false);
            $conexion->close();
            $resultado = $stmt->fetchAll(); 
            $table_respuesta = $this->makeTblForBuscarObra($resultado);
            echo $table_respuesta;    
        } catch (Throwable $e) {
            $respuesta = array("error" => "Error al consultar registros");
            print_r(json_encode($respuesta));
            $this->handlerError($e);
        }
        return;
    }

    private function makeTblForBuscarObra ($resultado): string 
    {
        $tablaRow = '';
        foreach ($resultado as $row ) {
            $id = $row['id'];
            $titulo = $row['titulo'];
            $descripcion = $row['descripcion'];
            $tipo = $row['tipo'];
            $fecha = $row['fecha'];
            $fecha_subida = $row['fecha_subida'];

            $tablaRow .= "<tr>";
            $tablaRow .= "<td class=\"text-center\">$id</td>";
            $tablaRow .= "<td class=\"text-center\">$titulo</td>";
            $tablaRow .= "<td class=\"text-center\" style=\"max-width: 300px;\" >$descripcion</td>";
            $tablaRow .= "<td class=\"text-center\">$tipo</td>";
            $tablaRow .= "<td class=\"text-center\">$fecha</td>";
            $tablaRow .= "<td class=\"text-center\">$fecha_subida</td>";
            $tablaRow .= '
                <td class="text-center align-middle">
                    <i class="fa fa-edit mr-2 edit-icon" style="color:#9c74dd !important"></i>
                    <i class="fa fa-window-close fa-lg cancel-icon" style="color: #b40404; --fa-secondary-color: #c7fbff; display:none;"></i>
                </td>
            ';
            $tablaRow .= "</tr>";
        }

        $tabla = <<<Html
        <table class="table table-hover table-md w-100" id="resultadosBusquedaObras">
            <thead class="table-bordered" >
                <tr>
                    <th class="text-center">id</th>
                    <th class="text-center">Titulo</th>
                    <th class="text-center">Descripción</th>
                    <th style="text-center">Tipo</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Fecha de carga</th>
                    <th style="width: 80px" class="text-center ">Editar</th>
                </tr>
            </thead>
            <tbody>
                $tablaRow
            </tbody>
        </table>  
        Html;
        
        return $tabla;
    }

    public function ActualizarObra () 
    {
        $campoRequerido = ['id', 'titulo', 'tipo', 'fecha', 'descripcion']; 
        foreach ($campoRequerido as $campo) {
            if (empty ($_POST[$campo])) {
                $respuesta = array  ("error" => "Si ha borrado algún campo del formulario de actualización, debe rellenarlo de nuevo antes de enviarlo. Los campos vacíos pueden causar errores o retrasos en el proceso de actualización.");
                print_r(json_encode($respuesta));
                return;
            }
        }
        try {
            $conexion = new MySQLConnection();
            $id = $this->SanitizeVarInput($_POST['id']);
            $id = $this->SanitizeVarInput($_POST['id']);
            $titulo =  $this->SanitizeVarInput($_POST["titulo"]);
            $tipo =  $this->SanitizeVarInput($_POST["tipo"]);
            $fecha = $this->SanitizeVarInput($_POST["fecha"]);
            $descripcion = $this->SanitizeVarInput($_POST["descripcion"]);

            $archivo = $_FILES["archivo"] ?? null;
            if ($this->validarArchivo($archivo) == true) {
                $this->borrarArchivo($conexion, $id);
                $newPathFile = $this->guardarFichero($archivo, $titulo);
                $this->UpdateSetBd($conexion, $id, $titulo, $tipo, $fecha, $descripcion, $newPathFile);
                return;
            }
            $this->UpdateSetBd($conexion, $id, $titulo, $tipo, $fecha, $descripcion);
        } catch (Throwable $e) {
            $this->handlerError($e);
        }
        return;
    }

    private function UpdateSetBd(MySQLConnection $conexion, $id, $titulo, $tipo, $fecha, $descripcion, $newPathFile = null)
    {
        $sql = "UPDATE obras SET titulo = :titulo, descripcion = :descripcion , tipo = :tipo, fecha = :fecha, fecha_subida = :fecha_subida";
        $params[":titulo"] = $titulo;
        $params[":tipo"] = $tipo;
        $params[":fecha"] = $fecha;
        $params[":descripcion"] = $descripcion;
        $params[":fecha_subida"] = date("Y-m-d H:i:s");
        try {
            if ($newPathFile !==null) {
                $sql .= ", archivo = :archivo";
                $params[":archivo"] = $newPathFile;
            }
            $sql .= " WHERE id = :id";
            $params[":id"] = $id;
            $conexion->query($sql, $params, '', false);
            $conexion->close();
            $respuesta = array ("success" => "Registro actualizado exitosamente.");
            print_r(json_encode($respuesta));

        } catch (Throwable $e) {
            $respuesta = array ("error" => "La actualizacion ha fallado.");
            print_r(json_encode($respuesta));
            $this->handlerError($e);
        }
    }

    private function borrarArchivo (MySQLConnection $conexion, $id)
    {
        $sql = "SELECT archivo FROM obras WHERE id = :id";
        $params["id"] = $id;
        $stmt = $conexion->query($sql, $params, '', false);
        $file_to_delete = $stmt->fetchColumn();
        if (!unlink($file_to_delete)) {
            $respuesta = array("error" => "No se puede actualizar el archivo. Inténtelo más tarde o contacte con el soporte de la página.");
            print_r(json_encode($respuesta)); 
            throw new Exception("No se pudo reemplazar el archivo. Controlador de actualizacion, funcion reemplazar archivo");
        }
    }
}
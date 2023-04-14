<?php 

require_once(_RMODEL . 'conexion.php');
require_once(_RCONTROLLER . 'erroHandler_y_Sanitizevar.php');

class usuarios extends erroHandler_y_Sanitizevar{

    private $rutaAssets;

    public function __construct()
    {
        $this->rutaAssets = _RASSETS;
    }

    public function RegistrarUsuarios()
    {
        $ruta = $this->rutaAssets . 'js/usuarios.js';
        $colegios = $this->colegios();
        $html = <<<Html
        <div class="card card-primary mt-3 mx-auto">
            <div class="card-header">
            <h3 class="card-title">Registrar usuario</h3>
            </div>
            <form id="registrarUsuario">
                <div class="card-body">
                    <div class="form-group">
                        <label for="buscarDNI">DNI *</label>
                        <input type="number" class="form-control" id="dni" placeholder="Ingresar DNI..." required>
                    </div>
                    <button type="button" class="btn btn-secondary" id="searchDNI" >Buscar</button>
                    <div class="form-group">
                        <label for="labelUsuario">Usuario *</label>
                        <input type="text" class="form-control" id="nombre_usuario" placeholder="Ingrese nombre de usuario ...">
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="labelUsuario">Nombre *</label>
                                <input type="text" class="form-control" id="nombre" placeholder="Ingrese su nombre">
                            </div>
                            <div class="col-md-4">
                                <label for="apellidoPaterno">Apellido paterno *</label>
                                <input type="text" class="form-control" id="apellido_paterno" placeholder="Ingrese su apellido paterno">
                            </div>
                            <div class="col-md-4">
                                <label for="apellidoMaterno">Apellido materno *</label>
                                <input aria-label="apellido materno" type="text" class="form-control" id="apellido_materno" placeholder="Ingrese su apellido materno">
                            </div>
                            <div class="col-md-4">
                                <label for="contraseña">Contraseña *</label>
                                <input type="password" class="form-control" id="contrasena" placeholder="Ingrese su contraseña" autocomplete="on">
                            </div>
                            <div class="col-md-4">
                                <label for="numberTelefono">Número de teléfono</label>
                                <input type="text" class="form-control" id="numero_telefono" placeholder="Ingrese su teléfono">
                            </div>
                            <div class="col-md-4">
                                <label for="correoUsuario">Correo</label>
                                <input type="email" class="form-control" id="correo" placeholder="Ingrese su correo...">
                            </div>
                            <div class="col-md-4">
                                <label>Tipo de usuario *</label>
                                <div class="form-group">
                                    <select id="tipoUsuario"aria-label="tiposUsuarios"class="form-control select2 select2-danger"  style="width: 100%;">
                                        <option selected="selected" value="admin">Super Administrador</option>
                                        <option value="director">Director</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4" style="display: none">
                                <label>Colegio *</label>
                                <div class="form-group">
                                    <select id="colegio"aria-label="colegio"class="form-control select2 select2-danger"  style="width: 100%;">
                                        $colegios
                                    </select>
                                </div>
                            </div>
                        </div>                    
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
        <script  type ="module" src="$ruta"></script>
        Html;
        return $html;
    }

    private function colegios ()
    {
        try {
            $conexion = MySQLConnection::getInstance();
            $sql = "SELECT id_colegio ,CONCAT(nombre, ' ', cod_mod_ei) as colegio FROM colegio";
            $stmt = $conexion->query($sql, '');
            $resultado = $stmt->fetchAll();
            $options = '';
            foreach ($resultado as $row) {
                $id = $row['id_colegio'];
                $colegio = $row['colegio'];
                $options .= "<option value=\"$id\">$colegio</option>";
            }
            return $options;
        } catch (Throwable $e) {
            $this->handlerError($e);
        }
    }
}
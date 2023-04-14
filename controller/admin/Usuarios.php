<?php
require_once (_RCONTROLLER . 'admin/handleSanitize.php');
require_once  (_RMODEL . 'conexion.php');

class Usuarios extends handleSanitize {
    
    public function RegistrarUsuario()
    {   
        try{
            $dni = $_POST['dni'];
            $nombre_usuario = $_POST['nombre_usuario'];
            $nombre = $_POST['nombre'];
            $apellido_paterno = $_POST['apellido_paterno'];
            $apellido_materno = $_POST['apellido_materno'];
            $contrasena = $_POST['contrasena'];
            $numero_telefono = $_POST['numero_telefono'];
            $tipo_usuario = $_POST['tipo_usuario'];
            $correo = $_POST['correo'];
            $colegio = $_POST['colegio'];
            if (
                !empty($dni) &&
                !empty($nombre_usuario) &&
                !empty($nombre) &&
                !empty($apellido_paterno) &&
                !empty($apellido_materno) &&
                !empty($contrasena) &&
                !empty($tipo_usuario) &&
                !empty($correo) &&
                !empty($colegio)
            ) {
                $dni = $this->SanitizeVarInput($dni);
                $nombre_usuario = $this->SanitizeVarInput($nombre_usuario);
                $nombre = $this->SanitizeVarInput($nombre);
                $apellido_paterno = $this->SanitizeVarInput($apellido_paterno);
                $apellido_materno = $this->SanitizeVarInput($apellido_materno);
                $contrasena = $this->SanitizeVarInput($contrasena);
                $numero_telefono = $this->SanitizeVarInput($numero_telefono);
                $tipo_usuario = $this->SanitizeVarInput($tipo_usuario);
                $correo = $this->SanitizeVarInput($correo);
                $colegio = $this->SanitizeVarInput($colegio);

                $conexion = MySQLConnection::getInstance();
                $sqlSentence0 = 'SELECT usuario FROM directores WHERE usuario = ? OR dni = ?';
                $params0 = [$nombre_usuario, $dni];
                $resultado = $conexion->query($sqlSentence0, $params0);
                $existUser = $resultado->fetchAll();
                if (!empty($existUser)) {
                    if ($existUser[0]['nombre_usuario'] == $nombre_usuario) {
                        $respuesta = ['user' => 'El nombre usuario ya existe. Por favor escoja otro nombre'];
                    } else {
                        $respuesta = ['dni' => 'El DNI ya está siendo utilizado por otro usuario. Por favor verifique e intente de nuevo'];
                    }
                    print_r(json_encode($respuesta));
                    return;
                }
                
                $password = password_hash(
                    $contrasena,
                    PASSWORD_ARGON2I,
                    [
                        'cost' => 8,
                        'memory_cost' => 1 << 10,
                        'time_cost' => 2,
                        'threads' => 2,
                    ]
                );
                $fecha = date('Y-m-d');

                $sqlSentence = 'INSERT INTO directores (
                    nombre,
                    apellido_paterno,
                    apellido_materno,
                    usuario,
                    contrasena,
                    estado,
                    dni,
                    telefono,
                    tipo_user,
                    fecha_registro,
                    user_img,
                    correo,
                    id_colegio
                ) VALUES (
                    ?,?,?,?,?,?,?,?,?,?,?,?,?
                )';
                $params = [
                    $nombre, 
                    $apellido_paterno, 
                    $apellido_materno, 
                    $nombre_usuario,
                    $password,
                    1,
                    $dni,
                    $numero_telefono,
                    $tipo_usuario,
                    $fecha,
                    '',
                    $correo,
                    $colegio
                ];
                $conexion->query($sqlSentence, $params);
                $conexion->close();
                $respuesta = array('success' => 'Usuario registrado con exito');
                print_r(json_encode($respuesta));
                
            } else {
                $respuesta = array('error' => 'Los datos obligatorios no deben estar vacíos.');
                print_r(json_encode($respuesta));
            }
        }catch (Throwable $e) {
            $this->handlerError($e);
        }
        return;
    }
}
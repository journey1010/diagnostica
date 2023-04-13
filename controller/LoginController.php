<?php

require_once _RCONTROLLER . 'AbstractController.php';
require_once _RMODEL . 'conexion.php';

class LoginController extends AbstractController
{
    public function showLoginForm()
    {
        if (isset($_COOKIE['user'])) {
            $username = $_COOKIE['user'];
            $conexion = MySQLConnection::getInstance();
            $sqlSentences = "SELECT usuario, estado, tipo_user FROM directores WHERE usuario =  ? ";
            $arrayParams = [$username];
            $consulta  = $conexion->query($sqlSentences, $arrayParams);
            $ResultadoConsulta = $consulta->fetchAll();
            foreach ($ResultadoConsulta as $columna) {
                $estadoUsuario = $columna['estado'];
                $tipoUsuario = $columna['tipo_user'];
            }
            if ($estadoUsuario === '0') {
                setcookie("user", "", time() - 3600);
                $conexion->close();
                $this->renderView('login');
                exit;
            }
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['tipoUser'] = $tipoUsuario;
            $conexion->close();
            header('Location: /administrador/app');
        }
        $this->renderView('login');
        return;
    }

    public function processLoginForm()
    {
        $username = $this->SanitizeVar($_POST['username']) ?? '';
        $password = $this->SanitizeVar($_POST['password']) ?? '';

        $conexion = MySQLConnection::getInstance();
        $sqlSentences = "SELECT usuario, contrasena, tipo_user FROM directores WHERE usuario = ? ";
        $arrayParams = [$username];
        $consulta  = $conexion->query($sqlSentences, $arrayParams);
        $ResultadoConsulta = $consulta->fetchAll();

        if (count($ResultadoConsulta) == 0) {
            $response = array('error' => 'Puede que no exista el usuario');
            echo json_encode($response);
        } else {
            foreach ($ResultadoConsulta as $columna) {
                $usernameToCompare = $columna['usuario'];
                $passwordToCompare = $columna['contrasena'];
                $userTipo = $columna['tipo_user'];
            }
            if (!empty($passwordToCompare) && password_verify($password, $passwordToCompare) &&  $username == $usernameToCompare) {
                session_start();
                $_SESSION['username'] = $username;
                $_SESSION['tipoUser'] = $userTipo;
                setcookie("user", $username, time() + (90 * 24 * 60 * 60), "/");
                $conexion->close();
                $response = array('success' => true, 'redirect' => '/administrador/app');
                print_r(json_encode($response));
                exit;
            } else {
                $conexion->close();
                $response = array('error' => 'Usuario o contraseña incorrectos');
                print_r(json_encode($response));
            }
        }
    }
    
    protected function SanitizeVar(string $var)
    {
        $var = htmlspecialchars( $var,  ENT_QUOTES);
        $var = preg_replace('/[^a-zA-Z0-9.=+-_@^]/', 'a', $var);
        return $var;
    }
}
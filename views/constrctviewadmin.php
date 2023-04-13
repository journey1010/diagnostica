<?php
require_once _RMODEL . 'conexion.php';
require_once _RVIEWS . 'sideBarOptions.php';
require_once _RVIEWS . 'contentPageOptions.php';

class viewConstruct{
    
    private $user;
    private $tipoUser;
    private $contenido;

    public function __construct($user, $tipoUser, $contenido)
    {
        $this->user = $user;
        $this->tipoUser = $tipoUser;
        $this->contenido = $contenido;
    }

    public function logoUser()
    {
        try {
            $conexion = MySQLConnection::getInstance();
            $sqlSentences = "SELECT user_img from directores where usuario = ? ";
            $arrayParams = [$this->user];
            $consulta  = $conexion->query($sqlSentences, $arrayParams, '', false);
            $ResultadoConsulta = $consulta->fetchColumn();
            $conexion->close();
            $ImageName = (!empty($ResultadoConsulta)) ? $ResultadoConsulta : 'journii.jpg';
            $rutaImagen = _RASSETS . 'img/iconUser/' . $ImageName;
            return $rutaImagen;
        }catch (Throwable $e){
            $this->handlerError($e);
            return;
        }
    }
    
    public function  buildSideBar($tipoUser)
    {
        $sideBarOptions = new sideBarOptions ();
        try {
            switch($tipoUser){
                case 'admin':
                    $opciones = array ('usuarios', 'registro');
                break;
                case 'director':
                    $opciones = array ( 'registro' );
                break; 
                default:
                    throw new Exception('Clase de usuario no valido');
                break;
            }

            $sideBarHtml = '';
            foreach ($opciones as $opcion ){
                $sideBarHtml .= $sideBarOptions->$opcion();
            }
            return $sideBarHtml;

        }catch(Exception $e){
            $this->handlerError($e);
        }
    }

    public function buildContentPage()
    {
        try {
            $contentPage = new contentPageOptions();

            # Array para administrar los permisos para mostrar los diferentes contenidos de pagina por opciones 
            # del sidebar
            $opcionesMenu = [
                'admin' => [
                    '' => $contentPage->RegistrarUsuarios(),
                    'registrar-usuarios' => $contentPage->RegistrarUsuarios(),
                    // 'actualizar-usuarios' => $contentPage->ActualizarUsuarios(),
                    // 'registrar-archivo' => $contentPage->RegistrarArchivo()

                ],
                
                'director' =>[
                    '' => $contentPage->RegistrarArchivo(),
                    // 'registrar-archivo' => $contentPage->RegistrarArchivo(),
                    // 'contacto' => $contentPage->Contacto()
                ]
            ];
            
            # Si el usuario es de tipo "X" y el contenido solicitado no está disponible, muestra la pagina por
            # defecto.
            if ($opcionesMenu[$this->tipoUser] && !array_key_exists($this->contenido, $opcionesMenu[$this->tipoUser])){
                $contenido = $opcionesMenu[$this->tipoUser][''];
            }else {
                $contenido = $opcionesMenu[$this->tipoUser][$this->contenido];
            }
            return $contenido;
        }catch (Throwable $e){
            $this->handlerError($e);
        }
    }

    private function handlerError(Throwable $e)
    {
        $errorMessage = date('Y-m-d H:i:s') . ': Error constructViewPanelAdmin : ' . $e->getMessage() . "\n";
        error_log($errorMessage, 3, _ROOT_PATH . '/log/error.log' );
    }
}
?>
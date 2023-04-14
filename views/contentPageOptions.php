<?php

require_once(_RVIEWS . 'usuarios.php');
require_once(_RVIEWS . 'directores.php');

class contentPageOptions
{
    private $rutaAssets;

    public function __construct()
    {
        $this->rutaAssets = _RASSETS;
    }

    public function RegistrarUsuarios()
    {
        $usuarios = new usuarios();
        $vistaUsuario = $usuarios->RegistrarUsuarios();
        return $vistaUsuario;
    }

    public function ActualizarUsuarios()
    {
        $ruta = $this->rutaAssets . 'js/usuarios.js';
        $html = <<<Html
        <script  type ="module" src="$ruta"></script>
        Html;
        return $html;
    }

    public function RegistrarArchivo()
    {
        $ruta = $this->rutaAssets . 'js/director.js';
        $directores = new directores();
        $vistaDirectores = $directores->RegistrarArchivo();
        return $vistaDirectores;
    }

    public function Contacto()
    {
        $html = <<<Html
        <div>hola</div>
        Html;
        return $html;
    }
}
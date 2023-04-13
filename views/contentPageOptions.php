<?php

require_once(_RMODEL . 'conexion.php');

class contentPageOptions
{
    private $rutaAssets;

    public function __construct()
    {
        $this->rutaAssets = _RASSETS;
    }

    public function RegistrarUsuarios()
    {
        $ruta = $this->rutaAssets . 'js/usuarios.js';
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
                                <label for="numberTelefono">Código modular IE</label>
                                <input type="text" class="form-control" id="cod_mod_ie" placeholder="Ingrese su código modular IE">
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
        $ruta = $this->rutaAssets . 'js/obras.js';
        $conexion = MySQLConnection::getInstance();

        $html = <<<Html
        <div class="card card-warning mt-3 mx-auto w-100">
            <div class="card-header">
                <h3 class="card-title">Registrar proyecto de inversión pública</h3>
            </div>
            <form id="registrarObras" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="titulo">Título*</label>
                            <input type="text" class="form-control" id="tituloObra" placeholder="Ingrese el título ...">
                        </div>
                        <div class="col-md-6">
                            <label for="tipo de obra">Tipo *</label>
                            <select class="form-control select2 select2-hidden-accessible" data-placeholder="Selecciona un tipo de proyecto de inversión" style="width: 100%; 
                                height: calc(2.25rem + 2px);" tabindex="-1" aria-hidden="true">
                                <option value="Adicionales de obra">Adicionales de obra</option>
                                <option value="Liquidacíon de obras">Liquidacíon de obras</option>
                                <option value="Supervisión de contrataciones">Supervisión de contrataciones</option>
                                <option value="Historico">Historico</option>
                                <option value="Información Adicional">Información Adicional</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="archivoObra">Seleccione un archivo *</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="archivoObra" onchange="
                                    if (this.files.length > 0) {
                                        document.querySelector('.custom-file-label').innerHTML = this.files[0].name
                                    } else {
                                            document.querySelector('.custom-file-label').innerHTML = 'Seleccione un archivo'
                                    }
                                ">
                                <label class="custom-file-label" for="archivoObra" data-browse="Elegir archivo">Elegir archivo</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                            <label for="HoraIngreso">Fecha *</label>
                            <input type="date" class="form-control" id="fechaObra" value="">
                        </div>
                        <div class="col-md-12">
                            <label for="descripcion">Descripción *</label>
                            <textarea type="text" class="form-control text-content" id="descripcionObra" placeholder="Por favor ingrese una descripción..." style="min-height: 100px; max-width: 100%"></textarea>
                            <div id="contadorPalabras" style="color: red;"></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer mt-3">
                    <div class="progress">
                        <div class="progress-bar active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; border-radius: 10px;">
                            0%
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Guardar</button>
                </div>
        </form>
        </div>
        <script type ="module" src="$ruta" defer></script>
        Html;

        $conexion->close();
        return $html;
    }

    public function Contacto()
    {
        $html = <<<Html
        <div>hola</div>
        Html;
        return $html;
    }
}

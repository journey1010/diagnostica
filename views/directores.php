<?php 

require_once(_RMODEL . 'conexion.php');
require_once(_RCONTROLLER . 'erroHandler_y_Sanitizevar.php');

class directores {

    private $rutaAssets;

    public function __construct()
    {
        $this->rutaAssets = _RASSETS;
    }

    public function RegistrarArchivo()
    {
        $ruta = $this->rutaAssets . 'js/director.js';

        $fecha = date('Y');

        $html = <<<Html
        <div class="card card-warning mt-3 mx-auto w-100">
            <div class="card-header">
                <h3 class="card-title">Registrar archivo de evaluación diagnostica</h3>
            </div>
            <form id="registrarArchivo" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="archivoEvadiag">Seleccione un archivo *</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="archivoEvadiag" onchange="
                                    if (this.files.length > 0) {
                                        document.querySelector('.custom-file-label').innerHTML = this.files[0].name
                                    } else {
                                            document.querySelector('.custom-file-label').innerHTML = 'Seleccione un archivo'
                                    }
                                ">
                                <label class="custom-file-label" for="archivoEvadiag" data-browse="Elegir archivo">Elegir archivo</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                            <label for="fechaSubida">Fecha *</label>
                            <input type="text" class="form-control" id="fechaSubida" value="$fecha" disabled>
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
        return $html;
    }



}
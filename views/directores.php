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
        $matematica = $this->matematica();
        $lenguaje = $this->lenguaje();
        $html = <<<Html
        <div class="card card-warning mt-3 mx-auto w-100">
            <div class="card-header titulo_warning">
                <h3 class="card-title">Registro de Evaluación Diagnostica</h3>
            </div>
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Lenguaje *</label>
                                <form id="lenguajeform">
                                    $lenguaje
                                    <button id="btn-lenguaje"type="submit" class="btn btn_print">Guardar</button>
                                </form>
                            </div>
                            <div class="card-footer">
                                
                            </div>
                            <div class="col-md-12">
                                <label>Matemática *</label>
                                <form id="matematicaform">
                                    $matematica
                                    <button id="btn-matematica" type="submit" class="btn btn_print">Guardar</button>
                                </form>
                            </div>
                        </div>                    
                    </div>
                </div>
        </div>
        <script type ="module" src="$ruta" defer></script>
        Html;
        return $html;
    }

    private function matematica () 
    {
        $tabla = <<<html
        <div id="divmatematica" class="table-responsive">
            <table id ="matematica" class="table sheet0 gridlines text-center">
            <thead>
                <tr class="bg-secondary row0">
                <th class="text-center column7 style6 competencias">COMPETENCIAS</th>
                <th class="text-center column8 previo_inicio">Previo Inicio</th>
                <th class="text-center column9 inicio">Inicio</th>
                <th class="text-center column10 proceso">Proceso</th>
                <th class="text-center column11 style4 satisfactorio">Satisfactorio</th>
                </tr>
            </thead>
            <tbody>
                <tr class="row1">
                <td class="column7 style1 s">Resuelve problemas de cantidad.</td>
                <td><input type="text" class="column8 style5 null" required></td>
                <td><input type="text" class="column9 style5 null" required></td>
                <td><input type="text" class="column10 style5 null required"></td>
                <td><input type="text" class="column11 style5 null required"></td>
                </tr>
                <tr class="row2">
                <td class="column7 style1 s">Resuelve problemas de regularidad, equivalencia y cambio.</td>
                <td><input type="text" class="column8 style5 null" required></td>
                <td><input type="text" class="column9 style5 null" required></td>
                <td><input type="text" class="column10 style5 null required"></td>
                <td><input type="text" class="column11 style5 null required"></td>
                </tr>
                <tr class="row3">
                <td class="column7 style1 s">Resuelve problemas de forma, movimiento y localizacion.</td>
                <td><input type="text" class="column8 style5 null" required></td>
                <td><input type="text" class="column9 style5 null" required></td>
                <td><input type="text" class="column10 style5 null" required></td>
                <td><input type="text" class="column11 style5 null" required></td>
                </tr>
                <tr class="row4">
                <td class="column7 style1 s">Resuelve problemas de gestion de datos e incertidumbre.</td>
                <td><input type="text" class="column8 style5 null" required></td>
                <td><input type="text" class="column9 style5 null" required></td>
                <td><input type="text" class="column10 style5 null" required></td>
                <td><input type="text" class="column11 style5 null" required></td>
                </tr>
            </tbody>
            </table>
        </div>      
        html;
        return $tabla;
    }

    private function lenguaje () 
    {
        $tabla = <<<html
        <div id="divlenguaje" class="table-responsive">
            <table id ="lenguaje" class="table sheet0 gridlines">
            <thead>
                <tr class="bg-secondary row0">
                <th class="text-center column7 style6 competencias">COMPETENCIAS</th>
                <th class="text-center column8 previo_inicio">Previo Inicio</th>
                <th class="text-center column9 inicio">Inicio</th>
                <th class="text-center column10 proceso">Proceso</th>
                <th class="text-center column11 style4 satisfactorio">Satisfactorio</th>
                </tr>
            </thead>
            <tbody>
                <tr class="row1">
                <td class="column7 style1 s">Obtiene información del texto escrito.</td>
                <td><input type="text" class="column8 style5 null" required></td>
                <td><input type="text" class="column9 style5 null" required></td>
                <td><input type="text" class="column10 style5 null required"></td>
                <td><input type="text" class="column11 style5 null required"></td>
                </tr>
                <tr class="row2">
                <td class="column7 style1 s">Infiere  e interpreta información del texto.</td>
                <td><input type="text" class="column8 style5 null" required></td>
                <td><input type="text" class="column9 style5 null" required></td>
                <td><input type="text" class="column10 style5 null required"></td>
                <td><input type="text" class="column11 style5 null required"></td>
                </tr>
                <tr class="row3">
                <td class="column7 style1 s">Reflexiona y evalúa  la forma, el contennido y el contexto del texto.</td>
                <td><input type="text" class="column8 style5 null" required></td>
                <td><input type="text" class="column9 style5 null" required></td>
                <td><input type="text" class="column10 style5 null required"></td>
                <td><input type="text" class="column11 style5 null required"></td>
                </tr>
            </tbody>
            </table>
        </div>      
        html;
        return $tabla;
    }
}